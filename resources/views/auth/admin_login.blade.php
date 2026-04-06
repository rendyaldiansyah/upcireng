@extends('layout.app')

@section('title', 'Admin Login - UP Cireng')
@section('body_class', 'bg-gradient-to-br from-slate-950 via-slate-900 to-ink-950 min-h-screen')
@section('hide_nav', '1')
@section('hide_footer', '1')

@section('content')
<div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:px-6 sm:py-16"
     data-animate="fade-in-up">

    {{-- Background blobs --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute right-0 top-0 h-64 w-64 rounded-full bg-brand-500/10 blur-3xl sm:h-96 sm:w-96"></div>
        <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-brand-600/10 blur-3xl sm:h-96 sm:w-96"></div>
    </div>

    <div class="relative z-10 w-full max-w-sm sm:max-w-md">

        {{-- Logo Header --}}
        <div class="mb-8 text-center animate-fade-in-up sm:mb-12" data-delay="0">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-600 shadow-2xl transition-all duration-500 hover:shadow-brand-500/50 sm:mb-6 sm:h-20 sm:w-20 sm:rounded-3xl">
                <img src="{{ asset('assets/assets/logo.png') }}"
                     alt="UP Cireng"
                     class="h-10 w-10 rounded-xl sm:h-12 sm:w-12 sm:rounded-2xl">
            </div>
            <h1 class="mb-2 bg-gradient-to-r from-white via-white/90 to-slate-200 bg-clip-text text-3xl font-black text-transparent sm:mb-3 sm:text-4xl lg:text-5xl">
                Admin Panel
            </h1>
            <p class="text-sm leading-relaxed text-slate-300 sm:text-base lg:text-lg">
                Kelola toko Anda dengan dashboard modern
            </p>
        </div>

        {{-- Form Card --}}
        <div class="animate-scale-in rounded-2xl border border-white/20 bg-white/10 p-6 shadow-2xl backdrop-blur-xl transition-all duration-500 hover:border-white/30 sm:rounded-3xl sm:p-8 lg:p-10"
             data-delay="100">

            {{-- Errors --}}
            @if($errors->any())
                <div class="mb-6 animate-fade-in-down rounded-xl border-2 border-rose-400/40 bg-rose-500/15 p-4 sm:mb-8 sm:rounded-2xl sm:p-5"
                     data-delay="200">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-400 sm:h-6 sm:w-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <p class="text-sm font-semibold text-white">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.auth.login') }}"
                  method="POST"
                  class="space-y-5 sm:space-y-6"
                  data-animate="fade-in-up"
                  data-delay="200">
                @csrf

                {{-- Email --}}
                <div class="group">
                    <label for="email"
                           class="mb-2 block text-xs font-bold uppercase tracking-wider text-white/80 transition-colors group-focus-within:text-white sm:mb-3 sm:text-sm">
                        Email Address
                    </label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 transition-colors duration-300 group-focus-within:text-brand-400 sm:left-5 sm:h-5 sm:w-5"
                             fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            class="h-12 w-full rounded-xl border border-white/20 bg-white/5 pl-11 pr-4 text-sm font-semibold text-white placeholder-slate-500 outline-none transition-all duration-300 focus:border-brand-400 focus:bg-white/10 focus:ring-4 focus:ring-brand-500/30 sm:h-14 sm:rounded-2xl sm:pl-14 sm:pr-5 sm:text-base lg:h-16 lg:text-lg"
                            placeholder="admin@upcireng.test"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="group">
                    <label for="password"
                           class="mb-2 block text-xs font-bold uppercase tracking-wider text-white/80 transition-colors group-focus-within:text-white sm:mb-3 sm:text-sm">
                        Password
                    </label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 transition-colors duration-300 group-focus-within:text-brand-400 sm:left-5 sm:h-5 sm:w-5"
                             fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="h-12 w-full rounded-xl border border-white/20 bg-white/5 pl-11 pr-4 text-sm font-semibold text-white placeholder-slate-500 outline-none transition-all duration-300 focus:border-brand-400 focus:bg-white/10 focus:ring-4 focus:ring-brand-500/30 sm:h-14 sm:rounded-2xl sm:pl-14 sm:pr-5 sm:text-base lg:h-16 lg:text-lg"
                            placeholder="Masukkan password"
                        >
                    </div>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="mt-6 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 via-brand-600 to-brand-700 text-base font-black text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand-500/50 active:scale-[0.98] sm:mt-8 sm:h-14 sm:rounded-2xl sm:text-lg lg:h-16 lg:text-xl"
                >
                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Masuk ke Admin Panel
                </button>
            </form>

            {{-- Divider --}}
            <div class="my-6 flex items-center gap-3 sm:my-8">
                <div class="h-px flex-1 bg-white/10"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-white/40 sm:text-xs">Info Demo</span>
                <div class="h-px flex-1 bg-white/10"></div>
            </div>

            {{-- Demo Credentials --}}
            <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 p-4 backdrop-blur-sm sm:rounded-2xl sm:p-5">
                <p class="mb-3 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-brand-300 sm:mb-4 sm:text-xs">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a4 4 0 00-4-4H4a4 4 0 00-4 4v1h16z" />
                    </svg>
                    Kredensial Demo
                </p>
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center justify-between rounded-lg border border-white/10 bg-white/5 p-2">
                        <span class="text-xs text-slate-400">Email:</span>
                        <span class="font-mono text-xs font-bold text-white sm:text-sm">admin@upcireng.test</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-white/10 bg-white/5 p-2">
                        <span class="text-xs text-slate-400">Password:</span>
                        <span class="font-mono text-xs font-bold text-white sm:text-sm">admin123</span>
                    </div>
                </div>
            </div>

            {{-- Storefront Link --}}
            <div class="mt-6 border-t border-white/10 pt-5 text-center sm:mt-8 sm:pt-6">
                <a href="{{ route('home') }}"
                   class="group inline-flex items-center gap-2 text-sm font-bold text-white/70 transition-all duration-300 hover:text-white sm:text-base">
                    <svg class="h-4 w-4 rotate-180 transition-transform duration-300 group-hover:-translate-x-1 sm:h-5 sm:w-5"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Toko
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <p class="mt-6 px-4 text-center text-[10px] text-white/40 sm:mt-8 sm:text-xs">
            UP Cireng Admin © {{ date('Y') }} • Sistem Manajemen Toko Modern
        </p>
    </div>
</div>
@endsection