<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    private string $webhookUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->webhookUrl = config('services.google_sheets.webhook_url', '');
        $this->apiKey     = config('services.google_sheets.api_key', '');
    }

    public function bulkSync(array $rows, array $analytics): array
    {
        return $this->post([
            'event'     => 'bulk.sync',
            'rows'      => $rows,
            'analytics' => $analytics,
        ]);
    }

    public function orderCreated(array $order): array
    {
        return $this->post(['event' => 'order.created', 'order' => $order]);
    }

    public function orderUpdated(array $order, array $meta = []): array
    {
        return $this->post(['event' => 'order.updated', 'order' => $order, 'meta' => $meta]);
    }

    public function orderDeleted(array $order): array
    {
        return $this->post(['event' => 'order.deleted', 'order' => $order]);
    }

    // ── Internal ──────────────────────────────────────────────────────────────

    private function post(array $payload): array
    {
        if (empty($this->webhookUrl)) {
            Log::warning('[GoogleSheetService] GOOGLE_SHEETS_WEBHOOK_URL belum diisi di .env');
            return ['success' => false, 'message' => 'Webhook URL tidak dikonfigurasi'];
        }

        try {
            $response = Http::timeout(30)
                ->asJson()
                ->post($this->webhookUrl, array_merge($payload, [
                    'api_key' => $this->apiKey,
                ]));

            $body = $response->json() ?? [];

            if (! $response->successful()) {
                Log::error('[GoogleSheetService] HTTP error', [
                    'status' => $response->status(),
                    'event'  => $payload['event'] ?? null,
                    'body'   => $body,
                ]);
                return ['success' => false, 'message' => 'HTTP ' . $response->status()];
            }

            return $body;

        } catch (\Throwable $e) {
            Log::error('[GoogleSheetService] Exception', [
                'message' => $e->getMessage(),
                'event'   => $payload['event'] ?? null,
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}