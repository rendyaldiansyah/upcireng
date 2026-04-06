@extends('layout.app')

@section('title', 'Detail Pesanan - UP Cireng')

@section('content')
@php $waPhone = preg_replace('/\D+/', '', $storeProfile['phone']); @endphp
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#ffffff_30%,#f8fafc_100%)]">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] bg-white p-8 shadow-xl shadow-brand-500/20">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">{{ $order->reference }}</p>
                    <h1 class="mt-3 text-4xl font-black text-slate-950">{{ $order->summary_title }}</h1>
                    <p class="mt-3 text-sm text-slate-600">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $order->status_color }}">
                        {{ $order->status_label }}
                    </span>
                    <a href="{{ route('orders.my') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                        ← Kembali
                    </a>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-6">
                    <section class="rounded-[1.5rem] bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Rincian Item</p>
                        <div class="mt-4 space-y-3">
                            @foreach($order->items_summary as $item)
                                <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-4 py-3 shadow-sm">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $item['product_name'] }}</p>
                                        <p class="text-sm text-slate-500">
                                            @if($item['variant']){{ $item['variant'] }} · @endif
                                            {{ $item['quantity'] }} item
                                        </p>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-[1.5rem] bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pengiriman</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $order->customer_name }}</p>
                                <p class="text-sm text-slate-600">{{ $order->customer_phone }}</p>
                                <p class="text-sm text-slate-600">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Alamat</p>
                                <p class="text-sm text-slate-600">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                        @if($order->notes)
                            <div class="mt-4 rounded-2xl bg-white px-4 py-3 text-sm text-slate-600 shadow-sm">
                                {{ $order->notes }}
                            </div>
                        @endif
                    </section>
                </div>

                <div class="space-y-6">
                    <section class="rounded-[1.5rem] bg-gradient-to-br from-ink-950 to-brand-700 p-5 text-white">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-200">Pembayaran</p>
                        <p class="mt-4 text-3xl font-black">{{ $order->formatPrice() }}</p>
                        <p class="mt-2 text-sm text-white/80">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                        @if($order->payment_proof_url)
                            <a href="{{ $order->payment_proof_url }}" target="_blank" rel="noopener" class="mt-4 inline-flex rounded-full bg-white px-4 py-2 text-sm font-semibold text-ink-950 transition duration-300 hover:scale-105 hover:shadow-lg">
                                Lihat Bukti Pembayaran
                            </a>
                        @endif
                    </section>

                    <section class="rounded-[1.5rem] border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Status Sinkronisasi</p>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-lg font-bold text-slate-950">{{ $order->sync_status_label }}</p>
                            @if($order->can_retry_sync)
                                <form action="{{ route('order.retry-sync', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-blue-100 text-xs font-bold text-blue-600 hover:bg-blue-200 transition">
                                        Coba Lagi
                                    </button>
                                </form>
                            @endif
                        </div>
                        @if($order->sync_error_label)
                            <div class="mt-3 rounded-lg bg-amber-50 border border-amber-200 px-3 py-2">
                                <p class="text-xs text-amber-700">{{ $order->sync_error_label }}</p>
                            </div>
                        @endif
                    </section>

                    {{-- Tombol buka modal WA --}}
                    <button
                        onclick="openWaModal()"
                        class="wa-trigger-btn group relative w-full overflow-hidden rounded-2xl px-6 py-4 text-sm font-bold text-white shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-xl"
                    >
                        {{-- Gradient background --}}
                        <span class="absolute inset-0 bg-gradient-to-r from-[#25d366] to-[#128c7e] transition-all duration-300 group-hover:from-[#20bf5b] group-hover:to-[#0f7a6e]"></span>
                        {{-- Shine sweep --}}
                        <span class="wa-shine absolute inset-0 -translate-x-full skew-x-[-20deg] bg-white/20 transition-transform duration-700 group-hover:translate-x-[200%]"></span>
                        <span class="relative flex items-center justify-center gap-3">
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z"/>
                            </svg>
                            Kirim Pesanan ke WhatsApp
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL WHATSAPP ===================== --}}
<div
    id="waModalBackdrop"
    class="wa-modal-backdrop fixed inset-0 z-[100] flex items-center justify-center px-4"
    style="display:none"
    onclick="handleBackdropClick(event)"
