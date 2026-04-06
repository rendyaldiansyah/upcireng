@extends('layout.app')

@section('title', 'Tulis Testimoni - UP Cireng')

@section('content')
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#f8fafc_100%)]">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] bg-white p-8 shadow-xl shadow-brand-500/20">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Bagikan Pengalaman</p>
                    <h1 class="mt-3 text-3xl font-black text-slate-900">Tulis testimoni Anda</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Testimoni Anda akan ditinjau admin kami dan ditampilkan di halaman testimoni untuk pelanggan lainnya.
                    </p>
                </div>
                <a href="{{ route('testimonial.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-brand-300 hover:text-brand-600">
                    ← Kembali
                </a>
            </div>

            @if($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('testimonial.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user?->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', $user?->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Rating</label>
                    <select name="rating" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating', 5) == $i)>{{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Isi Testimoni</label>
                    <textarea name="message" rows="6" class="w-full rounded-[1.5rem] border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required placeholder="Bagikan pengalaman Anda berbelanja dengan kami...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3 text-sm font-bold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                    ✓ Kirim Testimoni
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
