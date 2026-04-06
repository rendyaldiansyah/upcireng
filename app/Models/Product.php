<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'status',
        'stock_status',
        'is_open',
        'variants',
        'sort_order',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'price' => 'decimal:2',
        'variants' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship: Product has many Orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    /**
     * Check if product is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active'
            && $this->stock_status === 'available'
            && $this->is_open;
    }

    /**
     * Format price to Rupiah.
     */
    public function formatPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get the product image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('assets/assets/logo.png');
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Str::startsWith($this->image, ['products/', 'payment-proofs/'])) {
            return asset('storage/' . $this->image);
        }

        return asset($this->image);
    }

    /**
     * Return configured variants as a clean array.
     *
     * @return array<int, string>
     */
    public function availableVariants(): array
    {
        return collect($this->variants ?? [])
            ->map(fn ($variant) => trim((string) $variant))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Get badge status.
     */
    public function getStatusBadge(): string
    {
        if ($this->stock_status === 'out_of_stock') {
            return '<span class="badge badge-danger">Habis</span>';
        }
        if ($this->status === 'inactive') {
            return '<span class="badge badge-secondary">Tidak Aktif</span>';
        }
        if (!$this->is_open) {
            return '<span class="badge badge-warning">Tutup</span>';
        }
        return '<span class="badge badge-success">Buka</span>';
    }
}
