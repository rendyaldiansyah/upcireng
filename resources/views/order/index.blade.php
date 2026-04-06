@extends('layout.app')

@section('title', 'UP Cireng | Order Modern')

@section('content')
@php
    $isOpen            = $storeOpen ?? false;
    $waPhone           = preg_replace('/\D+/', '', $storeProfile['phone']);
    $featuredImage     = $products->first()?->image_url ?? asset('assets/assets/logo.png');
    $availableProducts = $products->filter(fn ($p) => $p->isAvailable())->count();
    $storefrontState   = [
        'user' => $user ? [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ] : null,
        'storeOpen' => $isOpen,
        'hours'     => $hours,
        'routes'    => [
            'login'      => route('login'),
            'orderStore' => route('order.store'),
            'ordersMy'   => route('orders.my'),
        ],
        'products' => $products->map(fn ($product) => [
            'id'           => $product->id,
            'name'         => $product->name,
            'description'  => $product->description,
            'price'        => (float) $product->price,
            'image_url'    => $product->image_url,
            'available'    => $product->isAvailable(),
            'status'       => $product->status,
            'stock_status' => $product->stock_status,
            'is_open'      => $product->is_open,
            'variants'     => $product->availableVariants(),
        ])->values(),
    ];
@endphp

<div id="storefrontApp" class="pb-10 pt-6 sm:pt-10 lg:pt-12">

    {{-- ===================== HERO ===================== --}}
    <section class="relative mx-auto mb-14 max-w-7xl px-4 sm:px-6 lg:mb-20 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:gap-12">

            {{-- Hero Content --}}
            <div>
                {{-- Store status badge --}}
                <div class="mb-5 inline-flex items-center gap-2.5 rounded-full border border-brand-200 bg-brand-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.23em] text-brand-600 animate-slideInUp sm:mb-6 sm:gap-3 sm:py-2.5" style="animation-delay:0ms">
                    <span class="h-2 w-2 rounded-full sm:h-2.5 sm:w-2.5 {{ $isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    {{ $isOpen ? 'TOKO BUKA' : 'TOKO TUTUP' }}
                </div>

                <h1 class="display-font mb-5 text-3xl font-extrabold leading-tight text-ink-950 animate-slideInUp sm:mb-6 sm:text-4xl lg:text-5xl xl:text-6xl" style="animation-delay:100ms">
                    Pesan cireng favorit dengan mudah
                </h1>

                <p class="mb-7 max-w-2xl text-sm leading-relaxed text-slate-600 animate-slideInUp sm:mb-8 sm:text-base sm:leading-relaxed lg:text-lg lg:leading-8" style="animation-delay:200ms">
                    Sistem order modern dengan fitur checkout yang cepat. Pesanan langsung masuk ke admin kami, dan kamu bisa cek status kapan saja.
                </p>

                {{-- CTA Buttons --}}
                <div class="mb-8 flex flex-wrap gap-3 animate-slideInUp sm:mb-10" style="animation-delay:300ms">
                    <a href="#menu"
                       class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-ink-950 to-brand-600 px-6 py-3 text-sm font-bold text-white shadow-lg transition duration-300 hover:scale-105 hover:shadow-xl sm:px-7 sm:py-3.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Lihat Menu
                    </a>
                    <a href="https://wa.me/{{ $waPhone }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition duration-300 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600 sm:px-7 sm:py-3.5">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z" />
                        </svg>
                        WhatsApp Kami
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-2 animate-fadeIn sm:gap-3 sm:gap-4" style="animation-delay:400ms">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 sm:rounded-2xl sm:p-4 lg:p-5">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:text-xs">Operasional</p>
                        <p class="text-base font-extrabold text-ink-950 sm:text-xl lg:text-2xl">{{ $hours['start'] }}-{{ $hours['end'] }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 sm:rounded-2xl sm:p-4 lg:p-5">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:text-xs">Produk</p>
                        <p class="text-base font-extrabold text-ink-950 sm:text-xl lg:text-2xl">{{ $availableProducts }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 sm:rounded-2xl sm:p-4 lg:p-5">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:text-xs">Status</p>
                        <p class="text-base font-extrabold sm:text-xl lg:text-2xl {{ $isOpen ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $isOpen ? 'BUKA' : 'TUTUP' }}
                        </p>
                    </div>
                </div>

                @unless($isOpen)
                    <div class="mt-6 flex items-start gap-3 rounded-2xl border-2 border-rose-200 bg-rose-50 px-4 py-4 sm:mt-8 sm:px-5">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-2-4a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2h4a2 2 0 002-2V1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-rose-700">Toko sedang tutup</p>
                            <p class="mt-1 text-sm text-rose-600">Buka kembali pukul {{ $hours['start'] }} WIB. Checkout tersedia saat toko buka.</p>
                        </div>
                    </div>
                @endunless
            </div>

            {{-- Hero Image Card --}}
            <div class="relative animate-fadeIn" style="animation-delay:200ms">
                <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-brand-500 to-ink-950 p-5 shadow-2xl sm:rounded-3xl sm:p-6">
                    <div class="absolute inset-0 bg-mesh opacity-30"></div>
                    <div class="relative">
                        <div class="mb-4">
                            <p class="mb-1.5 text-xs font-bold uppercase tracking-wider text-brand-200 sm:text-sm">
                                Tentang {{ $storeProfile['name'] }}
                            </p>
                            <h2 class="display-font text-xl font-extrabold text-white sm:text-2xl lg:text-3xl">
                                {{ $storeProfile['name'] }}
                            </h2>
                            <p class="mt-2 text-xs leading-relaxed text-white/80 sm:text-sm sm:leading-relaxed sm:mt-3">
                                Menyediakan cireng segar dengan sistem order yang modern dan mudah. Semua pesanan langsung terkelola dengan baik.
                            </p>
                        </div>

                        <div class="mb-4 overflow-hidden rounded-xl border-4 border-white/20 sm:mb-5 sm:rounded-2xl">
                            <img src="{{ $featuredImage }}" alt="Produk unggulan"
                                 class="h-48 w-full object-cover sm:h-64">
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-white/90 sm:gap-3">
                            <div class="rounded-lg bg-white/10 p-2.5 backdrop-blur-sm sm:p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider opacity-75">Telepon</p>
                                <p class="mt-1 text-xs font-bold sm:text-sm lg:text-base">{{ $storeProfile['phone'] }}</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-2.5 backdrop-blur-sm sm:p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider opacity-75">Email</p>
                                <p class="mt-1 truncate text-xs font-bold sm:text-sm lg:text-base">{{ $storeProfile['email'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== PRODUCTS ===================== --}}
    <section id="menu" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 sm:mt-20 lg:px-8">

        <div class="mb-8 animate-slideInUp sm:mb-12">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:text-sm">Menu Pilihan</p>
            <h2 class="display-font mb-3 text-2xl font-extrabold text-ink-950 sm:mb-4 sm:text-3xl lg:text-4xl">
                Pilih produk favorit mu
            </h2>
            <p class="max-w-2xl text-sm text-slate-600 sm:text-base">
                Semua produk dibuat segar dan siap dikirim ke alamat kamu. Pesan sekarang dan nikmati!
            </p>
        </div>

        {{-- Toolbar --}}
        <div class="mb-6 flex flex-col gap-3 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-600">{{ $products->count() }} produk tersedia</p>

            <button
                id="openCartButton"
                type="button"
                class="inline-flex w-full items-center justify-center gap-3 rounded-full px-6 py-3 text-sm font-bold transition duration-300 sm:w-auto
                       {{ $isOpen ? 'bg-gradient-to-r from-ink-950 to-brand-600 text-white hover:scale-105 hover:shadow-lg' : 'cursor-not-allowed bg-slate-200 text-slate-500' }}"
                {{ $isOpen ? '' : 'disabled' }}
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Keranjang</span>
                <span id="cartCountBadge" class="inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-white/20 px-2 py-0.5 text-xs font-bold">0</span>
            </button>
        </div>

        {{-- Product Grid --}}
        <div id="productGrid" class="grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($products as $product)
                @php
                    $productVariants = $product->availableVariants();
                    $productEnabled  = $isOpen && $product->isAvailable();
                    $productStatus   = !$isOpen
                        ? 'TOKO TUTUP'
                        : ($product->stock_status === 'out_of_stock'
                            ? 'Habis'
                            : ((!$product->is_open || $product->status !== 'active') ? 'Tutup' : 'Siap'));
                @endphp
                <article
                    data-product-card
                    data-delay="{{ $loop->index * 50 }}"
                    class="product-card group overflow-hidden rounded-2xl border border-white/80 bg-white shadow-md animate-fadeIn"
                >
                    {{-- Image --}}
                    <div class="relative h-44 overflow-hidden bg-slate-100 sm:h-52 lg:h-56">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="product-card__img h-full w-full object-cover">
                        <div class="product-card__overlay absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                        <div class="absolute inset-x-3 top-3 flex items-center justify-between gap-2">
                            <span class="rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-700 shadow-sm">
                                {{ $productStatus }}
                            </span>
                            <span class="rounded-full bg-brand-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm">
                                Live
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 sm:p-5">
                        <h3 class="display-font line-clamp-1 text-base font-bold text-ink-950 sm:text-lg lg:text-xl">
                            {{ $product->name }}
                        </h3>
                        <p class="mt-1 line-clamp-2 text-xs text-slate-600 sm:text-sm">
                            {{ $product->description ?: 'Cireng berkualitas UP Cireng' }}
                        </p>

                        {{-- Price --}}
                        <div class="mt-3 flex items-baseline justify-between sm:mt-4">
                            <div>
                                <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Harga</p>
                                <p class="text-xl font-extrabold text-ink-950 sm:text-2xl lg:text-3xl">
                                    {{ $product->formatPrice() }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-brand-500">Stock</p>
                                <div class="inline-flex items-center justify-center rounded-lg bg-brand-50 px-2 py-1">
                                    <p class="text-xs font-bold text-brand-600">{{ $productEnabled ? '✓ Ready' : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Variant & Qty --}}
                        <div class="mt-3 space-y-2 sm:mt-4 sm:space-y-2.5">
                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:mb-1.5">Pilih Varian</label>
                                <select
                                    data-product-variant
                                    class="product-field w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 outline-none sm:text-sm"
                                    {{ $productEnabled ? '' : 'disabled' }}
                                >
                                    @forelse($productVariants as $variant)
                                        <option value="{{ $variant }}">{{ $variant }}</option>
                                    @empty
                                        <option value="Regular">Regular</option>
                                    @endforelse
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:mb-1.5">Jumlah</label>
                                <input
                                    data-product-qty
                                    type="number"
                                    min="1"
                                    value="1"
                                    class="product-field w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 outline-none sm:text-sm"
                                    {{ $productEnabled ? '' : 'disabled' }}
                                >
                            </div>
                        </div>

                        {{-- Add to Cart --}}
                        <button
                            type="button"
                            data-add-product="{{ $product->id }}"
                            class="product-btn mt-4 w-full rounded-lg px-4 py-2.5 text-xs font-bold sm:text-sm
                                   {{ $productEnabled ? 'bg-gradient-to-r from-ink-950 to-brand-600 text-white' : 'cursor-not-allowed bg-slate-200 text-slate-500' }}"
                            {{ $productEnabled ? '' : 'disabled' }}
                        >
                            {{ $productEnabled ? '➕ Tambah ke Keranjang' : $productStatus }}
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ===================== TESTIMONIALS ===================== --}}
    <section id="testimoni" class="mx-auto mt-20 max-w-7xl px-4 sm:px-6 sm:mt-24 lg:px-8">

        <div class="mb-8 animate-slideInUp sm:mb-12">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:text-sm">Kepuasan Pelanggan</p>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="display-font mb-2 text-2xl font-extrabold text-ink-950 sm:mb-3 sm:text-3xl lg:text-4xl">
                        Apa kata pelanggan kami
                    </h2>
                    <p class="max-w-2xl text-sm text-slate-600 sm:text-base">
                        Testimoni asli dari pelanggan setia yang puas dengan produk dan layanan kami
                    </p>
                </div>
                <a href="{{ route('testimonial.index') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition duration-300 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600 sm:w-auto sm:px-6 sm:py-3">
                    Lihat Semua Testimoni
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($testimonials as $testimonial)
                <article
                    data-delay="{{ $loop->index * 50 }}"
                    class="rounded-2xl border border-white/80 bg-white p-5 shadow-md transition duration-300 hover:shadow-lg animate-fadeIn sm:p-6"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex gap-1">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 {{ $i < (int)$testimonial->rating_stars ? 'fill-current text-yellow-400' : 'text-slate-300' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-bold text-slate-400">{{ $testimonial->created_at->translatedFormat('d M Y') }}</span>
                    </div>

                    <h3 class="display-font mb-2 text-base font-bold text-ink-950 sm:text-lg">
                        {{ $testimonial->customer_name }}
                    </h3>
                    <p class="line-clamp-3 text-sm leading-relaxed text-slate-600">
                        "{{ $testimonial->message }}"
                    </p>
                    <a href="{{ route('testimonial.index') }}"
                       class="mt-3 inline-flex items-center text-xs font-bold text-brand-500 hover:text-brand-600">
                        Baca selengkapnya →
                    </a>
                </article>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-gradient-to-br from-slate-50 to-white px-6 py-14 text-center sm:col-span-2 sm:py-16 lg:col-span-3">
                    <svg class="mx-auto mb-3 h-10 w-10 text-slate-400 sm:h-12 sm:w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-500">Belum ada testimoni</p>
                    <p class="mt-1 text-xs text-slate-400">Jadilah yang pertama memberikan testimoni</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===================== CONTACT ===================== --}}
    <section id="kontak" class="mx-auto mb-16 mt-20 max-w-7xl px-4 sm:px-6 sm:mb-20 sm:mt-24 lg:px-8">
        <div class="grid gap-5 sm:gap-6 lg:grid-cols-2">

            {{-- Main Contact Card --}}
            <div class="rounded-2xl bg-gradient-to-br from-ink-950 to-brand-700 p-7 text-white shadow-xl animate-slideInUp sm:rounded-3xl sm:p-10">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-200 sm:mb-3 sm:text-sm">Butuh Bantuan?</p>
                <h2 class="display-font mb-3 text-2xl font-extrabold sm:mb-4 sm:text-3xl lg:text-4xl">Hubungi Kami</h2>
                <p class="mb-7 text-sm leading-relaxed text-white/90 sm:mb-8 sm:text-base sm:leading-relaxed">
                    Ada pertanyaan tentang produk? Ingin komplain atau repeat order? Tim kami siap membantu 24/7 melalui WhatsApp atau email.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-ink-950 transition duration-300 hover:bg-white/90 sm:px-6 sm:py-3">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z" />
                        </svg>
                        WhatsApp
                    </a>
                    <a href="mailto:{{ $storeProfile['email'] }}"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-white px-5 py-2.5 text-sm font-bold text-white transition duration-300 hover:bg-white/10 sm:px-6 sm:py-3">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        Email
                    </a>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="space-y-4 animate-fadeIn sm:space-y-4" style="animation-delay:200ms">
                @php
                    $infoCards = [
                        [
                            'label' => 'Lokasi',
                            'value' => $storeProfile['address'],
                            'sub'   => null,
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        ],
                        [
                            'label' => 'Jam Buka',
                            'value' => $hours['start'] . ' - ' . $hours['end'] . ' WIB',
                            'sub'   => 'Senin - Minggu',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        [
                            'label' => 'Media Sosial',
                            'value' => $storeProfile['instagram'] ?? 'Follow kami di Instagram',
                            'sub'   => null,
                            'icon'  => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>',
                        ],
                    ];
                @endphp

                @foreach($infoCards as $card)
                    <div class="rounded-2xl border border-white/80 bg-white p-5 shadow-md transition hover:shadow-lg sm:p-6">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="shrink-0 rounded-lg bg-brand-50 p-2.5 sm:p-3">
                                <svg class="h-5 w-5 text-brand-600 sm:h-6 sm:w-6" fill="{{ str_contains($card['icon'], 'stroke-linecap') ? 'none' : 'currentColor' }}" stroke="{{ str_contains($card['icon'], 'stroke-linecap') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24">
                                    {!! $card['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <p class="mb-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:mb-1 sm:text-xs">
                                    {{ $card['label'] }}
                                </p>
                                <p class="text-sm font-semibold leading-relaxed text-slate-700">{{ $card['value'] }}</p>
                                @if($card['sub'])
                                    <p class="mt-1 text-xs text-slate-500">{{ $card['sub'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== CART DRAWER ===================== --}}
    <aside id="cartDrawer"
           class="fixed right-0 top-0 z-[90] flex h-full w-full translate-x-full flex-col border-l border-white/70 bg-white shadow-panel transition duration-300 max-w-sm sm:max-w-md lg:max-w-lg">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6 sm:py-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-500">Cart</p>
                <h2 class="display-font mt-1 text-xl font-extrabold text-ink-950 sm:mt-2 sm:text-2xl">Keranjang Pesanan</h2>
            </div>
            <button id="closeCartButton" type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-base font-black text-slate-600 transition hover:border-brand-200 hover:text-brand-600 sm:h-11 sm:w-11 sm:rounded-2xl sm:text-lg">
                ✕
            </button>
        </div>
        <div id="cartItems" class="flex-1 space-y-4 overflow-y-auto px-5 py-5 sm:px-6 sm:py-6"></div>
        <div class="border-t border-slate-100 px-5 py-4 sm:px-6 sm:py-5">
            <div class="mb-4 flex items-center justify-between text-sm font-bold text-slate-500">
                <span>Total</span>
                <span id="cartTotal" class="text-lg font-extrabold text-ink-950 sm:text-xl">Rp 0</span>
            </div>
            <button
                id="checkoutButton"
                type="button"
                class="w-full rounded-xl py-3 text-sm font-bold transition sm:rounded-2xl sm:py-3
                       {{ $isOpen ? 'bg-ink-950 text-white hover:bg-brand-500' : 'cursor-not-allowed bg-slate-200 text-slate-500' }}"
                {{ $isOpen ? '' : 'disabled' }}
            >
                {{ $isOpen ? 'Lanjut Checkout' : 'STORE CLOSED' }}
            </button>
        </div>
    </aside>

    <div id="cartBackdrop" class="fixed inset-0 z-[80] hidden bg-slate-950/50 backdrop-blur-sm"></div>

    {{-- ===================== CHECKOUT DIALOG ===================== --}}
    <dialog id="checkoutDialog" class="w-[calc(100%-2rem)] max-w-4xl rounded-2xl border border-white/70 p-0 shadow-panel sm:rounded-[2rem]">
        <form id="checkoutForm" class="overflow-hidden rounded-2xl bg-white sm:rounded-[2rem]">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6 sm:py-5">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-500">Checkout</p>
                <h2 class="display-font mt-1.5 text-xl font-extrabold text-ink-950 sm:mt-2 sm:text-2xl">Konfirmasi Data Pengiriman</h2>
            </div>

            <div class="grid gap-6 px-5 py-5 sm:px-6 sm:py-6 lg:grid-cols-[1fr_0.84fr] lg:gap-8">
                {{-- Form Fields --}}
                <div class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama</label>
                            <input type="text" name="customer_name" id="customerName"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                                   required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">WhatsApp</label>
                            <input type="text" name="customer_phone" id="customerPhone"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Email</label>
                        <input type="email" name="customer_email" id="customerEmail"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                               required>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Alamat Pengiriman</label>
                        <textarea name="delivery_address" id="deliveryAddress" rows="3"
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-[1.5rem] sm:rows-4 sm:py-3"
                                  required></textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Metode Pembayaran</label>
                        <select name="payment_method" id="paymentMethod"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                                required>
                            <option value="cod">Cash on Delivery</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    {{-- Payment Details --}}
                    <div id="paymentDetailsCheckout" class="space-y-3">
                        <div id="checkout-cod" class="payment-detail-card hidden animate-fade-in rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-emerald-600">💵 Cash On Delivery</p>
                            <p class="text-sm text-slate-700">Bayar langsung ke kurir saat barang tiba.</p>
                        </div>

                        <div id="checkout-bank_transfer" class="payment-detail-card hidden animate-fade-in space-y-2 rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-cyan-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-blue-600">🏦 Transfer Bank</p>
                            <div class="space-y-2">
                                @foreach([['Bank Jago','105 3012 9xxx'],['SeaBank','901 067 9xxx'],['BCA','789 123 4xxx'],['BRI','456 789 0xxx']] as [$bank, $no])
                                    <div class="flex items-center justify-between rounded-lg border border-blue-100 bg-white p-2">
                                        <span class="text-xs font-semibold text-slate-700">{{ $bank }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-slate-900">{{ $no }}</span>
                                            <button type="button" class="copy-btn rounded bg-brand-100 px-2 py-1 text-xs font-bold text-brand-600 transition hover:bg-brand-200" data-copy="{{ $no }}" title="Salin">📋</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-slate-600">a.n. Rendy Al Diansyah</p>
                        </div>

                        <div id="checkout-ewallet" class="payment-detail-card hidden animate-fade-in rounded-2xl border border-purple-200 bg-gradient-to-br from-purple-50 to-pink-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-purple-600">📱 E-Wallet</p>
                            <div class="flex items-center justify-between rounded-lg border border-purple-100 bg-white p-2">
                                <span class="text-xs font-semibold text-slate-700">DANA • OVO • GoPay • ShopeePay</span>
                                <button type="button" class="copy-btn rounded bg-brand-100 px-2 py-1 text-xs font-bold text-brand-600 transition hover:bg-brand-200" data-copy="085189014426" title="Salin">📋</button>
                            </div>
                            <p class="mt-1 font-mono text-xs font-bold text-slate-900">085189014426</p>
                            <p class="mt-2 text-xs text-slate-600">a.n. Rendy Al Diansyah</p>
                        </div>

                        <div id="checkout-qris" class="payment-detail-card hidden animate-fade-in rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 to-red-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-orange-600">📲 QRIS</p>
                            <p class="text-xs text-slate-600">Scan QR code dengan aplikasi mobile banking atau e-wallet Anda</p>
                        </div>
                    </div>

                    <div id="paymentProofWrapper" class="hidden">
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" id="paymentProof" accept="image/*"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 sm:rounded-2xl sm:py-3">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Catatan</label>
                        <textarea name="notes" id="orderNotes" rows="3"
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-[1.5rem] sm:py-3"></textarea>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="rounded-2xl bg-ink-950 p-4 text-white sm:rounded-[1.8rem] sm:p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-300">Ringkasan Order</p>
                    <div id="checkoutItems" class="mt-4 space-y-3 text-sm text-slate-200"></div>
                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4 text-base font-extrabold">
                        <span>Total Bayar</span>
                        <span id="checkoutTotal">Rp 0</span>
                    </div>
                    <p class="mt-4 text-xs leading-6 text-slate-400">
                        Order akan disimpan ke database Laravel terlebih dahulu, kemudian mengalir ke email, notifikasi, dan laporan.
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:justify-end sm:px-6 sm:py-5">
                <button type="button" id="closeCheckoutButton"
                        class="w-full rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-brand-200 hover:text-brand-600 sm:w-auto sm:rounded-2xl sm:py-3">
                    Batal
                </button>
                <button type="submit" id="submitOrderButton"
                        class="w-full rounded-xl bg-brand-500 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-ink-950 sm:w-auto sm:rounded-2xl sm:py-3">
                    Kirim Order
                </button>
            </div>
        </form>
    </dialog>

    {{-- Toast --}}
    <div id="toast"
         class="pointer-events-none fixed bottom-4 left-1/2 z-[95] hidden -translate-x-1/2 rounded-full bg-ink-950 px-5 py-3 text-sm font-bold text-white shadow-panel">
    </div>

    <script id="storefrontState" type="application/json">@json($storefrontState)</script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Stagger animation for cards with data-delay
        document.querySelectorAll('[data-delay]').forEach(function (el) {
            const delay = el.getAttribute('data-delay');
            if (delay) el.style.setProperty('animation-delay', delay + 'ms');
        });

        // Delete confirm
        document.querySelectorAll('form[data-delete-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (!confirm(this.getAttribute('data-delete-confirm'))) e.preventDefault();
            });
        });
    });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }

        /* ── Product Card: smooth hover tanpa konflik dengan select/input ── */
        .product-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        /* Hover naik hanya saat TIDAK ada elemen di dalamnya yang aktif */
        .product-card:hover:not(:has(.product-field:focus)) {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.18);
        }

        /* Saat focus pada select/input → card diam, tidak goyang */
        .product-card:has(.product-field:focus) {
            transform: translateY(0) !important;
        }

        /* Image zoom hanya saat card hover DAN tidak ada focus di dalam */
        .product-card:hover:not(:has(.product-field:focus)) .product-card__img {
            transform: scale(1.07);
        }
        .product-card__img {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Overlay fade */
        .product-card__overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .product-card:hover:not(:has(.product-field:focus)) .product-card__overlay {
            opacity: 1;
        }

        /* ── Select & Input: smooth transition ── */
        .product-field {
            transition: border-color 0.2s ease,
                        background-color 0.2s ease,
                        box-shadow 0.2s ease;
            position: relative;
            z-index: 1;
        }

        .product-field:hover:not(:disabled) {
            border-color: #fdba74; /* brand-300 */
            background-color: #fff;
        }

        .product-field:focus {
            border-color: #fb923c; /* brand-400 */
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
        }

        /* ── Tombol tambah ke keranjang ── */
        .product-btn {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-btn:not(:disabled):hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px -6px rgba(249, 115, 22, 0.4);
        }

        .product-btn:not(:disabled):active {
            transform: scale(0.97);
        }
    </style>

    <script src="{{ asset('js/script.js') }}" defer></script>
</div>
@endsection