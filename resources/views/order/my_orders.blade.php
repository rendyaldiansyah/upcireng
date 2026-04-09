@extends('layout.app')

@section('title', 'Pesanan Saya - UP Cireng')

@section('content')
@php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); @endphp

<style>
/* ── Accordion ── */
.order-panel        { display:grid; grid-template-rows:0fr; transition:grid-template-rows .32s ease; }
.order-panel.open   { grid-template-rows:1fr; }
.order-panel > div  { overflow:hidden; }
.chevron            { transition:transform .3s ease; }
.chevron.open       { transform:rotate(180deg); }

/* ── Status badge ── */
.status-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.2rem .65rem; border-radius:9999px; font-size:.65rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; white-space:nowrap; }

/* ── Proof button ── */
.proof-btn { display:inline-flex; align-items:center; gap:.4rem; border-radius:.75rem; padding:.5rem .9rem; font-size:.78rem; font-weight:700; transition:all .15s; white-space:nowrap; }
.proof-btn:not([disabled]):hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(234,88,12,.15); }

/* ── WA Modal ── */
.wa-backdrop {
    position:fixed; inset:0; z-index:200; display:flex; align-items:center; justify-content:center; padding:1rem;
    background:rgba(15,23,42,0); backdrop-filter:blur(0px);
    transition:background .28s ease, backdrop-filter .28s ease;
    pointer-events:none;
}
.wa-backdrop.open  { background:rgba(15,23,42,.55); backdrop-filter:blur(6px); pointer-events:all; }
.wa-backdrop.closing { background:rgba(15,23,42,0); backdrop-filter:blur(0px); }

.wa-modal {
    width:100%; max-width:420px; border-radius:1.75rem; overflow:hidden;
    background:#fff; box-shadow:0 25px 60px rgba(0,0,0,.25);
    transform:translateY(36px) scale(.97); opacity:0;
    transition:transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
}
.wa-backdrop.open .wa-modal    { transform:translateY(0) scale(1); opacity:1; }
.wa-backdrop.closing .wa-modal { transform:translateY(20px) scale(.97); opacity:0; transition:transform .2s ease-in, opacity .2s ease-in; }

.wa-chat-bg {
    background-color:#e5ddd5;
    background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23c8bfb8' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.wa-bubble::-webkit-scrollbar       { width:4px; }
.wa-bubble::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:99px; }
</style>

