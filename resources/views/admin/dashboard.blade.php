@extends('layout.app')

@section('title', 'Dashboard Admin - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-white to-slate-50 text-slate-900')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- 🔥 Laravel Echo for realtime notifications --}}
    @if(config('broadcasting.default') !== 'null')
        <script src="//{{ request()->getHost() }}:8080/socket.io/socket.io.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.1/dist/echo.iife.js"></script>
        <script>
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: '{{ env('REVERB_APP_KEY') }}',
                wsHost: '{{ env('REVERB_HOST', request()->getHost()) }}',
                wsPort: {{ env('REVERB_PORT', 8080) }},
                wssPort: {{ env('REVERB_PORT', 443) }},
                forceTLS: {{ env('APP_ENV') === 'production' ? 'true' : 'false' }},
                enabledTransports: ['ws', 'wss'],
            });
        </script>
    @endif
@endpush

@push('scripts')
    {{-- 🔥 Realtime order notification system --}}
    <script src="{{ asset('js/realtime-notifications.js') }}"></script>
@endpush

@section('content')
@php
    $adminSidebarTitle = 'Dashboard Ringkasan';
    $adminSidebarMetricLabel = 'Pesanan Aktif';
    $adminSidebarMetricValue = ($pendingOrders ?? 0) + ($processingOrders ?? 0);
    $adminSidebarBody = 'Metrik real-time lengkap dari database Laravel.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-5 sm:py-8 lg:px-8 xl:px-10">
        <!-- Breadcrumb -->
        <nav class="mb-6 sm:mb-8 flex items-center space-x-2 text-xs sm:text-sm font-medium text-slate-500" data-animate="fade-in-down" data-delay="0">
            <span class="font-semibold text-slate-900">Dashboard Manajemen</span>
        </nav>

        <!-- Hero Header -->
        <section class="mb-10 sm:mb-12 lg:mb-16 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-brand-50 via-white to-brand-50/50 p-5 sm:p-8 lg:p-12 shadow-lg border border-brand-100/50" data-animate="fade-in-up" data-delay="100">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="inline-flex items-center gap-2 rounded-full bg-brand-100 px-3 py-2 mb-4">
                        <span class="h-2 w-2 bg-brand-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] sm:text-xs font-bold text-brand-700 uppercase tracking-wider">Sistem Aktif</span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black bg-gradient-to-r from-ink-950 to-slate-700 bg-clip-text text-transparent leading-tight">
                        Kontrol Toko
                    </h1>
                    <p class="mt-3 sm:mt-4 max-w-2xl text-sm sm:text-base lg:text-lg leading-relaxed text-slate-600">
                        Pantau pendapatan, pesanan, dan operasional dengan dashboard profesional real-time.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 sm:gap-3 xl:justify-end">
                    {{-- ★ Tombol Manajemen Customer --}}
                    <a href="{{ route('admin.customers') }}" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl border-2 border-slate-200 bg-white px-4 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-slate-800 transition-all duration-300 hover:border-brand-400 hover:shadow-xl hover:-translate-y-1 hover:bg-brand-50 min-h-11">
                        <svg class="w-4 h-4 flex-shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Data Customer</span>
                        <span class="inline-flex items-center justify-center rounded-full bg-brand-100 px-2 py-0.5 text-xs font-bold text-brand-700 ml-1">{{ $totalCustomers ?? 0 }}</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl border-2 border-slate-200 bg-white px-4 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-slate-800 transition-all duration-300 hover:border-brand-400 hover:shadow-xl hover:-translate-y-1 hover:bg-brand-50 min-h-11">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Produk</span>
                    </a>

                    <a href="{{ route('admin.orders') }}" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 sm:px-6 py-2.5 sm:py-3 text-sm font-bold text-white transition-all duration-300 hover:shadow-xl hover:shadow-brand-500/40 hover:-translate-y-1 min-h-11">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Pesanan</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Key Metrics -->
        <section class="grid gap-4 sm:gap-5 mb-10 sm:mb-12 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6" data-animate="fade-in-up" data-delay="200">

            {{-- Pendapatan --}}
            <div class="group xl:col-span-2 rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="px-2.5 py-1 rounded-full bg-emerald-100 text-[10px] font-bold text-emerald-700">Selesai</div>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Pendapatan</p>
                <p class="text-2xl sm:text-3xl font-black text-ink-950 leading-tight break-words">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
            </div>

            {{-- Total Pesanan --}}
            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="h-12 w-12 rounded-2xl bg-blue-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Pesanan</p>
                <p id="metricTotalOrders" class="text-2xl sm:text-3xl font-black text-ink-950">{{ $totalOrders ?? 0 }}</p>
            </div>

            {{-- Pending --}}
            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="h-12 w-12 rounded-2xl bg-amber-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Menunggu</p>
                <p id="metricPendingOrders" class="text-2xl sm:text-3xl font-black text-amber-700">{{ $pendingOrders ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-2">Butuh tindakan</p>
            </div>

            {{-- Selesai --}}
            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="h-12 w-12 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Selesai</p>
                <p class="text-2xl sm:text-3xl font-black text-emerald-700">{{ $completedOrders ?? 0 }}</p>
            </div>

            {{-- Customer --}}
            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="h-12 w-12 rounded-2xl bg-sky-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Customer</p>
                <p class="text-2xl sm:text-3xl font-black text-sky-700">{{ $totalCustomers ?? 0 }}</p>
                @if(($newCustomers ?? 0) > 0)
                    <p class="text-xs text-emerald-600 font-bold mt-2">+{{ $newCustomers }} hari ini</p>
                @endif
            </div>
        </section>

        <!-- Charts & Quick Actions -->
        <section class="grid gap-6 lg:grid-cols-[2fr_1fr]" data-animate="fade-in-up" data-delay="300">
            <div class="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 lg:p-8 shadow-panel border border-slate-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
                    <div>
                        <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-500 mb-2">Analytics</p>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-ink-950">Performa 7 Hari</h2>
                    </div>
                </div>
                <div class="h-64 sm:h-80 lg:h-96">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>

            <div class="space-y-6 lg:space-y-8">
                <!-- Top Products -->
                <div class="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 lg:p-8 shadow-panel border border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-500 mb-2">Produk Terlaris</p>
                            <h3 class="text-xl sm:text-2xl font-black text-ink-950">Minggu Ini</h3>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="text-brand-500 font-bold hover:text-brand-600 text-sm whitespace-nowrap">Lihat Semua →</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($topProducts ?? [] as $index => $product)
                            <div class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-gradient-to-r from-slate-50 to-transparent hover:from-brand-50 transition-all border border-slate-100 hover:border-brand-200">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white font-black text-xs">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-sm truncate">{{ $product['name'] ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-500">{{ $product['quantity'] ?? 0 }} terjual</p>
                                    </div>
                                </div>
                                <p class="font-black text-ink-950 text-sm ml-2 flex-shrink-0">Rp {{ number_format($product['revenue'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-500">
                                <p class="text-sm font-semibold">Belum ada data penjualan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 p-5 sm:p-6 lg:p-8 shadow-panel">
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-100 mb-6">Aksi Cepat</p>
                    <div class="space-y-3">
                        <a href="{{ route('admin.customers') }}" class="block w-full rounded-2xl bg-white/20 hover:bg-white/30 px-5 py-3.5 text-sm font-bold text-white transition-all border border-white/20 hover:-translate-y-0.5">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Kelola Customer
                            </span>
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="block w-full rounded-2xl bg-white/95 hover:bg-white px-5 py-3.5 text-center font-bold text-brand-700 text-sm transition-all hover:shadow-xl hover:-translate-y-1">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Produk Baru
                            </span>
                        </a>
                        <form action="{{ route('admin.recap.send') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full rounded-2xl bg-white/20 hover:bg-white/30 px-5 py-3.5 text-sm font-bold text-white transition-all border border-white/20 hover:-translate-y-0.5">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Kirim Ringkasan Harian
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== REALTIME RECENT ORDERS ===================== -->
        <section class="mt-14 sm:mt-16 lg:mt-20" data-animate="fade-in-up" data-delay="400">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8 sm:mb-10">
                <div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-500 mb-2 sm:mb-3">Aktivitas Terbaru</p>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black bg-gradient-to-r from-ink-950 to-slate-700 bg-clip-text text-transparent">
                            Pesanan Terkini
                        </h2>
                        {{-- ★ Realtime indicator --}}
                        <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5">
                            <span id="realtimeIndicator" class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Live</span>
                        </div>
                    </div>
                    <p id="realtimeStatus" class="text-xs text-slate-400 mt-1">Auto-refresh setiap 5 detik</p>
                </div>

                <a href="{{ route('admin.orders') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-5 sm:px-6 py-3 sm:py-4 text-sm sm:text-base font-bold text-slate-800 hover:border-brand-400 hover:shadow-2xl hover:-translate-y-1 transition-all">
                    <span>Lihat Semua</span>
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- ★ Notifikasi order baru --}}
            <div id="newOrderBanner" class="hidden mb-6 flex items-center gap-3 rounded-2xl border-2 border-emerald-300 bg-emerald-50 px-5 py-4 shadow-sm">
                <span class="text-2xl">🔔</span>
                <div class="flex-1">
                    <p class="font-bold text-emerald-800" id="newOrderBannerText">Ada pesanan baru masuk!</p>
                    <p class="text-sm text-emerald-600">Halaman diperbarui otomatis</p>
                </div>
                <button onclick="document.getElementById('newOrderBanner').classList.add('hidden')" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ★ Order cards container — diisi JS realtime --}}
            <div id="recentOrdersGrid" class="grid gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                {{-- Render server-side pertama kali --}}
                @forelse($recentOrders as $order)
                    <a href="{{ route('admin.orders') }}?reference={{ urlencode($order->reference ?? '') }}"
                       class="group relative rounded-2xl sm:rounded-3xl bg-white shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden block">
                        <article class="relative p-5 sm:p-6 lg:p-8 flex flex-col h-full">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-100/20 rounded-full -mr-16 -mt-16 group-hover:bg-brand-100/40 transition-colors duration-500"></div>

                            <div class="relative z-10 flex-1 flex flex-col">
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Referensi</p>
                                        <p class="text-lg sm:text-2xl font-black text-brand-600 truncate">{{ $order->reference ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] sm:text-xs font-bold {{ $order->status_color ?? 'text-slate-600' }} whitespace-nowrap flex-shrink-0">
                                        {{ $order->status_label ?? 'Unknown' }}
                                    </span>
                                </div>

                                <div class="space-y-3 mb-5 flex-1">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pelanggan</p>
                                        <p class="font-black text-slate-900 text-sm truncate">{{ $order->customer_name ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $order->customer_phone ?? '-' }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="rounded-xl bg-slate-50 p-3">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Item</p>
                                            <p class="text-lg font-black text-ink-950 mt-1">{{ is_array($order->items_summary ?? null) ? count($order->items_summary) : 0 }}</p>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 p-3">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</p>
                                            <p class="text-sm font-black text-ink-950 mt-1 truncate">{{ method_exists($order, 'formatPrice') ? $order->formatPrice() : 'Rp 0' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-200 group-hover:border-brand-200 transition-colors">
                                    <span class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white">
                                        Lihat Pesanan
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </article>
                    </a>
                @empty
                    <div id="emptyOrders" class="md:col-span-2 lg:col-span-3 rounded-2xl bg-white p-16 text-center shadow-panel border border-slate-100">
                        <p class="text-lg font-black text-slate-500">Belum Ada Pesanan Terbaru</p>
                        <p class="text-sm text-slate-400 mt-2">Pesanan akan muncul otomatis saat ada yang masuk</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</div>

@push('scripts')
<script type="application/json" id="chartData">
{!! json_encode([
    'labels'  => $chartLabels ?? [],
    'orders'  => $chartOrders ?? [],
    'revenue' => $chartRevenue ?? [],
]) !!}
</script>

<script>
// ── Chart ─────────────────────────────────────────────────────────────────
(function () {
    try {
        const chartData = JSON.parse(document.getElementById('chartData').textContent);
        const ctx = document.getElementById('dashboardChart');
        if (!ctx || !chartData.labels?.length) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: chartData.revenue,
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.08)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(249, 115, 22)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                    },
                    {
                        label: 'Pesanan (Unit)',
                        data: chartData.orders,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y:  { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { weight: 'bold' } } },
                    y1: { position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { weight: 'bold' } } },
                    x:  { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { weight: 'bold' } } },
                },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, padding: 24, font: { weight: 'bold', size: 12 } } },
                },
            },
        });
    } catch (e) {
        console.error('Chart error:', e);
    }
})();

