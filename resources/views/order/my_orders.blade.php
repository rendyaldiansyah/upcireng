@extends('layout.app')

@section('title', 'Pesanan Saya - UP Cireng')

@section('content')
@php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); @endphp

<style>
    .proof-modal        { display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.75); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
    .proof-modal.open   { display:flex; }
    .proof-modal img    { max-width:min(90vw,480px); max-height:85vh; border-radius:1rem; box-shadow:0 25px 60px rgba(0,0,0,.5); object-fit:contain; }
    .order-panel        { display:grid; grid-template-rows:0fr; transition:grid-template-rows .3s ease; }
    .order-panel.open   { grid-template-rows:1fr; }
    .order-panel > div  { overflow:hidden; }
    .chevron            { transition:transform .3s ease; }
    .chevron.open       { transform:rotate(180deg); }
    .status-badge       { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .75rem; border-radius:9999px; font-size:.7rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; }
</style>

<div class="min-h-screen bg-gradient-to-b from-orange-50 via-white to-slate-50">
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6">

        {{-- ── Header ── --}}
        <div class="mb-6 rounded-2xl bg-white p-6 shadow-lg shadow-orange-100 border border-orange-100">
            <p class="text-[10px] font-extrabold uppercase tracking-[.3em] text-orange-500 mb-1">Pesanan Saya</p>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">Riwayat Pesanan</h1>
            <p class="mt-1.5 text-sm text-slate-500 leading-relaxed">
                Status diperbarui langsung dari admin. Ketuk kartu untuk melihat detail.
            </p>
            <div class="mt-4 flex flex-wrap gap-2.5">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-orange-300 hover:text-orange-600 transition-colors">
                    ← Kembali ke Toko
                </a>
                <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition-colors shadow-sm">
                    💬 Hubungi Admin
                </a>
            </div>
        </div>

        {{-- ── Orders List ── --}}
        <div class="space-y-2.5">
            @forelse($orders as $order)
            @php
                $statusMap = [
                    'pending'    => ['dot' => 'bg-amber-400',   'badge' => 'bg-amber-50 text-amber-700 border border-amber-200',   'icon' => '⏳'],
                    'processing' => ['dot' => 'bg-sky-400',     'badge' => 'bg-sky-50 text-sky-700 border border-sky-200',         'icon' => '🔄'],
                    'delivering' => ['dot' => 'bg-violet-400',  'badge' => 'bg-violet-50 text-violet-700 border border-violet-200','icon' => '🚚'],
                    'completed'  => ['dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200','icon' => '✅'],
                    'cancelled'  => ['dot' => 'bg-rose-400',    'badge' => 'bg-rose-50 text-rose-700 border border-rose-200',       'icon' => '❌'],
                ];
                $s = $statusMap[$order->status] ?? ['dot'=>'bg-slate-400','badge'=>'bg-slate-50 text-slate-600 border border-slate-200','icon'=>'·'];
            @endphp

            <article class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">

                {{-- Card header --}}
                <button type="button" onclick="toggleOrder({{ $order->id }})"
                        class="w-full text-left px-4 py-3.5 flex items-center gap-3 hover:bg-slate-50/70 transition-colors group">

                    {{-- Status dot --}}
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $s['dot'] }} ring-4 ring-offset-0"
                          style="box-shadow:0 0 0 3px {{ str_contains($s['dot'],'amber')?'#fef3c7':(str_contains($s['dot'],'sky')?'#e0f2fe':(str_contains($s['dot'],'violet')?'#ede9fe':(str_contains($s['dot'],'emerald')?'#d1fae5':'#ffe4e6'))) }}"></span>

                    {{-- Product + ref --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate leading-snug">{{ $order->summary_title }}</p>
                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $order->reference }}</p>
                    </div>

                    {{-- Price + status + date --}}
                    <div class="flex-shrink-0 text-right flex flex-col items-end gap-1">
                        <p class="text-sm font-black text-slate-900">{{ $order->formatPrice() }}</p>
                        <span class="status-badge {{ $s['badge'] }}">{{ $s['icon'] }} {{ $order->status_label }}</span>
                        <p class="text-[10px] text-slate-400">{{ $order->created_at->translatedFormat('d M Y') }}</p>
                    </div>

                    {{-- Chevron --}}
                    <svg id="chevron-{{ $order->id }}" class="chevron w-4 h-4 text-slate-300 group-hover:text-slate-500 flex-shrink-0 ml-1"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- ── Expandable detail panel ── --}}
                <div class="order-panel" id="order-{{ $order->id }}">
                    <div>
                        <div class="border-t border-slate-100 px-4 py-4 space-y-4">

                            {{-- Item breakdown --}}
                            <section>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Rincian Item</p>
                                <div class="divide-y divide-slate-50">
                                    @foreach($order->items_summary as $item)
                                    <div class="flex items-center justify-between gap-3 py-1.5 text-sm">
                                        <span class="text-slate-700 leading-snug">
                                            {{ $item['product_name'] }}
                                            @if($item['variant'])
                                                <span class="text-slate-400 text-xs"> · {{ $item['variant'] }}</span>
                                            @endif
                                            <span class="text-slate-400 text-xs"> ×{{ $item['quantity'] }}</span>
                                        </span>
                                        <span class="font-semibold text-slate-800 flex-shrink-0 text-sm">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-between items-center pt-2.5 mt-1 border-t-2 border-slate-100">
                                    <span class="text-sm font-black text-slate-900">Total</span>
                                    <span class="text-base font-black text-orange-600">{{ $order->formatPrice() }}</span>
                                </div>
                            </section>

                            {{-- Shipping info --}}
                            <section class="rounded-xl bg-slate-50 px-4 py-3 space-y-1.5">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Info Pengiriman</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-base">👤</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 leading-snug">{{ $order->customer_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $order->customer_phone }}</p>
                                        @if($order->customer_email)
                                            <p class="text-xs text-slate-400">{{ $order->customer_email }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($order->delivery_address)
                                <div class="flex items-start gap-2 pt-1">
                                    <span class="text-base flex-shrink-0">📍</span>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $order->delivery_address }}</p>
                                </div>
                                @endif
                            </section>

                            {{-- Payment info --}}
                            <section class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">Pembayaran</p>
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
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
                                        <p class="text-xs font-semibold mt-0.5 {{ $syncColor }}">{{ $syncLabel }}</p>
                                    </div>

                                    {{-- ── Bukti Pembayaran Button ── --}}
                                    @if($order->payment_proof_path)
                                        <button
                                            type="button"
                                            onclick="openProof('{{ asset('storage/' . $order->payment_proof_path) }}')"
                                            class="inline-flex items-center gap-2 rounded-xl bg-white border-2 border-orange-200 px-4 py-2.5 text-sm font-bold text-orange-600 hover:bg-orange-50 hover:border-orange-400 transition-all shadow-sm active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Lihat Bukti Bayar
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Belum Ada Bukti
                                        </span>
                                    @endif
                                </div>
                            </section>

                            {{-- Pending verification banner --}}
                            @if($order->status === 'pending' && $order->payment_method !== 'cod')
                            <div class="flex items-start gap-3 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3">
                                <span class="text-lg flex-shrink-0">⏳</span>
                                <div>
                                    <p class="text-sm font-bold text-amber-800 leading-snug">Menunggu verifikasi admin</p>
                                    <p class="text-xs text-amber-600 mt-0.5 leading-relaxed">Pembayaran Anda sedang dicek. Biasanya 5–15 menit.</p>
                                </div>
                            </div>
                            @endif

                            {{-- Notes --}}
                            @if($order->notes)
                            <section class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Catatan</p>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $order->notes }}</p>
                            </section>
                            @endif

                            {{-- Action buttons --}}
                            <div class="space-y-2 pt-1">

                                @php
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
                                @endphp

                                <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMsg) }}" target="_blank"
                                   class="flex items-center justify-center gap-2 w-full rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition-colors shadow-sm">
                                    💬 Kirim ke WhatsApp
                                </a>

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
                    </div>
                </div>
            </article>
            @empty
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white px-6 py-16 text-center">
                <p class="text-3xl mb-3">🛍️</p>
                <p class="text-slate-500 font-semibold">Anda belum memiliki pesanan.</p>
                <a href="{{ route('home') }}" class="mt-3 inline-block text-sm font-bold text-orange-500 hover:underline">
                    Mulai pesan →
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">{{ $orders->links() }}</div>

    </div>