<div class="min-h-screen bg-gradient-to-b from-orange-50 via-white to-slate-50 py-8 px-4 sm:px-6">
<div class="mx-auto max-w-5xl">

    {{-- ── Page Header ── --}}
    <div class="mb-6 rounded-2xl bg-white border border-orange-100 shadow-lg shadow-orange-100/40 p-6 sm:p-7">
        <p class="text-[10px] font-extrabold uppercase tracking-[.3em] text-orange-500 mb-1">Pesanan Saya</p>
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">Riwayat Pesanan</h1>
                <p class="mt-1 text-sm text-slate-500">Ketuk kartu untuk melihat detail & kirim ke WhatsApp.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-orange-300 hover:text-orange-600 transition-colors whitespace-nowrap">
                    ← Kembali ke Toko
                </a>
                <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition-colors shadow-sm whitespace-nowrap">
                    💬 Hubungi Admin
                </a>
            </div>
        </div>
    </div>

    {{-- ── Orders ── --}}
    <div class="space-y-3">
    @forelse($orders as $order)
    @php
        $sm = [
            'pending'    => ['dot'=>'bg-amber-400',  'ring'=>'#fef3c7','badge'=>'bg-amber-50 text-amber-700 border border-amber-200',    'icon'=>'⏳'],
            'processing' => ['dot'=>'bg-sky-400',    'ring'=>'#e0f2fe','badge'=>'bg-sky-50 text-sky-700 border border-sky-200',          'icon'=>'🔄'],
            'delivering' => ['dot'=>'bg-violet-400', 'ring'=>'#ede9fe','badge'=>'bg-violet-50 text-violet-700 border border-violet-200',  'icon'=>'🚚'],
            'completed'  => ['dot'=>'bg-emerald-400','ring'=>'#d1fae5','badge'=>'bg-emerald-50 text-emerald-700 border border-emerald-200','icon'=>'✅'],
            'cancelled'  => ['dot'=>'bg-rose-400',   'ring'=>'#ffe4e6','badge'=>'bg-rose-50 text-rose-700 border border-rose-200',        'icon'=>'❌'],
        ];
        $s = $sm[$order->status] ?? ['dot'=>'bg-slate-400','ring'=>'#f1f5f9','badge'=>'bg-slate-50 text-slate-600 border border-slate-200','icon'=>'·'];

        /* WA message (same as show blade) */
        $itemsText = collect($order->items_summary)->map(fn($i) =>
            '• ' . $i['product_name'] .
            ($i['variant'] ? ' · ' . $i['variant'] : '') .
            ' ×' . $i['quantity'] .
            ' (Rp ' . number_format($i['subtotal'], 0, ',', '.') . ')'
        )->implode("\n");
        $waMsg = "Halo UP Cireng!\n\n*Pesanan #{$order->reference}*\n\n{$itemsText}\n\n" .
                 '*Total:* Rp ' . number_format($order->total_price, 0, ',', '.') . "\n" .
                 '*Pembayaran:* ' . ucwords(str_replace('_', ' ', $order->payment_method)) . "\n\n" .
                 "*Alamat:*\n{$order->delivery_address}\n\nTerima kasih!";
        $waUrl     = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMsg);
        $adminUrl  = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMsg);
    @endphp

    <article class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">

        {{-- Card row / toggle --}}
        <button type="button" onclick="toggleOrder({{ $order->id }})"
                class="w-full text-left px-4 sm:px-5 py-4 flex items-center gap-3 hover:bg-slate-50/60 transition-colors group">

            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $s['dot'] }}"
                  style="box-shadow:0 0 0 4px {{ $s['ring'] }}"></span>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 truncate">{{ $order->summary_title }}</p>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $order->reference }}</p>
            </div>

            <div class="flex-shrink-0 flex flex-col items-end gap-1">
                <p class="text-sm font-black text-slate-900">{{ $order->formatPrice() }}</p>
                <span class="status-badge {{ $s['badge'] }}">{{ $s['icon'] }} {{ $order->status_label }}</span>
                <p class="text-[10px] text-slate-400">{{ $order->created_at->translatedFormat('d M Y') }}</p>
            </div>

            <svg id="chevron-{{ $order->id }}" class="chevron w-4 h-4 text-slate-300 group-hover:text-slate-500 flex-shrink-0 ml-1"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- ── Expandable detail ── --}}
        <div class="order-panel" id="order-{{ $order->id }}">
        <div>
        <div class="border-t border-slate-100 px-4 sm:px-5 py-5">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- LEFT: items + shipping + sync --}}
                <div class="space-y-4">

                    {{-- Items --}}
                    <section>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Rincian Item</p>
                        <div class="rounded-xl border border-slate-100 overflow-hidden">
                            @foreach($order->items_summary as $item)
                            <div class="flex items-center justify-between gap-3 px-3 py-2.5 text-sm {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                                <span class="text-slate-700 min-w-0">
                                    {{ $item['product_name'] }}
                                    @if($item['variant'])<span class="text-slate-400 text-xs"> · {{ $item['variant'] }}</span>@endif
                                    <span class="text-slate-400 text-xs"> ×{{ $item['quantity'] }}</span>
                                </span>
                                <span class="font-semibold text-slate-800 flex-shrink-0">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between items-center px-3 py-2.5 bg-slate-50 border-t-2 border-slate-100">
                                <span class="text-sm font-black text-slate-900">Total</span>
                                <span class="text-sm font-black text-orange-600">{{ $order->formatPrice() }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- Shipping --}}
                    <section class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3.5 space-y-2">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Info Pengiriman</p>
                        <div class="flex items-start gap-2.5">
                            <span class="flex-shrink-0 mt-0.5">👤</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $order->customer_name }}</p>
                                <p class="text-xs text-slate-500">{{ $order->customer_phone }}</p>
                                @if($order->customer_email)<p class="text-xs text-slate-400">{{ $order->customer_email }}</p>@endif
                            </div>
                        </div>
                        @if($order->delivery_address)
                        <div class="flex items-start gap-2.5">
                            <span class="flex-shrink-0 mt-0.5">📍</span>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $order->delivery_address }}</p>
                        </div>
                        @endif
                        @if($order->notes)
                        <div class="flex items-start gap-2.5">
                            <span class="flex-shrink-0 mt-0.5">📝</span>
                            <p class="text-xs text-slate-500 italic leading-relaxed">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </section>

                    {{-- Sync status (from show blade) --}}
                    @if(isset($order->sync_status))
                    <section class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Status Sinkronisasi</p>
                        <div class="flex items-center justify-between gap-3">
                            @php
                                $syncLabel = match($order->sync_status) {
                                    'synced'  => ['label'=>'✓ Pesanan tercatat', 'color'=>'text-emerald-600'],
                                    'failed'  => ['label'=>'⚠️ Ada masalah teknis','color'=>'text-rose-500'],
                                    default   => ['label'=>'⏳ Sedang diproses',  'color'=>'text-amber-500'],
                                };
                            @endphp
                            <p class="text-sm font-bold {{ $syncLabel['color'] }}">{{ $syncLabel['label'] }}</p>
                            @if($order->can_retry_sync ?? false)
                            <form action="{{ route('order.retry-sync', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="rounded-lg bg-sky-100 px-3 py-1.5 text-xs font-bold text-sky-600 hover:bg-sky-200 transition">
                                    Coba Lagi
                                </button>
                            </form>
                            @endif
                        </div>
                        @if($order->sync_error_label ?? false)
                        <div class="mt-2 rounded-lg bg-amber-50 border border-amber-200 px-3 py-2">
                            <p class="text-xs text-amber-700">{{ $order->sync_error_label }}</p>
                        </div>
                        @endif
                    </section>
                    @endif

                </div>

                {{-- RIGHT: payment + banners + actions --}}
                <div class="space-y-4">

                    {{-- Payment + proof --}}
                    <section class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3.5">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Pembayaran</p>
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                                @php
                                    $sc = match($order->sync_status ?? '') {
                                        'synced'  => 'text-emerald-600',
                                        'failed'  => 'text-rose-500',
                                        default   => 'text-amber-500',
                                    };
                                    $sl = match($order->sync_status ?? '') {
                                        'synced'  => '✓ Pesanan tercatat',
                                        'failed'  => '⚠️ Ada masalah teknis',
                                        default   => '⏳ Sedang diproses',
                                    };
                                @endphp
                                <p class="text-xs font-semibold mt-0.5 {{ $sc }}">{{ $sl }}</p>
                            </div>

                            {{-- Tombol bukti bayar → buka tab baru --}}
                            @if($order->payment_proof_path)
                                <a href="{{ route('payment.proof', $order->id) }}"
                                   target="_blank" rel="noopener"
                                   class="proof-btn bg-white border-2 border-orange-200 text-orange-600 hover:bg-orange-50 hover:border-orange-400">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Lihat Bukti Bayar
                                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @else
                                <span class="proof-btn bg-slate-100 border border-slate-200 text-slate-400 cursor-default">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Belum Ada Bukti
                                </span>
                            @endif
                        </div>
                    </section>

                    {{-- Pending verification banner --}}
                    @if($order->status === 'pending' && $order->payment_method !== 'cod')
                    <div class="flex items-start gap-3 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3.5">
                        <span class="text-lg flex-shrink-0">⏳</span>
                        <div>
                            <p class="text-sm font-bold text-amber-800">Menunggu verifikasi admin</p>
                            <p class="text-xs text-amber-600 mt-0.5 leading-relaxed">Pembayaran sedang dicek. Biasanya 5–15 menit.</p>
                        </div>
                    </div>
                    @elseif($order->status === 'processing')
                    <div class="flex items-start gap-3 rounded-xl bg-sky-50 border border-sky-200 px-4 py-3.5">
                        <span class="text-lg flex-shrink-0">⚙️</span>
                        <div>
                            <p class="text-sm font-bold text-sky-800">Pembayaran terverifikasi!</p>
                            <p class="text-xs text-sky-600 mt-0.5">Pesanan Anda sedang diproses oleh admin.</p>
                        </div>
                    </div>
                    @elseif($order->status === 'delivering')
                    <div class="flex items-start gap-3 rounded-xl bg-violet-50 border border-violet-200 px-4 py-3.5">
                        <span class="text-lg flex-shrink-0">🚚</span>
                        <div>
                            <p class="text-sm font-bold text-violet-800">Pesanan sedang dikirim!</p>
                            <p class="text-xs text-violet-600 mt-0.5">Segera siapkan diri untuk menerima pesanan.</p>
                        </div>
                    </div>
                    @elseif($order->status === 'completed')
                    <div class="flex items-start gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3.5">
                        <span class="text-lg flex-shrink-0">✅</span>
                        <div>
                            <p class="text-sm font-bold text-emerald-800">Pesanan selesai!</p>
                            <p class="text-xs text-emerald-600 mt-0.5">Terima kasih sudah berbelanja di UP Cireng.</p>
                        </div>
                    </div>
                    @endif

                    {{-- ── Action buttons ── --}}
                    <div class="space-y-2">

                        {{-- WA Modal trigger (replaces direct link) --}}
                        <button
                            type="button"
                            onclick="openWaModal('{{ $order->id }}', `{{ addslashes($waMsg) }}`, '{{ $waUrl }}', '{{ $adminUrl }}')"
                            class="group relative w-full overflow-hidden rounded-xl px-4 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:scale-[1.01] hover:shadow-lg">
                            <span class="absolute inset-0 bg-gradient-to-r from-[#25d366] to-[#128c7e] transition-all duration-300 group-hover:from-[#20bf5b] group-hover:to-[#0f7a6e]"></span>
                            <span class="absolute inset-0 -translate-x-full skew-x-[-20deg] bg-white/20 transition-transform duration-700 group-hover:translate-x-[200%]"></span>
                            <span class="relative flex items-center justify-center gap-2">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z"/>
                                </svg>
                                Kirim Pesanan ke WhatsApp
                            </span>
                        </button>

                        @if($order->canBeCancelled())
                        <form action="{{ route('order.cancel', $order) }}" method="POST" class="space-y-2">
                            @csrf @method('PATCH')
                            <input type="text" name="cancel_reason"
                                   placeholder="Alasan pembatalan (opsional)"
                                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-100">
                            <button type="submit"
                                    class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-100 transition-colors">
                                Batalkan Pesanan
                            </button>
                        </form>
                        @endif

                        @if($order->canBeDeletedByCustomer())
                        <form action="{{ route('order.destroy', $order) }}" method="POST"
                              onsubmit="return confirm('Sembunyikan order ini dari riwayat Anda?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-400 hover:text-slate-600 hover:border-slate-300 transition-colors">
                                Hapus dari Riwayat
                            </button>
                        </form>
                        @endif

                    </div>
                </div>
                {{-- end RIGHT --}}

            </div>
            {{-- end grid --}}

        </div>
        </div>
        </div>
        {{-- end panel --}}

    </article>
    @empty
    <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white px-6 py-16 text-center">
        <p class="text-4xl mb-3">🛍️</p>
        <p class="text-slate-500 font-semibold">Anda belum memiliki pesanan.</p>
        <a href="{{ route('home') }}" class="mt-3 inline-block text-sm font-bold text-orange-500 hover:underline">Mulai pesan →</a>
    </div>
    @endforelse
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>

