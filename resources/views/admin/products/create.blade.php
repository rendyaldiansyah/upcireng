@extends('layout.app')

@section('title', 'Tambah Produk Baru - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle        = 'Produk Baru';
    $adminSidebarMetricLabel  = 'Status';
    $adminSidebarMetricValue  = 'Draft';
    $adminSidebarBody         = 'Buat produk baru dengan form yang clean dan validasi real-time.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-10 lg:py-10">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center space-x-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.products.index') }}"
               class="hover:text-brand-500 transition-colors duration-200">
                Produk
            </a>
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-semibold text-slate-900">Tambah Baru</span>
        </nav>

        {{-- Header --}}
        <section class="mb-8 rounded-2xl bg-white p-6 shadow-panel sm:rounded-3xl sm:p-8">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:text-sm">
                        Produk Baru
                    </p>
                    <h1 class="mt-3 text-2xl font-black text-ink-950 sm:text-3xl lg:text-4xl xl:text-5xl">
                        Tambah Produk ke Catalog
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                        Isi detail produk dengan mudah. Semua data tersimpan langsung ke database
                        utama dan siap tampil di storefront.
                    </p>
                </div>

                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex w-full items-center justify-center rounded-xl border-2 border-slate-200 px-6 py-3 text-sm font-bold text-slate-800 transition-all duration-300 hover:border-brand-400 hover:shadow-md hover:-translate-y-px sm:w-auto sm:rounded-2xl sm:px-8 sm:py-4 sm:text-base">
                    ← Kembali ke List
                </a>
            </div>
        </section>

        {{-- Form Card --}}
        <section class="mx-auto max-w-4xl">
            <div class="rounded-2xl bg-white p-5 shadow-panel sm:rounded-3xl sm:p-8 lg:p-10">
                <form action="{{ route('admin.products.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      novalidate>
                    @csrf

                    @include('admin.products.partials.form', ['product' => null])

                    {{-- Action Buttons --}}
                    <div class="mt-8 flex flex-col gap-3 sm:mt-12 sm:flex-row sm:gap-4">
                        <a href="{{ route('admin.products.index') }}"
                           class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-6 py-4 text-center text-base font-bold text-slate-800 transition-all duration-300 hover:border-brand-300 hover:shadow-md hover:-translate-y-px sm:rounded-2xl sm:px-8 sm:py-5 sm:text-lg">
                            Batal
                        </a>
                        <button type="submit"
                                class="flex-1 rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-6 py-4 text-base font-bold text-white transition-all duration-300 hover:shadow-lg hover:-translate-y-px hover:from-brand-500 hover:to-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-100/50 sm:rounded-2xl sm:px-8 sm:py-5 sm:text-lg">
                            Buat Produk Baru
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </main>
</div>
@endsection