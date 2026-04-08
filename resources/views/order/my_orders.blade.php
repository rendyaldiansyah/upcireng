@extends('layout.app')

@section('title', 'Pesanan Saya - UP Cireng')

@section('content')
@php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); @endphp

<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#ffffff_30%,#f8fafc_100%)] min-h-screen">
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6">

        {{-- Header --}}
        <div class="mb-8 rounded-3xl bg-white p-6 sm:p-8 shadow-xl shadow-brand-500/10 border border-slate-100">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-brand-500">Pesanan Saya</p>
            <h1 class="mt-2 text-3xl sm:text-4xl font-black text-slate-950">Riwayat Pesanan</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Status diperbarui langsung dari admin. Ketuk kartu untuk melihat detail.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('home') }}"
                   class="rounded-full border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                    ← Kembali ke Toko
                </a>
                <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener"
                   class="rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600 hover:shadow-lg">
                    💬 Hubungi Admin
                </a>
            </div>
        </div>

        {{-- Orders --}}
        <div class="space-y-3">
            @forelse($orders as $order)
                <article class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-shadow hover:shadow-md">

                    {{-- ── Header kartu: klik untuk expand ── --}}
                    <button
                        type="button"
                        onclick="toggleOrder({{ $order->id }})"
                        class="w-full text-left px-5 py-4 flex items-center gap-3 hover:bg-slate-50/80 transition-colors"
                    >
                        {{-- Status dot --}}
                        @php
                            $dotColor = match($order->status) {
                                'pending'    => 'bg-amber-400',
                                'processing' => 'bg-sky-400',
                                'delivering' => 'bg-violet-400',
                                'completed'  => 'bg-emerald-400',
                                'cancelled'  => 'bg-rose-400',
                                default      => 'bg-slate-400',
                            };
                        @endphp
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $dotColor }}"></span>

                        {{-- Info utama --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline gap-2 flex-wrap">
                                <p class="text-sm font-black text-slate-900 truncate">{{ $order->summary_title }}</p>
                                <p class="text-xs text-slate-400 flex-shrink-0">{{ $order->created_at->translatedFormat('d M Y') }}</p>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ $order->reference }}</p>
                        </div>

                        {{-- Harga & status & chevron --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-black text-slate-900">{{ $order->formatPrice() }}</p>
                                <p class="text-xs font-semibold {{ $order->status_color }}">{{ $order->status_label }}</p>
                            </div>
                            <svg id="chevron-{{ $order->id }}"
                                 class="h-4 w-4 text-slate-400 transition-transform duration-300 flex-shrink-0"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    {{-- Mobile: harga & status --}}
                    <div class="sm:hidden px-5 pb-3 flex items-center justify-between border-b border-slate-100">
                        <span class="text-xs font-semibold {{ $order->status_color }}">{{ $order->status_label }}</span>
                        <span class="text-sm font-black text-slate-900">{{ $order->formatPrice() }}</span>
                    </div>

                    {{-- ── Panel detail ── --}}
                    <div id="order-{{ $order->id }}" class="hidden">
                        <div class="px-5 py-4 space-y-4 border-t border-slate-100">

                            {{-- Rincian Item --}}
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Rincian Item</p>
                                <div class="space-y-1.5">
                                    @foreach($order->items_summary as $item)
                                        <div class="flex items-center justify-between gap-2 text-sm">
                                            <span class="text-slate-700">
                                                {{ $item['product_name'] }}
                                                @if($item['variant'])
                                                    <span class="text-slate-400">· {{ $item['variant'] }}</span>
                                                @endif
                                                <span class="text-slate-400">× {{ $item['quantity'] }}</span>
                                            </span>
                                            <span class="font-semibold text-slate-900 flex-shrink-0">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between pt-2 border-t border-slate-100 text-sm font-black text-slate-900">
                                        <span>Total</span>
                                        <span>{{ $order->formatPrice() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Pengiriman --}}
                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Pengiriman</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $order->customer_name }}</p>
                                <p class="text-xs text-slate-500">{{ $order->customer_phone }}</p>
                                @if($order->customer_email)
                                    <p class="text-xs text-slate-500">{{ $order->customer_email }}</p>
                                @endif
                                @if($order->delivery_address)
                                    <div class="flex items-start gap-1.5 mt-2">
                                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-xs text-slate-600 leading-5">{{ $order->delivery_address }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Pembayaran + Bukti --}}
                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Pembayaran</p>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                        </p>
                                        @php
                                            $syncLabel = match($order->sync_status) {
                                                'synced'  => '✓ Pesanan tercatat',
                                                'failed'  => '⚠️ Ada masalah teknis',
                                                default   => '⏳ Sedang diproses',
                                            };
                                            $syncColor = match($order->sync_status) {
                                                'synced'  => 'text-emerald-600',
                                                'failed'  => 'text-rose-500',
                                                default   => 'text-amber-500',
                                            };
                                        @endphp
                                        <p class="text-xs font-semibold mt-1 {{ $syncColor }}">{{ $syncLabel }}</p>
                                    </div>

                                    {{-- Thumbnail bukti bayar --}}
                                    @if($order->payment_proof_path)
                                        <a href="{{ asset('storage/' . $order->payment_proof_path) }}"
                                           target="_blank"
                                           title="Lihat bukti pembayaran"
                                           class="flex-shrink-0 group relative">
                                            <img src="{{ asset('storage/' . $order->payment_proof_path) }}"
                                                 alt="Bukti Bayar"
                                                 class="w-16 h-16 rounded-xl object-cover border-2 border-brand-200 shadow-sm group-hover:border-brand-400 transition">
                                            <div class="absolute inset-0 rounded-xl bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </div>
                                            <p class="text-[10px] text-center text-brand-500 font-semibold mt-1">Bukti Bayar</p>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Banner verifikasi pending --}}
                            @if($order->status === 'pending' && $order->payment_method !== 'cod')
                                <div class="flex items-start gap-3 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3">
                                    <span class="text-base flex-shrink-0">⏳</span>
                                    <div>
                                        <p class="text-sm font-bold text-amber-800">Menunggu verifikasi admin</p>
                                        <p class="text-xs text-amber-600 mt-0.5">Pembayaran Anda sedang dicek. Biasanya 5–15 menit.</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Catatan --}}
                            @if($order->notes)
                                <div class="rounded-xl bg-slate-50 px-4 py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan</p>
                                    <p class="text-sm text-slate-600">{{ $order->notes }}</p>
                                </div>
                            @endif

                            {{-- Tombol Aksi --}}
                            <div class="space-y-2 pt-1">

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
                                   class="flex items-center justify-center gap-2 w-full rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition">
                                    💬 Kirim ke WhatsApp
                                </a>

                                {{-- Batalkan pesanan --}}
                                @if($order->canBeCancelled())
                                    <form action="{{ route('order.cancel', $order) }}" method="POST" class="space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="cancel_reason"
                                               placeholder="Alasan pembatalan (opsional)"
                                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                                        <button type="submit"
                                                class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                                            Batalkan Pesanan
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
                                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300 hover:text-slate-700">
                                            Hapus dari Riwayat
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white px-6 py-16 text-center">
                    <p class="text-slate-400 font-semibold">Anda belum memiliki pesanan.</p>
                    <a href="{{ route('home') }}" class="mt-3 inline-block text-sm font-bold text-brand-500 hover:underline">
                        Mulai pesan →
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
function toggleOrder(id) {
    const panel   = document.getElementById('order-' + id);
    const chevron = document.getElementById('chevron-' + id);
    const isOpen  = !panel.classList.contains('hidden');

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