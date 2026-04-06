<?php $__env->startSection('title', 'Kelola Pesanan - UP Cireng Admin'); ?>
<?php $__env->startSection('hide_nav', '1'); ?>
<?php $__env->startSection('hide_footer', '1'); ?>
<?php $__env->startSection('body_class', 'bg-mist-50 text-slate-900'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $adminSidebarTitle = 'Order Management';
    $adminSidebarMetricLabel = 'Filtered Results';
    $adminSidebarMetricValue = $orders->total();
    $adminSidebarBody = 'Full order workflow with status updates, customer details, and payment verification.';
?>

<div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="px-4 py-6 sm:px-6 lg:px-8">

        
        <nav class="mb-6 flex items-center space-x-2 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-brand-500">Dashboard</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-semibold text-slate-900">Orders</span>
        </nav>

        
        <section class="mb-8 rounded-2xl bg-gradient-to-r from-slate-50 to-brand-50 p-5 sm:p-7 shadow-md border border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-brand-500 mb-1">Order Operations</p>
                    <h1 class="text-2xl sm:text-3xl font-black text-ink-950">Manage All Orders</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Complete control over customer orders with instant status updates.
                    </p>
                </div>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="self-start sm:self-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-brand-400 hover:shadow transition-all whitespace-nowrap">
                    ← Dashboard
                </a>
            </div>
        </section>

        
        <section class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3 mb-8">
            <?php $__currentLoopData = [
                ['label' => 'Pending',    'key' => 'pending',    'color' => 'amber'],
                ['label' => 'Processing', 'key' => 'processing', 'color' => 'sky'],
                ['label' => 'Delivering', 'key' => 'delivering', 'color' => 'violet'],
                ['label' => 'Completed',  'key' => 'completed',  'color' => 'emerald'],
                ['label' => 'Cancelled',  'key' => 'cancelled',  'color' => 'rose'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-xl bg-<?php echo e($stat['color']); ?>-50 border border-<?php echo e($stat['color']); ?>-200/60 p-4 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-<?php echo e($stat['color']); ?>-600 mb-1"><?php echo e($stat['label']); ?></p>
                <p class="text-2xl font-black text-<?php echo e($stat['color']); ?>-800"><?php echo e($statusCounts[$stat['key']] ?? 0); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>

        
        <section class="mb-8 rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100">
            <h2 class="text-base font-bold text-ink-950 mb-4">Advanced Filter</h2>
            <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">Search</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Reference, name, email..."
                               class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                        <option value="">All Status</option>
                        <?php $__currentLoopData = \App\Models\Order::statusOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">From Date</label>
                    <input type="date" name="from_date" value="<?php echo e(request('from_date')); ?>"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold text-slate-700">To Date</label>
                    <input type="date" name="to_date" value="<?php echo e(request('to_date')); ?>"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-ink-950 to-brand-600 px-4 py-2 text-sm font-bold text-white hover:from-brand-500 hover:to-brand-600 hover:shadow-md transition-all">
                        Filter Orders
                    </button>
                </div>
            </form>
        </section>

        
        <section class="grid gap-4 mb-8">
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="relative group rounded-2xl bg-white p-5 sm:p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-brand-500/5 to-transparent"></div>

                    <div class="relative flex flex-col lg:flex-row lg:items-start lg:gap-6">

                        
                        <div class="lg:flex-1">
                            
                            <div class="flex items-start justify-between mb-5">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-0.5">Order #<?php echo e($order->reference); ?></p>
                                    <h2 class="text-lg font-black text-ink-950"><?php echo e($order->customer_name); ?></h2>
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mt-0.5">
                                        <span><?php echo e($order->customer_phone); ?></span>
                                        <span><?php echo e($order->customer_email); ?></span>
                                    </div>
                                </div>
                                <span class="ml-2 px-3 py-1 rounded-lg text-xs font-bold <?php echo e($order->status_color); ?> bg-opacity-20 whitespace-nowrap">
                                    <?php echo e($order->status_label); ?>

                                </span>
                            </div>

                            
                            <div class="mb-5">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Order Items</h4>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $order->items_summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 group-hover:bg-slate-100 transition-colors">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-bold text-slate-500"><?php echo e($item['product_name'][0]); ?></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate"><?php echo e($item['product_name']); ?></p>
                                                <?php if($item['variant']): ?>
                                                    <p class="text-xs text-slate-400"><?php echo e($item['variant']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <p class="text-sm font-black text-ink-950">x<?php echo e($item['quantity']); ?></p>
                                                <p class="text-xs font-bold text-brand-600">Rp <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="lg:w-72 lg:flex-shrink-0 space-y-4">

                            
                            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Payment Details</p>
                                <p class="text-xl font-black text-ink-950 mb-1"><?php echo e($order->formatPrice()); ?></p>
                                <p class="text-xs font-bold uppercase text-slate-500 mb-3"><?php echo e($order->payment_method); ?></p>
                                <?php if($order->payment_proof_url): ?>
                                    <a href="<?php echo e($order->payment_proof_url); ?>" target="_blank"
                                       class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-bold text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        View Proof
                                    </a>
                                <?php endif; ?>
                            </div>

                            
                            <form action="<?php echo e(route('admin.order.status', $order)); ?>" method="POST" class="space-y-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Update Status</label>
                                <select name="status"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition">
                                    <?php $__currentLoopData = \App\Models\Order::statusOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>" <?php if($order->status === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <textarea name="cancel_reason" rows="2"
                                          placeholder="Reason (if cancelled)"
                                          class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition resize-none"><?php echo e(old('cancel_reason', $order->cancel_reason)); ?></textarea>
                                <button type="submit"
                                        class="w-full rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-bold text-white transition hover:shadow-md">
                                    Update Status
                                </button>
                            </form>

                            
                            <form action="<?php echo e(route('admin.order.delete', $order)); ?>" method="POST"
                                  class="pt-3 border-t border-slate-200"
                                  onsubmit="return confirm('Delete this order permanently?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        class="w-full rounded-xl border border-rose-200 px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-400 transition bg-white">
                                    Delete Order
                                </button>
                            </form>
                        </div>
                    </div>

                    
                    <?php if($order->sync_error): ?>
                        <div class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-rose-900">Sync Error</h4>
                                    <p class="text-sm text-rose-700 mt-0.5"><?php echo e($order->sync_error); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-20 rounded-2xl bg-white shadow-sm border-2 border-dashed border-slate-200">
                    <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-ink-950 mb-2">No Orders Found</h3>
                    <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">No orders match your current filters.</p>
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                       class="inline-block rounded-xl bg-ink-950 px-6 py-2.5 text-sm font-bold text-white hover:bg-brand-600 transition">
                        ← Back to Dashboard
                    </a>
                </div>
            <?php endif; ?>
        </section>

        
        <?php if($orders->hasPages()): ?>
            <div class="flex justify-center mt-8">
                <?php echo e($orders->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>

    </main>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/admin/orders.blade.php ENDPATH**/ ?>