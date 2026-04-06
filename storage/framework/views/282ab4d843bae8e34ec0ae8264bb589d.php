<?php
    $adminSidebarTitle       = $adminSidebarTitle       ?? 'Panel Admin';
    $adminSidebarBody        = $adminSidebarBody        ?? 'Kelola operasional toko dari satu panel yang konsisten.';
    $adminSidebarMetricLabel = $adminSidebarMetricLabel ?? null;
    $adminSidebarMetricValue = $adminSidebarMetricValue ?? null;

    $navItemClass       = 'flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-bold transition-all duration-200';
    $activeNavItemClass = 'bg-white/10 text-white';
    $idleNavItemClass   = 'text-slate-300 hover:bg-white/5 hover:text-white';
?>


<header class="sticky top-0 z-40 flex items-center justify-between border-b border-slate-200 bg-ink-950 px-4 py-3 md:hidden">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2.5">
        <img src="<?php echo e(asset('assets/assets/logo.png')); ?>"
             class="h-9 w-9 rounded-xl border border-white/10 object-cover">
        <div>
            <p class="display-font text-base font-extrabold text-white leading-none">UP Cireng</p>
            <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-brand-300">Admin Panel</p>
        </div>
    </a>

    <button id="openSidebar"
            aria-label="Buka menu"
            class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white transition hover:bg-white/10">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</header>


<div id="sidebarOverlay"
     class="fixed inset-0 z-40 hidden bg-black/50 md:hidden"
     aria-hidden="true">
</div>


<aside id="sidebar"
       class="fixed left-0 top-0 z-50 flex h-full w-72 -translate-x-full flex-col
              overflow-y-auto overflow-x-hidden border-r border-white/10
              bg-ink-950 px-5 py-7 text-white shadow-panel
              transition-transform duration-300
              md:relative md:h-auto md:translate-x-0 md:px-6 md:py-8">

    <div class="pointer-events-none absolute inset-0 bg-mesh opacity-70"></div>

    <div class="relative flex flex-col gap-6">

        
        <div class="flex items-center justify-between">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3">
                <img src="<?php echo e(asset('assets/assets/logo.png')); ?>"
                     class="h-11 w-11 rounded-2xl border border-white/10 object-cover">
                <div>
                    <p class="display-font text-xl font-extrabold leading-none">UP Cireng</p>
                    <p class="mt-0.5 text-xs font-bold uppercase tracking-[0.24em] text-brand-300">Admin Panel</p>
                </div>
            </a>

            
            <button id="closeSidebar"
                    aria-label="Tutup menu"
                    class="flex h-8 w-8 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white transition hover:bg-white/10 md:hidden">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        
        <div class="rounded-[1.6rem] border border-white/10 bg-white/5 p-5">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-300">
                <?php echo e($adminSidebarTitle); ?>

            </p>

            <?php if(!is_null($adminSidebarMetricValue)): ?>
                <p class="mt-3 text-3xl font-extrabold"><?php echo e($adminSidebarMetricValue); ?></p>
            <?php endif; ?>

            <p class="mt-2 text-sm leading-7 text-slate-300">
                <?php if($adminSidebarMetricLabel): ?>
                    <span class="mb-1.5 block text-xs font-bold uppercase tracking-[0.22em] text-slate-400">
                        <?php echo e($adminSidebarMetricLabel); ?>

                    </span>
                <?php endif; ?>
                <?php echo e($adminSidebarBody); ?>

            </p>
        </div>

        
        <nav class="space-y-1.5" aria-label="Admin navigation">
            <?php
                $navLinks = [
                    ['route' => 'admin.dashboard',    'pattern' => 'admin.dashboard',    'label' => 'Dashboard', 'sub' => 'Overview'],
                    ['route' => 'admin.products.index','pattern' => 'admin.products.*',   'label' => 'Produk',    'sub' => 'Catalog'],
                    ['route' => 'admin.orders',        'pattern' => 'admin.orders',        'label' => 'Pesanan',   'sub' => 'Monitor'],
                    ['route' => 'admin.testimonials',  'pattern' => 'admin.testimonials',  'label' => 'Testimoni', 'sub' => 'Moderasi'],
                    ['route' => 'admin.settings',      'pattern' => 'admin.settings',      'label' => 'Pengaturan','sub' => 'Config'],
                ];
            ?>

            <?php $__currentLoopData = $navLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isActive = request()->routeIs($link['pattern']); ?>
                <a href="<?php echo e(route($link['route'])); ?>"
                   class="<?php echo e($navItemClass); ?> <?php echo e($isActive ? $activeNavItemClass : $idleNavItemClass); ?>">
                    <span><?php echo e($link['label']); ?></span>
                    <span class="text-xs <?php echo e($isActive ? 'text-brand-200' : 'text-slate-400'); ?>">
                        <?php echo e($link['sub']); ?>

                    </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        
        <div class="rounded-[1.6rem] border border-white/10 bg-white/5 p-5">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-300">Akses Cepat</p>

            <div class="mt-4 space-y-3">
                <a href="<?php echo e(route('home')); ?>"
                   class="block rounded-2xl border border-white/10 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:border-brand-300 hover:text-white">
                    Buka Storefront
                </a>

                <form action="<?php echo e(route('admin.auth.logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="w-full rounded-2xl bg-white px-4 py-3 text-sm font-bold text-ink-950 transition hover:bg-brand-500 hover:text-white">
                        Logout Admin
                    </button>
                </form>
            </div>
        </div>

    </div>
</aside>

<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const openBtn  = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        openBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
        openBtn?.setAttribute('aria-expanded', 'false');
    }

    openBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });

    // Close sidebar when a nav link is tapped on mobile
    sidebar?.querySelectorAll('nav a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 768) closeSidebar();
        });
    });
})();
</script><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/admin/partials/sidebar.blade.php ENDPATH**/ ?>