// ── ★ REALTIME POLLING ─────────────────────────────────────────────────────
(function () {
    const POLL_URL     = '{{ route("admin.api.realtime-orders") }}';
    const ORDERS_URL   = '{{ route("admin.orders") }}';
    const POLL_INTERVAL = 5000;

    let lastKnownId    = {{ (int) ($recentOrders->first()?->id ?? 0) }};
    let isFirstPoll    = true;

    const grid         = document.getElementById('recentOrdersGrid');
    const statusEl     = document.getElementById('realtimeStatus');
    const indicator    = document.getElementById('realtimeIndicator');
    const banner       = document.getElementById('newOrderBanner');
    const bannerText   = document.getElementById('newOrderBannerText');
    const metricTotal  = document.getElementById('metricTotalOrders');
    const metricPending = document.getElementById('metricPendingOrders');

    function statusColor(status) {
        const map = {
            'pending':    'bg-amber-100 text-amber-700',
            'processing': 'bg-blue-100 text-blue-700',
            'delivering': 'bg-purple-100 text-purple-700',
            'completed':  'bg-emerald-100 text-emerald-700',
            'cancelled':  'bg-rose-100 text-rose-700',
        };
        return map[status] || 'bg-slate-100 text-slate-600';
    }

    function renderOrderCard(order) {
        const isNew = order.id > lastKnownId && !isFirstPoll;
        return `
        <a href="${order.url}"
           class="group relative rounded-2xl sm:rounded-3xl bg-white shadow-panel border ${isNew ? 'border-emerald-300 ring-2 ring-emerald-200' : 'border-slate-100'} hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden block ${isNew ? 'animate-pulse-once' : ''}">
            <article class="relative p-5 sm:p-6 flex flex-col h-full">
                ${isNew ? '<div class="absolute top-3 right-3 z-20 inline-flex items-center gap-1 rounded-full bg-emerald-500 px-2.5 py-1 text-[10px] font-bold text-white"><span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span> BARU</div>' : ''}
                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-100/20 rounded-full -mr-16 -mt-16 group-hover:bg-brand-100/40 transition-colors"></div>

                <div class="relative z-10 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Referensi</p>
                            <p class="text-lg font-black text-brand-600 truncate">${order.reference}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold ${statusColor(order.status)} whitespace-nowrap flex-shrink-0">
                            ${order.status_label}
                        </span>
                    </div>

                    <div class="space-y-3 mb-5 flex-1">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pelanggan</p>
                            <p class="font-black text-slate-900 text-sm truncate">${order.customer_name}</p>
                            <p class="text-xs text-slate-500 truncate">${order.customer_phone}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="text-[10px] font-bold uppercase text-slate-400">Item</p>
                                <p class="text-lg font-black text-ink-950 mt-1">${order.items_count}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="text-[10px] font-bold uppercase text-slate-400">Total</p>
                                <p class="text-sm font-black text-ink-950 mt-1 truncate">${order.total_formatted}</p>
                            </div>
                        </div>

                        <p class="text-[10px] text-slate-400">${order.created_ago}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 group-hover:border-brand-200 transition-colors">
                        <span class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2.5 text-xs font-bold text-white">
                            Lihat Pesanan
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </article>
        </a>`;
    }

    async function poll() {
        try {
            const resp = await fetch(POLL_URL, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!resp.ok) throw new Error('HTTP ' + resp.status);

            const data = await resp.json();

            // Update metrics
            if (metricTotal)   metricTotal.textContent   = data.total;
            if (metricPending) metricPending.textContent = data.pending;

            // Cek ada order baru
            const hasNew = data.latest_id > lastKnownId && !isFirstPoll;

            if (hasNew && banner && bannerText) {
                const diff = data.latest_id - lastKnownId;
                bannerText.textContent = `${diff} pesanan baru masuk! 🎉`;
                banner.classList.remove('hidden');

                // Notifikasi browser (jika diizinkan)
                if (Notification.permission === 'granted') {
                    new Notification('UP Cireng — Pesanan Baru!', {
                        body: `${diff} pesanan baru masuk.`,
                        icon: '/favicon.ico',
                    });
                }
            }

            // Render ulang grid
            if (data.orders && data.orders.length > 0) {
                grid.innerHTML = data.orders.map(o => renderOrderCard(o)).join('');
            }

            lastKnownId = data.latest_id;

            // Status indicator
            if (statusEl) {
                const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                statusEl.textContent = `Terakhir diperbarui: ${now}`;
            }
            if (indicator) {
                indicator.classList.remove('bg-rose-500');
                indicator.classList.add('bg-emerald-500', 'animate-pulse');
            }

        } catch (err) {
            console.warn('Realtime poll error:', err);
            if (indicator) {
                indicator.classList.remove('bg-emerald-500', 'animate-pulse');
                indicator.classList.add('bg-rose-500');
            }
            if (statusEl) statusEl.textContent = 'Koneksi terputus, mencoba lagi…';
        } finally {
            isFirstPoll = false;
        }
    }

    // Minta izin notifikasi browser
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Mulai polling
    setInterval(poll, POLL_INTERVAL);
})();
</script>

<style>
@keyframes pulse-once {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.015); }
}
.animate-pulse-once {
    animation: pulse-once 0.8s ease-in-out 2;
}
</style>
@endpush
@endsection