>
    <div id="waModal" class="wa-modal relative w-full max-w-md overflow-hidden rounded-[1.75rem] bg-white shadow-2xl">

        {{-- Header --}}
        <div class="wa-modal-header relative flex items-center gap-3 overflow-hidden px-6 py-5">
            {{-- BG gradient --}}
            <span class="absolute inset-0 bg-gradient-to-r from-[#25d366] to-[#128c7e]"></span>
            {{-- Decorative circle --}}
            <span class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></span>
            <span class="absolute -bottom-4 right-12 h-14 w-14 rounded-full bg-white/10"></span>

            <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z"/>
                </svg>
            </div>
            <div class="relative">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/70">Pratinjau Pesan</p>
                <h3 class="text-base font-extrabold text-white">Kirim ke WhatsApp</h3>
            </div>
            <button
                onclick="closeWaModal()"
                class="relative ml-auto flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/30"
                aria-label="Tutup"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Preview bubble (chat style) --}}
        <div class="wa-chat-bg px-5 py-5">
            <div class="wa-bubble relative max-h-56 overflow-y-auto rounded-[1.25rem] rounded-tl-sm bg-white px-4 py-3.5 shadow-md">
                {{-- WA tail --}}
                <span class="absolute -left-2 top-0 h-4 w-4 overflow-hidden">
                    <svg viewBox="0 0 10 10" class="absolute -left-1 top-0 h-5 w-5 fill-white drop-shadow-sm">
                        <path d="M0 0 Q10 0 10 10 L0 0Z"/>
                    </svg>
                </span>
                <pre id="waMessagePreview" class="whitespace-pre-wrap font-sans text-[13px] leading-relaxed text-slate-800"></pre>
                <p class="mt-2 text-right text-[10px] text-slate-400">Pesan otomatis · UP Cireng</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-2.5 px-5 pb-6">
            {{-- Copy --}}
            <button
                onclick="copyWaMessage()"
                id="copyBtn"
                class="group flex w-full items-center justify-center gap-2.5 rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition-all duration-200 hover:border-slate-300 hover:bg-slate-50 active:scale-[0.98]"
            >
                <span id="copyIcon" class="text-base transition-transform duration-200 group-hover:scale-110">📋</span>
                <span id="copyText">Salin Pesan</span>
            </button>

            {{-- Kirim ke sendiri --}}
            <a
                id="waSendBtn"
                href="#"
                target="_blank"
                rel="noopener"
                class="group flex w-full items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-[#25d366] to-[#128c7e] px-4 py-3 text-sm font-bold text-white shadow-md shadow-[#25d366]/30 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg active:scale-[0.98]"
            >
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z"/>
                </svg>
                Kirim ke WhatsApp Saya
            </a>

            {{-- Kirim ke admin --}}
            <button
                onclick="sendToAdmin()"
                class="group flex w-full items-center justify-center gap-2.5 rounded-xl border-2 border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 transition-all duration-200 hover:border-emerald-300 hover:bg-emerald-100 active:scale-[0.98]"
            >
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Kirim ke Admin Langsung
            </button>
        </div>
    </div>
</div>

<script>
// ── Data dari Laravel ──────────────────────────────────────────────────────
const WA_MESSAGE  = `{{ addslashes($whatsappMessage) }}`;
const WA_SEND_URL = '{{ $whatsappUrl }}';
const WA_ADMIN_URL = '{{ $adminWaUrl }}';

// ── Open / Close ───────────────────────────────────────────────────────────
function openWaModal() {
    document.getElementById('waMessagePreview').textContent = WA_MESSAGE;
    document.getElementById('waSendBtn').href = WA_SEND_URL;

    const backdrop = document.getElementById('waModalBackdrop');
    backdrop.style.display = 'flex';
    requestAnimationFrame(() => {
        backdrop.classList.add('wa-modal-open');
    });
    document.body.style.overflow = 'hidden';
}

function closeWaModal() {
    const backdrop = document.getElementById('waModalBackdrop');
    backdrop.classList.remove('wa-modal-open');
    backdrop.classList.add('wa-modal-closing');
    setTimeout(() => {
        backdrop.style.display = 'none';
        backdrop.classList.remove('wa-modal-closing');
        document.body.style.overflow = '';
    }, 280);
}

function handleBackdropClick(e) {
    if (e.target === document.getElementById('waModalBackdrop')) {
        closeWaModal();
    }
}

// Tutup dengan Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeWaModal();
});

// ── Copy ───────────────────────────────────────────────────────────────────
function copyWaMessage() {
    navigator.clipboard.writeText(WA_MESSAGE).then(() => {
        const btn  = document.getElementById('copyBtn');
        const icon = document.getElementById('copyIcon');
        const text = document.getElementById('copyText');

        icon.textContent = '✅';
        text.textContent = 'Tersalin!';
        btn.classList.add('border-emerald-300', 'bg-emerald-50', 'text-emerald-700');
        btn.classList.remove('border-slate-200', 'text-slate-700');

        setTimeout(() => {
            icon.textContent = '📋';
            text.textContent = 'Salin Pesan';
            btn.classList.remove('border-emerald-300', 'bg-emerald-50', 'text-emerald-700');
            btn.classList.add('border-slate-200', 'text-slate-700');
        }, 2000);

        if (typeof toast === 'function') toast('Pesan disalin!', 'success');
    }).catch(() => {
        if (typeof toast === 'function') toast('Gagal copy', 'error');
    });
}

// ── Kirim ke admin ─────────────────────────────────────────────────────────
function sendToAdmin() {
    window.open(WA_ADMIN_URL, '_blank');
    if (typeof toast === 'function') toast('Membuka WhatsApp admin…', 'success');
    closeWaModal();
}
</script>

<style>
    /* ── Backdrop ── */
    .wa-modal-backdrop {
        background: rgba(15, 23, 42, 0);
        backdrop-filter: blur(0px);
        transition: background 0.28s ease, backdrop-filter 0.28s ease;
    }
    .wa-modal-backdrop.wa-modal-open {
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(6px);
    }
    .wa-modal-backdrop.wa-modal-closing {
        background: rgba(15, 23, 42, 0);
        backdrop-filter: blur(0px);
    }

    /* ── Modal slide-up ── */
    .wa-modal {
        transform: translateY(40px) scale(0.97);
        opacity: 0;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                    opacity  0.25s ease;
    }
    .wa-modal-open .wa-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
    .wa-modal-closing .wa-modal {
        transform: translateY(24px) scale(0.97);
        opacity: 0;
        transition: transform 0.22s ease-in, opacity 0.22s ease-in;
    }

    /* ── WhatsApp chat bubble background ── */
    .wa-chat-bg {
        background-color: #e5ddd5;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c8bfb8' fill-opacity='0.25'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* ── Bubble scroll ── */
    .wa-bubble::-webkit-scrollbar { width: 4px; }
    .wa-bubble::-webkit-scrollbar-track { background: transparent; }
    .wa-bubble::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

    /* ── Trigger button shine ── */
    .wa-trigger-btn .wa-shine { pointer-events: none; }
</style>
@endsection