<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Handle payment webhook callback with signature checking and idempotency.
     */
    public function payment(Request $request): JsonResponse
    {
        $payload = $request->all();
        $signature = $request->header('X-BelajarYuk-Signature') ?? $request->header('X-Signature');

        $result = $this->paymentService->handleWebhook($payload, $signature);

        if (! $result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result, 200);
    }
}
