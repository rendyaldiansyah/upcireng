@extends('layout.app')

@section('title', 'Tulis Testimoni - UP Cireng')

@section('content')
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#f8fafc_100%)] min-h-screen">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- ── Form Card ── --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-xl shadow-brand-500/20 sm:p-8">

            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Bagikan Pengalaman</p>
                    <h1 class="mt-2 text-2xl font-black text-slate-900 sm:mt-3 sm:text-3xl">Tulis testimoni Anda</h1>
                    <p class="mt-2 text-sm leading-7 text-slate-600 sm:mt-3">
                        Testimoni Anda akan ditinjau admin kami dan ditampilkan di halaman testimoni.
                    </p>
                </div>
                <div class="flex shrink-0 items-start">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-brand-300 hover:text-brand-600 sm:px-4 sm:text-sm whitespace-nowrap">
                        ← Kembali ke Toko
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('testimonial.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user?->name) }}"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', $user?->email) }}"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" required>
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
                    <textarea name="message" rows="6"
                              class="w-full rounded-[1.5rem] border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100"
                              required placeholder="Bagikan pengalaman Anda berbelanja dengan kami...">{{ old('message') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-ink-950 to-brand-600 px-5 py-3 text-sm font-bold text-white transition duration-300 hover:shadow-lg hover:scale-105">
                    ✓ Kirim Testimoni
                </button>
            </form>
        </div>

        {{-- ── Testimoni Saya ── --}}
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-black text-slate-900">Testimoni Saya</h2>
                @if(isset($myTestimonials) && $myTestimonials->count() > 0)
                    <span class="rounded-full bg-brand-100 px-3 py-1 text-xs font-bold text-brand-600">
                        {{ $myTestimonials->count() }} testimoni
                    </span>
                @endif
            </div>

            @if(isset($myTestimonials) && $myTestimonials->count() > 0)
                <div class="space-y-3">
                    @foreach($myTestimonials as $t)
                    @php
                        $sc = match($t->status ?? 'pending') {
                            'approved' => ['bg'=>'bg-emerald-50','border'=>'border-emerald-200','badge'=>'bg-emerald-100 text-emerald-700','label'=>'✅ Ditampilkan'],
                            'rejected' => ['bg'=>'bg-rose-50',  'border'=>'border-rose-200',  'badge'=>'bg-rose-100 text-rose-700',  'label'=>'❌ Ditolak'],
                            default    => ['bg'=>'bg-amber-50', 'border'=>'border-amber-200', 'badge'=>'bg-amber-100 text-amber-700', 'label'=>'⏳ Menunggu Review'],
                        };
                    @endphp
                    <div class="rounded-2xl {{ $sc['bg'] }} border {{ $sc['border'] }} p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-amber-400 text-sm">
                                        {{ str_repeat('★', $t->rating ?? 5) }}{{ str_repeat('☆', 5 - ($t->rating ?? 5)) }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        {{ optional($t->created_at)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $t->message }}</p>
                            </div>
                            <span class="flex-shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold whitespace-nowrap {{ $sc['badge'] }}">
                                {{ $sc['label'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white px-6 py-8 text-center">
                    <p class="text-2xl mb-2">✍️</p>
                    <p class="text-sm font-semibold text-slate-500">Anda belum pernah menulis testimoni.</p>
                </div>
            @endif
        </div>

        <p class="mt-8 text-center text-xs font-medium text-slate-400">
            &copy; {{ date('Y') }} UP Cireng. All rights reserved.
        </p>
    </div>
</div>
@endsection