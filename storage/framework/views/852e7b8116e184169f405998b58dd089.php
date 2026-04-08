<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - UP Cireng</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #f97316; color: white; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 8px 0 0; opacity: 0.9; font-size: 14px; }
        .body { padding: 30px 40px; }
        .greeting { font-size: 16px; color: #333; margin-bottom: 20px; }
        .info-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .info-box h3 { margin: 0 0 15px; color: #ea580c; font-size: 15px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .info-row .label { color: #666; }
        .info-row .value { font-weight: bold; color: #333; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        .items-table th { background: #f9fafb; padding: 10px; text-align: left; border-bottom: 2px solid #e5e7eb; color: #374151; }
        .items-table td { padding: 10px; border-bottom: 1px solid #f3f4f6; color: #374151; }
        .total-row td { font-weight: bold; font-size: 16px; color: #ea580c; border-top: 2px solid #e5e7eb; border-bottom: none; }
        .status-badge { display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; font-size: 13px; color: #6b7280; border-top: 1px solid #e5e7eb; }
        .footer a { color: #f97316; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🛍️ UP Cireng</h1>
        <p>Pesanan Anda Berhasil Dibuat!</p>
    </div>

    <div class="body">
        <p class="greeting">Halo, <strong><?php echo e($order->customer_name); ?></strong>!</p>
        <p style="color:#555;font-size:14px;">Terima kasih telah memesan di UP Cireng. Pesanan Anda sedang kami proses. Berikut adalah detail pesanan Anda:</p>

        <div class="info-box">
            <h3>📋 Informasi Pesanan</h3>
            <div class="info-row">
                <span class="label">Nomor Referensi</span>
                <span class="value"><?php echo e($order->reference); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal Pesan</span>
                <span class="value"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Metode Pembayaran</span>
                <span class="value"><?php echo e(ucwords(str_replace('_', ' ', $order->payment_method))); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Status</span>
                <span class="value"><span class="status-badge">⏳ Menunggu Verifikasi</span></span>
            </div>
        </div>

        
        <?php if($order->items && count($order->items) > 0): ?>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th style="text-align:center">Qty</th>
                    <th style="text-align:right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <?php echo e($item['product_name'] ?? 'Produk'); ?>

                        <?php if(!empty($item['variant'])): ?>
                            <br><small style="color:#9ca3af"><?php echo e($item['variant']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center"><?php echo e($item['quantity'] ?? 1); ?></td>
                    <td style="text-align:right">Rp <?php echo e(number_format($item['subtotal'] ?? 0, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td style="text-align:right">Rp <?php echo e(number_format($order->total_price, 0, ',', '.')); ?></td>
                </tr>
            </tbody>
        </table>
        <?php endif; ?>

        <div class="info-box">
            <h3>📍 Informasi Pengiriman</h3>
            <div class="info-row">
                <span class="label">Nama</span>
                <span class="value"><?php echo e($order->customer_name); ?></span>
            </div>
            <div class="info-row">
                <span class="label">No. WhatsApp</span>
                <span class="value"><?php echo e($order->customer_phone); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Alamat</span>
                <span class="value" style="text-align:right;max-width:300px"><?php echo e($order->delivery_address); ?></span>
            </div>
        </div>

        <p style="color:#555;font-size:14px;text-align:center;">
            Kami akan menghubungi Anda melalui WhatsApp untuk konfirmasi pesanan.<br>
            Jika ada pertanyaan, hubungi kami melalui WhatsApp.
        </p>
    </div>

    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem UP Cireng.</p>
        <p>© <?php echo e(date('Y')); ?> UP Cireng. All rights reserved.</p>
    </div>
</div>
</body>
</html><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/emails/order-confirmation-customer.blade.php ENDPATH**/ ?>