<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Diterima</title>
</head>
<body style="margin:0;background:#fff7ed;font-family:Arial,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 20px 40px rgba(249,115,22,0.12);">
            <div style="padding:32px;background:linear-gradient(135deg,#0f172a 0%,#f97316 100%);color:#ffffff;">
                <p style="margin:0;font-size:12px;letter-spacing:0.3em;text-transform:uppercase;opacity:0.8;">UP Cireng</p>
                <h1 style="margin:12px 0 0;font-size:32px;line-height:1.2;">Pesanan Anda sudah kami terima.</h1>
            </div>

            <div style="padding:32px;">
                <p>Halo <strong><?php echo e($order->customer_name); ?></strong>,</p>
                <p>Pesanan Anda sudah tercatat di sistem Laravel dan sedang menunggu diproses admin.</p>

                <div style="margin-top:24px;border:1px solid #fed7aa;border-radius:20px;padding:20px;background:#fff7ed;">
                    <p style="margin:0 0 8px;"><strong>Reference:</strong> <?php echo e($order->reference); ?></p>
                    <p style="margin:0 0 8px;"><strong>Status:</strong> <?php echo e($order->status_label); ?></p>
                    <p style="margin:0 0 8px;"><strong>Total:</strong> <?php echo e($order->formatPrice()); ?></p>
                    <p style="margin:0 0 8px;"><strong>Pembayaran:</strong> <?php echo e(strtoupper($order->payment_method)); ?></p>
                    <p style="margin:0;"><strong>Alamat:</strong> <?php echo e($order->delivery_address); ?></p>
                </div>

                <div style="margin-top:24px;">
                    <p style="margin:0 0 12px;font-weight:bold;">Ringkasan item</p>
                    <?php $__currentLoopData = $order->items_summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="margin-bottom:10px;padding:14px 16px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0;">
                            <strong><?php echo e($item['product_name']); ?></strong>
                            <?php if($item['variant']): ?> | <?php echo e($item['variant']); ?> <?php endif; ?>
                            x <?php echo e($item['quantity']); ?>

                            <div style="margin-top:4px;color:#475569;">Rp <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($order->notes): ?>
                    <p style="margin-top:24px;"><strong>Catatan:</strong> <?php echo e($order->notes); ?></p>
                <?php endif; ?>

                <p style="margin-top:28px;">Anda akan menerima update status berikutnya ketika admin mengubah status pesanan atau ketika order dibatalkan.</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/emails/order_confirmation.blade.php ENDPATH**/ ?>