@extends('layout.app')
@php use App\Models\Order; @endphp

@section('title', 'Analytics - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-slate-50 text-slate-900')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap');

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        .period-pill {
            display: inline-flex; align-items: center;
            padding: 5px 13px; border-radius: 999px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.03em;
            border: 1.5px solid #e2e8f0; background: #fff; color: #64748b;
            text-decoration: none; transition: all 0.18s ease; white-space: nowrap;
        }
        .period-pill:hover { border-color: #f97316; color: #ea580c; background: #fff7ed; }
        .period-pill.active { background: #f97316; border-color: #f97316; color: #fff; box-shadow: 0 4px 12px rgba(249,115,22,0.3); }

        .kpi-card {
            background: #fff; border-radius: 20px; padding: 20px 22px;
            border: 1px solid #f1f5f9; position: relative; overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 36px -6px rgba(15,23,42,0.1); }
        .kpi-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; border-radius: 20px 20px 0 0;
        }
        .kpi-card.orange::before { background: linear-gradient(90deg,#f97316,#fb923c); }
        .kpi-card.green::before  { background: linear-gradient(90deg,#10b981,#34d399); }
        .kpi-card.amber::before  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
        .kpi-card.blue::before   { background: linear-gradient(90deg,#3b82f6,#60a5fa); }
        .kpi-card.violet::before { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
        .kpi-card.emerald::before{ background: linear-gradient(90deg,#059669,#10b981); }

        .chart-card { background:#fff; border-radius:20px; padding:22px 24px; border:1px solid #f1f5f9; }

        .funnel-bar { height:32px; border-radius:8px; background:#f1f5f9; overflow:hidden; position:relative; }
        .funnel-fill { height:100%; border-radius:8px; transition:width 0.8s cubic-bezier(0.34,1.56,0.64,1); }
        .funnel-label {
            position:absolute; left:12px; top:0; bottom:0;
            display:flex; align-items:center;
            font-size:11px; font-weight:700; color:#1e293b; pointer-events:none;
        }

        .send-btn {
            display:inline-flex; align-items:center; gap:8px;
            background:linear-gradient(135deg,#059669,#10b981); color:white;
            font-weight:700; font-size:12px; padding:9px 18px; border-radius:12px;
            border:none; cursor:pointer; box-shadow:0 4px 14px rgba(5,150,105,0.28);
            transition:all 0.2s ease; font-family:inherit;
        }
        .send-btn:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(5,150,105,0.38); }
        .send-btn:active { transform:scale(0.98); }
        .send-btn:disabled { opacity:0.6; cursor:not-allowed; transform:none; }

        .status-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .progress-track { height:5px; background:#f1f5f9; border-radius:999px; overflow:hidden; }
        .progress-fill  { height:100%; border-radius:999px; transition:width 0.6s ease; }

        .order-row {
            display:flex; align-items:center; justify-content:space-between; gap:12px;
            padding:11px 13px; border-radius:12px; background:#f8fafc;
            transition:background 0.15s;
        }
        .order-row:hover { background:#f1f5f9; }

        .section-title {
            font-size:10px; font-weight:800; letter-spacing:0.13em;
            text-transform:uppercase; color:#94a3b8; margin-bottom:14px;
        }

        .empty-state {
            display:flex; flex-direction:column; align-items:center;
            justify-content:center; padding:40px 0; color:#cbd5e1;
        }
        .empty-state span { font-size:36px; margin-bottom:8px; }
        .empty-state p { font-size:12px; font-weight:600; }

        @keyframes slideUp {
            from { opacity:0; transform:translateY(14px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .au  { animation:slideUp 0.45s ease both; }
        .d1  { animation-delay:0.04s; } .d2 { animation-delay:0.08s; }
        .d3  { animation-delay:0.13s; } .d4 { animation-delay:0.18s; }
        .d5  { animation-delay:0.23s; } .d6 { animation-delay:0.28s; }

        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }
    </style>
@endpush

@section('content')
@php
    $adminSidebarTitle       = 'Analytics';
    $adminSidebarMetricLabel = 'Total Pesanan';
    $adminSidebarMetricValue = $totalOrders;
    $adminSidebarBody        = 'Analisis performa penjualan dan tren bisnis UP Cireng.';

    $periods = [
        7   => '7H',
        14  => '14H',
        30  => '30H',
        60  => '60H',
        90  => '90H',
        180 => '6 Bln',
        365 => '1 Tahun',
    ];
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8 xl:px-10">

        {{-- ── Header ── --}}
        <div class="au mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-700">Analytics</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Analytics Penjualan</h1>
                <p class="mt-1 text-sm text-slate-400 font-medium flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Data real-time · periode {{ $period }} hari terakhir
                </p>
            </div>

            <div class="flex flex-col gap-2.5 sm:items-end">
                {{-- Period pills --}}
                <div class="flex items-center gap-1.5 flex-wrap">
                    @foreach($periods as $days => $label)
                        <a href="{{ route('admin.analytics', ['period' => $days]) }}"
                           class="period-pill {{ $period == $days ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                {{-- Send sheet --}}
                <form method="POST" action="{{ route('admin.analytics.send-sheet') }}" id="sendSheetForm">
                    @csrf
                    <input type="hidden" name="period" value="{{ $period }}">
                    <button type="submit" id="sendSheetBtn" class="send-btn">
                        <svg id="sendSheetIcon" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <svg id="sendSheetSpinner" class="h-3.5 w-3.5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span id="sendSheetLabel">Kirim ke Google Sheet</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Revenue Cards ── --}}
        <div class="au d1 mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">

            <div class="kpi-card orange">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💰</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-orange-500">Gross Revenue</span>
                    </div>
                    <span class="mono text-[9px] text-slate-300 font-semibold bg-slate-50 px-1.5 py-0.5 rounded">ALL</span>
                </div>
                <p class="mono text-2xl sm:text-3xl font-black text-slate-900 truncate">
                    Rp {{ number_format($grossRevenue,0,',','.') }}
                </p>
                <div class="mt-3 flex items-center justify-between text-xs">
                    <span class="text-slate-400">{{ $totalOrders }} order masuk</span>
                    <span class="mono font-black text-orange-500">100%</span>
                </div>
                <div class="progress-track mt-1.5"><div class="progress-fill bg-orange-400" style="width:100%"></div></div>
            </div>

            <div class="kpi-card green">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">✅</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Net Revenue</span>
                    </div>
                    <span class="mono text-[9px] text-slate-300 font-semibold bg-slate-50 px-1.5 py-0.5 rounded">DONE</span>
                </div>
                @if($netRevenue > 0)
                    <p class="mono text-2xl sm:text-3xl font-black text-slate-900 truncate">
                        Rp {{ number_format($netRevenue,0,',','.') }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-slate-300 italic">Belum ada transaksi selesai</p>
                @endif
                <div class="mt-3 flex items-center justify-between text-xs">
                    <span class="text-slate-400">{{ $completedOrders }} order selesai</span>
                    @php $netPct = $grossRevenue > 0 ? round($netRevenue/$grossRevenue*100) : 0; @endphp
                    <span class="mono font-black text-emerald-500">{{ $netPct }}%</span>
                </div>
                <div class="progress-track mt-1.5"><div class="progress-fill bg-emerald-400" style="width:{{ $netPct }}%"></div></div>
            </div>

            <div class="kpi-card amber">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">⏳</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-500">Pending Revenue</span>
                    </div>
                    <span class="mono text-[9px] text-slate-300 font-semibold bg-slate-50 px-1.5 py-0.5 rounded">WAIT</span>
                </div>
                <p class="mono text-2xl sm:text-3xl font-black text-slate-900 truncate">
                    Rp {{ number_format($pendingRevenue,0,',','.') }}
                </p>
                <div class="mt-3 flex items-center justify-between text-xs">
                    <span class="text-slate-400">{{ $totalOrders-$completedOrders-$cancelledOrders }} belum selesai</span>
                    @php $pendPct = $grossRevenue > 0 ? round($pendingRevenue/$grossRevenue*100) : 0; @endphp
                    <span class="mono font-black text-amber-500">{{ $pendPct }}%</span>
                </div>
                <div class="progress-track mt-1.5"><div class="progress-fill bg-amber-400" style="width:{{ $pendPct }}%"></div></div>
            </div>
        </div>

        {{-- ── KPI Grid ── --}}
        <div class="au d2 mb-4 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @php
            $kpis = [
                ['label'=>'Total Pesanan',   'val'=>$totalOrders,        'sub'=>$cancelledOrders.' dibatalkan',               'color'=>'blue',   'icon'=>'📦','mono'=>false],
                ['label'=>'Konversi',        'val'=>$conversionRate.'%', 'sub'=>$completedOrders.'/'.$totalOrders.' selesai', 'color'=>'violet', 'icon'=>'🎯','mono'=>true],
                ['label'=>'Avg Order Value', 'val'=>'Rp '.number_format($avgOrderValue,0,',','.'), 'sub'=>'Per transaksi gross','color'=>'amber','icon'=>'📊','mono'=>true],
                ['label'=>'Repeat Customer', 'val'=>$repeatRate.'%',     'sub'=>$repeatCustomers.'/'.$uniqueCustomers.' cust','color'=>'emerald','icon'=>'🔁','mono'=>true],
            ];
            $cc=['blue'=>['text-blue-600','bg-blue-50'],'violet'=>['text-violet-600','bg-violet-50'],'amber'=>['text-amber-600','bg-amber-50'],'emerald'=>['text-emerald-600','bg-emerald-50']];
            @endphp
            @foreach($kpis as $k)
            @php [$tc,$ic]=$cc[$k['color']]; @endphp
            <div class="kpi-card {{ $k['color'] }}">
                <div class="flex items-center gap-2 mb-2.5">
                    <div class="h-7 w-7 rounded-lg {{ $ic }} flex items-center justify-center text-sm">{{ $k['icon'] }}</div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $tc }}">{{ $k['label'] }}</span>
                </div>
                <p class="{{ $k['mono']?'mono':'' }} text-xl sm:text-2xl font-black text-slate-900">{{ $k['val'] }}</p>
                <p class="mt-1 text-[11px] text-slate-400">{{ $k['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Conversion Funnel ── --}}
        <div class="au d2 chart-card mb-4">
            <p class="section-title">🎯 Conversion Funnel</p>
            @if($totalOrders === 0)
                <div class="empty-state"><span>📭</span><p>Belum ada data transaksi</p></div>
            @else
            @php
            $funnelCfg=['orange'=>'bg-orange-400','blue'=>'bg-blue-400','purple'=>'bg-purple-400','green'=>'bg-emerald-400','red'=>'bg-red-400'];
            @endphp
            <div class="space-y-2">
                @foreach($funnel as $step)
                @php $pct=$totalOrders>0?round($step['count']/$totalOrders*100):0; @endphp
                <div class="flex items-center gap-3">
                    <span class="w-28 flex-shrink-0 text-[11px] font-semibold text-slate-500">{{ $step['label'] }}</span>
                    <div class="funnel-bar flex-1">
                        <div class="funnel-fill {{ $funnelCfg[$step['color']] }}"
                             style="width:{{ max($pct,$step['count']>0?4:0) }}%"></div>
                        <span class="funnel-label">
                            {{ number_format($step['count']) }}
                            <span class="text-slate-400 font-normal ml-1">({{ $pct }}%)</span>
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── Charts ── --}}
        <div class="au d3 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="chart-card">
                <div class="flex items-center justify-between mb-1">
                    <p class="section-title mb-0">Tren Revenue</p>
                    <div class="flex gap-3 text-[10px] font-bold text-slate-400">
                        <span class="flex items-center gap-1"><span class="inline-block h-1.5 w-4 rounded bg-orange-400"></span>Gross</span>
                        <span class="flex items-center gap-1"><span class="inline-block h-1.5 w-4 rounded bg-emerald-400"></span>Net</span>
                    </div>
                </div>
                <div class="mb-3"></div>
                @if($totalOrders===0)
                    <div class="empty-state py-10"><span>📈</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:196px;"><canvas id="revenueChart"></canvas></div>
                @endif
            </div>
            <div class="chart-card">
                <p class="section-title">Volume Pesanan</p>
                @if($totalOrders===0)
                    <div class="empty-state py-10"><span>📦</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:196px;"><canvas id="ordersChart"></canvas></div>
                @endif
            </div>
        </div>

        {{-- ── Status + Payment ── --}}
        <div class="au d3 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="chart-card">
                <p class="section-title">Distribusi Status Pesanan</p>
                @if($totalOrders===0)
                    <div class="empty-state"><span>📊</span><p>Belum ada data</p></div>
                @else
                @php
                $si=['pending'=>['Pending','bg-amber-400','#fbbf24'],'processing'=>['Diproses','bg-blue-400','#60a5fa'],'delivering'=>['Dikirim','bg-violet-400','#a78bfa'],'completed'=>['Selesai','bg-emerald-400','#34d399'],'cancelled'=>['Dibatalkan','bg-red-400','#f87171']];
                $st=array_sum($statusCounts);
                @endphp
                <div class="flex flex-col sm:flex-row gap-5 items-center">
                    <div style="position:relative;width:150px;height:150px;flex-shrink:0;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="flex flex-col gap-2.5 w-full">
                        @foreach($si as $key=>[$lbl,$dotClass,$hex])
                        @php $cnt=$statusCounts[$key];$p=$st>0?round($cnt/$st*100):0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="status-dot {{ $dotClass }}"></span>
                                    <span class="text-[11px] font-semibold text-slate-600">{{ $lbl }}</span>
                                </div>
                                <span class="mono text-[11px] font-bold text-slate-700">
                                    {{ $cnt }} <span class="text-slate-300">({{ $p }}%)</span>
                                </span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width:{{ $p }}%;background:{{ $hex }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="chart-card">
                <p class="section-title">Metode Pembayaran</p>
                @if($paymentMethods->isEmpty())
                    <div class="empty-state"><span>💳</span><p>Belum ada data</p></div>
                @else
                @php $pmt=$paymentMethods->sum('count'); @endphp
                <div class="space-y-3.5">
                    @foreach($paymentMethods as $pm)
                    @php $p=$pmt>0?round($pm['count']/$pmt*100):0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs font-bold text-slate-700 capitalize">{{ $pm['method'] }}</span>
                            <div class="flex items-center gap-2">
                                <span class="mono text-[10px] text-slate-400">{{ $pm['count'] }}x</span>
                                <span class="mono text-[11px] font-black text-slate-700">{{ $p }}%</span>
                            </div>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill bg-orange-400" style="width:{{ $p }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Weekly + Hourly ── --}}
        <div class="au d4 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="chart-card">
                <p class="section-title">Pola Hari dalam Seminggu</p>
                @if($totalOrders===0)
                    <div class="empty-state"><span>📅</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:176px;"><canvas id="weeklyChart"></canvas></div>
                @endif
            </div>
            <div class="chart-card">
                <p class="section-title">Jam Sibuk Pesanan</p>
                @if($totalOrders===0)
                    <div class="empty-state"><span>🕐</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:176px;"><canvas id="hourlyChart"></canvas></div>
                @endif
            </div>
        </div>

        {{-- ── Top Products + Orders ── --}}
        <div class="au d5 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="chart-card">
                <p class="section-title">Produk Terlaris</p>
                @if($topProducts->isEmpty())
                    <div class="empty-state"><span>🛒</span><p>Belum ada data produk</p></div>
                @else
                @php $maxR=$topProducts->max('revenue')?:1; @endphp
                <div class="space-y-3.5">
                    @foreach($topProducts as $i=>$product)
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center mono text-[9px] font-black text-slate-400 flex-shrink-0">{{ $i+1 }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[11px] font-bold text-slate-700 truncate">{{ $product['name'] }}</span>
                                <span class="mono text-[11px] font-black text-slate-800 ml-2 flex-shrink-0">Rp {{ number_format($product['revenue'],0,',','.') }}</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill bg-orange-400" style="width:{{ round($product['revenue']/$maxR*100) }}%"></div>
                            </div>
                            <p class="mono text-[10px] text-slate-400 mt-0.5">{{ number_format($product['quantity'],0) }} unit</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="chart-card">
                <p class="section-title">Order Nilai Tertinggi</p>
                @if($topOrders->isEmpty())
                    <div class="empty-state"><span>🏆</span><p>Belum ada order</p></div>
                @else
                <div class="space-y-2">
                    @foreach($topOrders as $order)
                    @php
                    $badge=match($order->status){
                        Order::STATUS_COMPLETED =>['bg-emerald-100 text-emerald-700','✓ Selesai'],
                        Order::STATUS_CANCELLED =>['bg-red-100 text-red-700','✕ Batal'],
                        Order::STATUS_DELIVERING=>['bg-violet-100 text-violet-700','→ Kirim'],
                        Order::STATUS_PROCESSING=>['bg-blue-100 text-blue-700','⚙ Proses'],
                        default=>['bg-amber-100 text-amber-700','• Pending'],
                    };
                    @endphp
                    <div class="order-row">
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-slate-800 truncate">{{ $order->customer_name ?? 'Customer' }}</p>
                            <p class="mono text-[10px] text-slate-400 mt-0.5">{{ $order->reference ?? 'N/A' }} · {{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $badge[0] }}">{{ $badge[1] }}</span>
                            <span class="mono text-sm font-black text-slate-800">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Customer Summary ── --}}
        <div class="au d6 chart-card mb-8">
            <p class="section-title">Ringkasan Customer</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php
                $cCards=[
                    ['val'=>number_format($totalCustomers),'label'=>'Total Customer','bg'=>'bg-slate-50 border-slate-100','vc'=>'text-slate-800'],
                    ['val'=>number_format($newCustomers),'label'=>'Baru ('.$period.'hr)','bg'=>'bg-emerald-50 border-emerald-100','vc'=>'text-emerald-800'],
                    ['val'=>$repeatRate.'%','label'=>'Repeat Customer','bg'=>'bg-violet-50 border-violet-100','vc'=>'text-violet-800'],
                    ['val'=>'Rp '.($uniqueCustomers>0?number_format(round($grossRevenue/$uniqueCustomers),0,',','.'):'0'),'label'=>'Revenue/Customer','bg'=>'bg-blue-50 border-blue-100','vc'=>'text-blue-800'],
                ];
                @endphp
                @foreach($cCards as $c)
                <div class="rounded-2xl {{ $c['bg'] }} border p-4 text-center">
                    <p class="mono text-2xl font-black {{ $c['vc'] }}">{{ $c['val'] }}</p>
                    <p class="text-[11px] text-slate-500 mt-1.5 font-semibold">{{ $c['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </main>
</div>

@push('scripts')
<script>
(function(){
    @if($totalOrders > 0)
    const font={family:"'Plus Jakarta Sans', sans-serif",size:10,weight:'600'};
    const grid='rgba(0,0,0,0.04)';

    new Chart(document.getElementById('revenueChart'),{
        type:'line',
        data:{labels:@json($trendLabels),datasets:[
            {label:'Gross',data:@json($trendRevenue),borderColor:'#f97316',backgroundColor:'rgba(249,115,22,0.07)',fill:true,tension:0.4,pointRadius:2,borderWidth:2.5},
            {label:'Net',data:@json($trendNetRevenue),borderColor:'#10b981',backgroundColor:'rgba(16,185,129,0.04)',fill:true,tension:0.4,pointRadius:2,borderWidth:2,borderDash:[5,4]}
        ]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{mode:'index',intersect:false}},
        scales:{x:{ticks:{font,maxRotation:45,autoSkip:true,maxTicksLimit:8},grid:{color:grid}},y:{ticks:{font,callback:v=>'Rp '+Intl.NumberFormat('id').format(v)},grid:{color:grid}}}}
    });

    new Chart(document.getElementById('ordersChart'),{
        type:'bar',
        data:{labels:@json($trendLabels),datasets:[{data:@json($trendOrders),backgroundColor:'rgba(59,130,246,0.6)',borderRadius:5,borderSkipped:false}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
        scales:{x:{ticks:{font,maxRotation:45,autoSkip:true,maxTicksLimit:8},grid:{display:false}},y:{ticks:{font,stepSize:1},grid:{color:grid}}}}
    });

    new Chart(document.getElementById('statusChart'),{
        type:'doughnut',
        data:{labels:['Pending','Diproses','Dikirim','Selesai','Dibatalkan'],datasets:[{data:@json(array_values($statusCounts)),backgroundColor:['#fbbf24','#60a5fa','#a78bfa','#34d399','#f87171'],borderWidth:3,borderColor:'#fff'}]},
        options:{responsive:true,maintainAspectRatio:false,cutout:'70%',plugins:{legend:{display:false}}}
    });

    new Chart(document.getElementById('weeklyChart'),{
        type:'bar',
        data:{labels:@json($weeklyLabels),datasets:[{data:@json($weeklyOrders),backgroundColor:'rgba(139,92,246,0.65)',borderRadius:5,borderSkipped:false}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
        scales:{x:{ticks:{font},grid:{display:false}},y:{ticks:{font,stepSize:1},grid:{color:grid}}}}
    });

    new Chart(document.getElementById('hourlyChart'),{
        type:'bar',
        data:{labels:Array.from({length:24},(_,i)=>i.toString().padStart(2,'0')+':00'),datasets:[{data:@json($hourlyOrders),backgroundColor:'rgba(249,115,22,0.65)',borderRadius:3,borderSkipped:false}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
        scales:{x:{ticks:{font,maxRotation:45,autoSkip:true,maxTicksLimit:8},grid:{display:false}},y:{ticks:{font,stepSize:1},grid:{color:grid}}}}
    });
    @endif

    document.getElementById('sendSheetForm').addEventListener('submit',function(){
        const btn=document.getElementById('sendSheetBtn');
        btn.disabled=true;
        document.getElementById('sendSheetIcon').classList.add('hidden');
        document.getElementById('sendSheetSpinner').classList.remove('hidden');
        document.getElementById('sendSheetLabel').textContent='Mengirim...';
    });
})();
</script>
@endpush
@endsection