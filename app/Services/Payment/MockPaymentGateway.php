<?php

namespace App\Services\Payment;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

class MockPaymentGateway implements PaymentGatewayInterface
{
    /**
     * Create a payment charge transaction for Indonesian payment methods.
     *
     * @return array{
     *     payment_reference: string,
     *     payment_code: string,
     *     amount: int,
     *     va_number: ?string,
     *     qr_url: ?string,
     *     status: string,
     *     raw_payload: array<string, mixed>
     * }
     */
    public function createCharge(Booking $booking, string $paymentMethod): array
    {
        $reference = 'MOCK-TXN-'.strtoupper(Str::random(10));
        $paymentCode = 'PAY-'.date('Ymd').'-'.rand(1000, 9999);
        $amount = $booking->total_amount;

        $vaNumber = null;
        $qrUrl = null;

        if ($paymentMethod === 'virtual_account' || $paymentMethod === 'bank_transfer') {
            // Simulated Indonesian VA (BCA format)
            $vaNumber = '80777'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT).rand(100, 999);
        } elseif ($paymentMethod === 'gopay' || $paymentMethod === 'dana' || $paymentMethod === 'qris') {
            // Simulated QRIS
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=BELAJARDONG_QRIS_'.$reference;
        }

        return [
            'payment_reference' => $reference,
            'payment_code' => $paymentCode,
            'amount' => $amount,
            'va_number' => $vaNumber,
            'qr_url' => $qrUrl,
            'status' => 'pending',
            'raw_payload' => [
                'provider' => 'mock_pg',
                'booking_code' => $booking->booking_code,
                'method' => $paymentMethod,
                'va_number' => $vaNumber,
                'qr_url' => $qrUrl,
                'created_at' => now()->toIso8601String(),
            ],
        ];
    }

    /**
     * Verify incoming webhook payload with signature checking.
     *
     * @param  array<string, mixed>  $payload
     * @return array{
     *     is_valid: bool,
     *     payment_code: string,
     *     transaction_reference: string,
     *     status: string,
     *     amount: int
     * }
     */
    public function verifyWebhook(array $payload, ?string $signature = null): array
    {
        // Secret for HMAC signature
        $secretKey = config('services.payment_gateway.mock_secret', 'belajar_yuk_mock_secret_key');

        $paymentCode = (string) ($payload['payment_code'] ?? '');
        $status = (string) ($payload['status'] ?? 'paid');
        $amount = (int) ($payload['amount'] ?? 0);
        $reference = (string) ($payload['transaction_reference'] ?? 'MOCK-REF');

        $isValid = true;
        if (! empty($signature)) {
            $expectedSignature = hash_hmac('sha256', $paymentCode.'|'.$status.'|'.$amount, $secretKey);
            $isValid = hash_equals($expectedSignature, $signature);
        }

        return [
            'is_valid' => $isValid,
            'payment_code' => $paymentCode,
            'transaction_reference' => $reference,
            'status' => $status,
            'amount' => $amount,
        ];
    }

    /**
     * Process simulated refund.
     *
     * @return array{
     *     success: bool,
     *     refund_id: string,
     *     message: string
     * }
     */
    public function processRefund(Payment $payment, int $amount, string $reason): array
    {
        $refundId = 'RFD-'.strtoupper(Str::random(8));

        return [
            'success' => true,
            'refund_id' => $refundId,
            'message' => 'Pengembalian dana Rp '.number_format($amount, 0, ',', '.').' berhasil diproses.',
        ];
    }
}
