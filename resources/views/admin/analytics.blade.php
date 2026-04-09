@extends('layout.app')

@section('title', 'Analytics - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-white to-slate-50 text-slate-900')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
@php
    $adminSidebarTitle       = 'Analytics';
    $adminSidebarMetricLabel = 'Total Pesanan';
    $adminSidebarMetricValue = $totalOrders;
    $adminSidebarBody        = 'Analisis performa penjualan dan tren bisnis UP Cireng.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-5 sm:py-8 lg:px-8 xl:px-10">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center space-x-2 text-xs sm:text-sm font-medium text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Dashboard</a>
            <span>/</span>
            <span class="font-semibold text-slate-900">Analytics</span>
        </nav>

        {{-- Header + Period Filter --}}
        <section class="mb-8 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-brand-50 via-white to-brand-50/50 p-5 sm:p-8 shadow-lg border border-brand-100/50">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-brand-100 px-3 py-2 mb-3">
                        <span class="h-2 w-2 bg-brand-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] sm:text-xs font-bold text-brand-700 uppercase tracking-wider">Data Real-Time</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Analytics Penjualan</h1>
                    <p class="mt-1 text-sm text-slate-500">Performa bisnis {{ $period }} hari terakhir</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @foreach([7 => '7 Hari', 30 => '30 Hari', 90 => '90 Hari'] as $days => $label)
                        <a href="{{ route('admin.analytics', ['period' => $days]) }}"
                           class="rounded-xl px-4 py-2 text-sm font-bold border-2 transition-all
                                  {{ $period == $days
                                     ? 'bg-brand-500 border-brand-500 text-white shadow'
                                     : 'bg-white border-slate-200 text-slate-700 hover:border-brand-400' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- KPI Cards --}}
        <div class="mb-8 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @php
            $kpis = [
                ['label' => 'Total Revenue',    'value' => 'Rp '.number_format($totalRevenue,0,',','.'), 'sub' => $completedOrders.' order selesai', 'color' => 'emerald'],
                ['label' => 'Total Pesanan',    'value' => $totalOrders,                                  'sub' => $cancelledOrders.' dibatalkan',    'color' => 'blue'],
                ['label' => 'Konversi',         'value' => $conversionRate.'%',                            'sub' => 'Pesanan selesai',                  'color' => 'violet'],
                ['label' => 'Rata-rata Order',  'value' => 'Rp '.number_format($avgOrderValue,0,',','.'), 'sub' => 'Per transaksi',                   'color' => 'amber'],
            ];
            $colorMap = [
                'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'val' => 'text-emerald-900'],
                'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'val' => 'text-blue-900'],
                'violet'  => ['bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'val' => 'text-violet-900'],
                'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'val' => 'text-amber-900'],
            ];
            @endphp

            @foreach($kpis as $kpi)
            @php $c = $colorMap[$kpi['color']]; @endphp
            <div class="rounded-2xl {{ $c['bg'] }} p-4 sm:p-5 border border-slate-100">
                <p class="text-xs font-bold uppercase tracking-wider {{ $c['text'] }}">{{ $kpi['label'] }}</p>
                <p class="mt-2 text-xl sm:text-2xl font-black {{ $c['val'] }} truncate">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $kpi['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Revenue & Order Trend Charts --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            {{-- Revenue Trend --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Tren Revenue</h2>
                <div style="position:relative;height:220px;">
                    <canvas id="revenueChart" role="img" aria-label="Grafik tren revenue harian"></canvas>
                </div>
            </div>
            {{-- Order Volume --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Volume Pesanan</h2>
                <div style="position:relative;height:220px;">
                    <canvas id="ordersChart" role="img" aria-label="Grafik volume pesanan harian"></canvas>
                </div>
            </div>
        </div>

        {{-- Status Distribution + Payment Methods --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            {{-- Status Pie --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Distribusi Status Pesanan</h2>
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <div style="position:relative;width:180px;height:180px;flex-shrink:0;">
                        <canvas id="statusChart" role="img" aria-label="Diagram status pesanan"></canvas>
                    </div>
                    <div class="flex flex-col gap-2 w-full">
                        @php
                        $statusInfo = [
                            'pending'    => ['label'=>'Pending',    'color'=>'bg-amber-400'],
                            'processing' => ['label'=>'Diproses',   'color'=>'bg-blue-400'],
                            'delivering' => ['label'=>'Dikirim',    'color'=>'bg-violet-400'],
                            'completed'  => ['label'=>'Selesai',    'color'=>'bg-emerald-400'],
                            'cancelled'  => ['label'=>'Dibatalkan', 'color'=>'bg-red-400'],
                        ];
                        $statusTotal = array_sum($statusCounts);
                        @endphp
                        @foreach($statusInfo as $key => $info)
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full {{ $info['color'] }} flex-shrink-0"></span>
                                <span class="text-xs font-medium text-slate-600">{{ $info['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">{{ $statusCounts[$key] }}</span>
                                <span class="text-xs text-slate-400">
                                    ({{ $statusTotal > 0 ? round($statusCounts[$key]/$statusTotal*100) : 0 }}%)
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Metode Pembayaran</h2>
                @if($paymentMethods->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada data</p>
                @else
                <div class="space-y-3">
                    @php $pmTotal = $paymentMethods->sum('count'); @endphp
                    @foreach($paymentMethods as $pm)
                    @php $pct = $pmTotal > 0 ? round($pm['count']/$pmTotal*100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-600 mb-1">
                            <span class="capitalize">{{ $pm['method'] }}</span>
                            <span class="font-bold text-slate-800">{{ $pm['count'] }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-brand-400 transition-all" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Weekly Pattern + Hourly --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            {{-- Weekly --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Pola Hari dalam Seminggu</h2>
                <div style="position:relative;height:200px;">
                    <canvas id="weeklyChart" role="img" aria-label="Grafik pesanan per hari dalam seminggu"></canvas>
                </div>
            </div>
            {{-- Hourly --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Jam Sibuk Pesanan</h2>
                <div style="position:relative;height:200px;">
                    <canvas id="hourlyChart" role="img" aria-label="Grafik volume pesanan per jam"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Products + Top Orders --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            {{-- Top Products --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Produk Terlaris</h2>
                @if($topProducts->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada data produk</p>
                @else
                @php $maxRevenue = $topProducts->max('revenue') ?: 1; @endphp
                <div class="space-y-3">
                    @foreach($topProducts as $i => $product)
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">{{ $i+1 }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-slate-700 truncate">{{ $product['name'] }}</span>
                                <span class="text-xs font-bold text-slate-800 flex-shrink-0 ml-2">Rp {{ number_format($product['revenue'],0,',','.') }}</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-slate-100">
                                <div class="h-1.5 rounded-full bg-brand-400" style="width:{{ round($product['revenue']/$maxRevenue*100) }}%"></div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ number_format($product['quantity'],1) }} unit terjual</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Top Orders --}}
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Order Nilai Tertinggi</h2>
                @if($topOrders->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada order selesai</p>
                @else
                <div class="space-y-3">
                    @foreach($topOrders as $order)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-4 py-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $order->customer_name ?? 'Customer' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order->reference ?? 'N/A' }} · {{ $order->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                        <span class="text-sm font-black text-emerald-700 flex-shrink-0">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Customer Summary --}}
        <div class="mb-8 rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Ringkasan Customer</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="rounded-xl bg-slate-50 p-4 text-center">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($totalCustomers) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total Customer</p>
                </div>
                <div class="rounded-xl bg-emerald-50 p-4 text-center">
                    <p class="text-2xl font-black text-emerald-800">{{ number_format($newCustomers) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Customer Baru ({{ $period }}hr)</p>
                </div>
                <div class="rounded-xl bg-blue-50 p-4 text-center col-span-2 sm:col-span-1">
                    <p class="text-2xl font-black text-blue-800">
                        {{ $totalCustomers > 0 ? number_format($totalRevenue / $totalCustomers, 0, ',', '.') : 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Revenue / Customer (Rp)</p>
                </div>
            </div>
        </div>

    </main>
</div>

@push('scripts')
<script>
(function () {
    const labels  = @json($trendLabels);
    const revenue = @json($trendRevenue);
    const orders  = @json($trendOrders);

    const font = { family: 'Inter, system-ui, sans-serif', size: 11 };
    const gridColor = 'rgba(0,0,0,0.05)';

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Revenue',
                data: revenue,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#16a34a',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { font, maxRotation: 45, autoSkip: true, maxTicksLimit: 10 }, grid: { color: gridColor } },
                y: { ticks: { font, callback: v => 'Rp '+Intl.NumberFormat('id').format(v) }, grid: { color: gridColor } }
            }
        }
    });

    // Orders Chart
    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Pesanan',
                data: orders,
                backgroundColor: 'rgba(59,130,246,0.7)',
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { font, maxRotation: 45, autoSkip: true, maxTicksLimit: 10 }, grid: { display: false } },
                y: { ticks: { font, stepSize: 1 }, grid: { color: gridColor } }
            }
        }
    });

    // Status Donut
    const statusData = @json(array_values($statusCounts));
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending','Diproses','Dikirim','Selesai','Dibatalkan'],
            datasets: [{
                data: statusData,
                backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#34d399','#f87171'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });

    // Weekly Chart
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Pesanan',
                data: @json($weeklyOrders),
                backgroundColor: 'rgba(139,92,246,0.7)',
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { font }, grid: { display: false } },
                y: { ticks: { font, stepSize: 1 }, grid: { color: gridColor } }
            }
        }
    });

    // Hourly Chart
    const hourLabels = Array.from({length: 24}, (_, i) => i.toString().padStart(2,'0')+':00');
    new Chart(document.getElementById('hourlyChart'), {
        type: 'bar',
        data: {
            labels: hourLabels,
            datasets: [{
                label: 'Pesanan',
                data: @json($hourlyOrders),
                backgroundColor: 'rgba(249,115,22,0.7)',
                borderRadius: 3,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { font, maxRotation: 45, autoSkip: true, maxTicksLimit: 8 }, grid: { display: false } },
                y: { ticks: { font, stepSize: 1 }, grid: { color: gridColor } }
            }
        }
    });
})();
</script>
@endpush
@endsection