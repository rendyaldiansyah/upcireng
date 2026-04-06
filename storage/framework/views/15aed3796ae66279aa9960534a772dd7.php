<?php $__env->startSection('title', 'Testimoni - UP Cireng'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#fffbeb_30%,#f8fafc_100%)]">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 rounded-[2rem] bg-white p-8 shadow-xl shadow-brand-500/20 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Testimoni Pelanggan</p>
                <h1 class="mt-3 text-4xl font-black tracking-tight text-slate-900">Apa kata pelanggan kami</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                    Testimoni asli dari pelanggan setia yang sudah memesan dan disetujui admin kami untuk ditampilkan di sini.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('home')); ?>" class="rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                    ← Kembali ke Toko
                </a>
                <?php if(session('user_id')): ?>
                    <a href="<?php echo e(route('testimonial.create')); ?>" class="rounded-full bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                        ✍️ Bagikan Testimoni
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/60">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900"><?php echo e($testimonial->customer_name); ?></h2>
                            <div class="mt-2 flex gap-1">
                                <?php for($i = 0; $i < 5; $i++): ?>
                                    <svg class="w-4 h-4 <?php echo e($i < intval($testimonial->rating_stars) ? 'text-yellow-400 fill-current' : 'text-slate-300'); ?>" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                            <?php echo e($testimonial->created_at->translatedFormat('d M Y')); ?>

                        </span>
                    </div>
                    <p class="mt-5 text-sm leading-7 text-slate-600"><?php echo e($testimonial->message); ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="md:col-span-2 xl:col-span-3 rounded-[2rem] border border-dashed border-slate-300 bg-gradient-to-br from-slate-50 to-brand-50/10 px-6 py-16 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <p class="text-slate-500">Belum ada testimoni yang disetujui</p>
                    <p class="text-sm text-slate-400 mt-1">Jadilah yang pertama berbagi pengalaman berbelanja Anda!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8">
            <?php echo e($testimonials->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/testimonial/index.blade.php ENDPATH**/ ?>