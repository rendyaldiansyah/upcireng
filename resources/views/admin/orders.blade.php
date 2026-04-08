@extends('layout.app')

@section('title', 'Kelola Pesanan - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle = 'Order Management';
    $adminSidebarMetricLabel = 'Filtered Results';
    $adminSidebarMetricValue = $orders->total();
    $adminSidebarBody = 'Full order workflow with status updates, customer details, and payment verification.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center space-x-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-500">Dashboard</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-semibold text-slate-900">Orders</span>
        </nav>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- Hero Header --}}
        <section class="mb-8 rounded-2xl bg-gradient-to-r from-slate-50 to-brand-50 p-5 sm:p-7 shadow-md border border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-500 mb-1">Order Operations</p>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Manage All Orders</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Complete control over customer orders with instant status updates.
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="self-start sm:self-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition-all whitespace-nowrap">
                    ← Dashboard
                </a>
            </div>
        </section>

        {{-- Status Metrics --}}
        <section class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3 mb-8">
            @foreach([
                ['label' => 'Pending',    'key' => 'pending',    'color' => 'amber'],
                ['label' => 'Processing', 'key' => 'processing', 'color' => 'sky'],
                ['label' => 'Delivering', 'key' => 'delivering', 'color' => 'violet'],
                ['label' => 'Completed',  'key' => 'completed',  'color' => 'emerald'],
                ['label' => 'Cancelled',  'key' => 'cancelled',  'color' => 'rose'],
            ] as $stat)
            <div class="rounded-xl bg-{{ $stat['color'] }}-50 border border-{{ $stat['color'] }}-200/60 p-4 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-{{ $stat['color'] }}-600 mb-1">{{ $stat['label'] }}</p>
                <p class="text-2xl font-black text-{{ $stat['color'] }}-800">{{ $statusCounts[$stat['key']] ?? 0 }}</p>
            </div>
            @endforeach
        </section>

        {{-- Advanced Filter --}}
        <section class="mb-8 rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100">
            <h2 class="text-base font-bold text-ink-950 mb-4">Advanced Filter</h2>
            <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">Search</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input name="search" value="{{ request('search') }}" placeholder="Reference, name, email..."
                               class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                        <option value="">All Status</option>
                        @foreach(\App\Models\Order::statusOptions() as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-4 py-2 text-sm font-bold text-white hover:from-brand-500 hover:to-brand-600 hover:shadow-md transition-all">
                        Filter Orders
                    </button>
                </div>
            </form>
        </section>

        {{-- Orders Grid --}}
        <section class="grid gap-4 mb-8">
            @forelse($orders as $order)
                <article class="relative group rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-brand-500/5 to-transparent"></div>

                    {{-- FIX: Banner khusus untuk pesanan pending dengan bukti bayar — minta admin verifikasi --}}
                    @if($order->status === 'pending' && $order->payment_proof_path)
                        <div class="relative mb-4 flex items-center gap-3 rounded-xl border-2 border-amber-300 bg-amber-50 px-4 py-3">
                            <span class="text-xl flex-shrink-0">🔔</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-amber-800">Bukti pembayaran diterima — menunggu verifikasi Anda</p>
                                <p class="text-xs text-amber-600 mt-0.5">Customer sudah upload bukti transfer. Silakan cek dan verifikasi.</p>
                            </div>
                            {{-- ★ Tombol Verifikasi Cepat --}}
                            <form action="{{ route('admin.order.status', $order) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="processing">
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:shadow-md whitespace-nowrap">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Verifikasi
                                </button>
                            </form>
                        </div>
                    @elseif($order->status === 'pending' && !$order->payment_proof_path)
                        {{-- Pending tapi belum upload bukti (COD atau belum upload) --}}
                        <div class="relative mb-4 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5">
                            <span class="text-base flex-shrink-0">⏳</span>
                            <p class="text-xs font-semibold text-slate-600">
                                @if($order->payment_method === 'cod')
                                    Metode COD — tidak perlu bukti transfer
                                @else
                                    Menunggu customer upload bukti pembayaran
                                @endif
                            </p>
                        </div>
                    @endif

                    <div class="relative flex flex-col lg:flex-row lg:items-start lg:gap-6">

                        {{-- Left: Order Info --}}
                        <div class="lg:flex-1">
                            {{-- Header --}}
                            <div class="flex items-start justify-between mb-5">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-0.5">Order #{{ $order->reference }}</p>
                                    <h2 class="text-lg font-black text-ink-950">{{ $order->customer_name }}</h2>
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mt-0.5">
                                        <span>{{ $order->customer_phone }}</span>
                                        <span>{{ $order->customer_email }}</span>
                                    </div>
                                    {{-- ✅ FIX: Tampilkan alamat pengiriman --}}
                                    @if($order->delivery_address)
                                        <div class="flex items-start gap-1.5 text-xs text-slate-500 mt-1.5">
                                            <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>{{ $order->delivery_address }}</span>
                                        </div>
                                    @endif
                                </div>
                                <span class="ml-2 px-3 py-1 rounded-lg text-xs font-bold {{ $order->status_color }} bg-opacity-20 whitespace-nowrap">
                                    {{ $order->status_label }}
                                </span>
                            </div>

                            {{-- Items --}}
                            <div class="mb-5">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Order Items</h4>
                                <div class="space-y-2">
                                    @foreach($order->items_summary as $item)
                                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 group-hover:bg-slate-100 transition-colors">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-bold text-slate-500">{{ $item['product_name'][0] }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $item['product_name'] }}</p>
                                                @if($item['variant'])
                                                    <p class="text-xs text-slate-400">{{ $item['variant'] }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <p class="text-sm font-black text-ink-950">x{{ $item['quantity'] }}</p>
                                                <p class="text-xs font-bold text-brand-600">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Right: Payment & Actions --}}
                        <div class="lg:w-72 lg:flex-shrink-0 space-y-4">

                            {{-- Payment --}}
                            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Payment Details</p>
                                <p class="text-xl font-black text-ink-950 mb-1">{{ $order->formatPrice() }}</p>
                                {{-- FIX: format payment method (bank_transfer → Bank Transfer) --}}
                                <p class="text-xs font-bold text-slate-500 mb-3">
                                    {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                </p>
                                @if($order->payment_proof_url)
                                    <a href="{{ $order->payment_proof_url }}" target="_blank"
                                       class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-bold text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Lihat Bukti Bayar
                                    </a>
                                @endif
                            </div>

                            {{-- ★ QUICK ACTION: Verifikasi Pembayaran (khusus pending + ada bukti) --}}
                            @if($order->status === 'pending' && $order->payment_proof_path)
                                <div class="rounded-xl bg-emerald-50 border-2 border-emerald-200 p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-3">
                                        ✓ Aksi Verifikasi
                                    </p>
                                    <div class="space-y-2">
                                        {{-- Approve --}}
                                        <form action="{{ route('admin.order.status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit"
                                                    class="w-full rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:shadow-md flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Verifikasi & Proses
                                            </button>
                                        </form>
                                        {{-- Reject --}}
                                        <form action="{{ route('admin.order.status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <input type="hidden" name="cancel_reason" value="Bukti pembayaran tidak valid">
                                            <button type="submit"
                                                    onclick="return confirm('Tolak & batalkan pesanan ini?')"
                                                    class="w-full rounded-xl border border-rose-200 bg-white px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50 transition flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Tolak Pembayaran
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            {{-- Status Update Manual --}}
                            <form action="{{ route('admin.order.status', $order) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <label class="block text-xs font-bold text-slate-700 mb-1">Update Status Manual</label>
                                <select name="status"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                                    @foreach(\App\Models\Order::statusOptions() as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <textarea name="cancel_reason" rows="2"
                                          placeholder="Alasan (jika dibatalkan)"
                                          class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition resize-none">{{ old('cancel_reason', $order->cancel_reason) }}</textarea>
                                <button type="submit"
                                        class="w-full rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-bold text-white transition hover:shadow-md">
                                    Update Status
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form action="{{ route('admin.order.delete', $order) }}" method="POST"
                                  class="pt-3 border-t border-slate-200"
                                  onsubmit="return confirm('Hapus pesanan ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full rounded-xl border border-rose-200 px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-400 transition bg-white">
                                    Hapus Pesanan
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Sync Error --}}
                    @if($order->sync_error)
                        <div class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-rose-900">Sync Error</h4>
                                    <p class="text-sm text-rose-700 mt-0.5">{{ $order->sync_error }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </article>
            @empty
                <div class="text-center py-20 rounded-2xl bg-white shadow-sm border-2 border-dashed border-slate-200">
                    <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-ink-950 mb-2">No Orders Found</h3>
                    <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">No orders match your current filters.</p>
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-block rounded-xl bg-ink-950 px-6 py-2.5 text-sm font-bold text-white hover:bg-brand-600 transition">
                        ← Back to Dashboard
                    </a>
                </div>
            @endforelse
        </section>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="flex justify-center mt-8">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif

    </main>
</div>
@endsection