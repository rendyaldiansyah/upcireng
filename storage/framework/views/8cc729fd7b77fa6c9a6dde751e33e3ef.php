<?php $__env->startSection('title', 'Masuk | UP Cireng'); ?>
<?php $__env->startSection('hide_footer', '1'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative min-h-[calc(100vh-72px)] flex items-center px-4 py-8 sm:py-12 sm:px-6 lg:px-8">

    
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-brand-400/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-brand-600/8 blur-3xl"></div>
    </div>

    <div class="relative mx-auto w-full max-w-6xl">
        <div class="grid gap-6 sm:gap-8 lg:grid-cols-[1fr_1.15fr] lg:items-center">

            
            <div class="flex flex-col justify-between rounded-2xl bg-gradient-to-br from-ink-950 via-ink-900 to-brand-700 p-6 text-white shadow-2xl sm:rounded-3xl sm:p-8 lg:p-10 lg:min-h-[540px]">
                <div>
                    
                    <div class="mb-7 inline-flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/15 bg-white/8 backdrop-blur-sm sm:h-13 sm:w-13">
                            <img src="<?php echo e(asset('assets/assets/logo.png')); ?>"
                                 alt="UP Cireng"
                                 class="h-8 w-8 rounded-xl object-cover sm:h-9 sm:w-9">
                        </div>
                        <div>
                            <p class="display-font text-lg font-extrabold sm:text-xl">UP Cireng</p>
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-brand-300 sm:text-xs">Order & Manage</p>
                        </div>
                    </div>

                    <h1 class="display-font mb-4 text-2xl font-extrabold leading-tight sm:mb-5 sm:text-4xl lg:text-[2.6rem] lg:leading-[1.15]">
                        Pesan cireng<br>
                        <span class="text-brand-300">favorit</span> kamu<br>
                        dengan mudah
                    </h1>

                    <p class="mb-7 max-w-sm text-sm leading-7 text-white/75 sm:mb-8 sm:text-base sm:leading-8">
                        Cukup masukkan nama dan nomor HP. Tidak perlu daftar akun — langsung bisa order!
                    </p>

                    <div class="space-y-4">
                        <?php
                            $features = [
                                ['icon' => '⚡', 'title' => 'Tanpa Daftar',     'desc' => 'Langsung masuk, tidak perlu buat akun'],
                                ['icon' => '📦', 'title' => 'Riwayat Pesanan',  'desc' => 'Lihat semua pesanan dan statusnya'],
                                ['icon' => '🍢', 'title' => 'Order Cepat',      'desc' => 'Checkout hanya butuh beberapa klik'],
                            ];
                        ?>
                        <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-3.5">
                                <span class="mt-0.5 shrink-0 text-lg"><?php echo e($f['icon']); ?></span>
                                <div>
                                    <p class="text-sm font-bold text-white sm:text-base"><?php echo e($f['title']); ?></p>
                                    <p class="text-xs text-white/65 sm:text-sm"><?php echo e($f['desc']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="mt-8 hidden border-t border-white/10 pt-6 sm:block">
                    <p class="mb-2.5 text-[10px] font-bold uppercase tracking-widest text-brand-300 sm:text-xs">Kata Pelanggan</p>
                    <div class="flex gap-1 mb-2">
                        <?php for($i = 0; $i < 5; $i++): ?>
                            <svg class="h-3.5 w-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="text-sm italic leading-relaxed text-white/80">
                        "Gampang banget, tinggal masukin nama sama nomor HP langsung bisa order. Ga ribet!"
                    </blockquote>
                    <p class="mt-2.5 text-sm font-semibold text-white/90">— Dewi, Customer Setia</p>
                </div>
            </div>

            
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-xl sm:rounded-3xl sm:p-8 lg:p-10">

                
                <div class="mb-7 sm:mb-8">
                    <p class="mb-1.5 text-xs font-bold uppercase tracking-[0.32em] text-brand-500 sm:mb-2">Masuk</p>
                    <h2 class="display-font mb-2.5 text-2xl font-extrabold text-ink-950 sm:text-3xl lg:text-4xl">
                        Halo! 👋
                    </h2>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Masukkan nama dan nomor HP kamu untuk melanjutkan. Jika belum pernah order, akun dibuat otomatis.
                    </p>
                </div>

                
                <?php if($errors->any()): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 sm:mb-6">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm font-semibold text-rose-700 space-y-0.5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm font-semibold text-rose-700">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('auth.login')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    
                    <div>
                        <label for="login_name" class="mb-2 block text-sm font-bold text-slate-700">
                            Nama Kamu <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input
                                id="login_name"
                                type="text"
                                name="name"
                                value="<?php echo e(old('name')); ?>"
                                autocomplete="name"
                                placeholder="Contoh: Rendy Al Diansyah"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-300 bg-rose-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required
                            >
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1.5 text-xs font-medium text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="login_phone" class="mb-2 block text-sm font-bold text-slate-700">
                            Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input
                                id="login_phone"
                                type="tel"
                                name="phone"
                                value="<?php echo e(old('phone')); ?>"
                                autocomplete="tel"
                                placeholder="08xx xxxx xxxx"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5 <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-300 bg-rose-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required
                            >
                        </div>
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1.5 text-xs font-medium text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="mt-1.5 text-xs text-slate-400">
                            Nomor HP yang sama akan selalu masuk ke akun yang sama.
                        </p>
                    </div>

                    
                    <div class="flex items-start gap-3 rounded-xl border border-brand-100 bg-brand-50 px-4 py-3.5">
                        <span class="mt-0.5 shrink-0 text-base">💡</span>
                        <p class="text-xs leading-relaxed text-slate-600">
                            <strong class="text-slate-700">Tidak perlu buat akun.</strong>
                            Jika nomor HP belum pernah digunakan, akun baru dibuat otomatis saat kamu klik Masuk.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="mt-1 w-full rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-500/20 transition duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-brand-500/30"
                    >
                        Masuk Sekarang →
                    </button>
                </form>

                <p class="mt-5 text-center text-xs text-slate-400">
                    Ingin order sebagai tamu?
                    <a href="<?php echo e(route('home')); ?>" class="font-bold text-brand-500 hover:text-brand-600 transition">Kembali ke Menu</a>
                </p>
            </div>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\auth\login.blade.php ENDPATH**/ ?>