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

    /**
     * Handle GitHub Push Webhook for Auto Deployment.
     */
    public function githubDeploy(Request $request): JsonResponse
    {
        $secret = config('services.github.webhook_secret', env('GITHUB_WEBHOOK_SECRET'));

        if (! empty($secret)) {
            $signature = $request->header('X-Hub-Signature-256');
            if (! $signature) {
                return response()->json(['success' => false, 'error' => 'Missing signature'], 403);
            }

            $payload = $request->getContent();
            $expectedSignature = 'sha256='.hash_hmac('sha256', $payload, $secret);

            if (! hash_equals($expectedSignature, $signature)) {
                return response()->json(['success' => false, 'error' => 'Invalid signature'], 403);
            }
        }

        // Only deploy on push to main branch
        $ref = $request->input('ref');
        if ($ref && $ref !== 'refs/heads/main') {
            return response()->json([
                'success' => true,
                'message' => 'Ignored push event for branch: '.$ref,
            ]);
        }

        // Trigger background deploy
        $baseDir = base_path();
        $deployScript = $baseDir.'/deploy.sh';

        if (file_exists($deployScript)) {
            $logFile = storage_path('logs/deploy.log');
            // Execute in background so HTTP response returns immediately to avoid GitHub timeout
            $command = 'cd '.escapeshellarg($baseDir).' && bash deploy.sh > '.escapeshellarg($logFile).' 2>&1 &';
            exec($command);

            return response()->json([
                'success' => true,
                'message' => 'Auto-deployment started successfully',
                'branch' => $ref ?? 'main',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'deploy.sh script not found at '.$deployScript,
        ], 500);
    }
}
