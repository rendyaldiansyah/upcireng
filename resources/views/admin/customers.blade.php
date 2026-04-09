{{-- resources/views/admin/customers.blade.php --}}
@extends('layout.app')

@section('title', 'Manajemen Customer — Admin')
@section('hide_nav', '1')
@section('hide_footer', '1')
@section('body_class', 'bg-[#F7F8FA] text-slate-900')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }
        body { background: #F7F8FA; }

        :root {
            --bg:       #F7F8FA;
            --surface:  #FFFFFF;
            --border:   #EEF0F4;
            --border-2: #E4E7ED;
            --text-1:   #0F172A;
            --text-2:   #475569;
            --text-3:   #94A3B8;
            --accent:   #0EA5E9;
            --accent-bg:#F0F9FF;
            --green:    #10B981;
            --amber:    #F59E0B;
            --red:      #EF4444;
            --r-card:   18px;
            --shadow-sm: 0 2px 6px rgba(15,23,42,0.04), 0 1px 2px rgba(15,23,42,0.03);
            --shadow-md: 0 6px 18px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.03);
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--surface);
            border-radius: var(--r-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 1.5rem 1.8rem;
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        .stat-label {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-3);
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            line-height: 1.2;
            margin-top: 0.5rem;
        }

        /* ── Search Form ── */
        .search-wrapper {
            background: var(--surface);
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s;
        }
        .search-wrapper:focus-within {
            box-shadow: 0 0 0 3px rgba(14,165,233,0.15);
            border-color: var(--accent);
        }
        .search-input {
            background: transparent;
            border: none;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-1);
            width: 100%;
            outline: none;
        }
        .search-input::placeholder { color: var(--text-3); font-weight: 400; }

        /* ── Table ── */
        .table-container {
            background: var(--surface);
            border-radius: var(--r-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        .table-custom th {
            text-align: left;
            padding: 1.1rem 1.5rem;
            background: #F8FAFC;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-3);
            border-bottom: 1px solid var(--border);
        }
        .table-custom td {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table-custom tr:last-child td { border-bottom: none; }
        .table-custom tbody tr {
            transition: background 0.15s;
        }
        .table-custom tbody tr:hover {
            background: #F8FAFC;
        }

        /* Badge */
        .badge-order {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-bg);
            color: var(--accent);
            font-weight: 800;
            font-size: 0.75rem;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            border: 1px solid rgba(14,165,233,0.2);
        }

        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 1.1rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            background: white;
            border: 1px solid var(--border);
            color: var(--text-2);
            transition: all 0.15s;
            text-decoration: none;
            cursor: pointer;
        }
        .action-btn:hover {
            background: #F8FAFC;
            border-color: var(--border-2);
            color: var(--text-1);
        }
        .action-btn-danger:hover {
            background: #FEF2F2;
            border-color: #FECACA;
            color: #B91C1C;
        }
        .action-btn-edit:hover {
            background: #FFFBEB;
            border-color: #FDE68A;
            color: #B45309;
        }

        /* Modal */
        .modal-overlay {
            background: rgba(15,23,42,0.5);
            backdrop-filter: blur(4px);
        }
        .modal-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .modal-input {
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.85rem 1.2rem;
            font-weight: 500;
            transition: all 0.15s;
            width: 100%;
            outline: none;
        }
        .modal-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
            background: white;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.3rem;
        }
        .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.8rem;
            border-radius: 10px;
            background: white;
            border: 1px solid var(--border);
            font-weight: 600;
            color: var(--text-2);
            transition: all 0.15s;
        }
        .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }
        .page-item .page-link:hover {
            background: #F8FAFC;
            border-color: var(--border-2);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-enter {
            animation: fadeIn 0.2s ease-out;
        }

        /* Button Umum */
        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 14px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 4px 8px rgba(14,165,233,0.2);
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #0284C7;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(14,165,233,0.25);
        }
        .btn-secondary {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            color: var(--text-2);
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-secondary:hover {
            background: #F8FAFC;
            border-color: var(--border-2);
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    @include('admin.partials.sidebar')

    <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        {{-- ── Breadcrumb ── --}}
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition">Dashboard</a>
            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700">Customer</span>
        </nav>

        {{-- ── Page Header ── --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    Manajemen Customer
                </h1>
                <p class="mt-1.5 text-sm text-slate-500 font-medium">Lihat, cari, dan kelola data semua pelanggan.</p>
            </div>
        </div>

        {{-- ── Stats ── --}}
        <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            @php
                $statCards = [
                    ['label' => 'Total Customer', 'value' => $stats['total'], 'color' => 'text-sky-600', 'icon' => '👥'],
                    ['label' => 'Daftar Hari Ini', 'value' => $stats['today'], 'color' => 'text-emerald-600', 'icon' => '📅'],
                    ['label' => 'Minggu Ini',      'value' => $stats['week'],  'color' => 'text-violet-600', 'icon' => '📈'],
                ];
            @endphp
            @foreach($statCards as $sc)
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <span class="stat-label">{{ $sc['label'] }}</span>
                        <span class="text-2xl">{{ $sc['icon'] }}</span>
                    </div>
                    <p class="stat-value {{ $sc['color'] }}">{{ $sc['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ── Alerts ── --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-semibold text-emerald-700">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-sm font-semibold text-rose-700">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Search ── --}}
        <form method="GET" action="{{ route('admin.customers') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="search-wrapper flex-1">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama, email, atau nomor HP..."
                               class="search-input">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary px-6">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers') }}" class="btn-secondary px-5">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- ── Table ── --}}
        <div class="table-container">
            <div class="overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th class="text-center">Pesanan</th>
                            <th>Daftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                {{-- Avatar + Nama --}}
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-100 to-sky-200 text-sm font-black text-sky-700 shadow-sm">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $customer->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">#{{ $customer->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                {{-- Email --}}
                                <td>
                                    @if($customer->email)
                                        <p class="font-medium text-slate-700">{{ $customer->email }}</p>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-400">
                                            Tidak ada
                                        </span>
                                    @endif
                                </td>
                                {{-- Phone --}}
                                <td>
                                    <p class="font-medium text-slate-700">{{ $customer->phone ?? '-' }}</p>
                                </td>
                                {{-- Order Count --}}
                                <td class="text-center">
                                    <span class="badge-order">
                                        {{ $customer->orders_count }}
                                    </span>
                                </td>
                                {{-- Tanggal Daftar --}}
                                <td>
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $customer->created_at->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $customer->created_at->diffForHumans() }}</p>
                                </td>
                                {{-- Actions --}}
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Detail --}}
                                        <a href="{{ route('admin.customer.detail', $customer) }}"
                                           class="action-btn">
                                            Detail
                                        </a>
                                        {{-- Edit (modal trigger) --}}
                                        <button type="button"
                                                data-edit-customer="{{ $customer->id }}"
                                                data-name="{{ $customer->name }}"
                                                data-email="{{ $customer->email }}"
                                                data-phone="{{ $customer->phone }}"
                                                class="action-btn action-btn-edit">
                                            Edit
                                        </button>
                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.customer.delete', $customer) }}"
                                              onsubmit="return confirm('Hapus customer {{ addslashes($customer->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="action-btn action-btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-20 text-center">
                                    <div class="mx-auto max-w-xs">
                                        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100">
                                            <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-slate-700">Tidak ada customer ditemukan</p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            @if(request('search'))
                                                Coba kata kunci pencarian yang berbeda.
                                            @else
                                                Customer akan muncul di sini setelah melakukan login pertama kali.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($customers->hasPages())
                <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between">
                    <div class="text-xs text-slate-400">
                        Menampilkan {{ $customers->firstItem() }}–{{ $customers->lastItem() }} dari {{ $customers->total() }} customer
                    </div>
                    <div class="pagination">
                        {{ $customers->links() }}
                    </div>
                </div>
            @elseif($customers->count() > 0)
                <div class="border-t border-slate-100 px-6 py-3 text-xs text-slate-400">
                    Menampilkan {{ $customers->count() }} customer
                </div>
            @endif
        </div>

    </main>
</div>

{{-- ═══════════════════════ EDIT MODAL ═══════════════════════ --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 modal-overlay">
    {{-- Backdrop --}}
    <div id="editBackdrop" class="absolute inset-0"></div>

    <div class="relative w-full max-w-md modal-card p-6 sm:p-7 modal-enter">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-900">Edit Data Customer</h2>
            <button type="button" id="closeEditModal"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="editName"
                       class="modal-input"
                       required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Email</label>
                <input type="email" name="email" id="editEmail"
                       placeholder="Kosongkan jika tidak ada"
                       class="modal-input">
                <p class="mt-1.5 text-xs text-slate-400">Opsional — customer mungkin tidak memiliki email.</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Nomor HP <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" id="editPhone"
                       class="modal-input"
                       required>
            </div>

            <div class="flex gap-3 pt-3">
                <button type="button" id="cancelEditModal"
                        class="flex-1 btn-secondary">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const editModal    = document.getElementById('editModal');
    const editForm     = document.getElementById('editForm');
    const editName     = document.getElementById('editName');
    const editEmail    = document.getElementById('editEmail');
    const editPhone    = document.getElementById('editPhone');
    const closeEdit    = document.getElementById('closeEditModal');
    const cancelEdit   = document.getElementById('cancelEditModal');
    const editBackdrop = document.getElementById('editBackdrop');

    document.querySelectorAll('[data-edit-customer]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id    = this.dataset.editCustomer;
            const name  = this.dataset.name;
            const email = this.dataset.email;
            const phone = this.dataset.phone;

            editForm.action = `/adminup/customers/${id}`;
            editName.value  = name || '';
            editEmail.value = email !== 'null' ? (email || '') : '';
            editPhone.value = phone || '';

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        });
    });

    function closeModal() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    }

    closeEdit.addEventListener('click', closeModal);
    cancelEdit.addEventListener('click', closeModal);
    editBackdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection