<?php $__env->startSection('title', 'Admin — Warung Cireng'); ?>
<?php $__env->startSection('hide_nav', '1'); ?>
<?php $__env->startSection('hide_footer', '1'); ?>
<?php $__env->startSection('body_class', 'antialiased'); ?>

<?php $__env->startSection('content'); ?>
<div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10"
     style="background: linear-gradient(135deg, #080d08 0%, #0f1a10 40%, #0a1200 100%)">

    
    <div class="pointer-events-none absolute inset-0">
        
        <div class="absolute -left-48 top-0 h-[500px] w-[500px] rounded-full blur-[120px]"
             style="background: radial-gradient(circle, rgba(34,197,94,0.10) 0%, transparent 70%); animation: drift1 8s ease-in-out infinite alternate;"></div>
        <div class="absolute -right-48 bottom-0 h-[400px] w-[400px] rounded-full blur-[100px]"
             style="background: radial-gradient(circle, rgba(249,115,22,0.08) 0%, transparent 70%); animation: drift2 10s ease-in-out infinite alternate;"></div>
        <div class="absolute left-1/2 top-1/3 h-48 w-48 -translate-x-1/2 rounded-full blur-[80px]"
             style="background: radial-gradient(circle, rgba(34,197,94,0.06) 0%, transparent 70%);"></div>

        
        <div class="absolute inset-0 opacity-[0.035]"
             style="background-image: linear-gradient(rgba(255,255,255,0.4) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 56px 56px;"></div>

        
        <div class="absolute top-10 right-10 h-28 w-28 rounded-2xl border border-white/5 rotate-[15deg]"></div>
        <div class="absolute bottom-16 left-10 h-20 w-20 rounded-xl border border-green-500/8 -rotate-[8deg]"></div>
        <div class="absolute top-1/2 right-1/4 h-10 w-10 rounded-lg border border-orange-500/10 rotate-[30deg]"></div>
    </div>

    <div class="relative z-10 w-full max-w-[400px]">

        
        <div class="mb-8 text-center" style="animation: slideDown 0.6s cubic-bezier(0.22,1,0.36,1) both;">
            <div class="mx-auto mb-5 inline-flex h-20 w-20 items-center justify-center rounded-2xl border border-green-500/15 shadow-2xl"
                 style="background: linear-gradient(145deg, rgba(34,197,94,0.12), rgba(249,115,22,0.06)); backdrop-filter: blur(12px);">
                <img src="<?php echo e(asset('assets/assets/logo.png')); ?>"
                     alt="Warung Cireng"
                     class="h-14 w-14 rounded-xl object-cover">
            </div>
            <h1 class="display-font text-2xl font-extrabold text-white sm:text-[1.75rem]">
                Panel Admin
            </h1>
            <p class="mt-1.5 text-sm text-slate-500">Warung Cireng • Sistem Manajemen</p>
        </div>

        
        <div class="rounded-2xl border border-white/8 p-7 shadow-2xl sm:rounded-3xl sm:p-9"
             style="background: rgba(255,255,255,0.04); backdrop-filter: blur(24px); animation: slideUp 0.6s 0.1s cubic-bezier(0.22,1,0.36,1) both;">

            
            <?php if($errors->any() || session('error')): ?>
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-rose-500/25 bg-rose-500/10 p-3.5">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm font-semibold text-rose-300 space-y-0.5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p><?php echo e($err); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(session('error')): ?><p><?php echo e(session('error')); ?></p><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.auth.login')); ?>" method="POST" class="space-y-5" id="adminForm">
                <?php echo csrf_field(); ?>

                
                <div>
                    <label for="admin_email" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">
                        Email atau Username
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>
                        <input id="admin_email" type="text" name="email"
                               value="<?php echo e(old('email')); ?>"
                               autocomplete="username"
                               placeholder="admin@warungcireng.id"
                               class="w-full rounded-xl border border-white/10 py-3 pl-10 pr-4 text-sm font-semibold text-white placeholder-slate-600 outline-none transition focus:border-green-500/50 focus:ring-2 focus:ring-green-500/15"
                               style="background: rgba(255,255,255,0.06);"
                               required>
                    </div>
                </div>

                
                <div>
                    <label for="admin_password" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">
                        Password
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="admin_password" type="password" name="password"
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full rounded-xl border border-white/10 py-3 pl-10 pr-12 text-sm font-semibold text-white placeholder-slate-600 outline-none transition focus:border-green-500/50 focus:ring-2 focus:ring-green-500/15"
                               style="background: rgba(255,255,255,0.06);"
                               required>
                        
                        <button type="button" id="togglePw"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-slate-300 transition">
                            <svg id="eyeShow" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeHide" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                
                <button type="submit" id="loginBtn"
                        class="group mt-1 w-full rounded-xl py-3.5 text-sm font-bold text-white shadow-lg transition hover:scale-[1.01] active:scale-[0.99]"
                        style="background: linear-gradient(135deg, #16a34a, #15803d); box-shadow: 0 8px 24px -8px rgba(22,163,74,0.4);">
                    <span id="btnLabel" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 transition group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Masuk ke Panel Admin
                    </span>
                </button>
            </form>
        </div>

        
        <div class="mt-6 text-center" style="animation: slideUp 0.6s 0.2s cubic-bezier(0.22,1,0.36,1) both; opacity:0;">
            <a href="<?php echo e(route('home')); ?>"
               class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-slate-300">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Warung
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes slideDown {
        from { opacity:0; transform: translateY(-20px); }
        to   { opacity:1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity:0; transform: translateY(16px); }
        to   { opacity:1; transform: translateY(0); }
    }
    @keyframes drift1 {
        from { transform: translate(0, 0) scale(1); }
        to   { transform: translate(40px, 30px) scale(1.1); }
    }
    @keyframes drift2 {
        from { transform: translate(0, 0) scale(1); }
        to   { transform: translate(-30px, -20px) scale(1.08); }
    }
</style>

<script>
    // Toggle password visibility
    const togglePw  = document.getElementById('togglePw');
    const pwInput   = document.getElementById('admin_password');
    const eyeShow   = document.getElementById('eyeShow');
    const eyeHide   = document.getElementById('eyeHide');

    if (togglePw && pwInput) {
        togglePw.addEventListener('click', function () {
            const isHidden = pwInput.type === 'password';
            pwInput.type   = isHidden ? 'text' : 'password';
            eyeShow.classList.toggle('hidden', isHidden);
            eyeHide.classList.toggle('hidden', !isHidden);
        });
    }

    // Loading state on submit
    document.getElementById('adminForm').addEventListener('submit', function () {
        const btn   = document.getElementById('loginBtn');
        const label = document.getElementById('btnLabel');
        btn.disabled = true;
        label.innerHTML = '<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memverifikasi...';
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\auth\admin_login.blade.php ENDPATH**/ ?>