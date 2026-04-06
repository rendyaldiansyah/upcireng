@extends('layout.app')

@section('title', 'Edit {{ $product->name }} - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle = 'Edit Produk';
    $adminSidebarMetricLabel = 'Produk ID';
    $adminSidebarMetricValue = $product->id;
    $adminSidebarBody = 'Update detail, gambar, varian, dan status dari produk yang sudah ada.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center space-x-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.products.index') }}" class="hover:text-brand-500">Produk</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-semibold text-slate-900 truncate max-w-[200px]">{{ Str::limit($product->name, 30) }}</span>
        </nav>

        {{-- Header --}}
        <section class="mb-6 rounded-2xl bg-gradient-to-r from-slate-50 to-brand-50 p-5 sm:p-6 shadow-md border border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-500 mb-1">Edit Produk</p>
                    <h1 class="text-xl sm:text-2xl font-black text-ink-950 leading-tight">{{ $product->name }}</h1>
                    <p class="mt-1 text-sm text-slate-500">Perubahan langsung tersinkronisasi ke storefront.</p>
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                        <span>Harga: <span class="text-ink-950 font-black">{{ $product->formatPrice() }}</span></span>
                        <span class="px-2 py-0.5 rounded-full {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full {{ $product->stock_status === 'available' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $product->stock_status === 'available' ? 'Stok Tersedia' : 'Stok Habis' }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}"
                   class="self-start rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition-all whitespace-nowrap">
                    ← Lihat Semua
                </a>
            </div>
        </section>

        {{-- Form Card --}}
        <section class="max-w-3xl">
            <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.products.partials.form', ['product' => $product])

                    <div class="mt-6 flex gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.products.index') }}"
                           class="rounded-xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:border-brand-300 hover:shadow transition text-center">
                            Kembali
                        </a>
                        <button type="submit"
                                class="flex-1 rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-2 text-sm font-bold text-white hover:from-brand-500 hover:to-brand-600 hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-brand-100">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </main>
</div>
@endsection