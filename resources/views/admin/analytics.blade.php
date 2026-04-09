@extends('layout.app')
@php use App\Models\Order; @endphp

@section('title', 'Analytics - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-[#F7F8FA] text-slate-900')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --bg:       #F7F8FA;
            --surface:  #FFFFFF;
            --border:   #EEF0F4;
            --border-2: #E4E7ED;
            --text-1:   #0F172A;
            --text-2:   #475569;
            --text-3:   #94A3B8;
            --text-4:   #CBD5E1;
            --accent:   #F97316;
            --accent-2: #FB923C;
            --accent-bg:#FFF7ED;
            --green:    #10B981;
            --amber:    #F59E0B;
            --blue:     #3B82F6;
            --violet:   #8B5CF6;
            --red:      #EF4444;
            --r-card:   18px;
            --r-inner:  12px;
            --shadow-sm: 0 2px 6px rgba(15,23,42,0.04), 0 1px 2px rgba(15,23,42,0.03);
            --shadow-md: 0 6px 18px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.03);
            --shadow-lg: 0 12px 40px rgba(15,23,42,0.08), 0 4px 8px rgba(15,23,42,0.04);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text-1);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* ── Period Selector ── */
        .period-wrap {
            display: flex; gap: 4px; padding: 4px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; box-shadow: var(--shadow-sm);
        }
        .period-pill {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 46px; padding: 6px 12px; border-radius: 10px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.02em;
            color: var(--text-2); background: transparent;
            text-decoration: none; transition: all 0.2s ease; white-space: nowrap;
        }
        .period-pill:hover { background: var(--bg); color: var(--text-1); }
        .period-pill.active {
            background: var(--accent); color: #fff;
            box-shadow: 0 4px 10px rgba(249,115,22,0.25);
        }

        /* ── Cards ── */
        .card {
            background: var(--surface); border-radius: var(--r-card);
            border: 1px solid var(--border); box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s ease;
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-inner { padding: 22px 24px; }

        .kpi-card {
            background: var(--surface); border-radius: var(--r-card);
            border: 1px solid var(--border); box-shadow: var(--shadow-sm);
            padding: 20px 22px; position: relative; overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        .kpi-accent-line {
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            border-radius: var(--r-card) var(--r-card) 0 0;
        }

        /* ── Revenue Cards ── */
        .rev-card {
            background: var(--surface); border-radius: var(--r-card);
            border: 1px solid var(--border); box-shadow: var(--shadow-sm);
            padding: 24px 26px; position: relative; overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .rev-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ── Section Title ── */
        .section-title {
            font-size: 11px; font-weight: 800; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--text-3); margin-bottom: 0;
        }

        /* ── Badge ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 4px 10px; border-radius: 8px;
            font-size: 10px; font-weight: 700; letter-spacing: 0.03em;
            line-height: 1;
        }

        /* ── Progress ── */
        .prog-track {
            height: 5px; background: var(--bg); border-radius: 999px;
            overflow: hidden; margin-top: 6px;
        }
        .prog-fill {
            height: 100%; border-radius: 999px;
            transition: width 0.7s cubic-bezier(0.34,1.56,0.64,1);
        }

        /* ── Funnel ── */
        .funnel-row { display: flex; align-items: center; gap: 12px; }
        .funnel-bar-wrap {
            flex: 1; height: 32px; background: var(--bg); border-radius: 10px;
            overflow: hidden; position: relative;
        }
        .funnel-fill {
            height: 100%; border-radius: 10px;
            transition: width 0.8s cubic-bezier(0.34,1.56,0.64,1);
            display: flex; align-items: center; padding-left: 12px; min-width: fit-content;
        }
        .funnel-text {
            font-size: 11px; font-weight: 700; color: #fff; white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .funnel-pct {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            font-size: 11px; font-weight: 600; color: var(--text-3); pointer-events: none;
        }

        /* ── Status dot ── */
        .s-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        /* ── Order row ── */
        .order-row {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            padding: 12px 14px; border-radius: var(--r-inner); background: var(--bg);
            transition: background 0.15s, transform 0.1s;
            border: 1px solid transparent;
        }
        .order-row:hover {
            background: #F8FAFC;
            border-color: var(--border);
            transform: scale(1.01);
        }

        /* ── Send button ── */
        .send-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(145deg, #059669, #10B981);
            color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700;
            font-size: 12px; padding: 10px 18px; border-radius: 12px;
            border: none; cursor: pointer; white-space: nowrap;
            box-shadow: 0 6px 14px rgba(5,150,105,0.25);
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
        }
        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(5,150,105,0.3);
        }
        .send-btn:active { transform: scale(0.97); }
        .send-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        /* ── Customer mini-card ── */
        .cust-card {
            border-radius: 14px; padding: 18px; text-align: center;
            border: 1px solid var(--border); background: #fff;
            box-shadow: var(--shadow-sm);
        }

        /* ── Empty state ── */
        .empty-state {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 44px 0; gap: 10px;
        }
        .empty-state .empty-icon { font-size: 36px; opacity: 0.4; }
        .empty-state p { font-size: 13px; font-weight: 600; color: var(--text-3); margin: 0; }

        /* ── Animate ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .au { animation: fadeUp 0.5s ease both; }
        .d1 { animation-delay: 0.05s; } .d2 { animation-delay: 0.10s; }
        .d3 { animation-delay: 0.15s; } .d4 { animation-delay: 0.20s; }
        .d5 { animation-delay: 0.25s; } .d6 { animation-delay: 0.30s; }

        /* ── Divider ── */
        .divider { height: 1px; background: var(--border); margin: 16px 0; }

        /* ── Chart tooltip style ── */
        .chartjs-tooltip { font-family: 'Outfit', sans-serif !important; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 5px; }

        /* ── Icon box ── */
        .icon-box {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
            transition: transform 0.1s ease;
        }
        .card:hover .icon-box { transform: scale(1.02); }

        /* ── Trending tag ── */
        .trend-tag {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 9px; border-radius: 8px;
            font-size: 10px; font-weight: 700; letter-spacing: 0.3px;
            background: var(--accent-bg); color: var(--accent);
            border: 1px solid rgba(249,115,22,0.15);
        }

        /* ── Additional Polishing ── */
        .bg-slate-50 { background-color: #F8FAFC; }
        .rounded-2xl { border-radius: 18px; }
        .shadow-soft { box-shadow: var(--shadow-sm); }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

        /* Responsive fine-tuning */
        @media (max-width: 640px) {
            .card-inner { padding: 18px 16px; }
            .rev-card { padding: 20px 18px; }
            .kpi-card { padding: 16px 18px; }
            .period-pill { min-width: 40px; padding: 5px 8px; font-size: 10px; }
        }
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
        365 => '1 Thn',
    ];
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8 max-w-screen-xl mx-auto w-full">

        {{-- ── Header ── --}}
        <div class="au mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <nav class="flex items-center gap-1.5 text-xs font-medium text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-slate-600 font-semibold">Analytics</span>
                </nav>
                <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight leading-none">
                    Analytics Penjualan
                </h1>
                <p class="mt-2 text-sm text-slate-400 font-medium flex items-center gap-2">
                    <span class="flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
                        Data real-time
                    </span>
                    <span class="text-slate-300">·</span>
                    <span>Periode <strong class="text-slate-600 font-bold">{{ $period }} hari</strong> terakhir</span>
                </p>
            </div>

            <div class="flex flex-col gap-2.5 items-start sm:items-end">
                <div class="period-wrap">
                    @foreach($periods as $days => $label)
                        <a href="{{ route('admin.analytics', ['period' => $days]) }}"
                           class="period-pill {{ $period == $days ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
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
        <div class="au d1 mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3">

            {{-- Gross --}}
            <div class="rev-card">
                <div class="kpi-accent-line" style="background: linear-gradient(90deg,#F97316,#FB923C)"></div>
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="icon-box" style="background:#FFF7ED">💰</div>
                        <div>
                            <p class="section-title" style="color:#F97316">Gross Revenue</p>
                            <span class="badge" style="background:#FFF7ED;color:#EA580C;margin-top:2px">Semua Order</span>
                        </div>
                    </div>
                    <span class="mono text-[10px] font-bold px-2 py-1 rounded-lg" style="background:var(--bg);color:var(--text-3)">ALL</span>
                </div>
                <p class="mono text-3xl font-black text-slate-900 truncate leading-none">
                    Rp {{ number_format($grossRevenue,0,',','.') }}
                </p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">{{ $totalOrders }} order masuk</span>
                    <span class="trend-tag" style="background:#FFF7ED;color:#EA580C">100%</span>
                </div>
                <div class="prog-track"><div class="prog-fill" style="width:100%;background:#F97316"></div></div>
            </div>

            {{-- Net --}}
            <div class="rev-card">
                <div class="kpi-accent-line" style="background: linear-gradient(90deg,#10B981,#34D399)"></div>
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="icon-box" style="background:#ECFDF5">✅</div>
                        <div>
                            <p class="section-title" style="color:#10B981">Net Revenue</p>
                            <span class="badge" style="background:#ECFDF5;color:#059669;margin-top:2px">Order Selesai</span>
                        </div>
                    </div>
                    <span class="mono text-[10px] font-bold px-2 py-1 rounded-lg" style="background:var(--bg);color:var(--text-3)">DONE</span>
                </div>
                @if($netRevenue > 0)
                    <p class="mono text-3xl font-black text-slate-900 truncate leading-none">
                        Rp {{ number_format($netRevenue,0,',','.') }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-slate-300 italic leading-none mt-1">Belum ada transaksi selesai</p>
                @endif
                @php $netPct = $grossRevenue > 0 ? round($netRevenue/$grossRevenue*100) : 0; @endphp
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">{{ $completedOrders }} order selesai</span>
                    <span class="trend-tag" style="background:#ECFDF5;color:#059669">{{ $netPct }}%</span>
                </div>
                <div class="prog-track"><div class="prog-fill" style="width:{{ $netPct }}%;background:#10B981"></div></div>
            </div>

            {{-- Pending --}}
            <div class="rev-card">
                <div class="kpi-accent-line" style="background: linear-gradient(90deg,#F59E0B,#FBBF24)"></div>
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="icon-box" style="background:#FFFBEB">⏳</div>
                        <div>
                            <p class="section-title" style="color:#F59E0B">Pending Revenue</p>
                            <span class="badge" style="background:#FFFBEB;color:#D97706;margin-top:2px">Belum Selesai</span>
                        </div>
                    </div>
                    <span class="mono text-[10px] font-bold px-2 py-1 rounded-lg" style="background:var(--bg);color:var(--text-3)">WAIT</span>
                </div>
                <p class="mono text-3xl font-black text-slate-900 truncate leading-none">
                    Rp {{ number_format($pendingRevenue,0,',','.') }}
                </p>
                @php $pendPct = $grossRevenue > 0 ? round($pendingRevenue/$grossRevenue*100) : 0; @endphp
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">{{ $totalOrders-$completedOrders-$cancelledOrders }} belum selesai</span>
                    <span class="trend-tag" style="background:#FFFBEB;color:#D97706">{{ $pendPct }}%</span>
                </div>
                <div class="prog-track"><div class="prog-fill" style="width:{{ $pendPct }}%;background:#F59E0B"></div></div>
            </div>
        </div>

        {{-- ── KPI Grid ── --}}
        <div class="au d2 mb-4 grid grid-cols-2 lg:grid-cols-4 gap-3">
            @php
            $kpis = [
                [
                    'label'  => 'Total Pesanan',
                    'val'    => $totalOrders,
                    'sub'    => $cancelledOrders . ' dibatalkan',
                    'accent' => '#3B82F6',
                    'bgAcc'  => '#EFF6FF',
                    'icon'   => '📦',
                    'mono'   => false,
                ],
                [
                    'label'  => 'Konversi',
                    'val'    => $conversionRate . '%',
                    'sub'    => $completedOrders . '/' . $totalOrders . ' selesai',
                    'accent' => '#8B5CF6',
                    'bgAcc'  => '#F5F3FF',
                    'icon'   => '🎯',
                    'mono'   => true,
                ],
                [
                    'label'  => 'Avg Order Value',
                    'val'    => 'Rp ' . number_format($avgOrderValue,0,',','.'),
                    'sub'    => 'Per transaksi gross',
                    'accent' => '#F97316',
                    'bgAcc'  => '#FFF7ED',
                    'icon'   => '📊',
                    'mono'   => true,
                ],
                [
                    'label'  => 'Repeat Customer',
                    'val'    => $repeatRate . '%',
                    'sub'    => $repeatCustomers . '/' . $uniqueCustomers . ' pelanggan',
                    'accent' => '#10B981',
                    'bgAcc'  => '#ECFDF5',
                    'icon'   => '🔁',
                    'mono'   => true,
                ],
            ];
            @endphp

            @foreach($kpis as $k)
            <div class="kpi-card">
                <div class="kpi-accent-line" style="background:{{ $k['accent'] }}"></div>
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="icon-box" style="background:{{ $k['bgAcc'] }}">{{ $k['icon'] }}</div>
                    <p class="section-title" style="color:{{ $k['accent'] }}">{{ $k['label'] }}</p>
                </div>
                <p class="{{ $k['mono'] ? 'mono' : '' }} text-2xl font-black text-slate-900 leading-none truncate">
                    {{ $k['val'] }}
                </p>
                <p class="text-xs text-slate-400 font-medium mt-2">{{ $k['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Conversion Funnel ── --}}
        <div class="au d2 card card-inner mb-4">
            <div class="flex items-center gap-2.5 mb-5">
                <div class="icon-box" style="background:#FFF7ED;font-size:14px">🎯</div>
                <p class="section-title" style="color:var(--accent)">Conversion Funnel</p>
            </div>

            @if($totalOrders === 0)
                <div class="empty-state"><div class="empty-icon">📭</div><p>Belum ada data transaksi</p></div>
            @else
            @php
            $funnelColors = [
                'orange' => '#F97316',
                'blue'   => '#3B82F6',
                'purple' => '#8B5CF6',
                'green'  => '#10B981',
                'red'    => '#EF4444',
            ];
            @endphp
            <div class="space-y-2.5">
                @foreach($funnel as $step)
                @php $pct = $totalOrders > 0 ? round($step['count']/$totalOrders*100) : 0; @endphp
                <div class="funnel-row">
                    <span class="w-[110px] flex-shrink-0 text-xs font-semibold text-slate-500 leading-none">
                        {{ $step['label'] }}
                    </span>
                    <div class="funnel-bar-wrap">
                        <div class="funnel-fill"
                             style="width:{{ max($pct, $step['count']>0?3:0) }}%;background:{{ $funnelColors[$step['color']] }}cc">
                            @if($step['count'] > 0)
                            <span class="funnel-text">{{ number_format($step['count']) }}</span>
                            @endif
                        </div>
                        <span class="funnel-pct">{{ $pct }}%</span>
                    </div>
                    <span class="mono text-xs font-bold text-slate-500 w-7 text-right flex-shrink-0">
                        {{ $pct }}%
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── Charts Row ── --}}
        <div class="au d3 mb-4 grid gap-4 lg:grid-cols-2">

            <div class="card card-inner">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="icon-box" style="background:#FFF7ED;font-size:14px">📈</div>
                        <p class="section-title" style="color:var(--accent)">Tren Revenue</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                            <span class="inline-block h-1.5 w-5 rounded-full" style="background:#F97316"></span>Gross
                        </span>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                            <span class="inline-block h-0.5 w-5 rounded-full border-t-2 border-dashed" style="border-color:#10B981"></span>Net
                        </span>
                    </div>
                </div>
                @if($totalOrders===0)
                    <div class="empty-state py-8"><div class="empty-icon">📈</div><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:190px"><canvas id="revenueChart"></canvas></div>
                @endif
            </div>

            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#EFF6FF;font-size:14px">📦</div>
                    <p class="section-title" style="color:#3B82F6">Volume Pesanan</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty-state py-8"><div class="empty-icon">📦</div><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:190px"><canvas id="ordersChart"></canvas></div>
                @endif
            </div>
        </div>

        {{-- ── Status + Payment ── --}}
        <div class="au d3 mb-4 grid gap-4 lg:grid-cols-2">

            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#F5F3FF;font-size:14px">📊</div>
                    <p class="section-title" style="color:#8B5CF6">Distribusi Status</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty-state"><div class="empty-icon">📊</div><p>Belum ada data</p></div>
                @else
                @php
                $si = [
                    'pending'    => ['Pending',     '#F59E0B', '#FFFBEB', '#D97706'],
                    'processing' => ['Diproses',    '#3B82F6', '#EFF6FF', '#1D4ED8'],
                    'delivering' => ['Dikirim',     '#8B5CF6', '#F5F3FF', '#6D28D9'],
                    'completed'  => ['Selesai',     '#10B981', '#ECFDF5', '#047857'],
                    'cancelled'  => ['Dibatalkan',  '#EF4444', '#FEF2F2', '#B91C1C'],
                ];
                $st = array_sum($statusCounts);
                @endphp
                <div class="flex gap-5 items-center">
                    <div style="position:relative;width:130px;height:130px;flex-shrink:0">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="flex-1 space-y-2.5">
                        @foreach($si as $key => [$lbl, $hex, $bg, $dark])
                        @php $cnt = $statusCounts[$key]; $p = $st>0 ? round($cnt/$st*100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="s-dot" style="background:{{ $hex }}"></span>
                                    <span class="text-xs font-semibold text-slate-600">{{ $lbl }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="mono text-xs font-bold text-slate-700">{{ $cnt }}</span>
                                    <span class="mono text-[10px] text-slate-400">({{ $p }}%)</span>
                                </div>
                            </div>
                            <div class="prog-track">
                                <div class="prog-fill" style="width:{{ $p }}%;background:{{ $hex }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#FFF7ED;font-size:14px">💳</div>
                    <p class="section-title" style="color:var(--accent)">Metode Pembayaran</p>
                </div>
                @if($paymentMethods->isEmpty())
                    <div class="empty-state"><div class="empty-icon">💳</div><p>Belum ada data</p></div>
                @else
                @php $pmt = $paymentMethods->sum('count'); @endphp
                <div class="space-y-4">
                    @foreach($paymentMethods as $pm)
                    @php $p = $pmt>0 ? round($pm['count']/$pmt*100) : 0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs font-bold text-slate-700 capitalize">{{ $pm['method'] }}</span>
                            <div class="flex items-center gap-2">
                                <span class="mono text-[10px] text-slate-400 font-medium">{{ $pm['count'] }} transaksi</span>
                                <span class="trend-tag" style="background:#FFF7ED;color:#EA580C">{{ $p }}%</span>
                            </div>
                        </div>
                        <div class="prog-track"><div class="prog-fill" style="width:{{ $p }}%;background:#F97316"></div></div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Weekly + Hourly ── --}}
        <div class="au d4 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#F5F3FF;font-size:14px">📅</div>
                    <p class="section-title" style="color:#8B5CF6">Pola Hari dalam Seminggu</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty-state py-8"><div class="empty-icon">📅</div><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:170px"><canvas id="weeklyChart"></canvas></div>
                @endif
            </div>
            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#FFF7ED;font-size:14px">🕐</div>
                    <p class="section-title" style="color:var(--accent)">Jam Sibuk Pesanan</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty-state py-8"><div class="empty-icon">🕐</div><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:170px"><canvas id="hourlyChart"></canvas></div>
                @endif
            </div>
        </div>

        {{-- ── Top Products + Orders ── --}}
        <div class="au d5 mb-4 grid gap-4 lg:grid-cols-2">

            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#FFF7ED;font-size:14px">🛒</div>
                    <p class="section-title" style="color:var(--accent)">Produk Terlaris</p>
                </div>
                @if($topProducts->isEmpty())
                    <div class="empty-state"><div class="empty-icon">🛒</div><p>Belum ada data produk</p></div>
                @else
                @php $maxR = $topProducts->max('revenue') ?: 1; @endphp
                <div class="space-y-4">
                    @foreach($topProducts as $i => $product)
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center mono text-[10px] font-black flex-shrink-0"
                              style="background:{{ $i===0?'#F97316':($i===1?'#94A3B8':($i===2?'#B45309':'#E2E8F0')) }};color:{{ $i<3?'#fff':'#64748B' }}">
                            {{ $i+1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-slate-700 truncate pr-2">{{ $product['name'] }}</span>
                                <span class="mono text-xs font-black text-slate-800 flex-shrink-0">
                                    Rp {{ number_format($product['revenue'],0,',','.') }}
                                </span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-fill" style="width:{{ round($product['revenue']/$maxR*100) }}%;background:#F97316"></div>
                            </div>
                            <p class="mono text-[10px] text-slate-400 mt-1">{{ number_format($product['quantity'],0) }} unit terjual</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="card card-inner">
                <div class="flex items-center gap-2 mb-4">
                    <div class="icon-box" style="background:#FFFBEB;font-size:14px">🏆</div>
                    <p class="section-title" style="color:#F59E0B">Order Nilai Tertinggi</p>
                </div>
                @if($topOrders->isEmpty())
                    <div class="empty-state"><div class="empty-icon">🏆</div><p>Belum ada order</p></div>
                @else
                <div class="space-y-2">
                    @foreach($topOrders as $order)
                    @php
                    $badge = match($order->status) {
                        Order::STATUS_COMPLETED  => ['#ECFDF5','#047857','✓ Selesai'],
                        Order::STATUS_CANCELLED  => ['#FEF2F2','#B91C1C','✕ Batal'],
                        Order::STATUS_DELIVERING => ['#F5F3FF','#6D28D9','→ Kirim'],
                        Order::STATUS_PROCESSING => ['#EFF6FF','#1D4ED8','⚙ Proses'],
                        default                  => ['#FFFBEB','#D97706','• Pending'],
                    };
                    @endphp
                    <div class="order-row">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $order->customer_name ?? 'Customer' }}</p>
                            <p class="mono text-[10px] text-slate-400 mt-0.5">
                                {{ $order->reference ?? 'N/A' }} · {{ $order->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="badge" style="background:{{ $badge[0] }};color:{{ $badge[1] }}">{{ $badge[2] }}</span>
                            <span class="mono text-sm font-black text-slate-900">
                                Rp {{ number_format($order->total_price,0,',','.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Customer Summary ── --}}
        <div class="au d6 card card-inner mb-8">
            <div class="flex items-center gap-2 mb-5">
                <div class="icon-box" style="background:#EFF6FF;font-size:14px">👥</div>
                <p class="section-title" style="color:#3B82F6">Ringkasan Customer</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php
                $cCards = [
                    ['val'=>number_format($totalCustomers),    'label'=>'Total Customer',      'bg'=>'#F8FAFC','border'=>'#E2E8F0','vc'=>'#0F172A'],
                    ['val'=>number_format($newCustomers),      'label'=>'Baru ('.$period.'hr)','bg'=>'#ECFDF5','border'=>'#A7F3D0','vc'=>'#047857'],
                    ['val'=>$repeatRate.'%',                   'label'=>'Repeat Customer',     'bg'=>'#F5F3FF','border'=>'#DDD6FE','vc'=>'#6D28D9'],
                    ['val'=>'Rp '.($uniqueCustomers>0?number_format(round($grossRevenue/$uniqueCustomers),0,',','.'):'0'),
                     'label'=>'Revenue / Customer',           'bg'=>'#EFF6FF','border'=>'#BFDBFE','vc'=>'#1D4ED8'],
                ];
                @endphp
                @foreach($cCards as $c)
                <div class="rounded-2xl p-4 text-center" style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }}">
                    <p class="mono text-2xl font-black leading-none" style="color:{{ $c['vc'] }}">{{ $c['val'] }}</p>
                    <p class="text-[11px] text-slate-500 mt-2 font-semibold leading-tight">{{ $c['label'] }}</p>
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

    const font = { family: "'Outfit', sans-serif", size: 11, weight: '600' };
    const grid = 'rgba(0,0,0,0.05)';
    const tooltipDefaults = {
        backgroundColor: '#0F172A',
        titleColor: '#94A3B8',
        bodyColor: '#F8FAFC',
        padding: 10,
        cornerRadius: 8,
        titleFont: { family: "'Outfit', sans-serif", size: 10, weight: '700' },
        bodyFont: { family: "'JetBrains Mono', monospace", size: 11, weight: '600' },
        displayColors: false,
    };

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($trendLabels),
            datasets: [
                {
                    label: 'Gross',
                    data: @json($trendRevenue),
                    borderColor: '#F97316',
                    backgroundColor: 'rgba(249,115,22,0.08)',
                    fill: true, tension: 0.4, pointRadius: 2.5,
                    pointBackgroundColor: '#F97316', borderWidth: 2.5,
                },
                {
                    label: 'Net',
                    data: @json($trendNetRevenue),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.04)',
                    fill: true, tension: 0.4, pointRadius: 2,
                    pointBackgroundColor: '#10B981', borderWidth: 2,
                    borderDash: [6, 4],
                },
            ],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults,
                callbacks: { label: ctx => ' ' + ctx.dataset.label + ': Rp ' + Intl.NumberFormat('id').format(ctx.raw) }
            }},
            scales: {
                x: { ticks: { font, maxRotation: 45, autoSkip: true, maxTicksLimit: 7, color: '#94A3B8' }, grid: { color: grid }, border: { display: false } },
                y: { ticks: { font, color: '#94A3B8', callback: v => 'Rp ' + Intl.NumberFormat('id').format(v) }, grid: { color: grid }, border: { display: false } },
            },
        },
    });

    // Orders Chart
    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: @json($trendLabels),
            datasets: [{
                data: @json($trendOrders),
                backgroundColor: 'rgba(59,130,246,0.55)',
                hoverBackgroundColor: 'rgba(59,130,246,0.8)',
                borderRadius: 5, borderSkipped: false,
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults,
                callbacks: { label: ctx => '  ' + ctx.raw + ' order' }
            }},
            scales: {
                x: { ticks: { font, maxRotation: 45, autoSkip: true, maxTicksLimit: 7, color: '#94A3B8' }, grid: { display: false }, border: { display: false } },
                y: { ticks: { font, stepSize: 1, color: '#94A3B8' }, grid: { color: grid }, border: { display: false } },
            },
        },
    });

    // Status Doughnut
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'],
            datasets: [{
                data: @json(array_values($statusCounts)),
                backgroundColor: ['#F59E0B','#3B82F6','#8B5CF6','#10B981','#EF4444'],
                borderWidth: 3, borderColor: '#FFFFFF',
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '68%',
            plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults } },
        },
    });

    // Weekly Chart
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                data: @json($weeklyOrders),
                backgroundColor: 'rgba(139,92,246,0.55)',
                hoverBackgroundColor: 'rgba(139,92,246,0.8)',
                borderRadius: 5, borderSkipped: false,
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults,
                callbacks: { label: ctx => '  ' + ctx.raw + ' order' }
            }},
            scales: {
                x: { ticks: { font, color: '#94A3B8' }, grid: { display: false }, border: { display: false } },
                y: { ticks: { font, stepSize: 1, color: '#94A3B8' }, grid: { color: grid }, border: { display: false } },
            },
        },
    });

    // Hourly Chart
    new Chart(document.getElementById('hourlyChart'), {
        type: 'bar',
        data: {
            labels: Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, '0') + ':00'),
            datasets: [{
                data: @json($hourlyOrders),
                backgroundColor: 'rgba(249,115,22,0.55)',
                hoverBackgroundColor: 'rgba(249,115,22,0.8)',
                borderRadius: 3, borderSkipped: false,
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults,
                callbacks: { label: ctx => '  ' + ctx.raw + ' order' }
            }},
            scales: {
                x: { ticks: { font, maxRotation: 45, autoSkip: true, maxTicksLimit: 8, color: '#94A3B8' }, grid: { display: false }, border: { display: false } },
                y: { ticks: { font, stepSize: 1, color: '#94A3B8' }, grid: { color: grid }, border: { display: false } },
            },
        },
    });

    @endif

    // Send sheet button
    document.getElementById('sendSheetForm').addEventListener('submit', function() {
        const btn = document.getElementById('sendSheetBtn');
        btn.disabled = true;
        document.getElementById('sendSheetIcon').classList.add('hidden');
        document.getElementById('sendSheetSpinner').classList.remove('hidden');
        document.getElementById('sendSheetLabel').textContent = 'Mengirim...';
    });
})();
</script>
@endpush
@endsection