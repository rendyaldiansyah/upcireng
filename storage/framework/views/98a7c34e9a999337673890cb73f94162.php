

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                📸 Bukti Pembayaran
            </h1>
            <p class="text-slate-600 mt-2">
                Referensi: <span class="font-mono text-sm bg-slate-200 px-2 py-1 rounded"><?php echo e($order->reference); ?></span>
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Image Container -->
            <div class="relative bg-slate-900 p-6">
                <div class="rounded-lg overflow-hidden bg-slate-800 aspect-video">
                    <img 
                        src="<?php echo e($previewUrl); ?>" 
                        alt="Bukti Pembayaran" 
                        class="w-full h-full object-contain"
                        loading="lazy"
                    />
                </div>
                
                <!-- Watermark Info Badge -->
                <div class="mt-4 text-center text-xs text-slate-400">
                    <p>✓ Gambar dilindungi dengan watermark UP CIRENG</p>
                </div>
            </div>

            <!-- Order Details Section -->
            <div class="p-8 space-y-6">
                
                <!-- Customer Info -->
                <div class="border-b border-slate-200 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Nama Pemesan</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">
                                <?php echo e($customerName); ?>

                            </p>
                        </div>
                        <div>
                            <p class="text-slate-600 text-sm font-medium">No. Telepon</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">
                                <a href="tel:<?php echo e($order->customer_phone); ?>" class="text-blue-600 hover:underline">
                                    <?php echo e($order->customer_phone); ?>

                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border-b border-slate-200 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Metode Pembayaran</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1 capitalize">
                                <?php echo e(str_replace('_', ' ', $order->payment_method)); ?>

                            </p>
                        </div>
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Total Pembayaran</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">
                                <?php echo e($totalPrice); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="bg-gradient-to-r <?php echo e($status['color'] === 'yellow' ? 'from-yellow-50 to-yellow-100' :
                    ($status['color'] === 'blue' ? 'from-blue-50 to-blue-100' :
                    ($status['color'] === 'indigo' ? 'from-indigo-50 to-indigo-100' :
                    ($status['color'] === 'green' ? 'from-green-50 to-green-100' :
                    ($status['color'] === 'red' ? 'from-red-50 to-red-100' :
                    'from-gray-50 to-gray-100'))))); ?> rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Status Pesanan</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">
                                <?php echo e($status['icon']); ?> <?php echo e($status['label']); ?>

                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-600">Tanggal Pesanan</p>
                            <p class="text-sm font-medium text-slate-900 mt-1">
                                <?php echo e($orderDate); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Items Summary -->
                <?php if($order->items && is_array($order->items)): ?>
                <div class="border-t border-slate-200 pt-6">
                    <p class="text-sm font-medium text-slate-600 mb-4">Pesanan Anda:</p>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-start bg-slate-50 p-3 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-slate-900"><?php echo e($item['name'] ?? 'Produk'); ?></p>
                                <p class="text-sm text-slate-600 mt-1">
                                    <?php echo e($item['quantity'] ?? 1); ?>x @ Rp <?php echo e(number_format($item['price'] ?? 0, 0, ',', '.')); ?>

                                </p>
                                <?php if(isset($item['variant'])): ?>
                                <p class="text-xs text-slate-500 mt-1">
                                    <span class="bg-slate-200 px-2 py-0.5 rounded"><?php echo e($item['variant']); ?></span>
                                </p>
                                <?php endif; ?>
                            </div>
                            <p class="font-semibold text-slate-900">
                                Rp <?php echo e(number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.')); ?>

                            </p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- Action Buttons -->
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-200 flex flex-col md:flex-row gap-3 justify-center">
                <a 
                    href="<?php echo e(route('payment.download', $order->id)); ?>"
                    download
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors"
                >
                    📥 Download Bukti
                </a>
                
                <button 
                    type="button"
                    onclick="sharePaymentProof()"
                    class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors"
                >
                    💬 Bagikan ke WhatsApp
                </button>

                <a 
                    href="<?php echo e(route('orders.my')); ?>"
                    class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-lg transition-colors"
                >
                    ← Kembali ke Pesanan
                </a>
            </div>

        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-slate-600 text-sm">
            <p>
                Data pemesanan Anda aman dan dilindungi. 
                <br>
                Jika ada pertanyaan, hubungi kami melalui WhatsApp.
            </p>
        </div>

    </div>
</div>

<!-- Share Script -->
<script>
function sharePaymentProof() {
    const orderRef = "<?php echo e($order->reference); ?>";
    const paymentUrl = "<?php echo e(route('payment.proof', $order->id)); ?>";
    const message = `📸 Bukti Pembayaran\n\nReferensi: ${orderRef}\nTotal: <?php echo e($totalPrice); ?>\n\nLihat bukti: ${paymentUrl}`;
    
    // WhatsApp share
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

// Copy link functionality (optional)
function copyPaymentLink() {
    const link = "<?php echo e(route('payment.proof', $order->id)); ?>";
    navigator.clipboard.writeText(link).then(() => {
        alert('Link berhasil disalin!');
    });
}
</script>

<style>
    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\payment\preview.blade.php ENDPATH**/ ?>