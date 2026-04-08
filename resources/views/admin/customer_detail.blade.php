@extends('layout.app')

@section('title', 'Detail Customer - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-white to-slate-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle = 'Detail Customer';
    $adminSidebarMetricLabel = 'Total Pesanan';
    $adminSidebarMetricValue = $orders->count();
    $adminSidebarBody = 'Rincian akun pelanggan.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-5 sm:py-8 lg:px-8 xl:px-10">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-2 text-sm font-medium text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.customers') }}" class="hover:text-brand-600 transition">Customer</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-bold text-slate-900 truncate max-w-[140px]">{{ $customer->name }}</span>
        </nav>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_1.4fr]">

            {{-- ===================== PROFIL & EDIT FORM ===================== --}}
            <div class="space-y-6">

                {{-- Profil Card --}}
                <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-br from-sky-500 to-sky-700 p-6 sm:p-8 text-white shadow-xl">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center text-white font-black text-2xl flex-shrink-0 backdrop-blur-sm">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200">Customer #{{ $customer->id }}</p>
                            <h1 class="text-xl sm:text-2xl font-black truncate">{{ $customer->name }}</h1>
                            <p class="text-sm text-sky-200">Bergabung {{ $customer->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-sm">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200">Total Pesanan</p>
                            <p class="text-2xl font-black mt-1">{{ $orders->count() }}</p>
                        </div>
                        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-sm">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-200">Total Belanja</p>
                            <p class="text-lg font-black mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Edit Form --}}
                <div class="rounded-2xl sm:rounded-3xl bg-white shadow-panel border border-slate-100 p-6 sm:p-8">
                    <h2 class="text-lg font-black text-slate-900 mb-6">Edit Data Customer</h2>

                    @if($errors->any())
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('admin.customer.update', $customer) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                   required>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Nomor WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                   required>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">
                                Email
                                <span class="text-slate-400 font-normal">(opsional)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                   placeholder="Kosongkan jika tidak ada">
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="submit"
                                    class="flex-1 rounded-2xl bg-sky-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-sky-600 hover:shadow-lg">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.customers') }}"
                               class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                Batal
                            </a>
                        </div>
                    </form>

                    {{-- Hapus customer --}}
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <form action="{{ route('admin.customer.delete', $customer) }}" method="POST"
                              data-delete-confirm="Hapus customer {{ $customer->name }}? Semua data akan hilang.">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                                🗑️ Hapus Customer Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===================== RIWAYAT PESANAN ===================== --}}
            <div class="rounded-2xl sm:rounded-3xl bg-white shadow-panel border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Riwayat</p>
                        <h2 class="text-xl font-black text-slate-900 mt-1">Pesanan Customer</h2>
                    </div>
                    <span class="inline-flex items-center justify-center rounded-full bg-blue-100 px-3 py-1 text-sm font-bold text-blue-700">
                        {{ $orders->count() }}
                    </span>
                </div>

                <div class="divide-y divide-slate-100 max-h-[600px] overflow-y-auto">
                    @forelse($orders as $order)
                        <div class="px-6 py-5 hover:bg-slate-50 transition-colors">

                            {{-- Header: Reference & Status --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="min-w-0">
                                    <p class="font-black text-brand-600 text-sm">{{ $order->reference ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-xl px-2.5 py-1 text-[10px] font-bold {{ $order->status_color ?? 'bg-slate-100 text-slate-600' }} whitespace-nowrap flex-shrink-0">
                                    {{ $order->status_label ?? ucfirst($order->status) }}
                                </span>
                            </div>

                            {{-- Item Summary --}}
                            @if(is_array($order->items_summary ?? null) && count($order->items_summary) > 0)
                                <div class="mb-3 space-y-1">
                                    @foreach($order->items_summary as $item)
                                        <div class="flex items-center justify-between text-xs text-slate-600">
                                            <span class="truncate mr-2">
                                                {{ $item['product_name'] ?? '-' }}
                                                @if(!empty($item['variant'])) · {{ $item['variant'] }} @endif
                                                × {{ $item['quantity'] ?? 0 }}
                                            </span>
                                            <span class="font-semibold flex-shrink-0">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- ✅ FIX: Alamat Pengiriman --}}
                            @if($order->delivery_address)
                                <div class="flex items-start gap-1.5 mb-3 text-xs text-slate-500">
                                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $order->delivery_address }}</span>
                                </div>
                            @endif

                            {{-- Total & Lihat Detail --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</p>
                                    <p class="text-base font-black text-ink-950">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('admin.orders') }}?reference={{ urlencode($order->reference ?? '') }}"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                                    Lihat Detail
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <svg class="h-12 w-12 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <p class="font-bold text-slate-500">Belum ada pesanan</p>
                            <p class="text-sm text-slate-400 mt-1">Customer ini belum pernah memesan</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
</div>
@endsection