@extends('layout.app')

@section('title', 'Dashboard Admin - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-white to-slate-50 text-slate-900')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <section class="mb-10 sm:mb-12 lg:mb-16 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-brand-50 via-white to-brand-50/50 p-5 sm:p-8 lg:p-12 shadow-lg border border-brand-100/50 hover:border-brand-200/50 transition-all duration-500" data-animate="fade-in-up" data-delay="100">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="inline-flex items-center gap-2 rounded-full bg-brand-100 px-3 py-2 mb-4 max-w-full">
                        <span class="h-2 w-2 bg-brand-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] sm:text-xs font-bold text-brand-700 uppercase tracking-wider truncate">Sistem Aktif</span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black bg-gradient-to-r from-ink-950 to-slate-700 bg-clip-text text-transparent leading-tight">
                        Kontrol Toko
                    </h1>

                    <p class="mt-3 sm:mt-4 max-w-2xl text-sm sm:text-base lg:text-lg leading-relaxed text-slate-600">
                        Pantau pendapatan, pesanan, dan operasional dengan dashboard profesional yang didukung data Laravel real-time.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 sm:gap-3 xl:justify-end">
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl border-2 border-slate-200 bg-white px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base font-bold text-slate-800 transition-all duration-300 hover:border-brand-400 hover:shadow-2xl hover:-translate-y-1 hover:bg-brand-50 min-h-11 sm:min-h-12">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Produk</span>
                    </a>

                    <a href="{{ route('admin.orders') }}" class="inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base font-bold text-white transition-all duration-300 hover:shadow-2xl hover:shadow-brand-500/40 hover:-translate-y-1 hover:from-brand-600 hover:to-brand-700 min-h-11 sm:min-h-12">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Pesanan</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Key Metrics -->
        <section class="grid gap-4 sm:gap-5 mb-10 sm:mb-12 lg:mb-16 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5" data-animate="fade-in-up" data-delay="200">
            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="px-2.5 py-1 rounded-full bg-emerald-100 text-[10px] sm:text-xs font-bold text-emerald-700 whitespace-nowrap">+12%</div>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Pendapatan</p>
                <p class="text-2xl sm:text-3xl font-black text-ink-950 leading-tight break-words">@if($totalRevenue ?? false)Rp {{ number_format($totalRevenue, 0, ',', '.') }}@else Rp 0 @endif</p>
                <p class="text-xs text-slate-500 mt-3">Bulan ini</p>
            </div>

            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Pesanan</p>
                <p class="text-2xl sm:text-3xl font-black text-ink-950">{{ $totalOrders ?? 0 }}</p>
                <div class="h-2 bg-slate-200 rounded-full mt-4 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full w-2/3"></div>
                </div>
            </div>

            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Menunggu Proses</p>
                <p class="text-2xl sm:text-3xl font-black text-amber-700">{{ $pendingOrders ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-3">Butuh tindakan</p>
            </div>

            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Selesai</p>
                <p class="text-2xl sm:text-3xl font-black text-emerald-700">{{ $completedOrders ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-3">Sukses diproses</p>
            </div>

            <div class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 sm:col-span-2 lg:col-span-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-sky-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Review Tertunda</p>
                <p class="text-2xl sm:text-3xl font-black text-sky-700">{{ $pendingTestimonials ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-3">Perlu persetujuan</p>
            </div>
        </section>

        <!-- Charts & Quick Actions -->
        <section class="grid gap-6 lg:grid-cols-[2fr_1fr]" data-animate="fade-in-up" data-delay="300">
            <!-- Analytics Chart -->
            <div class="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 lg:p-8 shadow-panel border border-slate-100 hover:shadow-xl transition-all duration-500">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
                    <div>
                        <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-500 mb-2">Analytics</p>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-ink-950">Performa 7 Hari</h2>
                    </div>

                    <select class="w-full sm:w-auto rounded-xl sm:rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 transition-all">
                        <option>7 hari terakhir</option>
                        <option>30 hari terakhir</option>
                        <option>90 hari terakhir</option>
                    </select>
                </div>

                <div class="h-64 sm:h-80 lg:h-96">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="space-y-6 lg:space-y-8" data-animate="fade-in-up" data-delay="300">
                <!-- Top Products -->
                <div class="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 lg:p-8 shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 sm:mb-8">
                        <div>
                            <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-500 mb-2">Produk Terlaris</p>
                            <h3 class="text-xl sm:text-2xl font-black text-ink-950">Minggu Ini</h3>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="text-brand-500 font-bold hover:text-brand-600 text-sm whitespace-nowrap">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @if(!empty($topProducts) && is_array($topProducts))
                            @forelse($topProducts as $index => $product)
                                <div class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-gradient-to-r from-slate-50 to-transparent hover:from-brand-50 hover:to-brand-50/30 transition-all duration-300 border border-slate-100 hover:border-brand-200">
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
                                    <svg class="h-12 w-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-sm font-semibold">Belum ada data penjualan</p>
                                </div>
                            @endforelse
                        @else
                            <div class="text-center py-10 text-slate-500">
                                <svg class="h-12 w-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-sm font-semibold">Belum ada data penjualan</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 p-5 sm:p-6 lg:p-8 shadow-panel border border-brand-400/30 hover:shadow-2xl hover:shadow-brand-500/20 hover:-translate-y-2 transition-all duration-500">
                    <div class="flex items-center gap-3 mb-6 sm:mb-8">
                        <div class="h-10 w-10 rounded-2xl bg-brand-400/30 flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                            </svg>
                        </div>
                        <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-100 mb-0">Aksi Cepat</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.products.create') }}" class="block w-full rounded-2xl bg-white/95 hover:bg-white px-5 py-3.5 text-center font-bold text-brand-700 text-sm sm:text-base transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Produk Baru
                            </span>
                        </a>

                        <form action="{{ route('admin.recap.send') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full rounded-2xl bg-white/20 hover:bg-white/30 px-5 py-3.5 text-sm sm:text-base font-bold text-white transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-white/20">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Kirim Ringkasan Harian
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Orders Section -->
        <section class="mt-14 sm:mt-16 lg:mt-20" data-animate="fade-in-up" data-delay="400">
            <div class="flex flex-col gap-4 sm:gap-6 sm:flex-row sm:items-center sm:justify-between mb-8 sm:mb-10 lg:mb-12">
                <div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider text-brand-500 mb-2 sm:mb-3">Aktivitas Terbaru</p>
                    <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black bg-gradient-to-r from-ink-950 to-slate-700 bg-clip-text text-transparent">
                        Pesanan Terkini
                    </h2>
                </div>

                <a href="{{ route('admin.orders') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-5 sm:px-6 lg:px-8 py-3 sm:py-4 text-sm sm:text-base lg:text-lg font-bold text-slate-800 hover:border-brand-400 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <span>Lihat Semua Pesanan</span>
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                @if(!empty($recentOrders) && is_array($recentOrders))
                    @forelse($recentOrders as $order)
                        <a href="{{ route('admin.orders') }}?reference={{ urlencode($order->reference ?? '') }}" class="group relative rounded-2xl sm:rounded-3xl bg-white shadow-panel border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden block h-full">
                            <article class="relative p-5 sm:p-6 lg:p-8 h-full flex flex-col">
                                <div class="absolute top-0 right-0 -z-0 w-32 h-32 sm:w-40 sm:h-40 bg-brand-100/20 rounded-full -mr-16 -mt-16 sm:-mr-20 sm:-mt-20 group-hover:bg-brand-100/40 transition-colors duration-500"></div>

                                <div class="relative z-10 flex-1 flex flex-col">
                                    <div class="flex items-start justify-between gap-3 mb-4 sm:mb-6">
                                        <div class="flex-1 min-w-0 pr-2">
                                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 sm:mb-2">Referensi Pesanan</p>
                                            <p class="text-lg sm:text-2xl lg:text-3xl font-black text-brand-600 truncate">{{ $order->reference ?? 'N/A' }}</p>
                                        </div>

                                        <span class="px-2.5 sm:px-3 md:px-4 py-1 sm:py-2 rounded-xl sm:rounded-2xl text-[10px] sm:text-xs font-bold {{ $order->status_color ?? 'text-slate-600' }} bg-opacity-20 whitespace-nowrap flex-shrink-0">
                                            {{ $order->status_label ?? 'Unknown' }}
                                        </span>
                                    </div>

                                    <div class="mb-4 sm:mb-6 pb-4 sm:pb-6 border-b border-slate-200"></div>

                                    <div class="space-y-3 sm:space-y-5 mb-6 sm:mb-8 flex-1">
                                        <div>
                                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 sm:mb-2">Pelanggan</p>
                                            <p class="font-black text-slate-900 text-sm sm:text-base truncate">{{ $order->customer_name ?? 'N/A' }}</p>
                                            <p class="text-xs sm:text-sm text-slate-600 mt-1 truncate">{{ $order->customer_phone ?? '-' }}</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 sm:gap-3 md:gap-4">
                                            <div class="rounded-xl sm:rounded-2xl bg-slate-50 p-3 sm:p-4">
                                                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Item</p>
                                                <p class="text-lg sm:text-2xl font-black text-ink-950 mt-1">{{ is_array($order->items_summary ?? null) ? count($order->items_summary) : 0 }}</p>
                                            </div>

                                            <div class="rounded-xl sm:rounded-2xl bg-slate-50 p-3 sm:p-4">
                                                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total</p>
                                                <p class="text-sm sm:text-lg font-black text-ink-950 mt-1 truncate">{{ method_exists($order, 'formatPrice') ? $order->formatPrice() : 'Rp 0' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4 sm:pt-6 border-t border-slate-200 group-hover:border-brand-200 transition-colors">
                                        <span class="inline-flex items-center justify-center gap-2 w-full rounded-xl sm:rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 px-4 sm:px-5 lg:px-6 py-2.5 sm:py-3 lg:py-4 text-xs sm:text-sm font-bold text-white transition-all duration-300 group-hover:shadow-lg">
                                            Lihat Pesanan
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 rounded-2xl sm:rounded-3xl bg-white p-10 sm:p-16 lg:p-20 text-center shadow-panel border border-slate-100">
                            <svg class="h-16 w-16 sm:h-20 sm:w-20 mx-auto mb-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg sm:text-xl lg:text-2xl font-black text-slate-500 mb-2">Belum Ada Pesanan Terbaru</p>
                            <p class="text-sm sm:text-base text-slate-400 max-w-md mx-auto">Pesanan akan muncul di sini ketika pelanggan melakukan pemesanan</p>
                        </div>
                    @endforelse
                @else
                    <div class="md:col-span-2 lg:col-span-3 rounded-2xl sm:rounded-3xl bg-white p-10 sm:p-16 lg:p-20 text-center shadow-panel border border-slate-100">
                        <svg class="h-16 w-16 sm:h-20 sm:w-20 mx-auto mb-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg sm:text-xl lg:text-2xl font-black text-slate-500 mb-2">Belum Ada Pesanan Terbaru</p>
                        <p class="text-sm sm:text-base text-slate-400 max-w-md mx-auto">Pesanan akan muncul di sini ketika pelanggan melakukan pemesanan</p>
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>

@push('scripts')
<script type="application/json" id="chartData">
{!! json_encode([
    'labels' => $chartLabels ?? [],
    'orders' => $chartOrders ?? [],
    'revenue' => $chartRevenue ?? []
]) !!}
</script>
<script>
(function() {
    try {
        const chartDataElement = document.getElementById('chartData');
        if (!chartDataElement) {
            console.warn('Chart data element not found');
            return;
        }

        const chartData = JSON.parse(chartDataElement.textContent);

        if (!chartData.labels || chartData.labels.length === 0) {
            console.warn('No chart data available');
            return;
        }

        const ctx = document.getElementById('dashboardChart');
        if (!ctx) {
            console.warn('Chart canvas not found');
            return;
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: chartData.revenue || [],
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.08)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(249, 115, 22)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                    },
                    {
                        label: 'Pesanan (Unit)',
                        data: chartData.orders || [],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    },
                    y1: {
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 24,
                            font: { weight: 'bold', size: 12 }
                        }
                    },
                    filler: {
                        propagate: true
                    }
                }
            }
        });
    } catch (error) {
        console.error('Chart initialization error:', error);
    }
})();
</script>
@endpush
@endsection