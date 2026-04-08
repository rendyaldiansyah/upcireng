@extends('layout.app')

@section('title', 'Store Settings - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle        = 'Store Configuration';
    $adminSidebarMetricLabel  = 'Operational Hours';
    $adminSidebarMetricValue  = $settings['operational_start'] . ' - ' . $settings['operational_end'];
    $adminSidebarBody         = 'Configure store identity, contact details, and operational settings.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center space-x-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-500 transition-colors">Dashboard</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-semibold text-slate-900">Settings</span>
        </nav>

        {{-- Hero Header --}}
        <section class="mb-8 rounded-2xl border border-slate-100 bg-gradient-to-r from-slate-50 to-brand-50 p-5 shadow-md sm:p-7">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="mb-1 text-xs font-bold uppercase tracking-wider text-brand-500">Configuration</p>
                    <h1 class="text-2xl font-black text-ink-950 sm:text-3xl">Store Settings</h1>
                    <p class="mt-1 text-sm text-slate-500">Update store identity, contact info, operational hours, teks hero storefront, dan pengaturan pengiriman COD.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="self-start rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-all hover:border-brand-400 hover:shadow sm:self-center">
                    ← Dashboard
                </a>
            </div>
        </section>

        {{-- Success Flash --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Settings Form --}}
        <section class="max-w-3xl">
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:p-7">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    {{-- ── HERO CONTENT ─────────────────────────────────── --}}
                    <div>
                        <div class="mb-4 flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-brand-100 text-brand-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Hero Storefront — Teks Halaman Utama
                            </h3>
                        </div>

                        <div class="space-y-4 rounded-xl border border-brand-100 bg-brand-50/40 p-4">

                            {{-- Headline --}}
                            <div>
                                <label for="hero_headline" class="mb-1.5 block text-sm font-bold text-slate-700">
                                    Headline <span class="text-rose-500">*</span>
                                    <span class="ml-1 text-xs font-normal text-slate-400">— judul besar di hero</span>
                                </label>
                                <input
                                    id="hero_headline"
                                    type="text"
                                    name="hero_headline"
                                    value="{{ old('hero_headline', $settings['hero_headline']) }}"
                                    maxlength="120"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition @error('hero_headline') border-rose-300 @enderror"
                                    placeholder="Pesan cireng favorit dengan mudah"
                                    required
                                >
                                @error('hero_headline')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-slate-400">Maks 120 karakter</p>
                            </div>

                            {{-- Subheadline --}}
                            <div>
                                <label for="hero_subheadline" class="mb-1.5 block text-sm font-bold text-slate-700">
                                    Subheadline / Label Section Menu <span class="text-rose-500">*</span>
                                    <span class="ml-1 text-xs font-normal text-slate-400">— label kecil di atas judul & di section menu</span>
                                </label>
                                <input
                                    id="hero_subheadline"
                                    type="text"
                                    name="hero_subheadline"
                                    value="{{ old('hero_subheadline', $settings['hero_subheadline']) }}"
                                    maxlength="60"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition @error('hero_subheadline') border-rose-300 @enderror"
                                    placeholder="Menu Pilihan"
                                    required
                                >
                                @error('hero_subheadline')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="hero_description" class="mb-1.5 block text-sm font-bold text-slate-700">
                                    Deskripsi <span class="text-rose-500">*</span>
                                    <span class="ml-1 text-xs font-normal text-slate-400">— paragraf di bawah headline</span>
                                </label>
                                <textarea
                                    id="hero_description"
                                    name="hero_description"
                                    rows="3"
                                    maxlength="300"
                                    class="w-full resize-vertical rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition @error('hero_description') border-rose-300 @enderror"
                                    placeholder="Sistem order modern dengan fitur checkout yang cepat..."
                                    required
                                >{{ old('hero_description', $settings['hero_description']) }}</textarea>
                                @error('hero_description')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-slate-400">Maks 300 karakter</p>
                            </div>

                            {{-- Live Preview --}}
                            <div class="rounded-xl border border-dashed border-brand-200 bg-white p-4">
                                <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-brand-400">Preview Storefront</p>
                                <p id="preview-subheadline" class="text-xs font-bold uppercase tracking-widest text-brand-500">
                                    {{ $settings['hero_subheadline'] }}
                                </p>
                                <p id="preview-headline" class="mt-1 text-xl font-extrabold text-ink-950 leading-snug">
                                    {{ $settings['hero_headline'] }}
                                </p>
                                <p id="preview-description" class="mt-1.5 text-sm text-slate-500 leading-relaxed">
                                    {{ $settings['hero_description'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- ── OPERATIONAL HOURS ────────────────────────────── --}}
                    <div>
                        <div class="mb-4 flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Operational Hours</h3>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="operational_start" class="mb-1 block text-sm font-bold text-slate-700">
                                    Opening Hours <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <input id="operational_start" type="time" name="operational_start"
                                           value="{{ old('operational_start', $settings['operational_start']) }}"
                                           class="w-full rounded-xl border border-slate-200 py-2 pl-9 pr-3 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                           required>
                                </div>
                                @error('operational_start')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="operational_end" class="mb-1 block text-sm font-bold text-slate-700">
                                    Closing Hours <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <input id="operational_end" type="time" name="operational_end"
                                           value="{{ old('operational_end', $settings['operational_end']) }}"
                                           class="w-full rounded-xl border border-slate-200 py-2 pl-9 pr-3 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                           required>
                                </div>
                                @error('operational_end')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- ── STORE IDENTITY ───────────────────────────────── --}}
                    <div>
                        <div class="mb-4 flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Store Identity</h3>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="store_name" class="mb-1 block text-sm font-bold text-slate-700">
                                    Store Name <span class="text-rose-500">*</span>
                                </label>
                                <input id="store_name" type="text" name="store_name"
                                       value="{{ old('store_name', $settings['store_name']) }}"
                                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                       placeholder="UP Cireng Premium" required>
                                @error('store_name')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="store_phone" class="mb-1 block text-sm font-bold text-slate-700">
                                    WhatsApp Number <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">WA</span>
                                    <input id="store_phone" type="tel" name="store_phone"
                                           value="{{ old('store_phone', $settings['store_phone']) }}"
                                           class="w-full rounded-xl border border-slate-200 py-2 pl-9 pr-3 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                           placeholder="6281234567890" required>
                                </div>
                                @error('store_phone')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="store_email" class="mb-1 block text-sm font-bold text-slate-700">
                                    Store Email <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <input id="store_email" type="email" name="store_email"
                                           value="{{ old('store_email', $settings['store_email']) }}"
                                           class="w-full rounded-xl border border-slate-200 py-2 pl-9 pr-3 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                           placeholder="hello@upcireng.com" required>
                                </div>
                                @error('store_email')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="store_instagram" class="mb-1 block text-sm font-bold text-slate-700">Instagram Handle</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">@</span>
                                    <input id="store_instagram" type="text" name="store_instagram"
                                           value="{{ old('store_instagram', $settings['store_instagram']) }}"
                                           class="w-full rounded-xl border border-slate-200 py-2 pl-7 pr-3 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                           placeholder="upcireng_official">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- ── ADDRESS ──────────────────────────────────────── --}}
                    <div>
                        <label for="store_address" class="mb-1 block text-sm font-bold text-slate-700">Store Address</label>
                        <textarea id="store_address" name="store_address" rows="3"
                                  class="w-full resize-vertical rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                  placeholder="Jl. Contoh No.123, RT 01 RW 02, Kelurahan, Kota, 12345">{{ old('store_address', $settings['store_address']) }}</textarea>
                    </div>

                    <hr class="border-slate-100">

                    {{-- ★ ── DELIVERY / COD SETTINGS ───────────────────── --}}
                    <div>
                        <div class="mb-4 flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Delivery & Coverage COD
                            </h3>
                        </div>

                        <div class="space-y-5 rounded-xl border border-sky-100 bg-sky-50/40 p-4">

                            {{-- Info banner --}}
                            <div class="flex items-start gap-2 rounded-lg border border-sky-200 bg-white px-3 py-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs text-slate-600">
                                    Koordinat toko digunakan untuk menghitung jarak ke alamat pelanggan saat checkout COD.
                                    Cari koordinat toko di <a href="https://www.latlong.net" target="_blank" class="font-bold text-sky-600 hover:underline">latlong.net</a>
                                    atau klik kanan di Google Maps → "Apa yang ada di sini?"
                                </p>
                            </div>

                            {{-- Store coordinates --}}
                            <div>
                                <p class="mb-2 text-sm font-bold text-slate-700">Koordinat Toko (Titik Asal Pengiriman)</p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="store_lat" class="mb-1 block text-xs font-semibold text-slate-600">
                                            Latitude
                                            <span class="ml-1 text-[10px] font-normal text-slate-400">contoh: -7.4153</span>
                                        </label>
                                        <input id="store_lat" type="number" name="store_lat" step="0.000001"
                                               value="{{ old('store_lat', $settings['store_lat']) }}"
                                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('store_lat') border-rose-300 @enderror"
                                               placeholder="-7.4153">
                                        @error('store_lat')
                                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="store_lng" class="mb-1 block text-xs font-semibold text-slate-600">
                                            Longitude
                                            <span class="ml-1 text-[10px] font-normal text-slate-400">contoh: 109.3647</span>
                                        </label>
                                        <input id="store_lng" type="number" name="store_lng" step="0.000001"
                                               value="{{ old('store_lng', $settings['store_lng']) }}"
                                               class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('store_lng') border-rose-300 @enderror"
                                               placeholder="109.3647">
                                        @error('store_lng')
                                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Live coordinate status --}}
                                @if(!empty($settings['store_lat']) && !empty($settings['store_lng']))
                                    <div class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Koordinat aktif: {{ $settings['store_lat'] }}, {{ $settings['store_lng'] }}
                                    </div>
                                @else
                                    <div class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Koordinat belum diisi — fitur cek jarak belum aktif
                                    </div>
                                @endif
                            </div>

                            {{-- COD rules --}}
                            <div>
                                <p class="mb-2 text-sm font-bold text-slate-700">Aturan Ongkos Kirim COD</p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="cod_free_km" class="mb-1 block text-xs font-semibold text-slate-600">
                                            Radius Gratis (km) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input id="cod_free_km" type="number" name="cod_free_km"
                                                   min="0" max="100" step="0.5"
                                                   value="{{ old('cod_free_km', $settings['cod_free_km']) }}"
                                                   class="w-full rounded-xl border border-slate-200 py-2 pl-3 pr-12 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('cod_free_km') border-rose-300 @enderror"
                                                   required>
                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">km</span>
                                        </div>
                                        @error('cod_free_km')
                                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-[10px] text-slate-400">Jarak maksimal pengiriman gratis</p>
                                    </div>
                                    <div>
                                        <label for="cod_extra_per_km" class="mb-1 block text-xs font-semibold text-slate-600">
                                            Biaya Tambahan per km <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                            <input id="cod_extra_per_km" type="number" name="cod_extra_per_km"
                                                   min="0" step="500"
                                                   value="{{ old('cod_extra_per_km', $settings['cod_extra_per_km']) }}"
                                                   class="w-full rounded-xl border border-slate-200 py-2 pl-8 pr-3 text-sm font-semibold transition focus:border-brand-400 focus:ring-2 focus:ring-brand-100 @error('cod_extra_per_km') border-rose-300 @enderror"
                                                   required>
                                        </div>
                                        @error('cod_extra_per_km')
                                            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-[10px] text-slate-400">Dikenakan per km melebihi radius gratis</p>
                                    </div>
                                </div>

                                {{-- Preview rule --}}
                                <div class="mt-3 rounded-lg border border-sky-100 bg-white p-3 text-xs text-slate-600">
                                    🛵 Preview aturan: Gratis s/d
                                    <strong id="preview-free-km">{{ $settings['cod_free_km'] }}</strong> km.
                                    Lebih dari itu: <strong>Rp <span id="preview-extra-rate">{{ number_format($settings['cod_extra_per_km']) }}</span></strong>/km tambahan.
                                    <br>Contoh: 7 km → biaya = (7 − <span id="preview-free-km-2">{{ $settings['cod_free_km'] }}</span>) × Rp <span id="preview-extra-rate-2">{{ number_format($settings['cod_extra_per_km']) }}</span> = Rp <span id="preview-example-fee">{{ number_format((7 - $settings['cod_free_km']) * $settings['cod_extra_per_km']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- ★ ── QRIS MANAGEMENT ───────────────────────────── --}}
                    <div>
                        <div class="mb-4 flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">QRIS Payment</h3>
                        </div>

                        <div class="space-y-4 rounded-xl border border-orange-100 bg-orange-50/40 p-4">
                            <div>
                                <label for="qris_image" class="mb-1.5 block text-sm font-bold text-slate-700">
                                    QRIS Code Image <span class="text-rose-500">*</span>
                                    <span class="ml-1 text-xs font-normal text-slate-400">— jpg, png, webp (max 2MB)</span>
                                </label>
                                <input
                                    id="qris_image"
                                    type="file"
                                    name="qris_image"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-orange-100 file:text-orange-700
                                        hover:file:bg-orange-200 file:transition
                                        @error('qris_image') border-rose-300 @enderror"
                                >
                                @error('qris_image')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-slate-500">Upload QRIS image untuk ditampilkan di halaman checkout customer.</p>
                            </div>

                            @if(!empty($settings['qris_image']))
                                <div>
                                    <p class="mb-2 text-sm font-bold text-slate-700">Current QRIS Preview</p>
                                    <div class="flex justify-center rounded-lg border border-orange-200 bg-white p-4">
                                        <img src="{{ asset('storage/' . $settings['qris_image']) }}" alt="QRIS Code" class="h-40 w-40 rounded-lg object-cover shadow">
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500 text-center">
                                        Diupload: <strong>{{ basename($settings['qris_image']) }}</strong>
                                    </p>
                                </div>
                            @else
                                <div class="rounded-lg border border-orange-200 bg-white p-8 text-center">
                                    <svg class="mx-auto mb-2 h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-slate-600">Belum ada QRIS — silakan upload</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── SUBMIT ───────────────────────────────────────── --}}
                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row">
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:border-brand-400 hover:shadow">
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-2.5 text-sm font-bold text-white transition-all hover:from-brand-500 hover:to-brand-600 hover:shadow-md">
                            Save All Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<script>
