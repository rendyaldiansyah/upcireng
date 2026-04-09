@extends('layout.app')

@section('title', 'Detail Customer - UP Cireng')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-[#F7F8FA] text-slate-900')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
* { font-family: 'Outfit', sans-serif; }

:root {
    --bg: #F7F8FA;
    --surface: #FFFFFF;
    --border: #EEF0F4;
    --text-1: #0F172A;
    --text-2: #475569;
    --text-3: #94A3B8;
    --accent: #0EA5E9;
}

/* ===== CARD PROFILE ===== */
.card-profile {
    background: linear-gradient(135deg, #0EA5E9, #0284C7, #0369A1);
    border-radius: 24px;
    color: white;
    padding: 1.8rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(14,165,233,0.25);
}
.card-profile::after {
    content: '';
    position: absolute;
    top: -40%;
    right: -20%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,0.3), transparent 70%);
}

/* ===== CARD ===== */
.card {
    background: white;
    border-radius: 24px;
    border: 1px solid var(--border);
    box-shadow: 0 6px 18px rgba(0,0,0,0.04);
}

/* ===== INPUT ===== */
.input-modern {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 0.9rem 1rem;
    width: 100%;
    font-weight: 500;
}
.input-modern:focus {
    border-color: #0EA5E9;
    outline: none;
    box-shadow: 0 0 0 4px rgba(14,165,233,0.15);
}

/* ===== BUTTON ===== */
.btn-primary {
    background: #0EA5E9;
    color: white;
    border-radius: 14px;
    padding: 0.9rem;
    font-weight: 700;
    transition: .2s;
}
.btn-primary:hover {
    background: #0284C7;
    transform: translateY(-1px);
}

/* ===== ORDER ITEM ===== */
.order-item {
    padding: 1.2rem 1.4rem;
    border-bottom: 1px solid #F1F5F9;
    transition: .2s;
}
.order-item:hover {
    background: #F8FAFC;
    transform: translateX(4px);
}

/* ===== STATUS ===== */
.status {
    font-size: 0.7rem;
    font-weight: 800;
    padding: 0.3rem 0.8rem;
    border-radius: 999px;
}

/* ===== SCROLL ===== */
.scroll-area {
    height: calc(100vh - 160px);
    overflow-y: auto;
}
</style>
@endpush

@section('content')

<div class="min-h-screen lg:grid lg:grid-cols-[0.9fr_1.6fr]">

{{-- SIDEBAR --}}
@include('admin.partials.sidebar')

<main class="p-6">

{{-- GRID --}}
<div class="grid gap-6 lg:grid-cols-[0.9fr_1.6fr]">

{{-- LEFT --}}
<div class="space-y-6">

{{-- PROFILE --}}
<div class="card-profile">
    <div class="flex gap-4 items-center mb-6">
        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center text-2xl font-black">
            {{ strtoupper(substr($customer->name,0,1)) }}
        </div>
        <div>
            <p class="text-xs opacity-80">Customer #{{ $customer->id }}</p>
            <h1 class="text-2xl font-black">{{ $customer->name }}</h1>
            <p class="text-xs opacity-80 mt-1">Bergabung {{ $customer->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white/20 p-3 rounded-xl">
            <p class="text-xs">Total Pesanan</p>
            <p class="text-2xl font-bold">{{ $orders->count() }}</p>
        </div>
        <div class="bg-white/20 p-3 rounded-xl">
            <p class="text-xs">Total Belanja</p>
            <p class="font-bold">Rp {{ number_format($totalSpent,0,',','.') }}</p>
        </div>
    </div>
</div>

{{-- FORM --}}
<div class="card p-6">
    <h2 class="font-bold mb-4">Edit Data Customer</h2>

    <form method="POST" action="{{ route('admin.customer.update',$customer) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <input name="name" value="{{ $customer->name }}" class="input-modern">
        <input name="phone" value="{{ $customer->phone }}" class="input-modern">
        <input name="email" value="{{ $customer->email }}" class="input-modern">

        <button class="btn-primary w-full">Simpan</button>
    </form>
</div>

</div>

{{-- RIGHT --}}
<div class="card">

<div class="p-5 border-b flex justify-between">
    <div>
        <p class="text-xs text-slate-400">Riwayat</p>
        <h2 class="font-bold text-lg">Pesanan Customer</h2>
    </div>
    <div class="bg-blue-100 px-3 py-1 rounded-full font-bold text-blue-600">
        {{ $orders->count() }}
    </div>
</div>

<div class="scroll-area">

@foreach($orders as $order)
<div class="order-item">

<div class="flex justify-between mb-2">
    <div>
        <p class="font-bold text-sky-600 text-sm">{{ $order->reference }}</p>
        <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</p>
    </div>

    <div class="status bg-green-100 text-green-700">
        {{ $order->status }}
    </div>
</div>

<div class="text-sm text-gray-600 mb-2">
    {{ $order->summary_title }}
</div>

<div class="flex justify-between items-center">
    <div>
        <p class="text-xs text-gray-400">Total</p>
        <p class="font-bold">Rp {{ number_format($order->total_price,0,',','.') }}</p>
    </div>

    <a href="{{ route('admin.orders') }}?reference={{ $order->reference }}" 
       class="text-xs font-bold border px-3 py-1 rounded-lg hover:border-blue-400 hover:text-blue-500">
        Detail →
    </a>
</div>

</div>
@endforeach

</div>

</div>

</div>

</main>
</div>

@endsection