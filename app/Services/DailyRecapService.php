<?php

namespace App\Services;

use App\Mail\DailyOrderRecap;
use App\Models\Order;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DailyRecapService
{
    public function buildMessage(?CarbonInterface $date = null): string
    {
        $date = $date ? Carbon::parse($date, 'Asia/Jakarta') : now('Asia/Jakarta');

        $orders = Order::query()
            ->whereDate('created_at', $date->toDateString())
            ->get();

        $completedRevenue = $orders
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total_price');

        $cancelled = $orders->where('status', Order::STATUS_CANCELLED)->count();

        return implode("\n", [
            'UP Cireng - rekap harian',
            'Tanggal: ' . $date->translatedFormat('d F Y'),
            'Total order: ' . $orders->count(),
            'Pending: ' . $orders->where('status', Order::STATUS_PENDING)->count(),
            'Diproses: ' . $orders->where('status', Order::STATUS_PROCESSING)->count(),
            'Dikirim: ' . $orders->where('status', Order::STATUS_DELIVERING)->count(),
            'Selesai: ' . $orders->where('status', Order::STATUS_COMPLETED)->count(),
            'Batal: ' . $cancelled,
            'Omzet selesai: Rp ' . number_format($completedRevenue, 0, ',', '.'),
        ]);
    }

    public function send(?CarbonInterface $date = null): string
    {
        $date = $date ? Carbon::parse($date, 'Asia/Jakarta') : now('Asia/Jakarta');
        $message = $this->buildMessage($date);

        // Kirim via WhatsApp/Fonnte (existing)
        NotificationService::sendDailyRecap($message, $date);

        // Kirim via Email ke admin
        $this->sendEmail($date);

        return $message;
    }

    protected function sendEmail(CarbonInterface $date): void
    {
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));

        if (!$adminEmail) {
            Log::warning('DailyRecapService: ADMIN_EMAIL tidak diset, email rekap tidak dikirim.');
            return;
        }

        $orders = Order::query()
            ->whereDate('created_at', $date->toDateString())
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $totalRevenue = $orders
            ->whereNotIn('status', [Order::STATUS_CANCELLED])
            ->sum('total_price');

        try {
            Mail::to($adminEmail)->send(new DailyOrderRecap(
                orders: $orders,
                date: $date->translatedFormat('d F Y'),
                totalRevenue: $totalRevenue,
                totalOrders: $orders->count(),
            ));

            Log::info('Daily recap email sent', [
                'date'          => $date->toDateString(),
                'total_orders'  => $orders->count(),
                'total_revenue' => $totalRevenue,
                'admin_email'   => $adminEmail,
            ]);
        } catch (\Throwable $e) {
            Log::error('DailyRecapService: Gagal kirim email rekap', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}