// Live preview untuk hero content
(function () {
    const fields = {
        hero_headline:    'preview-headline',
        hero_subheadline: 'preview-subheadline',
        hero_description: 'preview-description',
    };

    Object.entries(fields).forEach(function ([inputId, previewId]) {
        const input   = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (!input || !preview) return;
        input.addEventListener('input', function () {
            preview.textContent = this.value || '—';
        });
    });

    // ★ Live preview untuk COD rules
    const freeKmInput   = document.getElementById('cod_free_km');
    const extraRateInput = document.getElementById('cod_extra_per_km');

    function updateCodPreview() {
        const freeKm    = parseFloat(freeKmInput?.value) || 5;
        const extraRate = parseFloat(extraRateInput?.value) || 5000;
        const exampleKm = 7;
        const exampleFee = Math.max(0, Math.ceil(exampleKm - freeKm)) * extraRate;

        ['preview-free-km', 'preview-free-km-2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = freeKm;
        });
        ['preview-extra-rate', 'preview-extra-rate-2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = extraRate.toLocaleString('id-ID');
        });
        const feeEl = document.getElementById('preview-example-fee');
        if (feeEl) feeEl.textContent = exampleFee.toLocaleString('id-ID');
    }

    freeKmInput?.addEventListener('input', updateCodPreview);
    extraRateInput?.addEventListener('input', updateCodPreview);
})();
</script>
@endsection