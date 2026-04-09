@extends('layout.app')

@section('title', 'Detail Customer - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-[#F7F8FA] text-slate-900')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }
        body { background: #F7F8FA; }

        :root {
            --bg:       #F7F8FA;
            --surface:  #FFFFFF;
            --border:   #EEF0F4;
            --border-2: #E4E7ED;
            --text-1:   #0F172A;
            --text-2:   #475569;
            --text-3:   #94A3B8;
            --accent:   #0EA5E9;
            --accent-bg:#F0F9FF;
            --green:    #10B981;
            --amber:    #F59E0B;
            --red:      #EF4444;
            --r-card:   20px;
            --shadow-sm: 0 2px 6px rgba(15,23,42,0.04), 0 1px 2px rgba(15,23,42,0.03);
            --shadow-md: 0 6px 18px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.03);
        }

        /* Card */
        .card-profile {
            background: linear-gradient(145deg, #0EA5E9, #0284C7);
            border-radius: var(--r-card);
            box-shadow: var(--shadow-md);
            color: white;
        }
        .card-form {
            background: var(--surface);
            border-radius: var(--r-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 1.8rem 2rem;
        }
        .card-orders {
            background: var(--surface);
            border-radius: var(--r-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }
        .card-orders:hover {
            box-shadow: var(--shadow-md);
        }

        /* Form Elements */
        .input-modern {
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.85rem 1.2rem;
            font-weight: 500;
            color: var(--text-1);
            transition: all 0.2s;
            width: 100%;
            outline: none;
        }
        .input-modern:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(14,165,233,0.12);
            background: white;
        }
        .label-modern {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--text-2);
            margin-bottom: 0.4rem;
            display: block;
        }

        /* Buttons */
        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 14px;
            padding: 0.85rem 1.5rem;
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
            box-shadow: 0 4px 8px rgba(14,165,233,0.2);
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary:hover {
            background: #0284C7;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(14,165,233,0.25);
        }
        .btn-secondary {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.85rem 1.5rem;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-2);
            transition: all 0.2s;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #F8FAFC;
            border-color: var(--border-2);
        }
        .btn-danger {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 14px;
            padding: 0.85rem 1.5rem;
            font-weight: 700;
            font-size: 0.9rem;
            color: #B91C1C;
            transition: all 0.2s;
            cursor: pointer;
            width: 100%;
        }
        .btn-danger:hover {
            background: #FEE2E2;
            border-color: #FCA5A5;
        }

        /* Order Item */
        .order-item {
            padding: 1.5rem 1.8rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .order-item:hover {
            background: #F8FAFC;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            background: #F1F5F9;
            color: #475569;
        }

        /* Breadcrumb */
        .breadcrumb-link {
            color: var(--text-3);
            font-weight: 600;
            font-size: 0.8rem;
            transition: color 0.15s;
            text-decoration: none;
        }
        .breadcrumb-link:hover {
            color: var(--accent);
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-icon {
            width: 80px;
            height: 80px;
            background: #F1F5F9;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--text-3);
        }
    </style>
@endpush

@section('content')
@php
    $adminSidebarTitle = 'Detail Customer';
    $adminSidebarMetricLabel = 'Total Pesanan';
    $adminSidebarMetricValue = $orders->count();
    $adminSidebarBody = 'Rincian akun pelanggan.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8 xl:px-10">

        {{-- Breadcrumb --}}
        <nav class="mb-8 flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.customers') }}" class="breadcrumb-link">Customer</a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-sm font-bold text-slate-800 truncate max-w-[160px]">{{ $customer->name }}</span>
        </nav>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_1.5fr]">

            {{-- ===================== PROFIL & EDIT FORM ===================== --}}
            <div class="space-y-6">

                {{-- Profil Card --}}
                <div class="card-profile p-6 sm:p-8">
                    <div class="flex items-center gap-5 mb-6">
                        <div class="h-20 w-20 rounded-2xl bg-white/20 flex items-center justify-center text-white font-black text-3xl flex-shrink-0 backdrop-blur-sm shadow-inner">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-sky-200">Customer #{{ $customer->id }}</p>
                            <h1 class="text-2xl sm:text-3xl font-black truncate">{{ $customer->name }}</h1>
                            <p class="text-sm text-sky-200 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Bergabung {{ $customer->created_at->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white/15 p-4 backdrop-blur-sm">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200">Total Pesanan</p>
                            <p class="text-3xl font-black mt-1">{{ $orders->count() }}</p>
                        </div>
                        <div class="rounded-xl bg-white/15 p-4 backdrop-blur-sm">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200">Total Belanja</p>
                            <p class="text-xl font-black mt-1 truncate">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Edit Form --}}
                <div class="card-form">
                    <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-sky-500 rounded-full"></span>
                        Edit Data Customer
                    </h2>

                    @if($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <p class="flex items-center gap-2">• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('admin.customer.update', $customer) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="label-modern">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                                   class="input-modern"
                                   required>
                        </div>

                        <div>
                            <label class="label-modern">Nomor WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                   class="input-modern"
                                   required>
                        </div>

                        <div>
                            <label class="label-modern">
                                Email
                                <span class="text-slate-400 font-medium text-[0.7rem] ml-1">(opsional)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                   class="input-modern"
                                   placeholder="Kosongkan jika tidak ada">
                        </div>

                        <div class="flex gap-3 pt-3">
                            <button type="submit" class="btn-primary flex-1">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.customers') }}" class="btn-secondary flex-1 text-center">
                                Batal
                            </a>
                        </div>
                    </form>

                    {{-- Hapus customer --}}
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <form action="{{ route('admin.customer.delete', $customer) }}" method="POST"
                              onsubmit="return confirm('Hapus customer {{ addslashes($customer->name) }}? Semua data akan hilang.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">
                                🗑️ Hapus Customer Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===================== RIWAYAT PESANAN ===================== --}}
            <div class="card-orders">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Riwayat</p>
                        <h2 class="text-xl font-black text-slate-900 mt-1">Pesanan Customer</h2>
                    </div>
                    <span class="inline-flex items-center justify-center rounded-full bg-sky-100 px-3 py-1 text-sm font-black text-sky-700">
                        {{ $orders->count() }}
                    </span>
                </div>

                <div class="max-h-[650px] overflow-y-auto">
                    @forelse($orders as $order)
                        <div class="order-item">

                            {{-- Header: Reference & Status --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="min-w-0">
                                    <p class="font-black text-sky-600 text-sm">{{ $order->reference ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                                </div>
                                <span class="status-badge {{ $order->status_color ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $order->status_label ?? ucfirst($order->status) }}
                                </span>
                            </div>

                            {{-- Item Summary --}}
                            @if(is_array($order->items_summary ?? null) && count($order->items_summary) > 0)
                                <div class="mb-3 space-y-1.5">
                                    @foreach($order->items_summary as $item)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-slate-600 truncate mr-2">
                                                <span class="font-semibold">{{ $item['product_name'] ?? '-' }}</span>
                                                @if(!empty($item['variant'])) <span class="text-slate-400">· {{ $item['variant'] }}</span> @endif
                                                <span class="text-slate-400 ml-1">×{{ $item['quantity'] ?? 0 }}</span>
                                            </span>
                                            <span class="font-semibold text-slate-700 flex-shrink-0">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Alamat Pengiriman --}}
                            @if($order->delivery_address)
                                <div class="flex items-start gap-2 mb-3 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="leading-relaxed">{{ $order->delivery_address }}</span>
                                </div>
                            @endif

                            {{-- Total & Lihat Detail --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</p>
                                    <p class="text-lg font-black text-slate-900">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('admin.orders') }}?reference={{ urlencode($order->reference ?? '') }}"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 transition hover:border-sky-300 hover:text-sky-600 hover:shadow-sm">
                                    Lihat Detail
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <p class="text-lg font-bold text-slate-700">Belum ada pesanan</p>
                            <p class="text-sm text-slate-500 mt-1">Customer ini belum pernah melakukan pemesanan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
</div>
@endsection