</div>
</div>

{{-- ══════════════════════════════════════════
     WA MODAL (from order show blade, adapted)
     ══════════════════════════════════════════ --}}
<div id="wa-backdrop" class="wa-backdrop" onclick="handleWaBackdrop(event)">
    <div id="wa-modal" class="wa-modal">

        {{-- Header --}}
        <div class="relative flex items-center gap-3 overflow-hidden px-6 py-5">
            <span class="absolute inset-0 bg-gradient-to-r from-[#25d366] to-[#128c7e]"></span>
            <span class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></span>
            <span class="absolute -bottom-4 right-12 h-14 w-14 rounded-full bg-white/10"></span>
            <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/20">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z"/>
                </svg>
            </div>
            <div class="relative">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/70">Pratinjau Pesan</p>
                <h3 class="text-base font-extrabold text-white">Kirim ke WhatsApp</h3>
            </div>
            <button onclick="closeWaModal()"
                    class="relative ml-auto flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Chat preview --}}
        <div class="wa-chat-bg px-5 py-5">
            <div class="wa-bubble relative max-h-52 overflow-y-auto rounded-[1.25rem] rounded-tl-sm bg-white px-4 py-3.5 shadow-md">
                <span class="absolute -left-2 top-0 h-4 w-4 overflow-hidden">
                    <svg viewBox="0 0 10 10" class="absolute -left-1 top-0 h-5 w-5 fill-white drop-shadow-sm"><path d="M0 0 Q10 0 10 10 L0 0Z"/></svg>
                </span>
                <pre id="wa-preview" class="whitespace-pre-wrap font-sans text-[13px] leading-relaxed text-slate-800"></pre>
                <p class="mt-2 text-right text-[10px] text-slate-400">Pesan otomatis · UP Cireng</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-2.5 px-5 pb-6">
            <button onclick="copyWaMsg()" id="wa-copy-btn"
                    class="flex w-full items-center justify-center gap-2.5 rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                <span id="wa-copy-icon">📋</span>
                <span id="wa-copy-text">Salin Pesan</span>
            </button>

            <a id="wa-send-self" href="#" target="_blank" rel="noopener"
               class="flex w-full items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-[#25d366] to-[#128c7e] px-4 py-3 text-sm font-bold text-white shadow-md hover:scale-[1.02] transition-all">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z"/>
                </svg>
                Kirim ke WhatsApp Saya
            </a>

            <button onclick="waSendAdmin()"
                    class="flex w-full items-center justify-center gap-2.5 rounded-xl border-2 border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 hover:bg-emerald-100 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Kirim ke Admin Langsung
            </button>
        </div>
    </div>
