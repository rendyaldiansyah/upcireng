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

        {{-- ── Header + Controls ──────────────────────────────────────────── --}}
        <section class="mb-8 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-brand-50 via-white to-brand-50/50 p-5 sm:p-8 shadow-lg border border-brand-100/50">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-brand-100 px-3 py-2 mb-3">
                        <span class="h-2 w-2 bg-brand-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] sm:text-xs font-bold text-brand-700 uppercase tracking-wider">Data Real-Time</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Analytics Penjualan</h1>
                    <p class="mt-1 text-sm text-slate-500">Performa bisnis {{ $period }} hari terakhir</p>
                </div>

                <div class="flex flex-col gap-3">
                    {{-- Period filter --}}
                    <div class="flex gap-2 flex-wrap">
                        @foreach([7 => '7 Hari', 30 => '30 Hari', 90 => '90 Hari'] as $days => $label)
                            <a href="{{ route('admin.analytics', ['period' => $days]) }}"
                               class="rounded-xl px-4 py-2 text-sm font-bold border-2 transition-all
                                      {{ $period == $days ? 'bg-brand-500 border-brand-500 text-white shadow' : 'bg-white border-slate-200 text-slate-700 hover:border-brand-400' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Kirim ke Sheet --}}
                    <form method="POST" action="{{ route('admin.analytics.send-sheet') }}" id="sendSheetForm">
                        @csrf
                        <input type="hidden" name="period" value="{{ $period }}">
                        <button type="submit" id="sendSheetBtn"
                                class="w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all disabled:opacity-60">
                            <svg id="sendSheetIcon" class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                            </svg>
                            <svg id="sendSheetSpinner" class="h-4 w-4 animate-spin hidden flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span id="sendSheetLabel">📊 Kirim ke Google Sheet</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        {{-- ── Revenue Breakdown: Gross / Net / Pending ───────────────────── --}}
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">

            <div class="rounded-2xl bg-orange-50 border border-orange-100 p-4 sm:p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-lg">💰</span>
                    <p class="text-xs font-bold uppercase tracking-wider text-orange-700">Gross Revenue</p>
                </div>
                <p class="text-xl sm:text-2xl font-black text-orange-900 truncate">
                    Rp {{ number_format($grossRevenue, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-slate-500">Semua {{ $totalOrders }} order masuk</p>
                <div class="mt-2 h-1.5 w-full rounded-full bg-orange-200">
                    <div class="h-1.5 rounded-full bg-orange-400" style="width:100%"></div>
                </div>
            </div>

            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 sm:p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-lg">✅</span>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Net Revenue</p>
                </div>
                @if($netRevenue > 0)
                    <p class="text-xl sm:text-2xl font-black text-emerald-900 truncate">
                        Rp {{ number_format($netRevenue, 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-slate-400 italic mt-1">Belum ada transaksi selesai</p>
                @endif
                <p class="mt-1 text-xs text-slate-500">{{ $completedOrders }} order selesai</p>
                <div class="mt-2 h-1.5 w-full rounded-full bg-emerald-200">
                    <div class="h-1.5 rounded-full bg-emerald-500 transition-all"
                         style="width:{{ $grossRevenue > 0 ? round($netRevenue/$grossRevenue*100) : 0 }}%"></div>
                </div>
            </div>

            <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4 sm:p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-lg">⏳</span>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Pending Revenue</p>
                </div>
                <p class="text-xl sm:text-2xl font-black text-amber-900 truncate">
                    Rp {{ number_format($pendingRevenue, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-slate-500">{{ $totalOrders - $completedOrders - $cancelledOrders }} order belum selesai</p>
                <div class="mt-2 h-1.5 w-full rounded-full bg-amber-200">
                    <div class="h-1.5 rounded-full bg-amber-400 transition-all"
                         style="width:{{ $grossRevenue > 0 ? round($pendingRevenue/$grossRevenue*100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- ── KPI Row 2: Order, Konversi, Avg, Repeat ────────────────────── --}}
        <div class="mb-8 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @php
            $kpis = [
                ['label'=>'Total Pesanan',   'value'=>$totalOrders,                                   'sub'=>$cancelledOrders.' dibatalkan',                'color'=>'blue'],
                ['label'=>'Konversi',        'value'=>$conversionRate.'%',                             'sub'=>$completedOrders.' dari '.$totalOrders.' order','color'=>'violet'],
                ['label'=>'Avg Order Value', 'value'=>'Rp '.number_format($avgOrderValue,0,',','.'),  'sub'=>'Per transaksi (gross)',                        'color'=>'amber'],
                ['label'=>'Repeat Customer', 'value'=>$repeatRate.'%',                                 'sub'=>$repeatCustomers.' dari '.$uniqueCustomers.' customer','color'=>'emerald'],
            ];
            $cm = [
                'blue'    => ['bg-blue-50',    'text-blue-700',    'text-blue-900'],
                'violet'  => ['bg-violet-50',  'text-violet-700',  'text-violet-900'],
                'amber'   => ['bg-amber-50',   'text-amber-700',   'text-amber-900'],
                'emerald' => ['bg-emerald-50', 'text-emerald-700', 'text-emerald-900'],
            ];
            @endphp
            @foreach($kpis as $kpi)
            @php [$bg,$tc,$vc] = $cm[$kpi['color']]; @endphp
            <div class="rounded-2xl {{ $bg }} p-4 sm:p-5 border border-slate-100">
                <p class="text-xs font-bold uppercase tracking-wider {{ $tc }}">{{ $kpi['label'] }}</p>
                <p class="mt-2 text-xl sm:text-2xl font-black {{ $vc }} truncate">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $kpi['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Conversion Funnel ───────────────────────────────────────────── --}}
        <div class="mb-8 rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-5">🎯 Conversion Funnel</h2>
            @if($totalOrders === 0)
                <p class="text-sm text-slate-400 text-center py-6">Belum ada transaksi selesai</p>
            @else
            @php
            $fc = [
                'orange' => 'bg-orange-400',
                'blue'   => 'bg-blue-400',
                'purple' => 'bg-purple-400',
                'green'  => 'bg-emerald-400',
                'red'    => 'bg-red-400',
            ];
            @endphp
            <div class="space-y-3">
                @foreach($funnel as $step)
                @php $pct = $totalOrders > 0 ? round($step['count'] / $totalOrders * 100) : 0; @endphp
                <div class="flex items-center gap-3">
                    <span class="w-28 flex-shrink-0 text-xs font-semibold text-slate-600">{{ $step['label'] }}</span>
                    <div class="flex-1 h-7 rounded-lg bg-slate-100 overflow-hidden relative">
                        <div class="h-full rounded-lg {{ $fc[$step['color']] }} transition-all duration-700"
                             style="width: {{ max($pct, $step['count'] > 0 ? 3 : 0) }}%"></div>
                        <span class="absolute inset-0 flex items-center px-3 text-xs font-bold text-slate-700">
                            {{ number_format($step['count']) }} ({{ $pct }}%)
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── Revenue Trend (Gross + Net dual line) ──────────────────────── --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Tren Revenue</h2>
                    <div class="flex gap-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1.5"><span class="inline-block w-4 h-1 rounded bg-orange-400"></span>Gross</span>
                        <span class="flex items-center gap-1.5"><span class="inline-block w-4 h-1 rounded bg-emerald-500 opacity-70" style="background:repeating-linear-gradient(90deg,#16a34a 0 4px,transparent 4px 8px)"></span>Net</span>
                    </div>
                </div>
                <div style="position:relative;height:220px;"><canvas id="revenueChart"></canvas></div>
            </div>

            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Volume Pesanan</h2>
                <div style="position:relative;height:220px;"><canvas id="ordersChart"></canvas></div>
            </div>
        </div>

        {{-- ── Status + Payment ────────────────────────────────────────────── --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Distribusi Status Pesanan</h2>
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <div style="position:relative;width:180px;height:180px;flex-shrink:0;"><canvas id="statusChart"></canvas></div>
                    <div class="flex flex-col gap-2 w-full">
                        @php
                        $si = ['pending'=>['Pending','bg-amber-400'],'processing'=>['Diproses','bg-blue-400'],'delivering'=>['Dikirim','bg-violet-400'],'completed'=>['Selesai','bg-emerald-400'],'cancelled'=>['Dibatalkan','bg-red-400']];
                        $st = array_sum($statusCounts);
                        @endphp
                        @foreach($si as $key => [$lbl, $clr])
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full {{ $clr }} flex-shrink-0"></span>
                                <span class="text-xs font-medium text-slate-600">{{ $lbl }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">{{ $statusCounts[$key] }}</span>
                                <span class="text-xs text-slate-400">({{ $st > 0 ? round($statusCounts[$key]/$st*100) : 0 }}%)</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Metode Pembayaran</h2>
                @if($paymentMethods->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada data</p>
                @else
                @php $pmt = $paymentMethods->sum('count'); @endphp
                <div class="space-y-3">
                    @foreach($paymentMethods as $pm)
                    @php $p = $pmt > 0 ? round($pm['count']/$pmt*100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-600 mb-1">
                            <span class="capitalize">{{ $pm['method'] }}</span>
                            <span class="font-bold text-slate-800">{{ $pm['count'] }} ({{ $p }}%)</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-brand-400 transition-all" style="width:{{ $p }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Weekly + Hourly ─────────────────────────────────────────────── --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Pola Hari dalam Seminggu</h2>
                <div style="position:relative;height:200px;"><canvas id="weeklyChart"></canvas></div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Jam Sibuk Pesanan</h2>
                <div style="position:relative;height:200px;"><canvas id="hourlyChart"></canvas></div>
            </div>
        </div>

        {{-- ── Top Products + Top Orders ────────────────────────────────────── --}}
        <div class="mb-8 grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-1">Produk Terlaris</h2>
                <p class="text-xs text-slate-400 mb-4">Berdasarkan semua order masuk</p>
                @if($topProducts->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada data produk</p>
                @else
                @php $maxR = $topProducts->max('revenue') ?: 1; @endphp
                <div class="space-y-3">
                    @foreach($topProducts as $i => $product)
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">{{ $i+1 }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-slate-700 truncate">{{ $product['name'] }}</span>
                                <span class="text-xs font-bold text-slate-800 ml-2 flex-shrink-0">Rp {{ number_format($product['revenue'],0,',','.') }}</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-slate-100">
                                <div class="h-1.5 rounded-full bg-brand-400" style="width:{{ round($product['revenue']/$maxR*100) }}%"></div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ number_format($product['quantity'],1) }} unit</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-1">Order Nilai Tertinggi</h2>
                <p class="text-xs text-slate-400 mb-4">Dari semua status, bukan hanya selesai</p>
                @if($topOrders->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada order</p>
                @else
                <div class="space-y-3">
                    @foreach($topOrders as $order)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-4 py-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $order->customer_name ?? 'Customer' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order->reference ?? 'N/A' }} · {{ $order->created_at->translatedFormat('d M Y') }}</p>
                            <span class="inline-block mt-0.5 text-[10px] font-bold px-2 py-0.5 rounded-full
                                {{ $order->status === Order::STATUS_COMPLETED ? 'bg-emerald-100 text-emerald-700' :
                                   ($order->status === Order::STATUS_CANCELLED  ? 'bg-red-100 text-red-700'       :
                                   ($order->status === Order::STATUS_DELIVERING ? 'bg-violet-100 text-violet-700' :
                                   'bg-blue-100 text-blue-700')) }}">
                                {{ $order->status_label ?? ucfirst($order->status) }}
                            </span>
                        </div>
                        <span class="text-sm font-black text-slate-800 flex-shrink-0">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Customer Summary ───────────────────────────────────────────── --}}
        <div class="mb-8 rounded-2xl bg-white border border-slate-100 shadow-sm p-5 sm:p-6">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Ringkasan Customer</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-xl bg-slate-50 p-4 text-center">
                    <p class="text-2xl font-black text-slate-800">{{ number_format($totalCustomers) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total Customer</p>
                </div>
                <div class="rounded-xl bg-emerald-50 p-4 text-center">
                    <p class="text-2xl font-black text-emerald-800">{{ number_format($newCustomers) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Baru ({{ $period }}hr)</p>
                </div>
                <div class="rounded-xl bg-violet-50 p-4 text-center">
                    <p class="text-2xl font-black text-violet-800">{{ $repeatRate }}%</p>
                    <p class="text-xs text-slate-500 mt-1">Repeat Customer</p>
                </div>
                <div class="rounded-xl bg-blue-50 p-4 text-center">
                    <p class="text-2xl font-black text-blue-800">
                        Rp {{ $uniqueCustomers > 0 ? number_format(round($grossRevenue / $uniqueCustomers), 0, ',', '.') : 0 }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Revenue/Customer</p>
                </div>
            </div>
        </div>

    </main>
</div>

@push('scripts')
<script>
(function () {
    const labels     = @json($trendLabels);
    const revenue    = @json($trendRevenue);
    const netRev     = @json($trendNetRevenue);
    const orders     = @json($trendOrders);
    const font       = { family: 'Manrope, system-ui, sans-serif', size: 11 };
    const grid       = 'rgba(0,0,0,0.05)';

    // Revenue (Gross + Net dual)
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                { label:'Gross', data:revenue, borderColor:'#f97316', backgroundColor:'rgba(249,115,22,0.08)', fill:true, tension:0.4, pointRadius:3, borderWidth:2 },
                { label:'Net',   data:netRev,  borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.05)',  fill:true, tension:0.4, pointRadius:3, borderWidth:2, borderDash:[5,3] }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display:false } },
            scales:{
                x:{ ticks:{ font, maxRotation:45, autoSkip:true, maxTicksLimit:10 }, grid:{ color:grid } },
                y:{ ticks:{ font, callback:v=>'Rp '+Intl.NumberFormat('id').format(v) }, grid:{ color:grid } }
            }
        }
    });

    // Orders
    new Chart(document.getElementById('ordersChart'), {
        type:'bar',
        data:{ labels, datasets:[{ data:orders, backgroundColor:'rgba(59,130,246,0.7)', borderRadius:4, borderSkipped:false }] },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ font, maxRotation:45, autoSkip:true, maxTicksLimit:10 }, grid:{ display:false } }, y:{ ticks:{ font, stepSize:1 }, grid:{ color:grid } } } }
    });

    // Status Donut
    new Chart(document.getElementById('statusChart'), {
        type:'doughnut',
        data:{ labels:['Pending','Diproses','Dikirim','Selesai','Dibatalkan'], datasets:[{ data:@json(array_values($statusCounts)), backgroundColor:['#fbbf24','#60a5fa','#a78bfa','#34d399','#f87171'], borderWidth:2, borderColor:'#fff' }] },
        options:{ responsive:true, maintainAspectRatio:false, cutout:'65%', plugins:{ legend:{ display:false } } }
    });

    // Weekly
    new Chart(document.getElementById('weeklyChart'), {
        type:'bar',
        data:{ labels:@json($weeklyLabels), datasets:[{ data:@json($weeklyOrders), backgroundColor:'rgba(139,92,246,0.7)', borderRadius:4, borderSkipped:false }] },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ font }, grid:{ display:false } }, y:{ ticks:{ font, stepSize:1 }, grid:{ color:grid } } } }
    });

    // Hourly
    new Chart(document.getElementById('hourlyChart'), {
        type:'bar',
        data:{ labels:Array.from({length:24},(_,i)=>i.toString().padStart(2,'0')+':00'), datasets:[{ data:@json($hourlyOrders), backgroundColor:'rgba(249,115,22,0.7)', borderRadius:3, borderSkipped:false }] },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ font, maxRotation:45, autoSkip:true, maxTicksLimit:8 }, grid:{ display:false } }, y:{ ticks:{ font, stepSize:1 }, grid:{ color:grid } } } }
    });

    // Kirim ke Sheet — loading state
    document.getElementById('sendSheetForm').addEventListener('submit', function () {
        const btn = document.getElementById('sendSheetBtn');
        btn.disabled = true;
        document.getElementById('sendSheetIcon').classList.add('hidden');
        document.getElementById('sendSheetSpinner').classList.remove('hidden');
        document.getElementById('sendSheetLabel').textContent = 'Mengirim data...';
        btn.classList.replace('bg-emerald-600', 'bg-emerald-400');
    });
})();
</script>
@endpush
@endsection