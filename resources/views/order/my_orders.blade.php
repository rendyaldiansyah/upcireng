@extends('layout.app')

@section('title', 'Pesanan Saya - UP Cireng')

@section('content')
@php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); @endphp
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#ffffff_30%,#f8fafc_100%)]">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 rounded-[2rem] bg-white p-8 shadow-xl shadow-brand-500/20 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Pesanan Saya</p>
                <h1 class="mt-3 text-4xl font-black text-slate-950">Riwayat pesanan Anda</h1>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Status pesanan diperbarui langsung dari admin. Anda dapat membatalkan pesanan yang masih pending atau sedang diproses.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                    ← Kembali ke Toko
                </a>
                <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener" class="rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                    💬 Hubungi Admin
                </a>
            </div>
        </div>

        <div class="mt-10 space-y-6">
            @forelse($orders as $order)
                <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/70">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-500">{{ $order->reference }}</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">{{ $order->summary_title }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                            <a href="{{ route('order.show', $order) }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                                📋 Detail
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        <div class="rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Ringkasan Item</p>
                            <ul class="mt-3 space-y-2 text-sm text-slate-700">
                                @foreach($order->items_summary as $item)
                                    <li>{{ $item['product_name'] }}@if($item['variant']) · {{ $item['variant'] }}@endif × {{ $item['quantity'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pembayaran</p>
                            <p class="mt-3 text-xl font-black text-slate-950">{{ $order->formatPrice() }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ strtoupper($order->payment_method) }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Sync Log</p>
                            <p class="mt-3 text-sm font-semibold text-slate-900">{{ $order->sync_status_label }}</p>
                            @if($order->sync_error)
                                <p class="mt-2 text-xs leading-6 text-rose-600">{{ $order->sync_error }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        @if($order->canBeCancelled())
                            <form action="{{ route('order.cancel', $order) }}" method="POST" class="flex-1 min-w-64">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="cancel_reason" placeholder="Alasan pembatalan (opsional)" class="mb-3 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                                <button type="submit" class="w-full rounded-2xl bg-rose-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-600">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif

                        @if($order->canBeDeletedByCustomer())
                            <form action="{{ route('order.destroy', $order) }}" method="POST" onsubmit="return confirm('Sembunyikan order ini dari riwayat Anda?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                                    Hapus dari Riwayat
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-slate-500">
                    Anda belum memiliki pesanan.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
