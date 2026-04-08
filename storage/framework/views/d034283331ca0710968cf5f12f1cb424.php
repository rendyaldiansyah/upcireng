<?php $__env->startSection('title', 'Dashboard Customer - UP Cireng'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-[2rem] bg-white p-10 text-center shadow-xl shadow-brand-500/20">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Akun Pelanggan</p>
        <h1 class="mt-4 text-4xl font-black text-slate-950">Kelola pesananmu dari sini</h1>
        <p class="mt-4 text-base leading-7 text-slate-600">
            Lihat riwayat pesanan terbaru, track status pengiriman, batalkan order, atau
            bagikan testimoni tentang pengalaman berbelanja kamu.
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="<?php echo e(route('home')); ?>" class="rounded-full bg-gradient-to-r from-ink-950 to-brand-600 px-6 py-3 text-sm font-bold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                ← Kembali ke Toko
            </a>
            <a href="<?php echo e(route('orders.my')); ?>" class="rounded-full border border-slate-200 px-6 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                Lihat pesanan saya
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\customer\dashboard.blade.php ENDPATH**/ ?>