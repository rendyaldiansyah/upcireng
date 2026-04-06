<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dibatalkan</title>
</head>
<body style="margin:0;background:#fff7ed;font-family:Arial,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 20px 40px rgba(239,68,68,0.12);">
            <div style="padding:32px;background:linear-gradient(135deg,#7f1d1d 0%,#ef4444 100%);color:#ffffff;">
                <p style="margin:0;font-size:12px;letter-spacing:0.3em;text-transform:uppercase;opacity:0.8;">UP Cireng</p>
                <h1 style="margin:12px 0 0;font-size:32px;line-height:1.2;">Pesanan dibatalkan.</h1>
            </div>

            <div style="padding:32px;">
                <p>Halo <strong><?php echo e($order->customer_name); ?></strong>,</p>
                <p>Pesanan dengan reference <strong><?php echo e($order->reference); ?></strong> telah dibatalkan di sistem.</p>

                <div style="margin-top:24px;border:1px solid #fecaca;border-radius:20px;padding:20px;background:#fff1f2;">
                    <p style="margin:0 0 8px;"><strong>Status:</strong> <?php echo e($order->status_label); ?></p>
                    <p style="margin:0 0 8px;"><strong>Total:</strong> <?php echo e($order->formatPrice()); ?></p>
                    <p style="margin:0;"><strong>Alasan:</strong> <?php echo e($order->cancel_reason ?: 'Tidak ada alasan tambahan.'); ?></p>
                </div>

                <p style="margin-top:28px;">Jika pembatalan ini tidak sesuai, silakan balas email ini atau hubungi admin melalui WhatsApp toko.</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/emails/order_cancelled.blade.php ENDPATH**/ ?>