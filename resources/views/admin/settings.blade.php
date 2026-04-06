@extends('layout.app')

@section('title', 'Store Settings - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle = 'Store Configuration';
    $adminSidebarMetricLabel = 'Operational Hours';
    $adminSidebarMetricValue = $settings['operational_start'] . ' - ' . $settings['operational_end'];
    $adminSidebarBody = 'Configure store identity, contact details, and operational settings.';
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center space-x-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-500">Dashboard</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-semibold text-slate-900">Settings</span>
        </nav>

        {{-- Hero Header --}}
        <section class="mb-8 rounded-2xl bg-gradient-to-r from-slate-50 to-brand-50 p-5 sm:p-7 shadow-md border border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-500 mb-1">Configuration</p>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Store Settings</h1>
                    <p class="mt-1 text-sm text-slate-500">Update store identity, contact info, and operational hours.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="self-start sm:self-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition-all whitespace-nowrap">
                    ← Dashboard
                </a>
            </div>
        </section>

        {{-- Settings Form --}}
        <section class="max-w-3xl">
            <div class="rounded-2xl bg-white p-5 sm:p-7 shadow-sm border border-slate-100">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Operational Hours --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Operational Hours</h3>
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
                                           class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
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
                                           class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
                                           required>
                                </div>
                                @error('operational_end')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Store Identity --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Store Identity</h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            {{-- Store Name --}}
                            <div>
                                <label for="store_name" class="mb-1 block text-sm font-bold text-slate-700">
                                    Store Name <span class="text-rose-500">*</span>
                                </label>
                                <input id="store_name" type="text" name="store_name"
                                       value="{{ old('store_name', $settings['store_name']) }}"
                                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
                                       placeholder="UP Cireng Premium" required>
                                @error('store_name')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- WhatsApp --}}
                            <div>
                                <label for="store_phone" class="mb-1 block text-sm font-bold text-slate-700">
                                    WhatsApp Number <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">WA</span>
                                    <input id="store_phone" type="tel" name="store_phone"
                                           value="{{ old('store_phone', $settings['store_phone']) }}"
                                           class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
                                           placeholder="6281234567890" required>
                                </div>
                                @error('store_phone')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
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
                                           class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
                                           placeholder="hello@upcireng.com" required>
                                </div>
                                @error('store_email')
                                    <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Instagram --}}
                            <div>
                                <label for="store_instagram" class="mb-1 block text-sm font-bold text-slate-700">Instagram Handle</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">@</span>
                                    <input id="store_instagram" type="text" name="store_instagram"
                                           value="{{ old('store_instagram', $settings['store_instagram']) }}"
                                           class="w-full rounded-xl border border-slate-200 pl-7 pr-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition"
                                           placeholder="upcireng_official">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Address --}}
                    <div>
                        <label for="store_address" class="mb-1 block text-sm font-bold text-slate-700">Store Address</label>
                        <textarea id="store_address" name="store_address" rows="3"
                                  class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition resize-vertical"
                                  placeholder="Jl. Contoh No.123, RT 01 RW 02, Kelurahan, Kota, 12345">{{ old('store_address', $settings['store_address']) }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition text-center">
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-2 text-sm font-bold text-white hover:from-brand-500 hover:to-brand-600 hover:shadow-md transition-all">
                            Save All Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </main>
</div>
@endsection