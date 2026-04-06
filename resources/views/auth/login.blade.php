@extends('layout.app')

@section('title', 'Login & Daftar | UP Cireng')
@section('hide_footer', '1')

@section('content')
<section class="relative px-4 py-8 sm:py-10 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 sm:gap-8 lg:grid-cols-[1fr_1.05fr]">

        {{-- ===== LEFT: Brand Info ===== --}}
        <div class="flex flex-col justify-between rounded-2xl bg-gradient-to-br from-ink-950 to-brand-700 p-6 text-white shadow-xl sm:rounded-3xl sm:p-8 lg:p-10">
            <div>
                {{-- Logo --}}
                <div class="mb-6 inline-flex items-center gap-3 sm:mb-8">
                    <img src="{{ asset('assets/assets/logo.png') }}"
                         alt="UP Cireng"
                         class="h-10 w-10 rounded-xl border-2 border-white/20 object-cover sm:h-12 sm:w-12">
                    <div>
                        <p class="display-font text-lg font-extrabold sm:text-xl">UP Cireng</p>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-brand-200 sm:text-xs">Order & Manage</p>
                    </div>
                </div>

                <h1 class="display-font mb-4 text-2xl font-extrabold leading-tight sm:mb-6 sm:text-4xl lg:text-5xl">
                    Pesan cireng favorit dengan mudah
                </h1>

                <p class="mb-6 max-w-md text-sm leading-7 text-white/90 sm:mb-8 sm:text-base sm:leading-8">
                    Login untuk melanjutkan checkout, cek riwayat pesanan, dan memberikan testimoni tentang produk kami.
                </p>

                {{-- Features --}}
                <div class="space-y-3 sm:space-y-4">
                    @php
                        $features = [
                            ['title' => 'Order Cepat',       'desc' => 'Checkout hanya butuh beberapa klik'],
                            ['title' => 'Riwayat Pesanan',   'desc' => 'Lihat semua pesanan dan statusnya'],
                            ['title' => 'Testimoni',         'desc' => 'Bagikan pengalaman mu dengan kami'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 rounded-full bg-brand-500 p-1.5 sm:p-2">
                                <svg class="h-3.5 w-3.5 text-white sm:h-4 sm:w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white sm:text-base">{{ $feature['title'] }}</p>
                                <p class="text-xs text-white/80 sm:text-sm">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Testimonial quote — hidden on small screens to save space --}}
            <div class="mt-8 hidden border-t border-white/10 pt-6 sm:block lg:mt-10 lg:pt-8">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-brand-200 sm:mb-3 sm:text-xs">Apa Kata Pelanggan</p>
                <blockquote class="text-sm italic text-white/90">
                    "Sistemnya simpel banget, order langsung masuk ke admin, dan aku bisa lihat status pesanan kapan saja. Recommended!"
                </blockquote>
                <p class="mt-2.5 text-sm font-semibold text-white sm:mt-3">— Budi, Customer Setia</p>
            </div>
        </div>

        {{-- ===== RIGHT: Forms ===== --}}
        <div class="grid gap-5 sm:gap-6">

            {{-- ---- Login Form ---- --}}
            <section class="rounded-2xl border border-white/80 bg-white p-6 shadow-lg sm:rounded-3xl sm:p-8 lg:p-10">
                <div class="mb-6 sm:mb-8">
                    <p class="mb-1.5 text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:mb-2 sm:text-sm">Login</p>
                    <h2 class="display-font mb-2 text-2xl font-extrabold text-ink-950 sm:mb-3 sm:text-3xl lg:text-4xl">
                        Masuk ke Akun
                    </h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Sudah punya akun? Login sekarang untuk melanjutkan berbelanja.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-5 flex items-start gap-3 rounded-xl border-2 border-rose-200 bg-rose-50 px-4 py-3 sm:mb-6 sm:rounded-2xl sm:py-4">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-rose-600 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-sm font-semibold text-rose-700">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('auth.login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="login_email" class="mb-2 block text-sm font-bold text-slate-700">Email Address</label>
                        <input
                            id="login_email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="nama@example.com"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                            required
                        >
                    </div>

                    <div>
                        <label for="login_password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                        <input
                            id="login_password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="mt-4 w-full rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3 text-sm font-bold text-white transition duration-300 hover:scale-105 hover:shadow-lg sm:mt-6 sm:py-3.5"
                    >
                        Login Sekarang
                    </button>
                </form>

                <p class="mt-4 text-center text-sm text-slate-600">
                    Lupa password?
                    <a href="#" class="font-bold text-brand-500 hover:text-brand-600">Reset di sini</a>
                </p>
            </section>

            {{-- ---- Register Form ---- --}}
            <section class="rounded-2xl border border-white/80 bg-white p-6 shadow-lg sm:rounded-3xl sm:p-8 lg:p-10">
                <div class="mb-6 sm:mb-8">
                    <p class="mb-1.5 text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:mb-2 sm:text-sm">Daftar</p>
                    <h2 class="display-font mb-2 text-2xl font-extrabold text-ink-950 sm:mb-3 sm:text-3xl lg:text-4xl">
                        Buat Akun Baru
                    </h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Belum punya akun? Daftar sekarang dan mulai berbelanja!
                    </p>
                </div>

                <form action="{{ route('auth.register') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Name & Email side by side on sm+ --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="register_name" class="mb-2 block text-sm font-bold text-slate-700">Nama Lengkap</label>
                            <input
                                id="register_name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                placeholder="Nama kamu"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                                required
                            >
                        </div>

                        <div>
                            <label for="register_email" class="mb-2 block text-sm font-bold text-slate-700">Email Address</label>
                            <input
                                id="register_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                placeholder="nama@example.com"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="register_phone" class="mb-2 block text-sm font-bold text-slate-700">No. WhatsApp</label>
                        <input
                            id="register_phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="08xx xxxx xxxx"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                            required
                        >
                    </div>

                    {{-- Password fields side by side on sm+ --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="register_password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                            <input
                                id="register_password"
                                type="password"
                                name="password"
                                placeholder="Min. 8 karakter"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                                required
                            >
                        </div>

                        <div>
                            <label for="register_password_confirm" class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi</label>
                            <input
                                id="register_password_confirm"
                                type="password"
                                name="password_confirmation"
                                placeholder="Ulangi password"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-2 focus:ring-brand-100 sm:py-3.5"
                                required
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="mt-4 w-full rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-5 py-3 text-sm font-bold text-white transition duration-300 hover:scale-105 hover:shadow-lg sm:mt-6 sm:py-3.5"
                    >
                        Daftar Sekarang
                    </button>
                </form>

                <p class="mt-4 text-center text-xs text-slate-500">
                    Dengan mendaftar, kamu setuju dengan
                    <a href="#" class="underline hover:text-slate-700">Syarat & Ketentuan</a>
                </p>
            </section>

        </div>{{-- /right grid --}}
    </div>
</section>
@endsection