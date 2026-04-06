@extends('layout.app')

@section('title', 'Kelola Produk - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle = 'Catalog Lengkap';
    $adminSidebarMetricLabel = 'Total Produk';
    $adminSidebarMetricValue = number_format($products->total(), 0, ',');
    $adminSidebarBody = 'Kelola semua produk dengan card view modern. Toggle status langsung tanpa page reload.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8">

        {{-- Header & Search --}}
        <section class="mb-6 rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-500 mb-1">Catalog Manager</p>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Semua Produk</h1>
                    <p class="mt-1 text-sm text-slate-500">Toggle status stok dan order langsung dari sini.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition-all">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                       class="rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-4 py-2 text-sm font-bold text-white hover:from-brand-500 hover:to-brand-600 hover:shadow-md transition-all">
                        + Tambah Produk
                    </a>
                </div>
            </div>

            {{-- Search --}}
            <div class="mt-4">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           placeholder="Cari produk berdasarkan nama atau varian..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
                           oninput="filterProducts(this.value)">
                </div>
            </div>
        </section>

        {{-- Products Grid --}}
        {{-- FIX: tambah data-product-card agar filter JS bisa bekerja --}}
        <section id="products-grid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($products as $product)
                <div data-product-card class="group rounded-2xl bg-white p-4 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">

                    {{-- Image --}}
                    <div class="mb-4">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="h-36 w-full rounded-xl object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    {{-- Details --}}
                    <div class="space-y-2 mb-4">
                        <div>
                            <h3 class="text-base font-black text-ink-950 group-hover:text-brand-600 transition-colors line-clamp-2 leading-tight">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Urutan #{{ $product->sort_order }}</p>
                        </div>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $product->description ?: 'Tanpa deskripsi.' }}
                        </p>

                        @php $variants = $product->availableVariants(); @endphp
                        @if(!empty($variants))
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($variants, 0, 2) as $variant)
                                    <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-600">{{ $variant }}</span>
                                @endforeach
                                @if(count($variants) > 2)
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">+{{ count($variants) - 2 }}</span>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Tanpa varian</p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="mb-3 pb-3 border-b border-slate-100">
                        <p class="text-lg font-black text-ink-950">{{ $product->formatPrice() }}</p>
                    </div>

                    {{-- Status Badges --}}
                    <div class="flex flex-wrap gap-1 mb-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold
                            {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold
                            {{ $product->stock_status === 'available' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $product->stock_status === 'available' ? 'Stok OK' : 'Stok 0' }}
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold
                            {{ $product->is_open ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $product->is_open ? 'Order ON' : 'Order OFF' }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="space-y-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="block w-full rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-center text-sm font-bold text-white transition hover:shadow-md">
                            Edit Detail
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('admin.products.toggle-stock', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs font-bold text-slate-700 hover:border-brand-400 hover:shadow transition-all">
                                    {{ $product->stock_status === 'available' ? 'Tutup' : 'Buka' }} Stok
                                </button>
                            </form>
                            <form action="{{ route('admin.products.toggle-open', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-xs font-bold text-slate-700 hover:border-brand-400 hover:shadow transition-all">
                                    {{ $product->is_open ? 'Tutup' : 'Buka' }} Order
                                </button>
                            </form>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  data-delete-confirm="Yakin hapus {{ $product->name }}?"
                                  class="col-span-2">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full rounded-xl border border-rose-200 bg-white px-2 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-400 transition">
                                    Hapus Produk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-ink-950 mb-2">Belum ada produk</h3>
                    <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">Mulai tambah produk pertama untuk melengkapi catalog toko.</p>
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-block rounded-xl bg-ink-950 hover:bg-brand-600 px-6 py-2.5 text-sm font-bold text-white transition">
                        + Tambah Produk Pertama
                    </a>
                </div>
            @endforelse
        </section>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </main>
</div>

<script>
    // FIX: selector diganti ke data-product-card agar filter bisa bekerja
    function filterProducts(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('[data-product-card]').forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    // Delete confirmation
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[data-delete-confirm]').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm(this.getAttribute('data-delete-confirm'))) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection