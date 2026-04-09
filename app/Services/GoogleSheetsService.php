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
     * ★ Bulk sync ALL orders + analytics ke Google Sheets.
     * Dipanggil dari admin dashboard tombol "Sync ke Sheets".
     */
    public static function bulkSyncAll(): array
    {
        $webhookUrl = config('services.google_sheets.url');
        $apiKey     = config('services.google_sheets.api_key');

        if (!$webhookUrl || !$apiKey) {
            return ['success' => false, 'message' => 'Google Sheets belum dikonfigurasi.'];
        }

        $orders = Order::withTrashed()->latest()->get();

        // Build rows for "Rekap Order" sheet
        $rows = $orders->map(function (Order $order) {
            return [
                $order->id,
                $order->reference ?? '',
                $order->created_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '',
                $order->customer_name ?? '',
                $order->customer_phone ?? '',
                $order->customer_email ?? '',
                $order->summary_title ?? '',
                $order->quantity ?? 0,
                $order->total_price ?? 0,
                $order->payment_method ?? '',
                $order->status_label ?? ucfirst($order->status),
                $order->delivery_address ?? '',
                $order->notes ?? '',
                $order->cancel_reason ?? '',
                $order->completed_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '',
            ];
        })->values()->all();

        // Build analytics summary
        $completed = $orders->where('status', Order::STATUS_COMPLETED);
        $analytics = [
            'total_orders'     => $orders->count(),
            'total_revenue'    => (int) $completed->sum('total_price'),
            'completed_orders' => $completed->count(),
            'cancelled_orders' => $orders->where('status', Order::STATUS_CANCELLED)->count(),
            'pending_orders'   => $orders->where('status', Order::STATUS_PENDING)->count(),
            'avg_order_value'  => $completed->count() > 0
                ? round($completed->sum('total_price') / $completed->count())
                : 0,
            'top_products'     => $orders->flatMap(fn ($o) => $o->items_summary)
                ->groupBy('product_name')
                ->map(fn ($items, $name) => [
                    'name'     => $name,
                    'qty'      => (float) $items->sum('quantity'),
                    'revenue'  => (float) $items->sum('subtotal'),
                ])
                ->sortByDesc('revenue')
                ->take(10)
                ->values()
                ->all(),
            'daily_revenue'    => self::buildDailyRevenue($orders),
            'status_counts'    => [
                'Selesai'    => $completed->count(),
                'Pending'    => $orders->where('status', Order::STATUS_PENDING)->count(),
                'Diproses'   => $orders->where('status', Order::STATUS_PROCESSING)->count(),
                'Dikirim'    => $orders->where('status', Order::STATUS_DELIVERING)->count(),
                'Dibatalkan' => $orders->where('status', Order::STATUS_CANCELLED)->count(),
            ],
        ];

        $payload = [
            'api_key'   => $apiKey,
            'event'     => 'bulk.sync',
            'rows'      => $rows,
            'analytics' => $analytics,
            'synced_at' => now('Asia/Jakarta')->toIso8601String(),
        ];

        try {
            $response = Http::acceptJson()
                ->timeout(60)
                ->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Google Sheets bulk sync success', ['total' => count($rows)]);
                return [
                    'success' => true,
                    'message' => 'Berhasil sync ' . count($rows) . ' order ke Google Sheets.',
                    'sheet_url' => $response->json('sheet_url') ?? null,
                ];
            }

            Log::error('Google Sheets bulk sync failed', ['status' => $response->status()]);
            return ['success' => false, 'message' => 'Gagal sync: ' . $response->body()];

        } catch (\Throwable $e) {
            Log::error('Google Sheets bulk sync exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Build daily revenue data for last 30 days.
     */
    protected static function buildDailyRevenue($orders): array
    {
        $result = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now('Asia/Jakarta')->subDays($i);
            $result[] = [
                'date'    => $day->format('d/m'),
                'revenue' => (int) $orders
                    ->where('status', Order::STATUS_COMPLETED)
                    ->filter(fn ($o) => $o->created_at->isSameDay($day))
                    ->sum('total_price'),
                'orders'  => $orders
                    ->filter(fn ($o) => $o->created_at->isSameDay($day))
                    ->count(),
            ];
        }
        return $result;
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
            $apiKey     = config('services.google_sheets.api_key');

            if (!$webhookUrl || !$apiKey) {
                Log::warning('Google Sheets sync skipped: missing URL or API key', [
                    'order_id' => $order->id,
                    'event'    => $event,
                ]);
                return false;
            }

            $payload = [
                'api_key' => $apiKey,
                'event'   => $event,
                'meta'    => array_merge([
                    'triggered_at' => now('Asia/Jakarta')->toIso8601String(),
                ], $meta),
                'order'   => [
                    'id'                => $order->id,
                    'reference'         => $order->reference,
                    'status'            => $order->status,
                    'status_label'      => $order->status_label,
                    'sync_status'       => $order->sync_status,
                    'payment_method'    => $order->payment_method,
                    'payment_proof_url' => $order->payment_proof_url,
                    'customer'          => [
                        'name'  => $order->customer_name,
                        'email' => $order->customer_email,
                        'phone' => $order->customer_phone,
                    ],
                    'delivery_address'  => $order->delivery_address,
                    'notes'             => $order->notes,
                    'cancel_reason'     => $order->cancel_reason,
                    'total_price'       => $order->total_price,
                    'quantity'          => $order->quantity,
                    'summary_title'     => $order->summary_title,
                    'items'             => $order->items_summary,
                    'timestamps'        => [
                        'ordered_at'   => ($order->order_time ?? $order->created_at)?->toIso8601String(),
                        'completed_at' => $order->completed_at?->toIso8601String(),
                        'deleted_at'   => $order->deleted_at?->toIso8601String(),
                    ],
                ],
            ];

            $response = Http::acceptJson()
                ->timeout(15)
                ->retry(2, 500)
                ->post($webhookUrl, $payload);

            if ($response->successful() && $response->json('success', true)) {
                self::markSyncResult($order, true);
                Log::info('Google Sheets sync success', ['order_id' => $order->id, 'event' => $event]);
                return true;
            }

            $message = $response->json('message') ?: $response->body();
            self::markSyncResult($order, false, $message);
            Log::error('Google Sheets sync failed', ['order_id' => $order->id, 'event' => $event, 'status' => $response->status()]);

        } catch (\Throwable $exception) {
            self::markSyncResult($order, false, $exception->getMessage());
            Log::error('Google Sheets sync exception', ['order_id' => $order->id, 'event' => $event, 'message' => $exception->getMessage()]);
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
                'sync_error'  => $successful ? null : $message,
            ]);
        });
    }
}