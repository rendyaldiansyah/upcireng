<?php
    $brandLogo        = asset('assets/assets/logo.png');
    $homeUrl          = route('home');
    $menuUrl          = $homeUrl . '#menu';
    $testimoniUrl     = $homeUrl . '#testimoni';
    $ordersUrl        = route('orders.my');
    $isCustomerLoggedIn = session()->has('user_id');
    $isAdminLoggedIn    = session()->has('admin_id');
    $storeOpen          = \App\Helpers\StoreHelper::isStoreOpen();
?>

<header
    id="main-navbar"
    class="fixed top-0 left-0 right-0 z-[70] border-b border-white/30 bg-white/80 backdrop-blur-2xl"
>
    <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:gap-4 sm:px-6 sm:py-4 lg:px-8">

        
        <a href="<?php echo e($homeUrl); ?>"
           class="group flex shrink-0 items-center gap-2.5 transition duration-300 hover:scale-105 sm:gap-3">
            <div class="relative">
                <img src="<?php echo e($brandLogo); ?>"
                     alt="UP Cireng"
                     class="h-9 w-9 rounded-xl border-2 border-brand-100 object-cover shadow-sm transition duration-300 group-hover:shadow-brand/50 sm:h-11 sm:w-11">
                <div class="absolute inset-0 rounded-xl bg-brand-500/0 transition duration-300 group-hover:bg-brand-500/10"></div>
            </div>
            <div class="hidden sm:block">
                <p class="display-font text-base font-bold text-ink-950 sm:text-lg">UP Cireng</p>
                <p class="text-xs font-semibold text-brand-600">Order System</p>
            </div>
        </a>

        
        <nav class="hidden items-center gap-4 lg:flex">
            <a href="<?php echo e($menuUrl); ?>"
               class="inline-block text-sm font-semibold text-slate-600 transition duration-300 hover:scale-105 hover:text-brand-600">
                Menu
            </a>
            <a href="<?php echo e($testimoniUrl); ?>"
               class="inline-block text-sm font-semibold text-slate-600 transition duration-300 hover:scale-105 hover:text-brand-600">
                Testimoni
            </a>
        </nav>

        
        <div class="flex-1"></div>

        
        <div class="flex items-center gap-1.5 rounded-full border px-3 py-1.5 sm:px-4 sm:py-2
                    <?php echo e($storeOpen
                        ? 'border-emerald-200 bg-gradient-to-r from-emerald-50 to-transparent'
                        : 'border-rose-200 bg-gradient-to-r from-rose-50 to-transparent'); ?>">
            <span class="h-2 w-2 rounded-full sm:h-2.5 sm:w-2.5 animate-pulse
                         <?php echo e($storeOpen ? 'bg-emerald-500' : 'bg-rose-500'); ?>"></span>
            <p class="text-[10px] font-bold uppercase tracking-wider sm:text-xs
                      <?php echo e($storeOpen ? 'text-emerald-700' : 'text-rose-700'); ?>">
                <?php echo e($storeOpen ? 'BUKA' : 'TUTUP'); ?>

            </p>
        </div>

        
        <div class="hidden items-center gap-3 lg:flex">
            <?php if($isCustomerLoggedIn): ?>
                <a href="<?php echo e(route('orders.my')); ?>"
                   class="relative inline-flex items-center gap-2 rounded-full border-2 border-brand-300 px-4 py-2 text-sm font-bold text-brand-600 transition duration-300 hover:border-brand-500 hover:bg-brand-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Pesanan Saya</span>
                    <span id="order-count-badge"
                          class="ml-1 inline-flex h-6 w-6 animate-pulse items-center justify-center rounded-full bg-brand-500 text-xs font-bold text-white">
                        0
                    </span>
                </a>
            <?php endif; ?>

            <?php if($isAdminLoggedIn): ?>
                <div class="relative">
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                       class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-500 to-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition duration-300 hover:scale-105 hover:shadow-float">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Panel
                    </a>
                    <span id="admin-order-badge"
                          class="absolute -right-2 -top-2 hidden inline-flex h-6 w-6 animate-bounce items-center justify-center rounded-full bg-rose-500 text-xs font-bold text-white">
                        🔔
                    </span>
                </div>
            <?php endif; ?>

            <?php if($isCustomerLoggedIn || $isAdminLoggedIn): ?>
                <form action="<?php echo e($isAdminLoggedIn ? route('admin.logout') : route('logout')); ?>"
                      method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="rounded-full border-2 border-slate-300 px-5 py-2 text-sm font-bold text-slate-600 transition duration-300 hover:border-rose-400 hover:bg-rose-50 hover:text-rose-600">
                        Keluar
                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>"
                   class="inline-flex items-center gap-2 rounded-full bg-ink-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition duration-300 hover:scale-105 hover:bg-brand-500 hover:shadow-float">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Masuk
                </a>
            <?php endif; ?>
        </div>

        
        <button
            id="mobile-menu-btn"
            aria-label="Buka menu"
            aria-expanded="false"
            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border-2 border-slate-200 bg-white text-slate-700 transition duration-300 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600 sm:h-10 sm:w-10 lg:hidden"
        >
            <svg id="icon-open" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg id="icon-close" class="hidden h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    
    <div
        id="mobile-menu"
        class="hidden overflow-hidden border-t border-white/20 bg-white/95 px-4 py-4 sm:px-6 lg:hidden"
    >
        <div class="space-y-1">
            <a href="<?php echo e($menuUrl); ?>"
               class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-brand-50 hover:text-brand-600">
                Menu
            </a>
            <a href="<?php echo e($testimoniUrl); ?>"
               class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-brand-50 hover:text-brand-600">
                Testimoni
            </a>

            <?php if($isAdminLoggedIn): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-brand-50 hover:text-brand-600">
                    Dashboard Admin
                </a>
            <?php endif; ?>

            <?php if($isCustomerLoggedIn): ?>
                <a href="<?php echo e($ordersUrl); ?>"
                   class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-brand-50 hover:text-brand-600">
                    Pesanan Saya
                </a>
            <?php endif; ?>

            <div class="border-t border-slate-100 pt-3">
                <?php if($isCustomerLoggedIn || $isAdminLoggedIn): ?>
                    <form action="<?php echo e($isAdminLoggedIn ? route('admin.logout') : route('logout')); ?>"
                          method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="w-full rounded-xl bg-ink-950 px-4 py-3 text-center text-sm font-bold text-white transition duration-300 hover:bg-brand-500">
                            Keluar
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>"
                       class="block w-full rounded-xl bg-ink-950 px-4 py-3 text-center text-sm font-bold text-white transition duration-300 hover:bg-brand-500">
                        Masuk / Daftar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>