</div>

{{-- ── Proof modal ── --}}
<div id="proof-modal" class="proof-modal" onclick="closeProof()">
    <div onclick="event.stopPropagation()" class="flex flex-col items-center gap-3">
        <div class="flex items-center justify-between w-full max-w-sm px-1">
            <p class="text-white text-sm font-semibold opacity-80">Bukti Pembayaran</p>
            <button onclick="closeProof()"
                    class="text-white/70 hover:text-white transition p-1.5 rounded-full hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <img id="proof-img" src="" alt="Bukti Pembayaran">
        <a id="proof-link" href="" target="_blank"
           class="inline-flex items-center gap-2 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 px-5 py-2 text-sm font-semibold text-white transition">
            ↗ Buka di tab baru
        </a>
    </div>
</div>

<script>
/* Toggle order panel */
function toggleOrder(id) {
    const panel   = document.getElementById('order-' + id);
    const chevron = document.getElementById('chevron-' + id);
    const isOpen  = panel.classList.contains('open');
    panel.classList.toggle('open', !isOpen);
    chevron.classList.toggle('open', !isOpen);
}

/* Payment proof modal */
function openProof(url) {
    document.getElementById('proof-img').src = url;
    document.getElementById('proof-link').href = url;
    document.getElementById('proof-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeProof() {
    document.getElementById('proof-modal').classList.remove('open');
    document.body.style.overflow = '';
    setTimeout(() => { document.getElementById('proof-img').src = ''; }, 300);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeProof(); });
</script>
@endsection