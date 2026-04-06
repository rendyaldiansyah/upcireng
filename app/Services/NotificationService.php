<?php

namespace App\Services;

use App\Models\Order;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public static function sendNewOrderAlert(Order $order): void
    {
        $message = implode("\n", [
            'UP Cireng - order baru masuk',
            'Ref: ' . $order->reference,
            'Pelanggan: ' . $order->customer_name,
            'Ringkasan: ' . $order->summary_title,
            'Total: ' . $order->formatPrice(),
            'Metode bayar: ' . strtoupper((string) $order->payment_method),
        ]);

        self::sendText($message);
    }

    public static function sendCancellationAlert(Order $order): void
    {
        $message = implode("\n", [
            'UP Cireng - order dibatalkan',
            'Ref: ' . $order->reference,
            'Pelanggan: ' . $order->customer_name,
            'Status: ' . $order->status_label,
            'Alasan: ' . ($order->cancel_reason ?: 'Tidak ada alasan'),
        ]);

        self::sendText($message);
    }

    public static function sendDailyRecap(string $message, ?CarbonInterface $date = null): void
    {
        self::sendText($message);
    }

    public static function sendText(string $message): void
    {
        self::sendTelegram($message);
        self::sendFonnte($message);
    }

    protected static function sendTelegram(string $message): void
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (!$botToken || !$chatId) {
            return;
        }

        try {
            Http::asForm()
                ->timeout(15)
                ->retry(2, 500)
                ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ])
                ->throw();
        } catch (\Throwable $exception) {
            Log::warning('Telegram notification failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected static function sendFonnte(string $message): void
    {
        $token = config('services.fonnte.token');
        $target = config('services.fonnte.target');
        $url = config('services.fonnte.url');

        if (!$token || !$target || !$url) {
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])
                ->asForm()
                ->timeout(15)
                ->retry(2, 500)
                ->post($url, [
                    'target' => $target,
                    'message' => $message,
                ])
                ->throw();
        } catch (\Throwable $exception) {
            Log::warning('Fonnte notification failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
