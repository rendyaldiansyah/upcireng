<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <title>@yield('title', 'UP Cireng')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- ★ Google Analytics G-X4N5GDNXCH --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X4N5GDNXCH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-X4N5GDNXCH');
    </script>

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
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa',
                            300: '#fdba74', 400: '#fb923c', 500: '#f97316',
                            600: '#ea580c', 700: '#c2410c', 800: '#9a3412', 900: '#7c2d12',
                        },
                        ink: { 950: '#09111f', 900: '#0f172a', 800: '#172033', 700: '#334155' },
                        mist: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0' },
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
        .display-font { font-family: Sora, ui-sans-serif, system-ui; }
        .glass-surface { backdrop-filter: blur(16px); background: rgba(255,255,255,0.78); }
        .bg-mesh {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(249,115,22,0.10), transparent 0 28%),
                radial-gradient(circle at 80% 0%, rgba(59,130,246,0.10), transparent 0 24%),
                radial-gradient(circle at 80% 80%, rgba(14,165,233,0.08), transparent 0 22%),
                linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0));
        }
        .grid-noise { position: relative; }
        .grid-noise::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(148,163,184,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148,163,184,0.08) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: linear-gradient(180deg, rgba(255,255,255,0.6), transparent 80%);
        }
        ::selection { background: rgba(249,115,22,0.18); color: #0f172a; }
        @keyframes fadeIn    { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes scaleIn   { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }
        @keyframes cartPulse { 0%,100% { transform:scale(1); } 50% { transform:scale(1.08); } }
        .animate-fadeIn    { animation: fadeIn    0.6s ease-out forwards; opacity: 0; }
        .animate-slideInUp { animation: slideInUp 0.6s ease-out forwards; opacity: 0; }
        .animate-scaleIn   { animation: scaleIn   0.5s ease-out forwards; opacity: 0; }
        button, a { transition: all 0.3s ease-out; }
        [data-product-card]:hover { transform: translateY(-4px); }
        .loading { opacity: 0.6; pointer-events: none; }

        /* Floating cart */
        #floatingCartBtn {
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s ease, opacity 0.25s ease;
        }
        #floatingCartBtn:hover { transform: scale(1.1) !important; }
        #floatingCartBtn.cart-has-items { animation: cartPulse 0.4s ease-out; }
    </style>

    @stack('head')
</head>

@php
    $hideNav    = trim($__env->yieldContent('hide_nav'))    === '1';
    $hideFooter = trim($__env->yieldContent('hide_footer')) === '1';
    $isAdmin    = session()->has('admin_id');
@endphp

<body class="@yield('body_class', 'bg-transparent text-slate-900 antialiased')" @if($isAdmin) data-admin="true" @endif>
    <div class="relative min-h-screen">

        @unless($hideNav)
            @include('components.navbar')
        @endunless

        <main class="@yield('main_class', '')">
            @yield('content')
        </main>

        @unless($hideFooter)
            <footer class="relative mt-20 border-t border-white/70 bg-white/70">
                <div class="absolute inset-0 bg-mesh opacity-60"></div>
                <div class="relative mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1.1fr_0.7fr_0.7fr] lg:px-8 card">
                    <div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <img src="{{ asset('assets/assets/logo.png') }}" alt="UP Cireng" class="h-11 w-11 rounded-2xl border border-brand-100 object-cover shadow-sm">
                            <div>
                                <p class="display-font text-lg font-extrabold text-ink-950">UP Cireng</p>
                                <p class="text-sm font-medium text-slate-500">Cireng segar dengan sistem order yang mudah.</p>
                            </div>
                        </a>
                        <p class="mt-5 max-w-md text-sm leading-7 text-slate-600">
                            Menyediakan cireng segar dengan sistem order modern. Semua pesanan langsung terkelola dengan cepat dan mudah.
                        </p>
                    </div>
                    <div>
                        <p class="display-font text-sm font-bold uppercase tracking-[0.25em] text-brand-500">Navigasi</p>
                        <div class="mt-4 space-y-3 text-sm font-semibold text-slate-600">
                            <a href="{{ route('home') }}#menu"      class="block transition hover:text-brand-500">Menu</a>
                            <a href="{{ route('home') }}#testimoni" class="block transition hover:text-brand-500">Testimoni</a>
                            <a href="{{ route('home') }}#kontak"    class="block transition hover:text-brand-500">Kontak</a>
                            <a href="{{ route('orders.my') }}"      class="block transition hover:text-brand-500">Pesanan Saya</a>
                        </div>
                    </div>
                    <div>
                        <p class="display-font text-sm font-bold uppercase tracking-[0.25em] text-brand-500">Akses</p>
                        <div class="mt-4 space-y-3 text-sm font-semibold text-slate-600">
                            <a href="{{ route('login') }}"             class="block transition hover:text-brand-500">Login</a>
                            <a href="{{ route('admin.login') }}"       class="block transition hover:text-brand-500">Panel Admin</a>
                            <a href="{{ route('testimonial.index') }}" class="block transition hover:text-brand-500">Lihat Testimoni</a>
                        </div>
                    </div>
                </div>
            </footer>
        @endunless
    </div>

    {{-- ★ FLOATING CART BUTTON --}}
    @unless($isAdmin || $hideNav)
    <button
        id="floatingCartBtn"
        type="button"
        aria-label="Keranjang"
        class="fixed bottom-5 right-5 z-[85] hidden items-center gap-2.5 rounded-2xl bg-gradient-to-r from-ink-950 to-brand-600 px-4 py-3 text-sm font-bold text-white shadow-float sm:bottom-7 sm:right-7 sm:px-5 sm:py-3.5"
        style="display:none"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <span class="hidden sm:inline">Keranjang</span>
        <span id="floatingCartCount"
              class="inline-flex min-w-[1.4rem] items-center justify-center rounded-full bg-white/25 px-1.5 py-0.5 text-xs font-black">
            0
        </span>
    </button>
    @endunless

    @stack('scripts')

    @include('components.toast')

    <script>
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text)
                .then(function () { return true; })
                .catch(function () { return fallbackCopy(text); });
        }
        return Promise.resolve(fallbackCopy(text));
    }

    function fallbackCopy(text) {
        try {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            return ok;
        } catch (e) {
            return false;
        }
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.copy-btn');
        if (!btn) return;
        e.preventDefault();
        const text = btn.getAttribute('data-copy') || '';
        const original = btn.innerHTML;
        copyToClipboard(text).then(function (ok) {
            if (ok !== false) {
                btn.innerHTML = '✅';
                btn.classList.add('bg-emerald-100', 'text-emerald-700');
                toast('✅ Berhasil disalin: ' + text, 'success');
            } else {
                toast('Salin manual: ' + text, 'warning');
            }
            setTimeout(function () {
                btn.innerHTML = original;
                btn.classList.remove('bg-emerald-100', 'text-emerald-700');
            }, 2000);
        });
    });

    (function () {
        const floatBtn   = document.getElementById('floatingCartBtn');
        const floatCount = document.getElementById('floatingCartCount');
        if (!floatBtn) return;
        function syncFloatingCart() {
            const mainBadge = document.getElementById('cartCountBadge');
            const count = mainBadge ? parseInt(mainBadge.textContent) || 0 : 0;
            if (floatCount) floatCount.textContent = count;
            if (count > 0) {
                floatBtn.style.display = 'inline-flex';
                floatBtn.style.opacity = '1';
            } else {
                floatBtn.style.opacity = '0';
                setTimeout(function () {
                    if (!parseInt(floatCount?.textContent)) floatBtn.style.display = 'none';
                }, 300);
            }
        }
        setInterval(syncFloatingCart, 500);
        floatBtn.addEventListener('click', function () {
            const openBtn = document.getElementById('openCartButton');
            if (openBtn && !openBtn.disabled) openBtn.click();
        });
    })();

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
            document.addEventListener('visibilitychange', () => { this.isVisible = !document.hidden; });
            this.setupAudio();
            this.startPolling();
            this.checkOrders();
        }
        setupAudio() {
            this.audioElement         = new Audio('{{ asset("sounds/sounds/chord.mp3") }}');
            this.audioElement.preload = 'auto';
            this.audioElement.volume  = 0.5;
        }
        startPolling() {
            this.pollInterval = setInterval(() => this.checkOrders(), 5000);
        }
        async checkOrders() {
            try {
                const response = await fetch('{{ route("api.orders.latest") }}');
                if (!response.ok) return;
                const data = await response.json();
                if (data.user_type === 'customer') this.handleCustomerUpdate(data);
                else if (data.user_type === 'admin') this.handleAdminUpdate(data);
            } catch (error) { /* silent */ }
        }
        handleCustomerUpdate(data) {
            const badge = document.getElementById('order-count-badge');
            if (badge) badge.textContent = data.total_orders;
            if (this.lastOrderId === null) {
                this.lastOrderId = data.latest_order_id;
                this.lastOrderCount = data.total_orders;
            } else if (data.latest_order_id > this.lastOrderId) {
                this.playSound();
                toast('✅ Pesanan baru Anda berhasil dibuat!', 'success');
                this.lastOrderId = data.latest_order_id;
            }
        }
        handleAdminUpdate(data) {
            const adminBadge = document.getElementById('admin-order-badge');
            if (adminBadge && data.total_orders > 0) adminBadge.classList.remove('hidden');
            if (this.lastOrderId === null) {
                this.lastOrderId = data.latest_order_id;
                this.lastOrderCount = data.total_orders;
            } else if (data.latest_order_id > this.lastOrderId && this.isVisible) {
                this.playSound();
                toast('🔔 Order baru masuk: #' + data.latest_order_id, 'info');
                this.lastOrderId = data.latest_order_id;
            }
        }
        playSound() {
            try {
                this.audioElement.currentTime = 0;
                this.audioElement.play().catch(() => {});
            } catch (e) {}
        }
    }

    @php
        $successMsg = session('success');
        $errorMsg   = session('error');
        $warningMsg = session('warning');
    @endphp

    document.addEventListener('DOMContentLoaded', function () {
        const successMsg = {!! json_encode($successMsg) !!};
        const errorMsg   = {!! json_encode($errorMsg) !!};
        const warningMsg = {!! json_encode($warningMsg) !!};
        if (successMsg) toast(successMsg, 'success');
        if (errorMsg)   toast(errorMsg, 'error');
        if (warningMsg) toast(warningMsg, 'warning');
        window.realtimeNotifications = new RealtimeNotifications();
    });
    </script>
</body>
</html>