<?php

namespace App\Services\Ledger;

use App\Models\Booking;
use App\Models\LedgerEntry;
use App\Models\Payment;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LedgerService
{
    /**
     * Get the current balance of a teacher based on ledger entries.
     */
    public function getTeacherBalance(int $userId): int
    {
        $earned = (int) LedgerEntry::where('user_id', $userId)
            ->where('transaction_type', 'teacher_earnings')
            ->where('direction', 'credit')
            ->sum('amount');

        $withdrawn = (int) LedgerEntry::where('user_id', $userId)
            ->where('transaction_type', 'payout_disbursed')
            ->where('direction', 'debit')
            ->sum('amount');

        return max(0, $earned - $withdrawn);
    }

    /**
     * Get total platform commission collected.
     */
    public function getTotalPlatformCommission(): int
    {
        return (int) LedgerEntry::where('transaction_type', 'platform_commission')
            ->where('direction', 'credit')
            ->sum('amount');
    }

    /**
     * Record payment received from parent.
     */
    public function recordPaymentReceived(Payment $payment): void
    {
        DB::transaction(function () use ($payment): void {
            $booking = $payment->booking;

            // 1. Gross payment received into platform escrow
            LedgerEntry::create([
                'uuid' => (string) Str::uuid(),
                'entry_code' => 'LED-PAY-'.strtoupper(Str::random(6)),
                'transaction_type' => 'payment_received',
                'direction' => 'credit',
                'amount' => $payment->amount,
                'user_id' => $booking->parent_id,
                'booking_id' => $booking->id,
                'balance_after' => $payment->amount,
                'description' => "Pembayaran diterima dari orang tua untuk les {$booking->booking_code}",
                'reference_id' => $payment->payment_code,
            ]);

            // 2. Platform commission snapshot entry
            LedgerEntry::create([
                'uuid' => (string) Str::uuid(),
                'entry_code' => 'LED-COM-'.strtoupper(Str::random(6)),
                'transaction_type' => 'platform_commission',
                'direction' => 'credit',
                'amount' => $booking->platform_commission_amount,
                'user_id' => null, // Platform system
                'booking_id' => $booking->id,
                'balance_after' => $this->getTotalPlatformCommission() + $booking->platform_commission_amount,
                'description' => "Komisi platform ({$booking->platform_commission_rate}%) untuk les {$booking->booking_code}",
                'reference_id' => $booking->booking_code,
            ]);
        });
    }

    /**
     * Record teacher earnings once the lesson is completed by both parties.
     */
    public function recordSessionCompleted(Booking $booking): void
    {
        DB::transaction(function () use ($booking): void {
            $currentBalance = $this->getTeacherBalance($booking->teacher_id);
            $newBalance = $currentBalance + $booking->teacher_net_amount;

            LedgerEntry::create([
                'uuid' => (string) Str::uuid(),
                'entry_code' => 'LED-ERN-'.strtoupper(Str::random(6)),
                'transaction_type' => 'teacher_earnings',
                'direction' => 'credit',
                'amount' => $booking->teacher_net_amount,
                'user_id' => $booking->teacher_id,
                'booking_id' => $booking->id,
                'balance_after' => $newBalance,
                'description' => "Pendapatan bersih les privat {$booking->booking_code} murid {$booking->student?->name}",
                'reference_id' => $booking->booking_code,
            ]);
        });
    }

    /**
     * Record payout disbursed to teacher.
     */
    public function recordPayoutDisbursed(Payout $payout): void
    {
        DB::transaction(function () use ($payout): void {
            $teacherUserId = $payout->teacherProfile->user_id;
            $currentBalance = $this->getTeacherBalance($teacherUserId);
            $newBalance = max(0, $currentBalance - $payout->amount);

            LedgerEntry::create([
                'uuid' => (string) Str::uuid(),
                'entry_code' => 'LED-DIS-'.strtoupper(Str::random(6)),
                'transaction_type' => 'payout_disbursed',
                'direction' => 'debit',
                'amount' => $payout->amount,
                'user_id' => $teacherUserId,
                'payout_id' => $payout->id,
                'balance_after' => $newBalance,
                'description' => "Pencairan dana ke rekening {$payout->bank_name} ({$payout->bank_account_number})",
                'reference_id' => $payout->payout_code,
            ]);
        });
    }

    /**
     * Record refund issued to parent.
     */
    public function recordRefundIssued(Booking $booking, int $amount, string $reason): void
    {
        DB::transaction(function () use ($booking, $amount, $reason): void {
            LedgerEntry::create([
                'uuid' => (string) Str::uuid(),
                'entry_code' => 'LED-RFD-'.strtoupper(Str::random(6)),
                'transaction_type' => 'refund_issued',
                'direction' => 'debit',
                'amount' => $amount,
                'user_id' => $booking->parent_id,
                'booking_id' => $booking->id,
                'balance_after' => 0,
                'description' => "Pengembalian dana (refund) untuk pemesanan {$booking->booking_code}: {$reason}",
                'reference_id' => $booking->booking_code,
            ]);
        });
    }
}
