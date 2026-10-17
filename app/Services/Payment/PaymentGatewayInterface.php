<?php

namespace App\Services\Payment;

use App\Models\Booking;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Create a payment charge transaction.
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
    public function createCharge(Booking $booking, string $paymentMethod): array;

    /**
     * Verify incoming webhook payload.
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
    public function verifyWebhook(array $payload, ?string $signature = null): array;

    /**
     * Refund a paid transaction.
     *
     * @return array{
     *     success: bool,
     *     refund_id: string,
     *     message: string
     * }
     */
    public function processRefund(Payment $payment, int $amount, string $reason): array;
}
