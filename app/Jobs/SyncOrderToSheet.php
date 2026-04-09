<?php

namespace App\Jobs;

use App\Services\GoogleSheetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncOrderToSheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 15;

    public function __construct(
        private readonly string $event,
        private readonly array  $orderData,
    ) {}

    public function handle(GoogleSheetService $service): void
    {
        $result = match ($this->event) {
            'order.created' => $service->orderCreated($this->orderData),
            'order.updated' => $service->orderUpdated($this->orderData),
            'order.deleted' => $service->orderDeleted($this->orderData),
            default         => ['success' => false, 'message' => 'Unknown event: ' . $this->event],
        };

        if (! ($result['success'] ?? false)) {
            Log::warning('[SyncOrderToSheet] Gagal sync ke Google Sheets', [
                'event'    => $this->event,
                'order_id' => $this->orderData['id'] ?? null,
                'response' => $result,
            ]);

            // Lepas ke retry berikutnya jika belum habis attempts
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff);
            }
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[SyncOrderToSheet] Job gagal permanen', [
            'event'    => $this->event,
            'order_id' => $this->orderData['id'] ?? null,
            'error'    => $e->getMessage(),
        ]);
    }
}