</div>

<script>
/* ── State ─────────────────────────────────── */
let _waMsg = '', _waUrl = '', _adminUrl = '';

/* ── Open ─────────────────────────────────── */
function openWaModal(orderId, msg, url, adminUrl) {
    _waMsg     = msg;
    _waUrl     = url;
    _adminUrl  = adminUrl;

    document.getElementById('wa-preview').textContent = msg;
    document.getElementById('wa-send-self').href = url;

    const bd = document.getElementById('wa-backdrop');
    bd.style.display = 'flex';
    requestAnimationFrame(() => bd.classList.add('open'));
    document.body.style.overflow = 'hidden';
}

/* ── Close ─────────────────────────────────── */
function closeWaModal() {
    const bd = document.getElementById('wa-backdrop');
    bd.classList.remove('open');
    bd.classList.add('closing');
    setTimeout(() => {
        bd.style.display = 'none';
        bd.classList.remove('closing');
        document.body.style.overflow = '';
    }, 280);
}

function handleWaBackdrop(e) {
    if (e.target === document.getElementById('wa-backdrop')) closeWaModal();
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeWaModal(); });

/* ── Copy ─────────────────────────────────── */
function copyWaMsg() {
    navigator.clipboard.writeText(_waMsg).then(() => {
        document.getElementById('wa-copy-icon').textContent = '✅';
        document.getElementById('wa-copy-text').textContent = 'Tersalin!';
        setTimeout(() => {
            document.getElementById('wa-copy-icon').textContent = '📋';
            document.getElementById('wa-copy-text').textContent = 'Salin Pesan';
        }, 2000);
    });
}

/* ── Send to admin ─────────────────────────── */
function waSendAdmin() {
    window.open(_adminUrl, '_blank');
    closeWaModal();
}

/* ── Accordion ─────────────────────────────── */
function toggleOrder(id) {
    document.getElementById('order-' + id).classList.toggle('open');
    document.getElementById('chevron-' + id).classList.toggle('open');
}
</script>
@endsection