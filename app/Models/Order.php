<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const SYNC_PENDING = 'pending';
    public const SYNC_SYNCED = 'synced';
    public const SYNC_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'product_id',
        'reference',
        'product_name',
        'quantity',
        'price_per_unit',
        'total_price',
        'items',
        'payment_method',
        'payment_proof_path',
        'status',
        'sync_status',
        'sync_error',
        'notes',
        'cancel_reason',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'order_time',
        'completed_at',
        'deleted_by_customer_at',
    ];

    protected $casts = [
        'items' => 'array',
        'order_time' => 'datetime',
        'completed_at' => 'datetime',
        'deleted_by_customer_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'quantity' => 'float',
        'price_per_unit' => 'float',
        'total_price' => 'float',
    ];

    /**
     * Get the user who made this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product ordered.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-amber-100 text-amber-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_DELIVERING => 'bg-violet-100 text-violet-800',
            self::STATUS_COMPLETED => 'bg-emerald-100 text-emerald-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    /**
     * Human readable status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_DELIVERING => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Human readable sync status.
     */
    public function getSyncStatusLabelAttribute(): string
    {
        return match ($this->sync_status) {
            self::SYNC_SYNCED => '✓ Tersinkron',
            self::SYNC_FAILED => '✕ Gagal sinkron',
            default => '⏳ Menunggu sinkron',
        };
    }

    /**
     * Get sync error message (user-friendly).
     */
    public function getSyncErrorLabelAttribute(): ?string
    {
        if (!$this->sync_error) {
            return null;
        }

        // Return user-friendly message instead of raw error
        if ($this->sync_status === self::SYNC_FAILED) {
            return 'Sinkronisasi dengan sistem lain gagal. Pesanan Anda tetap tercatat dengan baik.';
        }

        return null;
    }

    /**
     * Check if sync can be retried.
     */
    public function canRetrySyncAttribute(): bool
    {
        return $this->sync_status === self::SYNC_FAILED;
    }

    /**
     * Payment proof URL (Clean, Professional Preview)
     * Returns: /payment/{orderId} instead of /storage/...
     */
    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof_path) {
            return null;
        }

        // Return clean URL to preview page (not direct file access)
        return route('payment.proof', $this->id);
    }

    /**
     * Direct file storage URL (Legacy - use getPaymentProofUrlAttribute instead)
     */
    public function getStoragePaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof_path) {
            return null;
        }

        return asset('storage/' . $this->payment_proof_path);
    }

    /**
     * Check if payment proof exists
     */
    public function hasPaymentProof(): bool
    {
        return $this->payment_proof_path && Storage::disk('public')->exists($this->payment_proof_path);
    }

    /**
     * Summary lines for UI / email.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getItemsSummaryAttribute(): array
    {
        return collect($this->items ?? [])
            ->map(function (array $item): array {
                return [
                    'product_name' => $item['product_name'] ?? 'Produk',
                    'variant' => $item['variant'] ?? null,
                    'quantity' => (float) ($item['quantity'] ?? 0),
                    'unit_price' => (float) ($item['unit_price'] ?? 0),
                    'subtotal' => (float) ($item['subtotal'] ?? 0),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Main title for order cards.
     */
    public function getSummaryTitleAttribute(): string
    {
        if ($this->product_name) {
            return $this->product_name;
        }

        $items = $this->items_summary;

        if (count($items) === 1) {
            return $items[0]['product_name'];
        }

        if (count($items) > 1) {
            return sprintf('%s + %d item lain', $items[0]['product_name'], count($items) - 1);
        }

        return 'Pesanan';
    }

    /**
     * Format price as Indonesian rupiah.
     */
    public function formatPrice(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Available status options.
     *
     * @return array<int, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_DELIVERING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * Can be cancelled by customer.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING], true);
    }

    /**
     * Can be hidden from customer history.
     */
    public function canBeDeletedByCustomer(): bool
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED], true);
    }
}
