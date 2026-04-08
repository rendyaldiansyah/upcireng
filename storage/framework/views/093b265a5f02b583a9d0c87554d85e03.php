<?php $__env->startSection('title', 'Pesanan Saya - UP Cireng'); ?>

<?php $__env->startSection('content'); ?>
<?php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); ?>
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#ffffff_30%,#f8fafc_100%)]">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 rounded-[2rem] bg-white p-8 shadow-xl shadow-brand-500/20 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Pesanan Saya</p>
                <h1 class="mt-3 text-4xl font-black text-slate-950">Riwayat pesanan Anda</h1>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Status pesanan diperbarui langsung dari admin. Anda dapat membatalkan pesanan yang masih pending atau sedang diproses.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('home')); ?>" class="rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                    ← Kembali ke Toko
                </a>
                <a href="https://wa.me/<?php echo e($waPhone); ?>" target="_blank" rel="noopener" class="rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                    💬 Hubungi Admin
                </a>
            </div>
        </div>

        <div class="mt-10 space-y-6">
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/70">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-500"><?php echo e($order->reference); ?></p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950"><?php echo e($order->summary_title); ?></h2>
                            <p class="mt-2 text-sm text-slate-500"><?php echo e($order->created_at->translatedFormat('d F Y, H:i')); ?></p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-4 py-2 text-sm font-semibold <?php echo e($order->status_color); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                            <a href="<?php echo e(route('order.show', $order)); ?>" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                                📋 Detail
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        <div class="rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Ringkasan Item</p>
                            <ul class="mt-3 space-y-2 text-sm text-slate-700">
                                <?php $__currentLoopData = $order->items_summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($item['product_name']); ?><?php if($item['variant']): ?> · <?php echo e($item['variant']); ?><?php endif; ?> × <?php echo e($item['quantity']); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pembayaran</p>
                            <p class="mt-3 text-xl font-black text-slate-950"><?php echo e($order->formatPrice()); ?></p>
                            
                            <p class="mt-1 text-sm text-slate-600">
                                <?php echo e(ucwords(str_replace('_', ' ', $order->payment_method))); ?>

                            </p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Status Pesanan</p>
                            
                            <?php
                                $syncLabel = match($order->sync_status) {
                                    'synced'  => '✓ Pesanan tercatat',
                                    'failed'  => '⚠️ Hubungi admin',
                                    default   => '⏳ Sedang diproses',
                                };
                                $syncColor = match($order->sync_status) {
                                    'synced'  => 'text-emerald-700',
                                    'failed'  => 'text-rose-600',
                                    default   => 'text-amber-700',
                                };
                            ?>
                            <p class="mt-3 text-sm font-semibold <?php echo e($syncColor); ?>"><?php echo e($syncLabel); ?></p>
                            <?php if($order->sync_status === 'failed'): ?>
                                <p class="mt-1 text-xs text-rose-500 leading-5">Ada masalah teknis, pesanan Anda tetap aman. Hubungi admin jika perlu.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <?php if($order->status === 'pending' && $order->payment_method !== 'cod'): ?>
                        <div class="mt-4 flex items-start gap-3 rounded-2xl bg-amber-50 border border-amber-200 px-4 py-3">
                            <span class="text-lg flex-shrink-0">⏳</span>
                            <div>
                                <p class="text-sm font-bold text-amber-800">Menunggu verifikasi admin</p>
                                <p class="text-xs text-amber-700 mt-0.5">
                                    Pembayaran Anda sedang dicek. Biasanya 5–15 menit.
                                    <a href="<?php echo e(route('payment.proof', $order->id)); ?>" class="underline font-semibold">Lihat bukti bayar →</a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <?php if($order->canBeCancelled()): ?>
                            <form action="<?php echo e(route('order.cancel', $order)); ?>" method="POST" class="flex-1 min-w-64">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="text" name="cancel_reason" placeholder="Alasan pembatalan (opsional)" class="mb-3 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                                <button type="submit" class="w-full rounded-2xl bg-rose-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-600">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if($order->canBeDeletedByCustomer()): ?>
                            <form action="<?php echo e(route('order.destroy', $order)); ?>" method="POST" onsubmit="return confirm('Sembunyikan order ini dari riwayat Anda?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                                    Hapus dari Riwayat
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500">
                    Anda belum memiliki pesanan.
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8">
            <?php echo e($orders->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/order/my_orders.blade.php ENDPATH**/ ?>