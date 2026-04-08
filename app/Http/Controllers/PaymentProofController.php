<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    /**
     * Display payment proof preview page
     * GET /payment/{orderId}
     */
    public function show($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            abort(404, 'Order tidak ditemukan');
        }

        if (!$order->payment_proof_path || !Storage::disk('public')->exists($order->payment_proof_path)) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $previewUrl = route('payment.preview.image', $orderId);

        return view('payment.preview', [
            'order'        => $order,
            'previewUrl'   => $previewUrl,
            'customerName' => $order->customer_name ?? 'Customer',
            'totalPrice'   => $this->formatPrice($order->total_price),
            'orderDate'    => $order->created_at->format('d/m/Y H:i'),
            'status'       => $this->getStatusLabel($order->status),
        ]);
    }

    /**
     * Stream image dengan watermark
     * GET /payment/{orderId}/image
     */
    public function streamImage($orderId)
    {
        $order = Order::find($orderId);

        if (!$order || !$order->payment_proof_path) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $filePath = $this->resolveFilePath($order->payment_proof_path);

        if (!$filePath) {
            \Log::warning('Payment proof file not found', [
                'order_id'           => $orderId,
                'payment_proof_path' => $order->payment_proof_path,
            ]);
            abort(404, 'File bukti pembayaran tidak ditemukan');
        }

        $mime     = $this->getMimeType($filePath);
        $fileSize = filesize($filePath);
        $imageData = null;

        // Coba watermark hanya jika GD tersedia
        if (extension_loaded('gd')) {
            try {
                $imageData = $this->addWatermarkGD($filePath, 'UP CIRENG • ' . now()->format('d/m/Y H:i'));
            } catch (\Throwable $e) {
                \Log::warning('Watermark gagal: ' . $e->getMessage(), [
                    'order_id' => $orderId,
                ]);
                $imageData = null;
            }
        }

        if ($imageData !== null) {
            $data = $imageData;
            return response()->stream(
                function () use ($data) { echo $data; },
                200,
                [
                    'Content-Type'        => 'image/jpeg',
                    'Content-Disposition' => 'inline; filename="bukti-pembayaran.jpg"',
                    'Cache-Control'       => 'no-store',
                ]
            );
        }

        // Fallback: serve file asli
        return response()->stream(
            function () use ($filePath) { readfile($filePath); },
            200,
            [
                'Content-Type'        => $mime,
                'Content-Length'      => $fileSize,
                'Content-Disposition' => 'inline; filename="bukti-pembayaran.jpg"',
                'Cache-Control'       => 'no-store',
            ]
        );
    }

    /**
     * Coba semua kemungkinan lokasi file
     */
    private function resolveFilePath(string $paymentProofPath): ?string
    {
        $candidates = [
            storage_path('app/public/' . $paymentProofPath),
            storage_path('app/' . $paymentProofPath),
            storage_path('app/public/payment-proofs/' . basename($paymentProofPath)),
            storage_path('app/public/' . basename($paymentProofPath)),
            $paymentProofPath,
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Watermark dengan GD native - paling stabil tanpa library
     */
    private function addWatermarkGD(string $filePath, string $text): string
    {
        $mime = $this->getMimeType($filePath);

        $src = match ($mime) {
            'image/png'  => @imagecreatefrompng($filePath),
            'image/webp' => @imagecreatefromwebp($filePath),
            default      => @imagecreatefromjpeg($filePath),
        };

        if (!$src) {
            throw new \RuntimeException('GD gagal membaca gambar: ' . $filePath);
        }

        $width  = imagesx($src);
        $height = imagesy($src);
        $barH   = 60;

        $canvas = imagecreatetruecolor($width, $height);
        imagecopy($canvas, $src, 0, 0, 0, 0, $width, $height);
        imagedestroy($src);

        $black = imagecolorallocatealpha($canvas, 0, 0, 0, 50);
        imagefilledrectangle($canvas, 0, $height - $barH, $width - 1, $height - 1, $black);

        $white = imagecolorallocate($canvas, 255, 255, 255);
        $font  = 4;
        $textW = imagefontwidth($font) * strlen($text);
        $textX = max(0, intval(($width - $textW) / 2));
        $textY = $height - $barH + intval(($barH - imagefontheight($font)) / 2);
        imagestring($canvas, $font, $textX, $textY, $text, $white);

        ob_start();
        imagejpeg($canvas, null, 85);
        $data = ob_get_clean();
        imagedestroy($canvas);

        return $data;
    }

    private function getMimeType(string $filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return match ($ext) {
            'png'  => 'image/png',
            'webp' => 'image/webp',
            'gif'  => 'image/gif',
            default => 'image/jpeg',
        };
    }

    /**
     * Download bukti pembayaran
     * GET /payment/{orderId}/download
     */
    public function download($orderId)
    {
        $order = Order::find($orderId);

        if (!$order || !$order->payment_proof_path) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $filePath = $this->resolveFilePath($order->payment_proof_path);

        if (!$filePath) {
            abort(404, 'File tidak ditemukan');
        }

        $filename = 'bukti-pembayaran-' . ($order->reference ?? $orderId) . '.jpg';

        return response()->download($filePath, $filename, [
            'Content-Type' => $this->getMimeType($filePath),
        ]);
    }

    private function formatPrice($price): string
    {
        return 'Rp ' . number_format((float) $price, 0, ',', '.');
    }

    private function getStatusLabel(string $status): array
    {
        $statusMap = [
            Order::STATUS_PENDING => [
                'label' => 'Menunggu Verifikasi',
                'color' => 'yellow',
                'icon'  => '⏳',
            ],
            Order::STATUS_PROCESSING => [
                'label' => 'Sedang Diproses',
                'color' => 'blue',
                'icon'  => '⚙️',
            ],
            Order::STATUS_DELIVERING => [
                'label' => 'Sedang Dikirim',
                'color' => 'indigo',
                'icon'  => '🚗',
            ],
            Order::STATUS_COMPLETED => [
                'label' => 'Selesai',
                'color' => 'green',
                'icon'  => '✅',
            ],
            Order::STATUS_CANCELLED => [
                'label' => 'Dibatalkan',
                'color' => 'red',
                'icon'  => '❌',
            ],
        ];

        return $statusMap[$status] ?? [
            'label' => $status,
            'color' => 'gray',
            'icon'  => '❓',
        ];
    }
}