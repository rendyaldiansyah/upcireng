<?php $__env->startSection('title', 'Admin — Warung Cireng'); ?>
<?php $__env->startSection('hide_nav', '1'); ?>
<?php $__env->startSection('hide_footer', '1'); ?>
<?php $__env->startSection('body_class', 'antialiased'); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-orange-50 via-amber-50 to-white">

    
    
    
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(251,146,60,0.25),_transparent_50%),radial-gradient(ellipse_at_bottom_right,_rgba(249,115,22,0.2),_transparent_50%)] animate-bg-pulse"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="80" height="80" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f97316" fill-opacity="0.04"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

    
    
    
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <?php
            $colors = ['#f97316', '#fb923c', '#fdba74', '#ffedd5', '#ffffff', '#ea580c'];
        ?>
        <?php for($i = 0; $i < 120; $i++): ?>
            <?php
                $size = rand(2, 14);
                $duration = rand(6, 28);
                $delay = rand(0, 25);
                $left = rand(0, 100);
                $top = rand(0, 100);
                $color = $colors[array_rand($colors)];
                $opacity = rand(15, 55) / 100;
                $blur = $size > 8 ? rand(1, 3) : 0;
                $animType = rand(0, 4);
            ?>
            <div class="absolute rounded-full"
                 style="width: <?php echo e($size); ?>px; height: <?php echo e($size); ?>px; background: <?php echo e($color); ?>; left: <?php echo e($left); ?>%; top: <?php echo e($top); ?>%; opacity: <?php echo e($opacity); ?>; filter: blur(<?php echo e($blur); ?>px); animation: float-particle-<?php echo e($animType); ?> <?php echo e($duration); ?>s linear infinite; animation-delay: -<?php echo e($delay); ?>s;">
            </div>
        <?php endfor; ?>

        
        <div class="absolute top-1/5 left-1/6 h-96 w-96 rounded-full bg-orange-300/20 blur-3xl animate-float-orb1"></div>
        <div class="absolute bottom-1/4 right-1/5 h-[30rem] w-[30rem] rounded-full bg-amber-300/25 blur-3xl animate-float-orb2"></div>
        <div class="absolute top-2/3 left-1/2 h-80 w-80 rounded-full bg-orange-400/15 blur-3xl animate-float-orb3"></div>
        <div class="absolute -top-32 -right-32 h-[500px] w-[500px] rounded-full bg-orange-200/30 blur-3xl animate-pulse-glow"></div>
        <div class="absolute -bottom-40 -left-40 h-[450px] w-[450px] rounded-full bg-amber-200/25 blur-3xl animate-pulse-glow-delay"></div>
    </div>

    
    
    
    <div id="cursorSpotlight" class="pointer-events-none fixed z-20 h-96 w-96 rounded-full bg-orange-400/8 blur-3xl transition-all duration-150" style="display: none;"></div>

    
    
    
    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl items-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid w-full gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">

            
            <section class="hidden lg:block">
                <div class="max-w-xl">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white/80 px-5 py-2.5 text-xs font-black tracking-[0.22em] text-orange-600 shadow-lg backdrop-blur-md animate-slide-in-left">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-500 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-orange-500"></span>
                        </span>
                        ADMIN ACCESS • ULTIMATE CONTROL
                    </div>

                    <h1 class="text-6xl font-black leading-[1.05] tracking-tight animate-slide-in-left" style="animation-delay: 0.08s">
                        <span class="bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Warung Cireng</span>
                        <span class="block bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 bg-clip-text text-transparent animate-gradient-shift">Admin Panel</span>
                    </h1>

                    <p class="mt-6 max-w-lg text-lg leading-8 text-slate-600 animate-slide-in-left" style="animation-delay: 0.16s">
                        Dashboard manajemen pesanan, produk, dan operasional toko dengan performa terbaik.
                    </p>

                    <div class="mt-10 grid max-w-lg grid-cols-3 gap-5">
                        <div class="group relative overflow-hidden rounded-2xl border border-orange-100 bg-white/90 p-5 shadow-md backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-200/50 hover:border-orange-300 animate-slide-in-left" style="animation-delay: 0.24s">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-400/0 via-orange-400/10 to-orange-400/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">Akses</p>
                            <p class="mt-2 text-xl font-black text-slate-900 group-hover:text-orange-600 transition">Cepat</p>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-orange-100 bg-white/90 p-5 shadow-md backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-200/50 hover:border-orange-300 animate-slide-in-left" style="animation-delay: 0.28s">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-400/0 via-orange-400/10 to-orange-400/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">UI</p>
                            <p class="mt-2 text-xl font-black text-slate-900 group-hover:text-orange-600 transition">Clean</p>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-orange-100 bg-white/90 p-5 shadow-md backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-200/50 hover:border-orange-300 animate-slide-in-left" style="animation-delay: 0.32s">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-400/0 via-orange-400/10 to-orange-400/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                            <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">Mode</p>
                            <p class="mt-2 text-xl font-black text-slate-900 group-hover:text-orange-600 transition">Secure</p>
                        </div>
                    </div>
                </div>

                
                <div class="relative mt-16 h-[460px]">
                    <div class="absolute left-10 top-8 h-32 w-32 rounded-[2rem] border border-orange-200 bg-white/50 shadow-2xl backdrop-blur-xl animate-float-card-1"></div>
                    <div class="absolute right-20 top-28 h-24 w-24 rounded-2xl border border-amber-200 bg-orange-100/60 shadow-xl backdrop-blur-xl animate-float-card-2"></div>
                    <div class="absolute left-24 bottom-16 h-40 w-40 rounded-[2rem] border border-orange-100 bg-white/70 shadow-2xl backdrop-blur-xl animate-float-card-3"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-96 rounded-full bg-gradient-to-br from-orange-400/25 via-orange-200/15 to-transparent blur-2xl animate-spin-slow"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-80 w-80 rounded-full border-2 border-dashed border-orange-300/30 animate-spin-slow-reverse"></div>

                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="group relative h-80 w-80 rounded-[2.5rem] border border-white/70 bg-white/80 shadow-[0_40px_90px_rgba(249,115,22,0.15)] backdrop-blur-2xl transition-all duration-500 hover:scale-105 hover:shadow-orange-300/40 hover:rotate-1">
                            <div class="absolute inset-0 rounded-[2.5rem] bg-gradient-to-br from-white/80 via-white/30 to-orange-50/70"></div>
                            <div class="absolute inset-4 rounded-[2rem] border border-orange-100/80"></div>
                            <div class="relative z-10 flex h-full flex-col justify-between p-7">
                                <div class="flex items-center justify-between">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 shadow-lg animate-pulse-soft"></div>
                                    <div class="rounded-full border border-orange-200 bg-white/90 px-4 py-1.5 text-[11px] font-black uppercase tracking-[0.24em] text-orange-500 shadow-sm">
                                        ⚡ Live
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.28em] text-slate-400">Dashboard Preview</p>
                                    <div class="mt-4 space-y-3">
                                        <div class="h-3 w-3/4 rounded-full bg-slate-200 animate-pulse"></div>
                                        <div class="h-3 w-1/2 rounded-full bg-slate-200"></div>
                                        <div class="h-3 w-2/3 rounded-full bg-gradient-to-r from-orange-200 to-orange-300"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-xl bg-white p-3 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">Orders<span class="ml-2 text-base font-black text-orange-600">120</span></div>
                                    <div class="rounded-xl bg-white p-3 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">Menu<span class="ml-2 text-base font-black text-orange-600">18</span></div>
                                    <div class="rounded-xl bg-white p-3 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">Sec<span class="ml-2 text-base font-black text-orange-600">On</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            
            <section class="mx-auto w-full max-w-md">
                <div class="mb-6 text-center lg:hidden animate-fade-up">
                    <div class="mx-auto mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-orange-200 bg-white shadow-lg">
                        <img src="<?php echo e(asset('assets/assets/logo.png')); ?>" alt="Warung Cireng" class="h-10 w-10 rounded-xl object-cover">
                    </div>
                    <h1 class="text-2xl font-black text-slate-900">Admin Panel</h1>
                    <p class="mt-1 text-sm text-slate-500">Warung Cireng</p>
                </div>

                
                <div class="login-shell relative overflow-hidden rounded-[2.5rem] border border-white/80 bg-white/80 p-6 shadow-[0_40px_90px_rgba(0,0,0,0.12)] backdrop-blur-2xl sm:p-8 transition-all duration-500 hover:shadow-orange-200/60">
                    
                    <div class="absolute inset-0 rounded-[2.5rem] bg-gradient-to-r from-orange-400 via-orange-500 to-amber-400 opacity-0 blur-lg transition-opacity duration-500 group-hover:opacity-100 pointer-events-none -z-10 animate-border-run"></div>
                    <div class="absolute inset-[2px] rounded-[2.5rem] bg-white/95 backdrop-blur-md -z-5"></div>

                    
                    <div class="pointer-events-none absolute inset-0 rounded-[2.5rem] opacity-0 transition-opacity duration-300 group-hover:opacity-100" id="spotlight"></div>

                    <div class="relative z-10">
                        <div class="mb-7 text-center sm:text-left">
                            <p class="inline-block text-xs font-black uppercase tracking-[0.28em] text-orange-500 bg-orange-50 px-3 py-1 rounded-full animate-slide-in-right">✦ Masuk ke dashboard ✦</p>
                            <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl animate-slide-in-right" style="animation-delay: 0.05s">
                                Selamat datang<br>kembali
                            </h2>
                            <p class="mt-2 text-sm leading-7 text-slate-500 animate-slide-in-right" style="animation-delay: 0.1s">
                                Login untuk mengelola pesanan dan operasional toko.
                            </p>
                        </div>

                        <?php if($errors->any() || session('error')): ?>
                            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 animate-shake">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <p class="flex items-center gap-2"><svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg><?php echo e($err); ?></p> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(session('error')): ?> <p><?php echo e(session('error')); ?></p> <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('admin.auth.login')); ?>" method="POST" id="adminForm" class="space-y-5">
                            <?php echo csrf_field(); ?>

                            <div class="group animate-slide-in-right" style="animation-delay: 0.15s">
                                <label for="admin_email" class="mb-2 block text-[11px] font-black uppercase tracking-[0.24em] text-slate-500">
                                    Email atau Username
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-all duration-300 group-focus-within:text-orange-500 group-focus-within:scale-110">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                    </span>
                                    <input id="admin_email" type="text" name="email" value="<?php echo e(old('email')); ?>" autocomplete="username"
                                           placeholder="admin@warungcireng.id"
                                           class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3.5 pl-12 text-sm font-semibold text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:shadow-lg hover:border-orange-300"
                                           required>
                                </div>
                            </div>

                            <div class="group animate-slide-in-right" style="animation-delay: 0.2s">
                                <label for="admin_password" class="mb-2 block text-[11px] font-black uppercase tracking-[0.24em] text-slate-500">
                                    Password
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-all duration-300 group-focus-within:text-orange-500 group-focus-within:scale-110">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                    <input id="admin_password" type="password" name="password" autocomplete="current-password"
                                           placeholder="••••••••"
                                           class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3.5 pl-12 pr-12 text-sm font-semibold text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:shadow-lg hover:border-orange-300"
                                           required>
                                    <button type="button" id="togglePw"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full p-2 text-slate-400 transition-all duration-300 hover:bg-orange-100 hover:text-orange-600 hover:scale-110 active:scale-95">
                                        <svg id="eyeShow" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg id="eyeHide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" id="loginBtn"
                                    class="group relative mt-4 w-full overflow-hidden rounded-2xl bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 px-5 py-4 text-base font-black text-white shadow-[0_20px_35px_rgba(249,115,22,0.4)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_30px_50px_rgba(249,115,22,0.5)] active:translate-y-0 active:scale-[0.97] animate-slide-in-right"
                                    style="animation-delay: 0.25s">
                                <span class="absolute inset-0 bg-[linear-gradient(110deg,transparent_25%,rgba(255,255,255,0.5)_35%,transparent_45%)] bg-[length:200%_100%] opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:animate-shine"></span>
                                <span class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 group-hover:animate-pulse-ring"></span>
                                <span class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 group-hover:animate-ripple-burst"></span>
                                <span id="btnLabel" class="relative inline-flex items-center justify-center gap-3">
                                    <svg class="h-5 w-5 transition-all duration-300 group-hover:rotate-12 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Masuk ke Panel Admin
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-8 text-center animate-fade-up" style="animation-delay: 0.3s">
                    <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 transition-all duration-300 hover:text-orange-500 hover:gap-3 hover:scale-105">
                        <svg class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke website
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    /* ========== KEYFRAMES SUPER MAKSIMAL ========== */
    @keyframes float-particle-0 { 0% { transform: translateY(0) translateX(0); opacity: 0; } 10% { opacity: 0.8; } 90% { opacity: 0.6; } 100% { transform: translateY(-100vh) translateX(40px); opacity: 0; } }
    @keyframes float-particle-1 { 0% { transform: translateY(0) translateX(0); opacity: 0; } 10% { opacity: 0.7; } 90% { opacity: 0.5; } 100% { transform: translateY(-100vh) translateX(-50px); opacity: 0; } }
    @keyframes float-particle-2 { 0% { transform: translateY(0) translateX(0); opacity: 0; } 10% { opacity: 0.9; } 90% { opacity: 0.7; } 100% { transform: translateY(-100vh) translateX(20px) rotate(180deg); opacity: 0; } }
    @keyframes float-particle-3 { 0% { transform: translateY(0) translateX(0); opacity: 0; } 10% { opacity: 0.6; } 90% { opacity: 0.4; } 100% { transform: translateY(-100vh) translateX(-30px) scale(0.5); opacity: 0; } }
    @keyframes float-particle-4 { 0% { transform: translateY(0) translateX(0); opacity: 0; } 10% { opacity: 0.8; } 90% { opacity: 0.6; } 100% { transform: translateY(-100vh) translateX(60px) rotate(-90deg); opacity: 0; } }
    @keyframes float-orb1 { 0%,100% { transform: translate3d(0,0,0) scale(1); } 50% { transform: translate3d(40px,-30px,0) scale(1.12); } }
    @keyframes float-orb2 { 0%,100% { transform: translate3d(0,0,0) scale(1); } 50% { transform: translate3d(-35px,35px,0) scale(1.15); } }
    @keyframes float-orb3 { 0%,100% { transform: translate3d(0,0,0) scale(1); } 50% { transform: translate3d(25px,20px,0) scale(1.08); } }
    @keyframes float-card-1 { 0%,100% { transform: translate3d(0,0,0) rotate(6deg); } 50% { transform: translate3d(0,-18px,0) rotate(12deg); } }
    @keyframes float-card-2 { 0%,100% { transform: translate3d(0,0,0) rotate(-8deg); } 50% { transform: translate3d(0,-14px,0) rotate(-14deg); } }
    @keyframes float-card-3 { 0%,100% { transform: translate3d(0,0,0) rotate(4deg); } 50% { transform: translate3d(0,-16px,0) rotate(-2deg); } }
    @keyframes shine { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    @keyframes bg-pulse { 0% { opacity: 0.6; } 100% { opacity: 1; } }
    @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(249,115,22,0.6); } 70% { box-shadow: 0 0 0 20px rgba(249,115,22,0); } 100% { box-shadow: 0 0 0 0 rgba(249,115,22,0); } }
    @keyframes ripple-burst { 0% { transform: scale(0); opacity: 0.5; } 100% { transform: scale(2); opacity: 0; } }
    @keyframes gradient-shift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    @keyframes border-run { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    @keyframes spin-slow { from { transform: translate(-50%, -50%) rotate(0deg); } to { transform: translate(-50%, -50%) rotate(360deg); } }
    @keyframes spin-slow-reverse { from { transform: translate(-50%, -50%) rotate(0deg); } to { transform: translate(-50%, -50%) rotate(-360deg); } }
    @keyframes slide-in-left { from { opacity: 0; transform: translateX(-50px); filter: blur(6px); } to { opacity: 1; transform: translateX(0); filter: blur(0); } }
    @keyframes slide-in-right { from { opacity: 0; transform: translateX(50px); filter: blur(6px); } to { opacity: 1; transform: translateX(0); filter: blur(0); } }
    @keyframes fade-up { from { opacity: 0; transform: translateY(30px); filter: blur(4px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
    @keyframes shake { 0%,100% { transform: translateX(0); } 10%,30%,50%,70%,90% { transform: translateX(-4px); } 20%,40%,60%,80% { transform: translateX(4px); } }
    @keyframes pulse-soft { 0%,100% { opacity: 0.7; transform: scale(1); } 50% { opacity: 1; transform: scale(1.05); } }
    @keyframes pulse-glow-delay { 0%,100% { opacity: 0.15; transform: scale(1); } 50% { opacity: 0.35; transform: scale(1.1); } }

    .animate-bg-pulse { animation: bg-pulse 8s ease-in-out infinite alternate; }
    .animate-float-orb1 { animation: float-orb1 14s ease-in-out infinite; }
    .animate-float-orb2 { animation: float-orb2 16s ease-in-out infinite; }
    .animate-float-orb3 { animation: float-orb3 12s ease-in-out infinite; }
    .animate-float-card-1 { animation: float-card-1 8s ease-in-out infinite; }
    .animate-float-card-2 { animation: float-card-2 9s ease-in-out infinite; }
    .animate-float-card-3 { animation: float-card-3 10s ease-in-out infinite; }
    .animate-pulse-glow { animation: pulse-glow 5s ease-in-out infinite; }
    .animate-pulse-glow-delay { animation: pulse-glow-delay 7s ease-in-out infinite; }
    .animate-pulse-soft { animation: pulse-soft 3s ease-in-out infinite; }
    .animate-spin-slow { animation: spin-slow 25s linear infinite; }
    .animate-spin-slow-reverse { animation: spin-slow-reverse 20s linear infinite; }
    .animate-shine { animation: shine 1.1s linear infinite; }
    .animate-pulse-ring { animation: pulse-ring 0.8s cubic-bezier(0.4,0,0.2,1) infinite; }
    .animate-ripple-burst { animation: ripple-burst 0.6s cubic-bezier(0.4,0,0.2,1) 1; }
    .animate-gradient-shift { background-size: 200% auto; animation: gradient-shift 3s ease infinite; }
    .animate-border-run { background-size: 200% 100%; animation: border-run 2.5s linear infinite; }
    .animate-slide-in-left { animation: slide-in-left 0.6s cubic-bezier(0.2,0.9,0.4,1.1) forwards; opacity: 0; }
    .animate-slide-in-right { animation: slide-in-right 0.6s cubic-bezier(0.2,0.9,0.4,1.1) forwards; opacity: 0; }
    .animate-fade-up { animation: fade-up 0.5s ease-out forwards; opacity: 0; }
    .animate-shake { animation: shake 0.4s cubic-bezier(0.36,0.07,0.19,0.97) both; }

    /* Efek 3D tilt pada card */
    .login-shell {
        transform-style: preserve-3d;
        transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), box-shadow 0.4s cubic-bezier(0.22,1,0.36,1);
    }
    .login-shell:hover {
        transform: perspective(1500px) rotateX(2.5deg) rotateY(-2.5deg) translateY(-4px);
        box-shadow: 0 45px 100px rgba(0,0,0,0.15);
    }
    @media (max-width: 1024px) {
        .login-shell:hover { transform: none; }
    }

    /* Cursor spotlight effect */
    #cursorSpotlight {
        background: radial-gradient(circle, rgba(249,115,22,0.25) 0%, rgba(249,115,22,0) 70%);
    }
</style>

<script>
    (function() {
        // ========== TOGGLE PASSWORD (SAME LOGIC) ==========
        const togglePw = document.getElementById('togglePw');
        const pwInput  = document.getElementById('admin_password');
        const eyeShow  = document.getElementById('eyeShow');
        const eyeHide  = document.getElementById('eyeHide');

        if (togglePw && pwInput) {
            togglePw.addEventListener('click', () => {
                const hidden = pwInput.type === 'password';
                pwInput.type = hidden ? 'text' : 'password';
                eyeShow.classList.toggle('hidden', hidden);
                eyeHide.classList.toggle('hidden', !hidden);
            });
        }

        // ========== 3D TILT ON CARD (SAME LOGIC) ==========
        const shell = document.querySelector('.login-shell');
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isDesktop = window.matchMedia('(min-width: 1024px)').matches;

        if (shell && !reduceMotion && isDesktop) {
            document.addEventListener('mousemove', (e) => {
                const rect = shell.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                const rotateY = ((e.clientX - centerX) / rect.width) * 8;
                const rotateX = ((centerY - e.clientY) / rect.height) * 8;
                shell.style.transform = `perspective(1500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
            });
            document.addEventListener('mouseleave', () => {
                shell.style.transform = 'none';
            });
        }

        // ========== CURSOR SPOTLIGHT (BARU, SUPER MAKSIMAL) ==========
        const spotlight = document.getElementById('cursorSpotlight');
        if (spotlight) {
            spotlight.style.display = 'block';
            document.addEventListener('mousemove', (e) => {
                spotlight.style.transform = `translate(${e.clientX - 192}px, ${e.clientY - 192}px)`;
            });
        }

        // ========== LOADING STATE ON SUBMIT (SAME LOGIC) ==========
        document.getElementById('adminForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            const label = document.getElementById('btnLabel');
            btn.disabled = true;
            label.innerHTML = '<svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memverifikasi...';
        });
    })();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/auth/admin_login.blade.php ENDPATH**/ ?>