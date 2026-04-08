<?php $__env->startSection('title', 'Tulis Testimoni - UP Cireng'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#f8fafc_100%)]">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] bg-white p-6 shadow-xl shadow-brand-500/20 sm:p-8">

            
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Bagikan Pengalaman</p>
                    <h1 class="mt-2 text-2xl font-black text-slate-900 sm:mt-3 sm:text-3xl">Tulis testimoni Anda</h1>
                    <p class="mt-2 text-sm leading-7 text-slate-600 sm:mt-3">
                        Testimoni Anda akan ditinjau admin kami dan ditampilkan di halaman testimoni untuk pelanggan lainnya.
                    </p>
                </div>

                
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start">
                    <a href="<?php echo e(route('storefront.index')); ?>"
                       class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-brand-300 hover:text-brand-600 sm:px-4 sm:text-sm whitespace-nowrap">
                        ← Kembali ke Toko
                    </a>
                    <a href="<?php echo e(route('testimonial.create')); ?>"
                       class="inline-flex items-center gap-1.5 rounded-full border border-brand-200 bg-brand-50 px-3 py-2 text-xs font-semibold text-brand-600 transition hover:bg-brand-100 sm:px-4 sm:text-sm whitespace-nowrap">
                        ✍️ Bagikan Testimoni
                    </a>
                </div>
            </div>

            <?php if($errors->any()): ?>
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('testimonial.store')); ?>" method="POST" class="mt-8 space-y-5">
                <?php echo csrf_field(); ?>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" name="customer_name" value="<?php echo e(old('customer_name', $user?->name)); ?>"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="customer_email" value="<?php echo e(old('customer_email', $user?->email)); ?>"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Rating</label>
                    <select name="rating" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                        <?php for($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo e($i); ?>" <?php if(old('rating', 5) == $i): echo 'selected'; endif; ?>><?php echo e(str_repeat('★', $i)); ?><?php echo e(str_repeat('☆', 5 - $i)); ?> (<?php echo e($i); ?>)</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Isi Testimoni</label>
                    <textarea name="message" rows="6"
                              class="w-full rounded-[1.5rem] border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100"
                              required placeholder="Bagikan pengalaman Anda berbelanja dengan kami..."><?php echo e(old('message')); ?></textarea>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3 text-sm font-bold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                    ✓ Kirim Testimoni
                </button>
            </form>
        </div>

        
        <p class="mt-8 text-center text-xs font-medium text-slate-400">
            &copy; <?php echo e(date('Y')); ?> UP Cireng. All rights reserved.
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/testimonial/create.blade.php ENDPATH**/ ?>