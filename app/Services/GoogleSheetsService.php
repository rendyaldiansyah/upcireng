<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    /**
     * Sync a new order to Google Sheets log.
     */
    public static function syncCreated(Order $order): bool
    {
        return self::dispatch('order.created', $order);
    }

    /**
     * Sync an order status change to Google Sheets log.
     */
    public static function syncStatusUpdate(Order $order, ?string $previousStatus = null): bool
    {
        return self::dispatch('order.updated', $order, [
            'previous_status' => $previousStatus,
        ]);
    }

    /**
     * Sync a hard delete / admin delete to Google Sheets log.
     */
    public static function syncDeleted(Order $order): bool
    {
        return self::dispatch('order.deleted', $order);
    }

    /**
     * Dispatch webhook payload to Google Apps Script.
     *
     * @param  array<string, mixed>  $meta
     */
    protected static function dispatch(string $event, Order $order, array $meta = []): bool
    {
        try {
            $webhookUrl = config('services.google_sheets.url');
            $apiKey = config('services.google_sheets.api_key');

            if (!$webhookUrl || !$apiKey) {
                Log::warning('Google Sheets sync skipped: missing URL or API key', [
                    'order_id' => $order->id,
                    'event' => $event,
                ]);

                return false;
            }

            $payload = [
                'api_key' => $apiKey,
                'event' => $event,
                'meta' => array_merge([
                    'triggered_at' => now('Asia/Jakarta')->toIso8601String(),
                ], $meta),
                'order' => [
                    'id' => $order->id,
                    'reference' => $order->reference,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'sync_status' => $order->sync_status,
                    'payment_method' => $order->payment_method,
                    'payment_proof_url' => $order->payment_proof_url,
                    'customer' => [
                        'name' => $order->customer_name,
                        'email' => $order->customer_email,
                        'phone' => $order->customer_phone,
                    ],
                    'delivery_address' => $order->delivery_address,
                    'notes' => $order->notes,
                    'cancel_reason' => $order->cancel_reason,
                    'total_price' => $order->total_price,
                    'quantity' => $order->quantity,
                    'summary_title' => $order->summary_title,
                    'items' => $order->items_summary,
                    'timestamps' => [
                        'ordered_at' => ($order->order_time ?? $order->created_at)?->toIso8601String(),
                        'completed_at' => $order->completed_at?->toIso8601String(),
                        'deleted_at' => $order->deleted_at?->toIso8601String(),
                    ],
                ],
            ];

            $response = Http::acceptJson()
                ->timeout(15)
                ->retry(2, 500)
                ->post($webhookUrl, $payload);

            if ($response->successful() && $response->json('success', true)) {
                self::markSyncResult($order, true);

                Log::info('Google Sheets sync success', [
                    'order_id' => $order->id,
                    'event' => $event,
                ]);

                return true;
            }

            $message = $response->json('message') ?: $response->body();
            self::markSyncResult($order, false, $message);

            Log::error('Google Sheets sync failed', [
                'order_id' => $order->id,
                'event' => $event,
                'status' => $response->status(),
                'message' => $message,
            ]);
        } catch (\Throwable $exception) {
            self::markSyncResult($order, false, $exception->getMessage());

            Log::error('Google Sheets sync exception', [
                'order_id' => $order->id,
                'event' => $event,
                'message' => $exception->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Persist latest sync state without disturbing main workflow.
     */
    protected static function markSyncResult(Order $order, bool $successful, ?string $message = null): void
    {
        Order::withoutEvents(function () use ($order, $successful, $message): void {
            Order::whereKey($order->id)->update([
                'sync_status' => $successful ? Order::SYNC_SYNCED : Order::SYNC_FAILED,
                'sync_error' => $successful ? null : $message,
            ]);
        });
    }
}
