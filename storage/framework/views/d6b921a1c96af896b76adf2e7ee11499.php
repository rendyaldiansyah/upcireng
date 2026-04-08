


<?php $__env->startSection('title', 'Manajemen Customer — Admin'); ?>
<?php $__env->startSection('hide_nav', '1'); ?>
<?php $__env->startSection('hide_footer', '1'); ?>
<?php $__env->startSection('body_class', 'bg-slate-50 text-slate-900'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-slate-700 transition">Dashboard</a>
            <span>›</span>
            <span class="text-slate-700">Customer</span>
        </nav>

        
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="display-font text-2xl font-black text-slate-900 sm:text-3xl">
                    Manajemen Customer
                </h1>
                <p class="mt-1 text-sm text-slate-500">Lihat, cari, dan kelola data semua pelanggan.</p>
            </div>
        </div>

        
        <div class="mb-8 grid grid-cols-3 gap-4">
            <?php
                $statCards = [
                    ['label' => 'Total Customer', 'value' => $stats['total'], 'color' => 'text-sky-600', 'bg' => 'bg-sky-50'],
                    ['label' => 'Daftar Hari Ini', 'value' => $stats['today'], 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                    ['label' => 'Minggu Ini',      'value' => $stats['week'],  'color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
                ];
            ?>
            <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400"><?php echo e($sc['label']); ?></p>
                    <p class="mt-2 text-3xl font-black <?php echo e($sc['color']); ?>"><?php echo e($sc['value']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if(session('success')): ?>
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <form method="GET" action="<?php echo e(route('admin.customers')); ?>" class="mb-6">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                           placeholder="Cari nama, email, atau nomor HP..."
                           class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                </div>
                <button type="submit"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-sky-400 hover:text-sky-600">
                    Cari
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('admin.customers')); ?>"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-500 transition hover:text-slate-700">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>

        
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Customer</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">No. HP</th>
                            <th class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">Pesanan</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Daftar</th>
                            <th class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="group hover:bg-slate-50/50 transition">
                                
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sm font-black text-sky-600">
                                            <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900"><?php echo e($customer->name); ?></p>
                                            <p class="text-[11px] text-slate-400">#<?php echo e($customer->id); ?></p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-5 py-4">
                                    <?php if($customer->email): ?>
                                        <p class="font-medium text-slate-700"><?php echo e($customer->email); ?></p>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-400">
                                            Tidak ada
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-700"><?php echo e($customer->phone ?? '-'); ?></p>
                                </td>
                                
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center justify-center rounded-full bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                        <?php echo e($customer->orders_count); ?>

                                    </span>
                                </td>
                                
                                <td class="px-5 py-4">
                                    <p class="text-xs font-medium text-slate-500">
                                        <?php echo e($customer->created_at->translatedFormat('d M Y')); ?>

                                    </p>
                                    <p class="text-[11px] text-slate-400"><?php echo e($customer->created_at->diffForHumans()); ?></p>
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <a href="<?php echo e(route('admin.customer.detail', $customer)); ?>"
                                           class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-sky-300 hover:text-sky-600">
                                            Detail
                                        </a>
                                        
                                        <button type="button"
                                                data-edit-customer="<?php echo e($customer->id); ?>"
                                                data-name="<?php echo e($customer->name); ?>"
                                                data-email="<?php echo e($customer->email); ?>"
                                                data-phone="<?php echo e($customer->phone); ?>"
                                                class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-amber-300 hover:text-amber-600">
                                            Edit
                                        </button>
                                        
                                        <form method="POST" action="<?php echo e(route('admin.customer.delete', $customer)); ?>"
                                              onsubmit="return confirm('Hapus customer <?php echo e(addslashes($customer->name)); ?>? Tindakan ini tidak dapat dibatalkan.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-rose-300 hover:text-rose-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="mx-auto max-w-xs">
                                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                                            <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <p class="font-bold text-slate-600">Tidak ada customer ditemukan</p>
                                        <p class="mt-1 text-sm text-slate-400">
                                            <?php if(request('search')): ?>
                                                Coba kata kunci pencarian yang berbeda.
                                            <?php else: ?>
                                                Customer akan muncul di sini setelah melakukan login pertama kali.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <?php if($customers->hasPages()): ?>
                <div class="border-t border-slate-100 px-5 py-4">
                    <?php echo e($customers->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <?php if($customers->count() > 0): ?>
        <p class="mt-3 text-xs text-slate-400">
            Menampilkan <?php echo e($customers->firstItem()); ?>–<?php echo e($customers->lastItem()); ?> dari <?php echo e($customers->total()); ?> customer
        </p>
        <?php endif; ?>

    </main>
</div>


<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    
    <div id="editBackdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl sm:p-7">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="display-font text-lg font-black text-slate-900">Edit Data Customer</h2>
            <button type="button" id="closeEditModal"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-slate-700 transition">
                ✕
            </button>
        </div>

        <form id="editForm" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="editName"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100"
                       required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Email</label>
                <input type="email" name="email" id="editEmail"
                       placeholder="Kosongkan jika tidak ada"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                <p class="mt-1 text-xs text-slate-400">Opsional — customer mungkin tidak memiliki email.</p>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Nomor HP <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" id="editPhone"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-100"
                       required>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" id="cancelEditModal"
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\admin\customers.blade.php ENDPATH**/ ?>