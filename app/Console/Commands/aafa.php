<?php

namespace App\Console\Commands;

use App\Mail\DailyOrderRecap;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyOrderRecap extends Command
{
    protected $signature   = 'orders:daily-recap';
    protected $description = 'Kirim rekap pesanan harian ke admin via email';

    public function handle(): int
    {
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));

        if (!$adminEmail) {
            $this->error('ADMIN_EMAIL belum diset di .env');
            return self::FAILURE;
        }

        $today = now('Asia/Jakarta');

        $orders = Order::query()
            ->whereDate('created_at', $today->toDateString())
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $totalRevenue = $orders
            ->whereNotIn('status', [Order::STATUS_CANCELLED])
            ->sum('total_price');

        $date = $today->translatedFormat('d F Y');

        try {
            Mail::to($adminEmail)->send(new DailyOrderRecap(
                orders: $orders,
                date: $date,
                totalRevenue: $totalRevenue,
                totalOrders: $orders->count(),
            ));

            $this->info("✅ Rekap harian ({$orders->count()} pesanan) berhasil dikirim ke {$adminEmail}");

            Log::info('Daily order recap sent', [
                'date'          => $date,
                'total_orders'  => $orders->count(),
                'total_revenue' => $totalRevenue,
            ]);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Gagal kirim rekap: ' . $e->getMessage());

            Log::error('Daily recap email failed', ['error' => $e->getMessage()]);

            return self::FAILURE;
        }
    }
}