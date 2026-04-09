{{-- resources/views/admin/customers/detail.blade.php --}}
@extends('layout.app')

@section('title', "Detail Customer: $customer->name — Admin")
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-slate-50 text-slate-900')

@section('content')
<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        {{-- ── Breadcrumb ── --}}
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition">Dashboard</a>
            <span>›</span>
            <a href="{{ route('admin.customers') }}" class="hover:text-slate-700 transition">Customer</a>
            <span>›</span>
            <span class="text-slate-700">{{ $customer->name }}</span>
        </nav>

        {{-- ── Alerts ── --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- ── Main Column ── --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Profile Card --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-100 text-2xl font-black text-sky-600">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <h1 class="display-font text-2xl font-black text-slate-900">{{ $customer->name }}</h1>
                                <p class="text-sm text-slate-500">#{{ $customer->id }} • Daftar {{ $customer->created_at->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.customers') }}"
                           class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-slate-300">
                            Kembali
                        </a>
                    </div>

                    <div class="space-y-4 border-t border-slate-100 pt-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Email</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $customer->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor HP</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $customer->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $customer->address ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Order History --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-black text-slate-900">Riwayat Pesanan</h2>

                    @if($orders->count() > 0)
                        <div class="space-y-3">
                            @foreach($orders as $order)
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-4">
                                    <div>
                                        <p class="font-bold text-slate-900">#{{ $order->reference }}</p>
                                        <p class="text-xs text-slate-500">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        <span class="inline-flex items-center gap-1 rounded-full {{ $order->status_color ?? 'bg-slate-100 text-slate-600' }} px-2.5 py-0.5 text-[11px] font-bold">
                                            {{ $order->status_label ?? ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-6 text-center">
                            <p class="text-sm text-slate-500">Belum ada pesanan</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Sidebar ── --}}
            <div class="space-y-6">
                {{-- Stats Cards --}}
                @php
                    $statsList = [
                        ['label' => 'Total Pesanan', 'value' => $totalOrders, 'color' => 'text-sky-600', 'bg' => 'bg-sky-50'],
                        ['label' => 'Total Belanja', 'value' => 'Rp ' . number_format($totalSpent, 0, ',', '.'), 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                    ]
                @endphp
                @foreach($statsList as $stat)
                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</p>
                        <p class="mt-2 {{ $stat['color'] }} font-black">{{ $stat['value'] }}</p>
                    </div>
                @endforeach

                {{-- Actions --}}
                <div class="space-y-3 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tindakan</h3>
                    <button type="button" onclick="document.getElementById('editCustomerModal').classList.remove('hidden')"
                            class="w-full rounded-lg bg-sky-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-sky-600">
                        Edit Data
                    </button>
                    <form method="POST" action="{{ route('admin.customer.delete', $customer) }}"
                          onsubmit="return confirm('Hapus customer {{ addslashes($customer->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                            Hapus Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </main>
</div>

{{-- ═══════════════════════ EDIT MODAL ═══════════════════════ --}}
<div id="editCustomerModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('editCustomerModal').classList.add('hidden')"></div>

    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl sm:p-7">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="display-font text-lg font-black text-slate-900">Edit Data Customer</h2>
            <button type="button" onclick="document.getElementById('editCustomerModal').classList.add('hidden')"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-slate-700 transition">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('admin.customer.update', $customer) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ $customer->name }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100"
                       required>
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ $customer->email }}"
                       placeholder="Kosongkan jika tidak ada"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                <p class="mt-1 text-xs text-slate-400">Opsional — customer mungkin tidak memiliki email.</p>
                @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Nomor HP</label>
                <input type="text" name="phone" value="{{ $customer->phone }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100"
                       required>
                @error('phone')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('editCustomerModal').classList.add('hidden')"
                        class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-bold text-slate-700 transition hover:border-slate-300">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 rounded-xl bg-sky-500 py-2.5 text-sm font-bold text-white transition hover:bg-sky-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
