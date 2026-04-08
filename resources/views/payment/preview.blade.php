@extends('layout.app')

@section('title', 'Bukti Pembayaran - UP Cireng')
@section('hide_nav', '0')
@section('hide_footer', '1')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-xl">

        {{-- ── Header Badge ── --}}
        <div class="mb-6 text-center animate-fadeIn">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-brand-500 text-sm font-semibold transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
            <div class="inline-flex items-center gap-2 rounded-full bg-brand-100 px-4 py-2">
                <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-sm font-bold text-brand-700">Bukti Pembayaran</span>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="rounded-3xl bg-white shadow-panel border border-slate-100 overflow-hidden animate-scaleIn">

            {{-- ── Image Section ── --}}
            {{--
                FIX: Ganti background gelap (ink-950) ke putih/slate-50 agar tidak ada
                black letterboxing di sekitar foto. Foto sekarang tampil natural tanpa
                empty space hitam di kiri/kanan atau atas/bawah.
            --}}
            <div class="relative bg-slate-50 border-b border-slate-100">
                <div class="relative overflow-hidden">
                    <img
                        src="{{ $previewUrl }}"
                        alt="Bukti Pembayaran"
                        class="w-full object-contain max-h-[520px]"
                        style="min-height: 240px; background-color: #f8fafc;"
                    >
                    {{-- Watermark badge --}}
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1.5 rounded-full bg-black/50 px-3 py-1.5 backdrop-blur-sm">
                        <svg class="h-3 w-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-[11px] font-bold text-white/90">Dilindungi watermark UP CIRENG</span>
                    </div>
                </div>

                {{-- Download Button --}}
                <div class="py-3 flex justify-center border-t border-slate-100 bg-white">
                    <a href="{{ route('payment.download', $order->id) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 transition-all border border-slate-200">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh Bukti
                    </a>
                </div>
            </div>

            {{-- ── Status Banner ── --}}
            @php
                $statusConfig = [
                    'pending'    => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',  'text' => 'text-amber-800',  'dot' => 'bg-amber-400',  'pulse' => true],
                    'processing' => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',   'text' => 'text-blue-800',   'dot' => 'bg-blue-400',   'pulse' => true],
                    'delivering' => ['bg' => 'bg-violet-50',  'border' => 'border-violet-200', 'text' => 'text-violet-800', 'dot' => 'bg-violet-400', 'pulse' => false],
                    'completed'  => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200','text' => 'text-emerald-800','dot' => 'bg-emerald-500', 'pulse' => false],
                    'cancelled'  => ['bg' => 'bg-rose-50',    'border' => 'border-rose-200',   'text' => 'text-rose-800',   'dot' => 'bg-rose-400',   'pulse' => false],
                ];
                $s = $statusConfig[$order->status] ?? $statusConfig['pending'];
            @endphp
            <div class="mx-4 mt-4 rounded-2xl border {{ $s['border'] }} {{ $s['bg'] }} px-4 py-3.5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                        @if($s['pulse'])
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $s['dot'] }} opacity-75"></span>
                        @endif
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $s['dot'] }}"></span>
                    </span>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider {{ $s['text'] }} opacity-60">Status Pesanan</p>
                        <p class="font-black text-sm {{ $s['text'] }}">{{ $status['icon'] }} {{ $status['label'] }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal</p>
                    <p class="text-sm font-bold text-slate-600">{{ $orderDate }}</p>
                </div>
            </div>

            {{-- ── Info Grid ── --}}
            <div class="p-4 grid grid-cols-2 gap-3 mt-1">
                {{-- Customer Name --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Nama Pemesan</p>
                    <p class="font-black text-slate-900 text-base truncate">{{ $customerName }}</p>
                </div>

                {{-- Phone --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">No. Telepon</p>
                    @if($order->customer_phone)
                        <a href="tel:{{ $order->customer_phone }}" class="font-black text-brand-600 text-base hover:text-brand-700 truncate block">
                            {{ $order->customer_phone }}
                        </a>
                    @else
                        <p class="font-bold text-slate-500 text-sm">—</p>
                    @endif
                </div>

                {{-- Payment Method — FIX: format dari 'bank_transfer' → 'Bank Transfer' --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Metode Bayar</p>
                    <p class="font-black text-slate-900 text-base">
                        {{ ucwords(str_replace('_', ' ', $order->payment_method ?? 'QRIS')) }}
                    </p>
                </div>

                {{-- Total --}}
                <div class="rounded-2xl bg-gradient-to-br from-brand-50 to-brand-100/60 border border-brand-200/60 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-brand-500 mb-1.5">Total Bayar</p>
                    <p class="font-black text-brand-700 text-base">{{ $totalPrice }}</p>
                </div>
            </div>

            {{-- ── Order Items ── --}}
            @if($order->items_summary && count($order->items_summary) > 0)
            <div class="px-4 pb-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 bg-white">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pesanan Anda</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($order->items_summary as $item)
                        <div class="px-4 py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 text-sm truncate">{{ $item['name'] ?? 'Produk' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $item['quantity'] ?? 1 }}x
                                    @if(!empty($item['variant'])) · {{ $item['variant'] }}@endif
                                </p>
                            </div>
                            <p class="font-black text-slate-900 text-sm flex-shrink-0">
                                Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    {{-- Total row --}}
                    <div class="px-4 py-3 bg-white border-t border-slate-200 flex items-center justify-between">
                        <p class="font-bold text-slate-500 text-sm">Total</p>
                        <p class="font-black text-brand-600 text-base">{{ $totalPrice }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Help Note ── --}}
            @if($order->status === 'pending')
            <div class="mx-4 mb-4 mt-3 rounded-2xl bg-amber-50 border border-amber-200 px-4 py-3.5">
                <div class="flex gap-2.5">
                    <span class="text-lg flex-shrink-0 mt-0.5">⏳</span>
                    <div>
                        <p class="font-bold text-amber-800 text-sm">Menunggu verifikasi admin</p>
                        <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                            Pembayaran Anda sedang dicek oleh admin. Biasanya selesai dalam 5–15 menit.
                            Anda akan mendapat notifikasi setelah diverifikasi.
                        </p>
                    </div>
                </div>
            </div>
            @elseif($order->status === 'processing')
            <div class="mx-4 mb-4 mt-3 rounded-2xl bg-blue-50 border border-blue-200 px-4 py-3.5">
                <div class="flex gap-2.5">
                    <span class="text-lg flex-shrink-0">⚙️</span>
                    <div>
                        <p class="font-bold text-blue-800 text-sm">Pembayaran terverifikasi!</p>
                        <p class="text-xs text-blue-700 mt-1">Pesanan Anda sedang diproses oleh admin.</p>
                    </div>
                </div>
            </div>
            @elseif($order->status === 'completed')
            <div class="mx-4 mb-4 mt-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3.5">
                <div class="flex gap-2.5">
                    <span class="text-lg flex-shrink-0">✅</span>
                    <div>
                        <p class="font-bold text-emerald-800 text-sm">Pembayaran terverifikasi!</p>
                        <p class="text-xs text-emerald-700 mt-1">Pesanan Anda sudah selesai diproses.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Footer Actions ── --}}
            <div class="px-4 pb-5 pt-2 flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('orders.my') }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3.5 text-sm font-bold text-white transition-all hover:shadow-float hover:-translate-y-0.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Lihat Pesanan Saya
                </a>
                <a href="{{ route('payment.download', $order->id) }}"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-5 py-3.5 text-sm font-bold text-slate-700 transition-all hover:border-brand-300 hover:bg-brand-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh
                </a>
            </div>

        </div>

        {{-- ── Brand footer ── --}}
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400 font-medium">
                UP Cireng · Order System ·
                <a href="{{ route('home') }}" class="text-brand-500 hover:text-brand-600 font-bold">Kembali ke Menu</a>
            </p>
        </div>

    </div>
</div>
@endsection