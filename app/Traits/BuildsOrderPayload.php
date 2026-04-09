<?php

namespace App\Traits;

use App\Models\Order;

trait BuildsOrderPayload
{
    /**
     * Build payload sesuai format yang diharapkan Apps Script.
     * Akurat berdasarkan struktur Order model aktual:
     * — Tidak ada relasi customer, langsung field customer_*
     * — items_summary adalah accessor
     * — order_time adalah field timestamp order
     */
    protected function buildOrderPayload(Order $order): array
    {
        // Status label mapping — sesuai STATUS_* constants di Order model
        $statusMap = [
            Order::STATUS_PENDING    => 'Pending',
            Order::STATUS_PROCESSING => 'Diproses',
            Order::STATUS_DELIVERING => 'Dikirim',
            Order::STATUS_COMPLETED  => 'Selesai',
            Order::STATUS_CANCELLED  => 'Dibatalkan',
        ];

        return [
            'id'             => $order->id,
            'reference'      => $order->reference
                ?? ('ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)),

            // quantity & price langsung dari kolom — sudah final di DB
            'quantity'       => (float) $order->quantity,
            'total_price'    => (float) $order->total_price,

            'payment_method' => $order->payment_method ?? '',
            'status'         => $order->status ?? '',

            // Gunakan accessor status_label jika ada, fallback ke map
            'status_label'   => $order->status_label
                ?? $statusMap[$order->status]
                ?? ucfirst($order->status ?? ''),

            // product_name sudah disimpan sebagai summary di DB saat order dibuat
            'summary_title'  => $order->product_name ?? '',

            // ★ Tidak ada relasi customer — langsung kolom
            'customer' => [
                'name'  => $order->customer_name  ?? '',
                'phone' => $order->customer_phone ?? '',
                'email' => $order->customer_email ?? '',
            ],

            // ★ order_time adalah field tersendiri (bukan created_at)
            'timestamps' => [
                'ordered_at'   => $order->order_time?->toIso8601String()
                    ?? $order->created_at?->toIso8601String(),
                'completed_at' => $order->completed_at?->toIso8601String(),
            ],

            'delivery_address' => $order->delivery_address ?? '',
            'notes'            => $order->notes            ?? '',
            'cancel_reason'    => $order->cancel_reason    ?? '',
        ];
    }

    /**
     * Payload minimal untuk event deleted.
     * Apps Script hanya butuh id untuk mark deleted.
     */
    protected function buildDeletedPayload(Order $order): array
    {
        return ['id' => $order->id];
    }
}