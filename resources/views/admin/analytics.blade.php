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
            --bg:      #F7F8FA;
            --surface: #FFFFFF;
            --border:  #EAECF0;
            --text-1:  #0F172A;
            --text-2:  #475569;
            --text-3:  #94A3B8;
            --accent:  #F97316;
            --r:       14px;
            --sh:      0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
            --shd:     0 4px 16px rgba(15,23,42,.09);
        }

        body { font-family:'Outfit',sans-serif; background:var(--bg); color:var(--text-1); }
        .mono { font-family:'JetBrains Mono',monospace; }

        /* Period */
        .period-wrap {
            display:flex; gap:3px; padding:3px;
            background:var(--surface); border:1px solid var(--border);
            border-radius:10px; box-shadow:var(--sh);
        }
        .period-pill {
            display:inline-flex; align-items:center; justify-content:center;
            min-width:38px; padding:5px 9px; border-radius:7px;
            font-size:11px; font-weight:700; color:var(--text-2);
            background:transparent; text-decoration:none; transition:all .15s; white-space:nowrap;
        }
        .period-pill:hover { background:var(--bg); color:var(--text-1); }
        .period-pill.active { background:var(--accent); color:#fff; box-shadow:0 2px 8px rgba(249,115,22,.3); }

        /* Cards */
        .card { background:var(--surface); border-radius:var(--r); border:1px solid var(--border); box-shadow:var(--sh); }
        .card-p { padding:20px 22px; }

        .kpi-card {
            background:var(--surface); border-radius:var(--r); border:1px solid var(--border);
            box-shadow:var(--sh); padding:16px 18px; position:relative; overflow:hidden;
            transition:transform .18s,box-shadow .18s;
        }
        .kpi-card:hover { transform:translateY(-2px); box-shadow:var(--shd); }

        .rev-card {
            background:var(--surface); border-radius:var(--r); border:1px solid var(--border);
            box-shadow:var(--sh); padding:20px 22px; position:relative; overflow:hidden;
            transition:transform .18s,box-shadow .18s;
        }
        .rev-card:hover { transform:translateY(-2px); box-shadow:var(--shd); }

        .aline { position:absolute; top:0; left:0; right:0; height:2px; border-radius:var(--r) var(--r) 0 0; }

        /* Text helpers */
        .slabel { font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        .badge  { display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; font-size:10px; font-weight:700; }
        .tag    { display:inline-flex; align-items:center; padding:2px 7px; border-radius:5px; font-size:10px; font-weight:700; }

        /* Progress */
        .prog { height:3px; background:var(--bg); border-radius:99px; overflow:hidden; margin-top:7px; }
        .prog-fill { height:100%; border-radius:99px; transition:width .7s cubic-bezier(.34,1.56,.64,1); }

        /* Funnel */
        .funnel-row { display:flex; align-items:center; gap:10px; }
        .funnel-track { flex:1; height:26px; background:#F1F5F9; border-radius:7px; overflow:hidden; position:relative; }
        .funnel-bar {
            height:100%; border-radius:7px; display:flex; align-items:center; padding-left:9px;
            transition:width .8s cubic-bezier(.34,1.56,.64,1);
        }
        .funnel-bar span { font-size:11px; font-weight:700; color:#fff; white-space:nowrap; }

        /* Status */
        .sdot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }

        /* Order row */
        .order-row {
            display:flex; align-items:center; justify-content:space-between; gap:10px;
            padding:9px 11px; border-radius:9px; background:var(--bg); transition:background .15s;
        }
        .order-row:hover { background:#EFF2F7; }

        /* Icon */
        .ibox { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }

        /* Send */
        .send-btn {
            display:inline-flex; align-items:center; gap:6px;
            background:linear-gradient(135deg,#059669,#10B981); color:#fff;
            font-family:'Outfit',sans-serif; font-weight:700; font-size:12px;
            padding:8px 15px; border-radius:9px; border:none; cursor:pointer;
            white-space:nowrap; box-shadow:0 3px 10px rgba(5,150,105,.25);
            transition:all .18s;
        }
        .send-btn:hover { transform:translateY(-1px); box-shadow:0 5px 16px rgba(5,150,105,.32); }
        .send-btn:active { transform:scale(.98); }
        .send-btn:disabled { opacity:.5; cursor:not-allowed; transform:none; }

        /* Empty */
        .empty { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:32px 0; gap:6px; }
        .empty span { font-size:28px; opacity:.3; }
        .empty p { font-size:12px; font-weight:600; color:var(--text-3); margin:0; }

        /* Animate */
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
        .au { animation:fadeUp .38s ease both; }
        .d1{animation-delay:.05s}.d2{animation-delay:.09s}.d3{animation-delay:.13s}
        .d4{animation-delay:.17s}.d5{animation-delay:.21s}.d6{animation-delay:.25s}

        ::-webkit-scrollbar{width:4px;height:4px}
        ::-webkit-scrollbar-thumb{background:#DDE1E7;border-radius:4px}
    </style>
@endpush

@section('content')
@php
    $adminSidebarTitle       = 'Analytics';
    $adminSidebarMetricLabel = 'Total Pesanan';
    $adminSidebarMetricValue = $totalOrders;
    $adminSidebarBody        = 'Analisis performa penjualan dan tren bisnis UP Cireng.';

    $periods = [7=>'7H',14=>'14H',30=>'30H',60=>'60H',90=>'90H',180=>'6 Bln',365=>'1 Thn'];
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="min-w-0 px-4 py-6 sm:px-6 lg:px-8">

        {{-- ── HEADER ── --}}
        <div class="au mb-6">
            <nav class="flex items-center gap-1.5 text-xs font-medium text-slate-400 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-700 font-semibold">Analytics</span>
            </nav>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-[26px] font-black text-slate-900 tracking-tight leading-none">
                        Analytics Penjualan
                    </h1>
                    <p class="mt-1.5 text-sm text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
                        Data real-time · Periode
                        <strong class="text-slate-700 font-bold">{{ $period }} hari</strong> terakhir
                    </p>
                </div>

                {{-- Controls — same row --}}
                <div class="flex flex-wrap items-center gap-2">
                    <div class="period-wrap">
                        @foreach($periods as $days => $label)
                            <a href="{{ route('admin.analytics', ['period' => $days]) }}"
                               class="period-pill {{ $period == $days ? 'active' : '' }}">{{ $label }}</a>
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
                            <span id="sendSheetLabel">Kirim ke Sheet</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── REVENUE 3-COL ── --}}
        <div class="au d1 mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3">

            <div class="rev-card">
                <div class="aline" style="background:linear-gradient(90deg,#F97316,#FB923C)"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="ibox" style="background:#FFF7ED">💰</div>
                        <div>
                            <p class="slabel" style="color:#F97316">Gross Revenue</p>
                            <span class="badge" style="background:#FFF7ED;color:#C2410C">Semua Order</span>
                        </div>
                    </div>
                    <span class="mono text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:#F7F8FA;color:#94A3B8">ALL</span>
                </div>
                <p class="mono text-[26px] font-black text-slate-900 leading-none truncate">
                    Rp {{ number_format($grossRevenue,0,',','.') }}
                </p>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-slate-400">{{ $totalOrders }} order masuk</span>
                    <span class="tag" style="background:#FFF7ED;color:#C2410C">100%</span>
                </div>
                <div class="prog"><div class="prog-fill" style="width:100%;background:#F97316"></div></div>
            </div>

            <div class="rev-card">
                <div class="aline" style="background:linear-gradient(90deg,#10B981,#34D399)"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="ibox" style="background:#ECFDF5">✅</div>
                        <div>
                            <p class="slabel" style="color:#10B981">Net Revenue</p>
                            <span class="badge" style="background:#ECFDF5;color:#047857">Order Selesai</span>
                        </div>
                    </div>
                    <span class="mono text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:#F7F8FA;color:#94A3B8">DONE</span>
                </div>
                @if($netRevenue > 0)
                    <p class="mono text-[26px] font-black text-slate-900 leading-none truncate">
                        Rp {{ number_format($netRevenue,0,',','.') }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-slate-300 italic mt-1">Belum ada transaksi selesai</p>
                @endif
                @php $netPct = $grossRevenue > 0 ? round($netRevenue/$grossRevenue*100) : 0; @endphp
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-slate-400">{{ $completedOrders }} order selesai</span>
                    <span class="tag" style="background:#ECFDF5;color:#047857">{{ $netPct }}%</span>
                </div>
                <div class="prog"><div class="prog-fill" style="width:{{ $netPct }}%;background:#10B981"></div></div>
            </div>

            <div class="rev-card">
                <div class="aline" style="background:linear-gradient(90deg,#F59E0B,#FBBF24)"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="ibox" style="background:#FFFBEB">⏳</div>
                        <div>
                            <p class="slabel" style="color:#D97706">Pending Revenue</p>
                            <span class="badge" style="background:#FFFBEB;color:#92400E">Belum Selesai</span>
                        </div>
                    </div>
                    <span class="mono text-[9px] font-bold px-1.5 py-0.5 rounded" style="background:#F7F8FA;color:#94A3B8">WAIT</span>
                </div>
                <p class="mono text-[26px] font-black text-slate-900 leading-none truncate">
                    Rp {{ number_format($pendingRevenue,0,',','.') }}
                </p>
                @php $pendPct = $grossRevenue > 0 ? round($pendingRevenue/$grossRevenue*100) : 0; @endphp
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-slate-400">{{ $totalOrders-$completedOrders-$cancelledOrders }} belum selesai</span>
                    <span class="tag" style="background:#FFFBEB;color:#92400E">{{ $pendPct }}%</span>
                </div>
                <div class="prog"><div class="prog-fill" style="width:{{ $pendPct }}%;background:#F59E0B"></div></div>
            </div>

        </div>

        {{-- ── KPI 4-COL ── --}}
        <div class="au d2 mb-4 grid grid-cols-2 lg:grid-cols-4 gap-3">
            @php
            $kpis=[
                ['label'=>'Total Pesanan',  'val'=>$totalOrders,                                  'sub'=>$cancelledOrders.' dibatalkan',               'ac'=>'#3B82F6','bg'=>'#EFF6FF','icon'=>'📦','mono'=>false],
                ['label'=>'Konversi',       'val'=>$conversionRate.'%',                           'sub'=>$completedOrders.'/'.$totalOrders.' selesai', 'ac'=>'#8B5CF6','bg'=>'#F5F3FF','icon'=>'🎯','mono'=>true],
                ['label'=>'Avg Order Value','val'=>'Rp '.number_format($avgOrderValue,0,',','.'), 'sub'=>'Per transaksi gross',                        'ac'=>'#F97316','bg'=>'#FFF7ED','icon'=>'📊','mono'=>true],
                ['label'=>'Repeat Customer','val'=>$repeatRate.'%',                               'sub'=>$repeatCustomers.'/'.$uniqueCustomers.' pelanggan','ac'=>'#10B981','bg'=>'#ECFDF5','icon'=>'🔁','mono'=>true],
            ];
            @endphp
            @foreach($kpis as $k)
            <div class="kpi-card">
                <div class="aline" style="background:{{ $k['ac'] }}"></div>
                <div class="flex items-center gap-2 mb-2.5">
                    <div class="ibox" style="background:{{ $k['bg'] }}">{{ $k['icon'] }}</div>
                    <p class="slabel" style="color:{{ $k['ac'] }}">{{ $k['label'] }}</p>
                </div>
                <p class="{{ $k['mono']?'mono':'' }} text-[22px] font-black text-slate-900 leading-none truncate">{{ $k['val'] }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-1.5">{{ $k['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── FUNNEL ── --}}
        <div class="au d2 card card-p mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="ibox" style="background:#FFF7ED">🎯</div>
                <p class="slabel" style="color:#F97316">Conversion Funnel</p>
            </div>
            @if($totalOrders===0)
                <div class="empty"><span>📭</span><p>Belum ada data transaksi</p></div>
            @else
            @php $fC=['orange'=>'#F97316','blue'=>'#3B82F6','purple'=>'#8B5CF6','green'=>'#10B981','red'=>'#EF4444']; @endphp
            <div class="space-y-2">
                @foreach($funnel as $step)
                @php $pct=$totalOrders>0?round($step['count']/$totalOrders*100):0; @endphp
                <div class="funnel-row">
                    <span class="w-28 flex-shrink-0 text-xs font-semibold text-slate-500 leading-snug">{{ $step['label'] }}</span>
                    <div class="funnel-track">
                        @if($step['count']>0)
                        <div class="funnel-bar" style="width:{{ max($pct,3) }}%;background:{{ $fC[$step['color']] }}cc">
                            <span>{{ number_format($step['count']) }}</span>
                        </div>
                        @endif
                    </div>
                    {{-- Satu angka % saja di kanan, tidak dobel --}}
                    <span class="mono text-xs font-bold text-slate-500 w-9 text-right flex-shrink-0">{{ $pct }}%</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── CHARTS ROW 1 ── --}}
        <div class="au d3 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="card card-p">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="ibox" style="background:#FFF7ED">📈</div>
                        <p class="slabel" style="color:#F97316">Tren Revenue</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                            <span class="inline-block h-1.5 w-4 rounded-full" style="background:#F97316"></span>Gross
                        </span>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                            <span class="inline-block h-1.5 w-4 rounded-full" style="background:#10B981;opacity:.7"></span>Net
                        </span>
                    </div>
                </div>
                @if($totalOrders===0)
                    <div class="empty py-8"><span>📈</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:185px"><canvas id="revenueChart"></canvas></div>
                @endif
            </div>
            <div class="card card-p">
                <div class="flex items-center gap-2 mb-3">
                    <div class="ibox" style="background:#EFF6FF">📦</div>
                    <p class="slabel" style="color:#3B82F6">Volume Pesanan</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty py-8"><span>📦</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:185px"><canvas id="ordersChart"></canvas></div>
                @endif
            </div>
        </div>

        {{-- ── STATUS + PAYMENT ── --}}
        <div class="au d3 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="card card-p">
                <div class="flex items-center gap-2 mb-4">
                    <div class="ibox" style="background:#F5F3FF">📊</div>
                    <p class="slabel" style="color:#8B5CF6">Distribusi Status</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty"><span>📊</span><p>Belum ada data</p></div>
                @else
                @php
                $si=['pending'=>['Pending','#F59E0B'],'processing'=>['Diproses','#3B82F6'],'delivering'=>['Dikirim','#8B5CF6'],'completed'=>['Selesai','#10B981'],'cancelled'=>['Dibatalkan','#EF4444']];
                $st=array_sum($statusCounts);
                @endphp
                <div class="flex gap-4 items-center">
                    <div style="width:120px;height:120px;flex-shrink:0">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="flex-1 space-y-2">
                        @foreach($si as $key=>[$lbl,$hex])
                        @php $cnt=$statusCounts[$key];$p=$st>0?round($cnt/$st*100):0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-0.5">
                                <div class="flex items-center gap-1.5">
                                    <span class="sdot" style="background:{{ $hex }}"></span>
                                    <span class="text-xs font-semibold text-slate-600">{{ $lbl }}</span>
                                </div>
                                <span class="mono text-xs font-bold text-slate-600">
                                    {{ $cnt }} <span class="text-slate-400 font-normal text-[10px]">({{ $p }}%)</span>
                                </span>
                            </div>
                            <div class="prog"><div class="prog-fill" style="width:{{ $p }}%;background:{{ $hex }}"></div></div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="card card-p">
                <div class="flex items-center gap-2 mb-4">
                    <div class="ibox" style="background:#FFF7ED">💳</div>
                    <p class="slabel" style="color:#F97316">Metode Pembayaran</p>
                </div>
                @if($paymentMethods->isEmpty())
                    <div class="empty"><span>💳</span><p>Belum ada data</p></div>
                @else
                @php $pmt=$paymentMethods->sum('count'); @endphp
                <div class="space-y-3.5">
                    @foreach($paymentMethods as $pm)
                    @php $p=$pmt>0?round($pm['count']/$pmt*100):0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-slate-700 capitalize">{{ $pm['method'] }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="mono text-[10px] text-slate-400">{{ $pm['count'] }}x</span>
                                <span class="tag" style="background:#FFF7ED;color:#C2410C">{{ $p }}%</span>
                            </div>
                        </div>
                        <div class="prog"><div class="prog-fill" style="width:{{ $p }}%;background:#F97316"></div></div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── WEEKLY + HOURLY ── --}}
        <div class="au d4 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="card card-p">
                <div class="flex items-center gap-2 mb-3">
                    <div class="ibox" style="background:#F5F3FF">📅</div>
                    <p class="slabel" style="color:#8B5CF6">Pola Hari Seminggu</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty py-8"><span>📅</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:165px"><canvas id="weeklyChart"></canvas></div>
                @endif
            </div>
            <div class="card card-p">
                <div class="flex items-center gap-2 mb-3">
                    <div class="ibox" style="background:#FFF7ED">🕐</div>
                    <p class="slabel" style="color:#F97316">Jam Sibuk Pesanan</p>
                </div>
                @if($totalOrders===0)
                    <div class="empty py-8"><span>🕐</span><p>Belum ada data</p></div>
                @else
                <div style="position:relative;height:165px"><canvas id="hourlyChart"></canvas></div>
                @endif
            </div>
        </div>

        {{-- ── TOP PRODUCTS + TOP ORDERS ── --}}
        <div class="au d5 mb-4 grid gap-4 lg:grid-cols-2">
            <div class="card card-p">
                <div class="flex items-center gap-2 mb-4">
                    <div class="ibox" style="background:#FFF7ED">🛒</div>
                    <p class="slabel" style="color:#F97316">Produk Terlaris</p>
                </div>
                @if($topProducts->isEmpty())
                    <div class="empty"><span>🛒</span><p>Belum ada data</p></div>
                @else
                @php $maxR=$topProducts->max('revenue')?:1; @endphp
                <div class="space-y-3.5">
                    @foreach($topProducts as $i=>$product)
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center mono text-[9px] font-black flex-shrink-0"
                              style="background:{{ $i===0?'#F97316':($i===1?'#94A3B8':($i===2?'#B45309':'#E2E8F0')) }};color:{{ $i<3?'#fff':'#64748B' }}">
                            {{ $i+1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-0.5">
                                <span class="text-xs font-bold text-slate-700 truncate pr-2">{{ $product['name'] }}</span>
                                <span class="mono text-xs font-black text-slate-800 flex-shrink-0">Rp {{ number_format($product['revenue'],0,',','.') }}</span>
                            </div>
                            <div class="prog"><div class="prog-fill" style="width:{{ round($product['revenue']/$maxR*100) }}%;background:#F97316"></div></div>
                            <p class="mono text-[10px] text-slate-400 mt-0.5">{{ number_format($product['quantity'],0) }} unit terjual</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="card card-p">
                <div class="flex items-center gap-2 mb-4">
                    <div class="ibox" style="background:#FFFBEB">🏆</div>
                    <p class="slabel" style="color:#D97706">Order Nilai Tertinggi</p>
                </div>
                @if($topOrders->isEmpty())
                    <div class="empty"><span>🏆</span><p>Belum ada order</p></div>
                @else
                <div class="space-y-1.5">
                    @foreach($topOrders as $order)
                    @php
                    $badge=match($order->status){
                        Order::STATUS_COMPLETED  =>['#ECFDF5','#047857','✓ Selesai'],
                        Order::STATUS_CANCELLED  =>['#FEF2F2','#B91C1C','✕ Batal'],
                        Order::STATUS_DELIVERING =>['#F5F3FF','#6D28D9','→ Kirim'],
                        Order::STATUS_PROCESSING =>['#EFF6FF','#1D4ED8','⚙ Proses'],
                        default                  =>['#FFFBEB','#D97706','• Pending'],
                    };
                    @endphp
                    <div class="order-row">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $order->customer_name ?? 'Customer' }}</p>
                            <p class="mono text-[10px] text-slate-400 mt-0.5">{{ $order->reference ?? 'N/A' }} · {{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="badge" style="background:{{ $badge[0] }};color:{{ $badge[1] }}">{{ $badge[2] }}</span>
                            <span class="mono text-sm font-black text-slate-900">Rp {{ number_format($order->total_price,0,',','.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── CUSTOMER SUMMARY ── --}}
        <div class="au d6 card card-p mb-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="ibox" style="background:#EFF6FF">👥</div>
                <p class="slabel" style="color:#3B82F6">Ringkasan Customer</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php
                $cCards=[
                    ['val'=>number_format($totalCustomers),    'label'=>'Total Customer',        'bg'=>'#F8FAFC','bo'=>'#E2E8F0','vc'=>'#0F172A'],
                    ['val'=>number_format($newCustomers),      'label'=>'Baru ('.$period.' hr)', 'bg'=>'#ECFDF5','bo'=>'#A7F3D0','vc'=>'#047857'],
                    ['val'=>$repeatRate.'%',                   'label'=>'Repeat Customer',       'bg'=>'#F5F3FF','bo'=>'#DDD6FE','vc'=>'#6D28D9'],
                    ['val'=>'Rp '.($uniqueCustomers>0?number_format(round($grossRevenue/$uniqueCustomers),0,',','.'):'0'),
                     'label'=>'Revenue / Customer',           'bg'=>'#EFF6FF','bo'=>'#BFDBFE','vc'=>'#1D4ED8'],
                ];
                @endphp
                @foreach($cCards as $c)
                <div class="rounded-xl p-4 text-center" style="background:{{ $c['bg'] }};border:1px solid {{ $c['bo'] }}">
                    <p class="mono text-xl font-black leading-none" style="color:{{ $c['vc'] }}">{{ $c['val'] }}</p>
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
    const font={family:"'Outfit',sans-serif",size:11,weight:'600'};
    const grid='rgba(0,0,0,0.04)';
    const tip={
        backgroundColor:'#0F172A',titleColor:'#94A3B8',bodyColor:'#F8FAFC',
        padding:10,cornerRadius:8,displayColors:false,
        titleFont:{family:"'Outfit',sans-serif",size:10,weight:'700'},
        bodyFont:{family:"'JetBrains Mono',monospace",size:11,weight:'600'},
    };

    new Chart(document.getElementById('revenueChart'),{
        type:'line',
        data:{labels:@json($trendLabels),datasets:[
            {label:'Gross',data:@json($trendRevenue),borderColor:'#F97316',backgroundColor:'rgba(249,115,22,0.07)',fill:true,tension:0.4,pointRadius:2.5,borderWidth:2.5},
            {label:'Net',data:@json($trendNetRevenue),borderColor:'#10B981',backgroundColor:'rgba(16,185,129,0.04)',fill:true,tension:0.4,pointRadius:2,borderWidth:2,borderDash:[5,4]},
        ]},
        options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},
            plugins:{legend:{display:false},tooltip:{...tip,callbacks:{label:c=>' '+c.dataset.label+': Rp '+Intl.NumberFormat('id').format(c.raw)}}},
            scales:{
                x:{ticks:{font,color:'#94A3B8',maxRotation:45,autoSkip:true,maxTicksLimit:7},grid:{color:grid},border:{display:false}},
                y:{ticks:{font,color:'#94A3B8',callback:v=>'Rp '+Intl.NumberFormat('id').format(v)},grid:{color:grid},border:{display:false}},
            }
        }
    });

    new Chart(document.getElementById('ordersChart'),{
        type:'bar',
        data:{labels:@json($trendLabels),datasets:[{data:@json($trendOrders),backgroundColor:'rgba(59,130,246,0.55)',hoverBackgroundColor:'rgba(59,130,246,0.8)',borderRadius:5,borderSkipped:false}]},
        options:{responsive:true,maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{...tip,callbacks:{label:c=>'  '+c.raw+' order'}}},
            scales:{
                x:{ticks:{font,color:'#94A3B8',maxRotation:45,autoSkip:true,maxTicksLimit:7},grid:{display:false},border:{display:false}},
                y:{ticks:{font,color:'#94A3B8',stepSize:1},grid:{color:grid},border:{display:false}},
            }
        }
    });

    new Chart(document.getElementById('statusChart'),{
        type:'doughnut',
        data:{labels:['Pending','Diproses','Dikirim','Selesai','Dibatalkan'],
            datasets:[{data:@json(array_values($statusCounts)),backgroundColor:['#F59E0B','#3B82F6','#8B5CF6','#10B981','#EF4444'],borderWidth:3,borderColor:'#fff'}]},
        options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{display:false},tooltip:{...tip}}}
    });

    new Chart(document.getElementById('weeklyChart'),{
        type:'bar',
        data:{labels:@json($weeklyLabels),datasets:[{data:@json($weeklyOrders),backgroundColor:'rgba(139,92,246,0.55)',hoverBackgroundColor:'rgba(139,92,246,0.8)',borderRadius:5,borderSkipped:false}]},
        options:{responsive:true,maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{...tip,callbacks:{label:c=>'  '+c.raw+' order'}}},
            scales:{x:{ticks:{font,color:'#94A3B8'},grid:{display:false},border:{display:false}},y:{ticks:{font,color:'#94A3B8',stepSize:1},grid:{color:grid},border:{display:false}}}
        }
    });

    new Chart(document.getElementById('hourlyChart'),{
        type:'bar',
        data:{labels:Array.from({length:24},(_,i)=>i.toString().padStart(2,'0')+':00'),
            datasets:[{data:@json($hourlyOrders),backgroundColor:'rgba(249,115,22,0.55)',hoverBackgroundColor:'rgba(249,115,22,0.8)',borderRadius:3,borderSkipped:false}]},
        options:{responsive:true,maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{...tip,callbacks:{label:c=>'  '+c.raw+' order'}}},
            scales:{x:{ticks:{font,color:'#94A3B8',maxRotation:45,autoSkip:true,maxTicksLimit:8},grid:{display:false},border:{display:false}},y:{ticks:{font,color:'#94A3B8',stepSize:1},grid:{color:grid},border:{display:false}}}
        }
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