<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#0f172a">
    <title><?php echo $__env->yieldContent('title', 'UP Cireng'); ?></title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui'],
                        display: ['Sora', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        ink: {
                            950: '#09111f',
                            900: '#0f172a',
                            800: '#172033',
                            700: '#334155',
                        },
                        mist: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                        },
                    },
                    boxShadow: {
                        panel: '0 30px 80px -34px rgba(15, 23, 42, 0.26)',
                        float: '0 26px 60px -28px rgba(249, 115, 22, 0.35)',
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: Manrope, system-ui, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.08), transparent 22%),
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.06), transparent 22%),
                linear-gradient(180deg, #fff7ed 0%, #ffffff 22%, #f8fafc 100%);
            color: #0f172a;
        }

        .display-font {
            font-family: Sora, ui-sans-serif, system-ui;
        }

        .glass-surface {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.78);
        }

        .bg-mesh {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(249, 115, 22, 0.10), transparent 0 28%),
                radial-gradient(circle at 80% 0%, rgba(59, 130, 246, 0.10), transparent 0 24%),
                radial-gradient(circle at 80% 80%, rgba(14, 165, 233, 0.08), transparent 0 22%),
                linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0));
        }

        .grid-noise {
            position: relative;
        }

        .grid-noise::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: linear-gradient(180deg, rgba(255,255,255,0.6), transparent 80%);
        }

        ::selection {
            background: rgba(249, 115, 22, 0.18);
            color: #0f172a;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }

        .animate-fadeIn    { animation: fadeIn    0.6s ease-out forwards; opacity: 0; }
        .animate-slideInUp { animation: slideInUp 0.6s ease-out forwards; opacity: 0; }
        .animate-scaleIn   { animation: scaleIn   0.5s ease-out forwards; opacity: 0; }

        button, a { transition: all 0.3s ease-out; }

        [data-product-card]:hover { transform: translateY(-4px); }

        .loading { opacity: 0.6; pointer-events: none; }
    </style>

    <?php echo $__env->yieldPushContent('head'); ?>
</head>

<?php
    $hideNav    = trim($__env->yieldContent('hide_nav'))    === '1';
    $hideFooter = trim($__env->yieldContent('hide_footer')) === '1';
    $isAdmin    = session()->has('admin_id');
?>

<body class="<?php echo $__env->yieldContent('body_class', 'bg-transparent text-slate-900 antialiased'); ?>" <?php if($isAdmin): ?> data-admin="true" <?php endif; ?>>
    <div class="relative min-h-screen">

        <?php if (! ($hideNav)): ?>
            <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <main class="<?php echo $__env->yieldContent('main_class', ''); ?>">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <?php if (! ($hideFooter)): ?>
            <footer class="relative mt-20 border-t border-white/70 bg-white/70">
                <div class="absolute inset-0 bg-mesh opacity-60"></div>

                <div class="relative mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1.1fr_0.7fr_0.7fr] lg:px-8 card">
                    <div>
                        <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-3">
                            <img src="<?php echo e(asset('assets/assets/logo.png')); ?>" alt="UP Cireng" class="h-11 w-11 rounded-2xl border border-brand-100 object-cover shadow-sm">
                            <div>
                                <p class="display-font text-lg font-extrabold text-ink-950">UP Cireng</p>
                                <p class="text-sm font-medium text-slate-500">Cireng artisan dengan order system modern.</p>
                            </div>
                        </a>
                        <p class="mt-5 max-w-md text-sm leading-7 text-slate-600">
                            Dibangun dengan Laravel sebagai pusat data utama untuk order, dashboard admin, notifikasi, dan operasional harian.
                        </p>
                    </div>

                    <div>
                        <p class="display-font text-sm font-bold uppercase tracking-[0.25em] text-brand-500">Navigasi</p>
                        <div class="mt-4 space-y-3 text-sm font-semibold text-slate-600">
                            <a href="<?php echo e(route('home')); ?>#menu"      class="block transition hover:text-brand-500">Menu</a>
                            <a href="<?php echo e(route('home')); ?>#testimoni" class="block transition hover:text-brand-500">Testimoni</a>
                            <a href="<?php echo e(route('home')); ?>#kontak"    class="block transition hover:text-brand-500">Kontak</a>
                            <a href="<?php echo e(route('orders.my')); ?>"      class="block transition hover:text-brand-500">Pesanan Saya</a>
                        </div>
                    </div>

                    <div>
                        <p class="display-font text-sm font-bold uppercase tracking-[0.25em] text-brand-500">Akses</p>
                        <div class="mt-4 space-y-3 text-sm font-semibold text-slate-600">
                            <a href="<?php echo e(route('login')); ?>"            class="block transition hover:text-brand-500">Login / Daftar</a>
                            <a href="<?php echo e(route('admin.login')); ?>"      class="block transition hover:text-brand-500">Panel Admin</a>
                            <a href="<?php echo e(route('testimonial.index')); ?>" class="block transition hover:text-brand-500">Lihat Testimoni</a>
                        </div>
                    </div>
                </div>
            </footer>
        <?php endif; ?>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo $__env->make('components.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        // ── 1. Definisi class DULU, baru dipanggil di DOMContentLoaded ────────
        class RealtimeNotifications {
            constructor() {
                this.lastOrderId    = null;
                this.lastOrderCount = 0;
                this.isVisible      = true;
                this.pollInterval   = null;
                this.audioElement   = null;
                this.isAdmin        = document.body.hasAttribute('data-admin');

                this.init();
            }

            init() {
                document.addEventListener('visibilitychange', () => {
                    this.isVisible = !document.hidden;
                });

                this.setupAudio();
                this.startPolling();
                this.checkOrders();
            }

            setupAudio() {
                this.audioElement         = new Audio('<?php echo e(asset("sounds/sounds/chord.mp3")); ?>');
                this.audioElement.preload = 'auto';
                this.audioElement.volume  = 0.5;
            }

            startPolling() {
                this.pollInterval = setInterval(() => this.checkOrders(), 5000);
            }

            stopPolling() {
                if (this.pollInterval) {
                    clearInterval(this.pollInterval);
                }
            }

            async checkOrders() {
                try {
                    const response = await fetch('<?php echo e(route("api.orders.latest")); ?>');
                    if (!response.ok) return;

                    const data = await response.json();

                    if (data.user_type === 'customer') {
                        this.handleCustomerUpdate(data);
                    } else if (data.user_type === 'admin') {
                        this.handleAdminUpdate(data);
                    }
                } catch (error) {
                    console.error('Realtime check failed:', error);
                }
            }

            handleCustomerUpdate(data) {
                const badge = document.getElementById('order-count-badge');
                if (badge) {
                    badge.textContent = data.total_orders;
                }

                if (this.lastOrderId === null) {
                    this.lastOrderId    = data.latest_order_id;
                    this.lastOrderCount = data.total_orders;
                } else if (data.latest_order_id > this.lastOrderId) {
                    this.playNewOrderNotification();
                    toast('✅ Pesanan baru Anda berhasil dibuat!', 'success');
                    this.lastOrderId    = data.latest_order_id;
                    this.lastOrderCount = data.total_orders;
                }
            }

            handleAdminUpdate(data) {
                const adminBadge = document.getElementById('admin-order-badge');
                if (adminBadge && data.total_orders > 0) {
                    adminBadge.classList.remove('hidden');
                }

                if (this.lastOrderId === null) {
                    this.lastOrderId    = data.latest_order_id;
                    this.lastOrderCount = data.total_orders;
                } else if (data.latest_order_id > this.lastOrderId && this.isVisible) {
                    this.playNewOrderNotification();
                    toast('🔔 Order baru masuk: #' + data.latest_order_id, 'info');
                    this.lastOrderId    = data.latest_order_id;
                    this.lastOrderCount = data.total_orders;
                }
            }

            playNewOrderNotification() {
                try {
                    this.audioElement.currentTime = 0;
                    this.audioElement.play().catch(err => {
                        console.log('Audio play failed:', err);
                    });
                } catch (error) {
                    console.error('Sound notification error:', error);
                }
            }
        }

        // ── 2. Baru panggil setelah DOM siap ──────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {

            // Tampilkan flash session toast
            <?php if(session('success')): ?>
                toast(<?php echo json_encode(session('success'), 15, 512) ?>, 'success');
            <?php endif; ?>

            <?php if(session('error')): ?>
                toast(<?php echo json_encode(session('error'), 15, 512) ?>, 'error');
            <?php endif; ?>

            <?php if(session('warning')): ?>
                toast(<?php echo json_encode(session('warning'), 15, 512) ?>, 'warning');
            <?php endif; ?>

            // Inisialisasi realtime notifications
            window.realtimeNotifications = new RealtimeNotifications();
        });
    </script>
</body>
</html><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/layout/app.blade.php ENDPATH**/ ?>