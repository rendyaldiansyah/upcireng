@extends('layout.app')

@section('title', 'Manage Testimonials - UP Cireng Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-mist-50 text-slate-900')

@section('content')
@php
    $adminSidebarTitle = 'Review Moderation';
    $adminSidebarMetricLabel = 'Testimonials';
    $adminSidebarMetricValue = $testimonials->total();
    $adminSidebarBody = 'Approve and moderate customer feedback with inline editing.';
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
            <span class="font-semibold text-slate-900">Testimonials</span>
        </nav>

        {{-- Hero Header --}}
        <section class="mb-8 rounded-2xl bg-gradient-to-r from-slate-50 to-brand-50 p-5 sm:p-7 shadow-md border border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-500 mb-1">Customer Reviews</p>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Testimonial Management</h1>
                    <p class="mt-1 text-sm text-slate-500">Moderate, edit, and approve customer testimonials.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="self-start sm:self-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition-all whitespace-nowrap">
                    ← Dashboard
                </a>
            </div>
        </section>

        {{-- Status Metrics --}}
        <section class="grid grid-cols-2 gap-3 mb-8">
            <div class="rounded-xl bg-amber-50 border border-amber-200/60 p-4 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600 mb-1">Pending Review</p>
                <p class="text-2xl font-black text-amber-800">{{ $approvalCounts['pending'] ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200/60 p-4 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1">Approved</p>
                <p class="text-2xl font-black text-emerald-800">{{ $approvalCounts['approved'] ?? 0 }}</p>
            </div>
        </section>

        {{-- Filter Bar --}}
        <section class="mb-8 rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100">
            <h2 class="text-base font-bold text-ink-950 mb-4">Filter Testimonials</h2>
            <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">Search</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input name="search" value="{{ request('search') }}" placeholder="Customer name or content..."
                               class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">Status</label>
                    <select name="approval" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                        <option value="">All Status</option>
                        <option value="pending"  @selected(request('approval') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('approval') === 'approved')>Approved</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-4 py-2 text-sm font-bold text-white hover:from-brand-500 hover:to-brand-600 hover:shadow-md transition-all">
                        Apply Filter
                    </button>
                    <a href="{{ route('admin.testimonials') }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 transition whitespace-nowrap">
                        Clear
                    </a>
                </div>
            </form>
        </section>

        {{-- Testimonials Grid --}}
        <div class="grid gap-4">
            @forelse($testimonials as $testimonial)
                <article class="relative group rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    <div class="flex flex-col lg:flex-row lg:items-start lg:gap-6">

                        {{-- Left: Review Content --}}
                        <div class="lg:flex-1">
                            {{-- Customer Header --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                                        {{ Str::substr($testimonial->customer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-ink-950 leading-tight">{{ $testimonial->customer_name }}</h3>
                                        <p class="text-xs text-slate-500">{{ $testimonial->customer_email }}</p>
                                    </div>
                                </div>
                                <span class="ml-2 px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap
                                    {{ $testimonial->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $testimonial->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </div>

                            {{-- Stars --}}
                            <div class="flex items-center gap-1 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>

                            {{-- Message --}}
                            <blockquote class="bg-slate-50 rounded-xl px-4 py-3 text-sm leading-relaxed text-slate-700 border-l-2 border-brand-300">
                                "{{ $testimonial->message }}"
                            </blockquote>
                        </div>

                        {{-- Right: Actions --}}
                        <div class="mt-5 lg:mt-0 lg:w-64 lg:flex-shrink-0 space-y-3">

                            {{-- Approve --}}
                            @if(!$testimonial->is_approved)
                                <form action="{{ route('admin.testimonial.approve', $testimonial) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:shadow-md">
                                        ✓ Approve Review
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-2 bg-emerald-50 rounded-xl text-xs font-bold text-emerald-700 border border-emerald-200">
                                    ✓ Already Approved
                                </div>
                            @endif

                            {{-- Edit Form --}}
                            <form action="{{ route('admin.testimonial.edit', $testimonial) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')

                                {{-- Star Rating --}}
                                <label class="block text-xs font-bold text-slate-700 mb-1">Edit Rating</label>
                                <div class="flex items-center gap-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}"
                                                   {{ $testimonial->rating == $i ? 'checked' : '' }}
                                                   class="sr-only">
                                            <svg class="w-6 h-6 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-slate-300 hover:text-amber-300' }} transition"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>

                                <textarea name="message" rows="3"
                                          class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-slate-50 focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition resize-none">{{ old('message', $testimonial->message) }}</textarea>

                                <button type="submit"
                                        class="w-full rounded-xl bg-ink-950 hover:bg-brand-600 px-4 py-2 text-sm font-bold text-white transition hover:shadow-md">
                                    Save Changes
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form action="{{ route('admin.testimonial.delete', $testimonial) }}" method="POST"
                                  onsubmit="return confirm('Permanently delete this testimonial?')"
                                  class="pt-2 border-t border-slate-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full rounded-xl border border-rose-200 px-4 py-2 text-sm font-bold text-rose-600 bg-white hover:bg-rose-50 hover:border-rose-400 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-20 rounded-2xl bg-white shadow-sm border-2 border-dashed border-slate-200">
                    <svg class="mx-auto w-12 h-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-xl font-black text-ink-950 mb-2">No Testimonials</h3>
                    <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">No reviews match your current filters.</p>
                    <div class="flex flex-col sm:flex-row gap-2 justify-center">
                        <a href="{{ route('admin.testimonials') }}"
                           class="rounded-xl bg-brand-500 hover:bg-brand-600 px-5 py-2 text-sm font-bold text-white transition">
                            Clear Filters
                        </a>
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 transition">
                            ← Dashboard
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($testimonials->hasPages())
            <div class="flex justify-center mt-8">
                {{ $testimonials->links() }}
            </div>
        @endif

    </main>
</div>
@endsection