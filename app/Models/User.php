<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',    // nullable — customer tidak wajib punya email
        'phone',
        'password', // nullable — customer login pakai phone
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Jumlah pesanan yang sudah selesai.
     */
    public function completedOrdersCount(): int
    {
        return $this->orders()->where('status', Order::STATUS_COMPLETED)->count();
    }

    /**
     * Total belanja semua pesanan selesai.
     */
    public function totalSpent(): float
    {
        return (float) $this->orders()->where('status', Order::STATUS_COMPLETED)->sum('total_price');
    }
}