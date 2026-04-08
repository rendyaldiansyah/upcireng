<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Baru</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0f172a, #1e3a5f); padding: 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 20px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.7); font-size: 13px; margin-top: 6px; }
        .alert-banner { background: #fef3c7; border-bottom: 2px solid #f59e0b; padding: 12px 24px; text-align: center; font-size: 13px; font-weight: 700; color: #92400e; }
        .body { padding: 28px 32px; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; margin: 20px 0 10px; }
        .section-title:first-child { margin-top: 0; }
        .info-box { background: #f8fafc; border-radius: 12px; padding: 16px 18px; margin-bottom: 4px; }
        .info-row { display: flex; justify-content: space-between; align-items: flex-start; font-size: 13px; padding: 6px 0; border-bottom: 1px solid #f1f5f9; }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: #64748b; min-width: 140px; }
        .info-row .value { font-weight: 600; color: #0f172a; text-align: right; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .items-table th { background: #1e293b; color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 10px 12px; text-align: left; }
        .items-table th:last-child { text-align: right; }
        .items-table td { padding: 11px 12px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
        .items-table td:last-child { text-align: right; font-weight: 700; }
        .total-row td { background: #f0fdf4; font-weight: 800; color: #16a34a; font-size: 15px; border-bottom: none; }
        .proof-btn { display: inline-block; background: #dbeafe; color: #1d4ed8 !important; text-decoration: none; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 8px; margin-top: 8px; }
        .footer-cta { text-align: center; margin: 24px 0 8px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #f97316, #ea580c); color: #fff !important; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 10px; }
        .footer { background: #f8fafc; padding: 20px 32px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>🛒 Ada Pesanan Baru!</h1>
        <p>Notifikasi otomatis · UP Cireng Admin</p>
    </div>

    <div class="alert-banner">
        ⚡ Pesanan masuk pada <?php echo e($order->created_at->translatedFormat('d F Y, H:i')); ?> WIB
    </div>

    <div class="body">

        
        <p class="section-title">Ringkasan Pesanan</p>
        <div class="info-box">
            <div class="info-row">
                <span class="label">Nomor Referensi</span>
                <span class="value"><?php echo e($order->reference); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Total</span>
                <span class="value" style="color:#ea580c;font-size:15px;"><?php echo e($order->formatPrice()); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Pembayaran</span>
                <span class="value"><?php echo e(strtoupper(str_replace('_', ' ', $order->payment_method))); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Status</span>
                <span class="value"><?php echo e($order->status_label); ?></span>
            </div>
        </div>

        
        <p class="section-title">Detail Item</p>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->items_summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <?php echo e($item['product_name']); ?>

                        <?php if($item['variant']): ?>
                            <br><small style="color:#94a3b8"><?php echo e($item['variant']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e((int)$item['quantity']); ?>x</td>
                    <td>Rp <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td><?php echo e($order->formatPrice()); ?></td>
                </tr>
            </tbody>
        </table>

        
        <p class="section-title">Data Pelanggan</p>
        <div class="info-box">
            <div class="info-row">
                <span class="label">Nama</span>
                <span class="value"><?php echo e($order->customer_name); ?></span>
            </div>
            <div class="info-row">
                <span class="label">No. WhatsApp</span>
                <span class="value"><?php echo e($order->customer_phone); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Email</span>
                <span class="value"><?php echo e($order->customer_email); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Alamat</span>
                <span class="value"><?php echo e($order->delivery_address); ?></span>
            </div>
            <?php if($order->notes): ?>
            <div class="info-row">
                <span class="label">Catatan</span>
                <span class="value"><?php echo e($order->notes); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <?php if($order->payment_proof_path): ?>
        <p class="section-title">Bukti Pembayaran</p>
        <div class="info-box">
            <a href="<?php echo e(route('payment.proof', $order->id)); ?>" class="proof-btn" target="_blank">
                🖼️ Lihat Bukti Pembayaran (Preview + Watermark)
            </a>
        </div>
        <?php endif; ?>

        <div class="footer-cta">
            <a href="<?php echo e(url('/admin/pesanan/' . $order->id)); ?>" class="btn">Kelola Pesanan di Dashboard →</a>
        </div>

    </div>

    <div class="footer">
        <p>Email otomatis dari sistem UP Cireng · Jangan dibalas</p>
    </div>

</div>
</body>
</html><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\emails\Order notification admin.blade.php ENDPATH**/ ?>