<div id="navbar-spacer"></div>

<style>
    /* ── Smooth navbar slide transition ────────────────────────── */
    #main-navbar {
        /* Durasi 350ms dengan easing cubic-bezier untuk efek natural */
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity  0.35s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }

    /* State: navbar tersembunyi (naik ke atas) */
    #main-navbar.navbar--hidden {
        transform: translateY(-105%);
        opacity: 0.5;
    }

    /* State: navbar terlihat (posisi normal) */
    #main-navbar.navbar--visible {
        transform: translateY(0);
        opacity: 1;
    }

    /* Animasi masuk pertama kali saat halaman load */
    @keyframes navSlideDown {
        from { opacity: 0; transform: translateY(-100%); }
        to   { opacity: 1; transform: translateY(0); }
    }
    #main-navbar {
        animation: navSlideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    /* Mobile menu slide animation */
    @keyframes menuSlideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    #mobile-menu.menu-open {
        animation: menuSlideDown 0.25s ease-out both;
    }
</style>

<script>
(function () {
    const navbar    = document.getElementById('main-navbar');
    const spacer    = document.getElementById('navbar-spacer');
    const btn       = document.getElementById('mobile-menu-btn');
    const menu      = document.getElementById('mobile-menu');
    const iconOpen  = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');

    // ── Set spacer height sesuai tinggi navbar aktual ──────────────
    function syncSpacerHeight() {
        if (spacer && navbar) {
            spacer.style.height = navbar.offsetHeight + 'px';
        }
    }
    syncSpacerHeight();
    window.addEventListener('resize', syncSpacerHeight);

    // ── Scroll hide / show logic ───────────────────────────────────
    let lastScrollY  = window.scrollY;
    let ticking      = false;
    let isHidden     = false;

    // Threshold minimal scroll sebelum bereaksi (px)
    const SCROLL_THRESHOLD = 6;
    // Jarak dari atas sebelum mulai menyembunyikan navbar
    const HIDE_OFFSET = 80;

    function handleScroll() {
        const currentY = window.scrollY;
        const diff     = currentY - lastScrollY;

        // Abaikan scroll micro (jitter)
        if (Math.abs(diff) < SCROLL_THRESHOLD) {
            ticking = false;
            return;
        }

        if (diff > 0 && currentY > HIDE_OFFSET) {
            // ── Scroll KE BAWAH → sembunyikan navbar ──
            if (!isHidden) {
                isHidden = true;
                navbar.classList.add('navbar--hidden');
                navbar.classList.remove('navbar--visible');
                closeMenu(); // tutup mobile menu kalau terbuka
            }
        } else {
            // ── Scroll KE ATAS → tampilkan navbar ──
            if (isHidden) {
                isHidden = false;
                navbar.classList.remove('navbar--hidden');
                navbar.classList.add('navbar--visible');
            }
        }

        lastScrollY = currentY;
        ticking     = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(handleScroll);
            ticking = true;
        }
    }, { passive: true });

    // ── Mobile menu toggle ─────────────────────────────────────────
    function openMenu() {
        menu.classList.remove('hidden');
        menu.classList.add('menu-open');
        iconOpen.classList.add('hidden');
        iconClose.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
        if (!menu || menu.classList.contains('hidden')) return;
        menu.classList.add('hidden');
        menu.classList.remove('menu-open');
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');
        btn.setAttribute('aria-expanded', 'false');
    }

    btn?.addEventListener('click', function () {
        menu.classList.contains('hidden') ? openMenu() : closeMenu();
    });

    // Tutup saat klik link/tombol di dalam menu
    menu?.querySelectorAll('a, button[type="submit"]').forEach(function (el) {
        el.addEventListener('click', closeMenu);
    });

    // Tutup saat tekan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
})();
</script><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/components/navbar.blade.php ENDPATH**/ ?>