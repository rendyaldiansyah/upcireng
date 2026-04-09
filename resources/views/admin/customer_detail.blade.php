@extends('layout.app')

@section('title', 'Detail Customer')

@section('content')
<div class="flex min-h-screen bg-[#F6F7FB]">

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN --}}
    <main class="flex-1 p-6">

        {{-- BREADCRUMB --}}
        <div class="text-sm text-slate-400 mb-4">
            Dashboard > Customer > <span class="text-slate-700 font-semibold">{{ $customer->name }}</span>
        </div>

        <div class="grid grid-cols-[2fr_1fr] gap-6">

            {{-- LEFT --}}
            <div class="space-y-6">

                {{-- PROFILE --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <div class="flex justify-between items-start mb-6">
                        <div class="flex gap-4 items-center">

                            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                                {{ strtoupper(substr($customer->name,0,1)) }}
                            </div>

                            <div>
                                <h1 class="text-xl font-bold text-slate-800">{{ $customer->name }}</h1>
                                <p class="text-sm text-slate-400">#{{ $customer->id }} • Daftar {{ $customer->created_at->format('d M Y') }}</p>
                            </div>

                        </div>

                        <a href="{{ route('admin.customers') }}" 
                           class="text-sm border px-4 py-1.5 rounded-lg hover:bg-slate-100">
                            Kembali
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-6 text-sm">

                        <div>
                            <p class="text-slate-400 mb-1">Email</p>
                            <p class="font-medium text-slate-700">{{ $customer->email ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Nomor HP</p>
                            <p class="font-medium text-slate-700">{{ $customer->phone }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Alamat</p>
                            <p class="font-medium text-slate-700">{{ $customer->address ?? '-' }}</p>
                        </div>

                    </div>

                </div>

                {{-- RIWAYAT --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <h2 class="font-semibold text-slate-700 mb-4">Riwayat Pesanan</h2>

                    @if($orders->count())
                        <div class="space-y-3">
                            @foreach($orders as $order)
                                <div class="border rounded-xl p-4 text-sm">
                                    <div class="flex justify-between mb-1">
                                        <span class="font-semibold text-slate-700">{{ $order->reference }}</span>
                                        <span class="text-xs text-slate-400">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-slate-500">
                                        Rp {{ number_format($order->total_price,0,',','.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border rounded-xl p-6 text-center text-slate-400">
                            Belum ada pesanan
                        </div>
                    @endif

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-4">

                {{-- TOTAL PESANAN --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs text-slate-400">TOTAL PESANAN</p>
                    <p class="text-lg font-bold text-blue-600 mt-1">{{ $orders->count() }}</p>
                </div>

                {{-- TOTAL BELANJA --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs text-slate-400">TOTAL BELANJA</p>
                    <p class="text-lg font-bold text-green-600 mt-1">
                        Rp {{ number_format($totalSpent,0,',','.') }}
                    </p>
                </div>

                {{-- ACTION --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">

                    <p class="text-xs text-slate-400">TINDAKAN</p>

                    <a href="{{ route('admin.customer.edit',$customer) }}"
                       class="block w-full text-center bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600">
                        Edit Data
                    </a>

                    <form method="POST" action="{{ route('admin.customer.delete',$customer) }}">
                        @csrf
                        @method('DELETE')
                        <button class="w-full border border-red-300 text-red-500 py-2 rounded-lg hover:bg-red-50">
                            Hapus Customer
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </main>
</div>
@endsection