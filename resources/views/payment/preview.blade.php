@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                📸 Bukti Pembayaran
            </h1>
            <p class="text-slate-600 mt-2">
                Referensi: <span class="font-mono text-sm bg-slate-200 px-2 py-1 rounded">{{ $order->reference }}</span>
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Image Container -->
            <div class="relative bg-slate-900 p-6">
                <div class="rounded-lg overflow-hidden bg-slate-800 aspect-video flex items-center justify-center">
                    <img 
                        id="proofImage"
                        src="{{ $previewUrl }}" 
                        alt="Bukti Pembayaran" 
                        class="w-full h-full object-contain"
                        loading="lazy"
                        onerror="handleImageError(this)"
                    />
                    {{-- Fallback jika gambar gagal load --}}
                    <div id="imageFallback" class="hidden flex-col items-center justify-center text-slate-400 text-center p-8">
                        <svg class="w-16 h-16 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium">Gambar tidak dapat dimuat</p>
                        <a href="{{ route('payment.download', $order->id) }}" class="mt-3 text-xs text-blue-400 hover:underline">
                            Download langsung →
                        </a>
                    </div>
                </div>
                
                <!-- Watermark Info Badge -->
                <div class="mt-4 text-center text-xs text-slate-400">
                    <p>✓ Gambar dilindungi dengan watermark UP CIRENG</p>
                </div>
            </div>

            <!-- Order Details Section -->
            <div class="p-8 space-y-6">
                
                <!-- Customer Info -->
                <div class="border-b border-slate-200 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Nama Pemesan</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">
                                {{ $customerName }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-600 text-sm font-medium">No. Telepon</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">
                                <a href="tel:{{ $order->customer_phone }}" class="text-blue-600 hover:underline">
                                    {{ $order->customer_phone }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border-b border-slate-200 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Metode Pembayaran</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1 capitalize">
                                {{ str_replace('_', ' ', $order->payment_method) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-600 text-sm font-medium">Total Pembayaran</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">
                                {{ $totalPrice }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="bg-gradient-to-r {{ 
                    $status['color'] === 'yellow' ? 'from-yellow-50 to-yellow-100' :
                    ($status['color'] === 'blue' ? 'from-blue-50 to-blue-100' :
                    ($status['color'] === 'indigo' ? 'from-indigo-50 to-indigo-100' :
                    ($status['color'] === 'green' ? 'from-green-50 to-green-100' :
                    ($status['color'] === 'red' ? 'from-red-50 to-red-100' :
                    'from-gray-50 to-gray-100'))))
                }} rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Status Pesanan</p>
                            <p class="text-lg font-semibold text-slate-900 mt-1">
                                {{ $status['icon'] }} {{ $status['label'] }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-600">Tanggal Pesanan</p>
                            <p class="text-sm font-medium text-slate-900 mt-1">
                                {{ $orderDate }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Items Summary -->
                {{-- 
                    FIX: Order->items menggunakan key:
                    - product_name  (bukan 'name')
                    - quantity
                    - unit_price    (bukan 'price')
                    - subtotal
                    - variant (opsional)
                    
                    Gunakan $order->items_summary yang sudah dinormalisasi oleh model,
                    atau fallback ke raw items dengan key yang benar.
                --}}
                @php
                    $itemsSummary = $order->items_summary ?? [];
                @endphp

                @if(count($itemsSummary) > 0)
                <div class="border-t border-slate-200 pt-6">
                    <p class="text-sm font-medium text-slate-600 mb-4">Pesanan Anda:</p>
                    <div class="space-y-3">
                        @foreach($itemsSummary as $item)
                        <div class="flex justify-between items-start bg-slate-50 p-3 rounded-lg">
                            <div class="flex-1">
                                {{-- FIX: key benar adalah 'product_name' --}}
                                <p class="font-medium text-slate-900">
                                    {{ $item['product_name'] ?? 'Produk' }}
                                </p>
                                <p class="text-sm text-slate-600 mt-1">
                                    {{ $item['quantity'] ?? 1 }}x 
                                    @ Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}
                                </p>
                                @if(!empty($item['variant']))
                                <p class="text-xs text-slate-500 mt-1">
                                    <span class="bg-slate-200 px-2 py-0.5 rounded">{{ $item['variant'] }}</span>
                                </p>
                                @endif
                            </div>
                            {{-- FIX: gunakan subtotal jika ada, fallback hitung manual --}}
                            <p class="font-semibold text-slate-900 ml-4 flex-shrink-0">
                                Rp {{ number_format(
                                    $item['subtotal'] ?? (($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1)),
                                    0, ',', '.'
                                ) }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total row --}}
                    <div class="mt-4 pt-3 border-t border-slate-200 flex justify-between items-center">
                        <p class="font-semibold text-slate-700">Total</p>
                        <p class="text-lg font-bold text-emerald-600">{{ $totalPrice }}</p>
                    </div>
                </div>

                @elseif($order->product_name)
                {{-- Fallback: order lama yang tidak punya items JSON, pakai kolom langsung --}}
                <div class="border-t border-slate-200 pt-6">
                    <p class="text-sm font-medium text-slate-600 mb-4">Pesanan Anda:</p>
                    <div class="bg-slate-50 p-3 rounded-lg flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-medium text-slate-900">{{ $order->product_name }}</p>
                            <p class="text-sm text-slate-600 mt-1">
                                {{ $order->quantity }}x 
                                @ Rp {{ number_format($order->price_per_unit, 0, ',', '.') }}
                            </p>
                        </div>
                        <p class="font-semibold text-slate-900 ml-4 flex-shrink-0">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200 flex justify-between items-center">
                        <p class="font-semibold text-slate-700">Total</p>
                        <p class="text-lg font-bold text-emerald-600">{{ $totalPrice }}</p>
                    </div>
                </div>
                @endif

            </div>

            <!-- Action Buttons -->
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-200 flex flex-col md:flex-row gap-3 justify-center">
                <a 
                    href="{{ route('payment.download', $order->id) }}"
                    download
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors"
                >
                    📥 Download Bukti
                </a>
                
                <button 
                    type="button"
                    onclick="sharePaymentProof()"
                    class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors"
                >
                    💬 Bagikan ke WhatsApp
                </button>

                <a 
                    href="{{ route('orders.my') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-lg transition-colors"
                >
                    ← Kembali ke Pesanan
                </a>
            </div>

        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-slate-600 text-sm">
            <p>
                Data pemesanan Anda aman dan dilindungi. 
                <br>
                Jika ada pertanyaan, hubungi kami melalui WhatsApp.
            </p>
        </div>

    </div>
</div>

<script>
// ── Image error handler ────────────────────────────────────────────────────
function handleImageError(img) {
    img.style.display = 'none';
    const fallback = document.getElementById('imageFallback');
    if (fallback) {
        fallback.classList.remove('hidden');
        fallback.classList.add('flex');
    }
}

// ── WhatsApp share ─────────────────────────────────────────────────────────
function sharePaymentProof() {
    const orderRef   = "{{ $order->reference }}";
    const paymentUrl = "{{ route('payment.proof', $order->id) }}";
    const message    = `📸 Bukti Pembayaran\n\nReferensi: ${orderRef}\nTotal: {{ $totalPrice }}\n\nLihat bukti: ${paymentUrl}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
}
</script>

@endsection