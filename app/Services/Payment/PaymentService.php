<?php

namespace App\Services\Payment;

use App\Models\Booking;
use App\Models\LessonSession;
use App\Models\Notification;
use App\Models\Payment;
use App\Services\Ledger\LedgerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        protected PaymentGatewayInterface $gateway,
        protected LedgerService $ledgerService
    ) {}

    /**
     * Initialize payment for a booking.
     */
    public function initiatePayment(Booking $booking, string $paymentMethod): Payment
    {
        return DB::transaction(function () use ($booking, $paymentMethod): Payment {
            // Check if there's already an existing pending payment
            $existing = Payment::where('booking_id', $booking->id)
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                return $existing;
            }

            $charge = $this->gateway->createCharge($booking, $paymentMethod);

            $payment = Payment::create([
                'uuid' => (string) Str::uuid(),
                'booking_id' => $booking->id,
                'payment_code' => $charge['payment_code'],
                'payment_gateway' => 'mock_pg',
                'payment_method' => $paymentMethod,
                'amount' => $charge['amount'],
                'status' => 'pending',
                'transaction_reference' => $charge['payment_reference'],
                'va_number' => $charge['va_number'],
                'qr_url' => $charge['qr_url'],
                'expires_at' => now()->addHours(24),
                'raw_payload' => $charge['raw_payload'],
            ]);

            return $payment;
        });
    }

    /**
     * Process webhook callback idempotently.
     *
     * @param  array<string, mixed>  $payload
     */
    public function handleWebhook(array $payload, ?string $signature = null): array
    {
        $verified = $this->gateway->verifyWebhook($payload, $signature);

        if (! $verified['is_valid']) {
            return [
                'success' => false,
                'message' => 'Invalid webhook signature',
            ];
        }

        $payment = Payment::where('payment_code', $verified['payment_code'])->first();

        if (! $payment) {
            return [
                'success' => false,
                'message' => 'Payment code not found',
            ];
        }

        // Idempotency: If already processed, return success immediately
        if ($payment->status === 'paid') {
            return [
                'success' => true,
                'message' => 'Payment already processed previously (Idempotent)',
            ];
        }

        if ($verified['status'] === 'paid') {
            $this->markAsPaid($payment, $verified['transaction_reference']);

            return [
                'success' => true,
                'message' => 'Payment successfully recorded',
            ];
        }

        return [
            'success' => true,
            'message' => 'Payment status updated to '.$verified['status'],
        ];
    }

    /**
     * Mark a payment as paid, update booking and ledger.
     */
    public function markAsPaid(Payment $payment, ?string $transactionRef = null): void
    {
        DB::transaction(function () use ($payment, $transactionRef): void {
            if ($payment->status === 'paid') {
                return;
            }

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'transaction_reference' => $transactionRef ?? $payment->transaction_reference,
            ]);

            $booking = $payment->booking;
            $booking->update([
                'status' => 'confirmed',
            ]);

            // Record to financial ledger
            $this->ledgerService->recordPaymentReceived($payment);

            // Automatically create lesson session if not exists
            $startDateTime = Carbon::parse($booking->booking_date->format('Y-m-d').' '.$booking->start_time);
            $endDateTime = Carbon::parse($booking->booking_date->format('Y-m-d').' '.$booking->end_time);

            LessonSession::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'uuid' => (string) Str::uuid(),
                    'scheduled_start' => $startDateTime,
                    'scheduled_end' => $endDateTime,
                    'status' => 'scheduled',
                ]
            );

            // Notify Parent
            Notification::create([
                'user_id' => $booking->parent_id,
                'title' => 'Pembayaran Berhasil!',
                'message' => "Pembayaran les {$booking->booking_code} sebesar Rp ".number_format($booking->total_amount, 0, ',', '.').' telah terkonfirmasi. Jadwal les Anda telah siap.',
                'type' => 'payment_success',
                'link' => route('parent.bookings.show', $booking->id),
            ]);

            // Notify Teacher
            Notification::create([
                'user_id' => $booking->teacher_id,
                'title' => 'Pemesanan Dikonfirmasi!',
                'message' => "Orang tua murid telah menyelesaikan pembayaran untuk les {$booking->booking_code}. Siapkan sesi pembelajaran Anda.",
                'type' => 'booking_confirmed',
                'link' => route('teacher.bookings.show', $booking->id),
            ]);
        });
    }

    /**
     * Process refund for a booking.
     */
    public function processBookingRefund(Booking $booking, string $reason): array
    {
        return DB::transaction(function () use ($booking, $reason): array {
            $payment = $booking->payment;

            if (! $payment || $payment->status !== 'paid') {
                return [
                    'success' => false,
                    'message' => 'Pemesanan tidak memiliki pembayaran aktif yang dapat direfund.',
                ];
            }

            $refundResult = $this->gateway->processRefund($payment, $payment->amount, $reason);

            $payment->update([
                'status' => 'refunded',
            ]);

            $booking->update([
                'status' => 'refunded',
                'cancellation_reason' => $reason,
            ]);

            // Record into ledger
            $this->ledgerService->recordRefundIssued($booking, $payment->amount, $reason);

            // Notification
            Notification::create([
                'user_id' => $booking->parent_id,
                'title' => 'Pengembalian Dana Berhasil',
                'message' => 'Pengembalian dana sebesar Rp '.number_format($payment->amount, 0, ',', '.')." untuk {$booking->booking_code} telah diproses.",
                'type' => 'refund_processed',
                'link' => route('parent.bookings.show', $booking->id),
            ]);

            return [
                'success' => true,
                'message' => 'Pengembalian dana berhasil diproses.',
            ];
        });
    }
}
