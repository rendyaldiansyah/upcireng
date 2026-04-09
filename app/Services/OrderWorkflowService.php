<?php

namespace App\Services;

use App\Jobs\SyncOrderToSheet;
use App\Models\Order;
use App\Traits\BuildsOrderPayload;
use Illuminate\Support\Facades\Log;

class OrderWorkflowService
{
    use BuildsOrderPayload;

    // =========================================================================
    // PUBLIC HOOKS — dipanggil dari Controller
    // =========================================================================

    /**
     * Dipanggil di OrderController::store() setelah Order::create()
     */
    public function handleCreated(Order $order): void
    {
        $this->updateSyncStatus($order, Order::SYNC_PENDING);

        // ★ Dispatch real-time ke Google Sheets (non-blocking)
        $this->dispatchSheetSync('order.created', $order);

        Log::info('[OrderWorkflow] Order created', [
            'order_id'  => $order->id,
            'reference' => $order->reference,
        ]);
    }

    /**
     * Dipanggil setiap kali status berubah:
     * — AdminController::updateOrderStatus()
     * — OrderController::cancel()
     * — OrderController::retrySyncOrder()
     */
    public function handleStatusChange(Order $order, string $previousStatus): void
    {
        if ($order->status === $previousStatus) {
            return; // Tidak ada perubahan nyata, skip
        }

        // ★ Dispatch real-time ke Google Sheets
        $this->dispatchSheetSync('order.updated', $order);

        Log::info('[OrderWorkflow] Status changed', [
            'order_id' => $order->id,
            'from'     => $previousStatus,
            'to'       => $order->status,
        ]);
    }

    /**
     * Dipanggil di AdminController::deleteOrder() sebelum $order->delete()
     */
    public function handleDeleted(Order $order): void
    {
        // ★ Dispatch real-time ke Google Sheets — mark deleted
        SyncOrderToSheet::dispatch('order.deleted', $this->buildDeletedPayload($order))
            ->onQueue('sheets');

        Log::info('[OrderWorkflow] Order deleted', [
            'order_id'  => $order->id,
            'reference' => $order->reference,
        ]);
    }

    // =========================================================================
    // INTERNAL HELPERS
    // =========================================================================

    /**
     * Dispatch job ke queue 'sheets'.
     * Delay 1 detik untuk pastikan DB sudah commit sempurna.
     */
    private function dispatchSheetSync(string $event, Order $order): void
    {
        try {
            SyncOrderToSheet::dispatch($event, $this->buildOrderPayload($order))
                ->onQueue('sheets')
                ->delay(now()->addSecond());
        } catch (\Throwable $e) {
            // Jangan sampai crash workflow jika dispatch gagal
            Log::error('[OrderWorkflow] Gagal dispatch sync job', [
                'event'    => $event,
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update sync_status di DB jika kolom tersedia.
     */
    private function updateSyncStatus(Order $order, string $status): void
    {
        try {
            $order->updateQuietly(['sync_status' => $status]);
        } catch (\Throwable $e) {
            Log::warning('[OrderWorkflow] Gagal update sync_status', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}