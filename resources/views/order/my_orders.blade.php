@extends('layout.app')

@section('title', 'Pesanan Saya - UP Cireng')

@section('content')
@php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); @endphp

<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#ffffff_30%,#f8fafc_100%)]">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
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
                <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener"
                   class="rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                    💬 Hubungi Admin
                </a>
            </div>
        </div>

        {{-- Orders --}}
        <div class="mt-10 space-y-4">
            @forelse($orders as $order)
                <article class="rounded-[2rem] border border-slate-200 bg-white shadow-lg shadow-slate-200/70 overflow-hidden">

                    {{-- ── Baris atas: selalu tampil, klik untuk expand ── --}}
                    <button
                        type="button"
                        onclick="toggleOrder('order-{{ $order->id }}')"
                        class="w-full text-left px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50 transition-colors"
                    >
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-500">{{ $order->reference }}</p>
                            <h2 class="mt-1 text-lg font-black text-slate-950 truncate">{{ $order->summary_title }}</h2>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="rounded-full px-4 py-1.5 text-sm font-semibold {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                            <span class="text-xs font-semibold text-slate-950 font-black">{{ $order->formatPrice() }}</span>
                            {{-- Chevron icon --}}
                            <svg id="chevron-{{ $order->id }}" class="h-5 w-5 text-slate-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    {{-- ── Panel detail: expand/collapse ── --}}
                    <div id="order-{{ $order->id }}" class="hidden border-t border-slate-100 px-6 py-5 space-y-5">

                        {{-- Grid info --}}
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            {{-- Rincian Item --}}
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Rincian Item</p>
                                <ul class="space-y-1.5 text-sm text-slate-700">
                                    @foreach($order->items_summary as $item)
                                        <li class="flex justify-between gap-2">
                                            <span class="truncate">
                                                {{ $item['product_name'] }}
                                                @if($item['variant']) · {{ $item['variant'] }} @endif
                                                × {{ $item['quantity'] }}
                                            </span>
                                            <span class="font-semibold flex-shrink-0">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Pengiriman --}}
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Pengiriman</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $order->customer_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $order->customer_phone }}</p>
                                <p class="text-xs text-slate-500">{{ $order->customer_email }}</p>
                                @if($order->delivery_address)
                                    <div class="flex items-start gap-1.5 mt-2">
                                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-xs text-slate-600">{{ $order->delivery_address }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Pembayaran & Status --}}
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Pembayaran</p>
                                <p class="text-xl font-black text-slate-950">{{ $order->formatPrice() }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 mb-3">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>

                                {{-- Bukti Pembayaran --}}
                                @if($order->payment_proof_path)
                                    <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 px-3 py-2 text-xs font-bold text-white transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Lihat Bukti Bayar
                                    </a>
                                @endif

                                {{-- Status Sinkron --}}
                                @php
                                    $syncLabel = match($order->sync_status) {
                                        'synced'  => '✓ Pesanan tercatat',
                                        'failed'  => '⚠️ Hubungi admin',
                                        default   => '⏳ Sedang diproses',
                                    };
                                    $syncColor = match($order->sync_status) {
                                        'synced'  => 'text-emerald-600',
                                        'failed'  => 'text-rose-600',
                                        default   => 'text-amber-600',
                                    };
                                @endphp
                                <p class="mt-3 text-xs font-semibold {{ $syncColor }}">{{ $syncLabel }}</p>
                                @if($order->sync_status === 'failed')
                                    <p class="mt-0.5 text-xs text-rose-400 leading-5">Ada masalah teknis. Hubungi admin jika perlu.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Banner verifikasi pending --}}
                        @if($order->status === 'pending' && $order->payment_method !== 'cod')
                            <div class="flex items-start gap-3 rounded-2xl bg-amber-50 border border-amber-200 px-4 py-3">
                                <span class="text-lg flex-shrink-0">⏳</span>
                                <div>
                                    <p class="text-sm font-bold text-amber-800">Menunggu verifikasi admin</p>
                                    <p class="text-xs text-amber-700 mt-0.5">Pembayaran Anda sedang dicek. Biasanya 5–15 menit.</p>
                                </div>
                            </div>
                        @endif

                        {{-- Catatan --}}
                        @if($order->notes)
                            <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan</p>
                                <p class="text-sm text-slate-600">{{ $order->notes }}</p>
                            </div>
                        @endif

                        {{-- Tombol aksi --}}
                        <div class="flex flex-wrap gap-3 pt-1">

                            {{-- Kirim ke WhatsApp --}}
                            @php
                                $itemsText = collect($order->items_summary)->map(fn($i) =>
                                    '* ' . $i['product_name'] .
                                    ($i['variant'] ? ' · ' . $i['variant'] : '') .
                                    ' x' . $i['quantity'] .
                                    ' (Rp ' . number_format($i['subtotal'], 0, ',', '.') . ')'
                                )->implode("\n");

                                $waMsg = "Halo UP Cireng!\n\n*Pesanan #{$order->reference}*\n\n{$itemsText}\n\n" .
                                         '*Total:* Rp ' . number_format($order->total_price, 0, ',', '.') . "\n" .
                                         '*Pembayaran:* ' . ucwords(str_replace('_', ' ', $order->payment_method)) . "\n\n" .
                                         "*Alamat:*\n{$order->delivery_address}\n\nTerima kasih!";
                            @endphp
                            <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMsg) }}" target="_blank"
                               class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white transition hover:shadow-md">
                                💬 Kirim ke WhatsApp
                            </a>

                            {{-- Batalkan pesanan --}}
                            @if($order->canBeCancelled())
                                <form action="{{ route('order.cancel', $order) }}" method="POST" class="flex gap-2 flex-1 min-w-56">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="cancel_reason"
                                           placeholder="Alasan pembatalan (opsional)"
                                           class="flex-1 rounded-2xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100">
                                    <button type="submit"
                                            class="rounded-2xl bg-rose-500 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-rose-600 whitespace-nowrap">
                                        Batalkan
                                    </button>
                                </form>
                            @endif

                            {{-- Hapus dari riwayat --}}
                            @if($order->canBeDeletedByCustomer())
                                <form action="{{ route('order.destroy', $order) }}" method="POST"
                                      onsubmit="return confirm('Sembunyikan order ini dari riwayat Anda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-2xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-rose-300 hover:text-rose-600">
                                        Hapus dari Riwayat
                                    </button>
                                </form>
                            @endif
                        </div>
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

<script>
function toggleOrder(id) {
    const panel   = document.getElementById(id);
    const orderId = id.replace('order-', '');
    const chevron = document.getElementById('chevron-' + orderId);

    const isOpen = !panel.classList.contains('hidden');

    if (isOpen) {
        panel.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        panel.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    }
}
</script>
@endsection