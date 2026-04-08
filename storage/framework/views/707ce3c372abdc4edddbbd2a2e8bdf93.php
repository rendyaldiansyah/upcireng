<?php $__env->startSection('title', 'Order Manual - UP Cireng'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[linear-gradient(180deg,#fff7ed_0%,#f8fafc_100%)]">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] bg-white p-8 shadow-xl shadow-brand-500/20">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-500">Manual Order</p>
                    <h1 class="mt-3 text-3xl font-black text-slate-950">Buat pesanan sendiri</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Tidak bisa pakai cart? Gunakan form ini untuk buat order langsung ke admin kami.</p>
                </div>
                <a href="<?php echo e(route('home')); ?>" class="rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-brand-300 hover:text-brand-600">
                    Kembali ke storefront
                </a>
            </div>

            <?php if(!$storeOpen): ?>
                <div class="mt-6 rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-2-4a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2h4a2 2 0 002-2V1z" clip-rule="evenodd"></path></svg>
                    <div>
                        <p class="font-bold">Toko sedang tutup</p>
                        <p class="mt-1">Buka kembali pukul <?php echo e($hours['start']); ?> WIB</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('order.store')); ?>" method="POST" enctype="multipart/form-data" class="mt-8 space-y-5">
                <?php echo csrf_field(); ?>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Produk</label>
                        <select name="product_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?> required>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?> - <?php echo e($product->formatPrice()); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Varian</label>
                        <input type="text" name="variant" value="<?php echo e(old('variant')); ?>" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" placeholder="Opsional" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Jumlah</label>
                        <input type="number" name="quantity" min="1" value="<?php echo e(old('quantity', 1)); ?>" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?> required>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div>
                    <label class="mb-3 block text-sm font-semibold text-slate-700">Metode Pembayaran *</label>
                    <div class="grid gap-3 grid-cols-2 sm:grid-cols-4">
                        <!-- COD Card -->
                        <button type="button" class="payment-method-btn group rounded-2xl border-2 border-slate-200 px-4 py-4 text-center transition-all duration-300 hover:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2" data-method="cod" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                            <div class="text-2xl mb-2">💵</div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-brand-600">Cash</div>
                            <div class="text-xs text-slate-500 mt-1">Bayar di tempat</div>
                        </button>

                        <!-- Transfer Bank Card -->
                        <button type="button" class="payment-method-btn group rounded-2xl border-2 border-slate-200 px-4 py-4 text-center transition-all duration-300 hover:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2" data-method="bank_transfer" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                            <div class="text-2xl mb-2">🏦</div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-brand-600">Transfer</div>
                            <div class="text-xs text-slate-500 mt-1">Bank lokal</div>
                        </button>

                        <!-- E-Wallet Card -->
                        <button type="button" class="payment-method-btn group rounded-2xl border-2 border-slate-200 px-4 py-4 text-center transition-all duration-300 hover:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2" data-method="ewallet" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                            <div class="text-2xl mb-2">📱</div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-brand-600">E-Wallet</div>
                            <div class="text-xs text-slate-500 mt-1">OVO, DANA dll</div>
                        </button>

                        <!-- QRIS Card -->
                        <button type="button" class="payment-method-btn group rounded-2xl border-2 border-slate-200 px-4 py-4 text-center transition-all duration-300 hover:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2" data-method="qris" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                            <div class="text-2xl mb-2">📲</div>
                            <div class="text-sm font-semibold text-slate-700 group-hover:text-brand-600">QRIS</div>
                            <div class="text-xs text-slate-500 mt-1">QR Code</div>
                        </button>
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method" value="<?php echo e(old('payment_method', 'cod')); ?>" required>
                </div>

                <!-- Payment Details by Method -->
                <div class="space-y-5">
                    <!-- COD Details -->
                    <div id="method-cod" class="payment-details hidden space-y-3 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 p-5 animate-fade-in">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">💵</span>
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-900">Cash On Delivery</h4>
                                <p class="text-sm text-slate-600 mt-1">Bayar langsung ke kurir saat barang tiba. Tidak ada biaya tambahan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer Details -->
                    <div id="method-bank_transfer" class="payment-details hidden space-y-3 rounded-2xl bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 p-5 animate-fade-in">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🏦</span>
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-900">Transfer Bank</h4>
                                <p class="text-sm text-slate-600 mt-2">Silakan transfer ke salah satu rekening berikut:</p>
                                <div class="mt-4 space-y-3">
                                    <div class="rounded-xl bg-white p-3 border border-blue-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-xs font-semibold uppercase tracking-wider text-blue-600">Bank Jago</div>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-blue-100 text-xs font-bold text-blue-600 hover:bg-blue-200 transition" data-copy="105 3012 9xxx" title="Salin Nomor">📋 Salin</button>
                                        </div>
                                        <div class="font-mono text-sm font-bold text-slate-900 mt-1">105 3012 9xxx</div>
                                        <div class="text-xs text-slate-600 mt-1">a.n. Rendy Al Diansyah</div>
                                    </div>
                                    <div class="rounded-xl bg-white p-3 border border-blue-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-xs font-semibold uppercase tracking-wider text-blue-600">SeaBank</div>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-blue-100 text-xs font-bold text-blue-600 hover:bg-blue-200 transition" data-copy="901 067 9xxx" title="Salin Nomor">📋 Salin</button>
                                        </div>
                                        <div class="font-mono text-sm font-bold text-slate-900 mt-1">901 067 9xxx</div>
                                        <div class="text-xs text-slate-600 mt-1">a.n. Rendy Al Diansyah</div>
                                    </div>
                                    <div class="rounded-xl bg-white p-3 border border-blue-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-xs font-semibold uppercase tracking-wider text-blue-600">BCA</div>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-blue-100 text-xs font-bold text-blue-600 hover:bg-blue-200 transition" data-copy="789 123 4xxx" title="Salin Nomor">📋 Salin</button>
                                        </div>
                                        <div class="font-mono text-sm font-bold text-slate-900 mt-1">789 123 4xxx</div>
                                        <div class="text-xs text-slate-600 mt-1">a.n. Rendy Al Diansyah</div>
                                    </div>
                                    <div class="rounded-xl bg-white p-3 border border-blue-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-xs font-semibold uppercase tracking-wider text-blue-600">BRI</div>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-blue-100 text-xs font-bold text-blue-600 hover:bg-blue-200 transition" data-copy="456 789 0xxx" title="Salin Nomor">📋 Salin</button>
                                        </div>
                                        <div class="font-mono text-sm font-bold text-slate-900 mt-1">456 789 0xxx</div>
                                        <div class="text-xs text-slate-600 mt-1">a.n. Rendy Al Diansyah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- E-Wallet Details -->
                    <div id="method-ewallet" class="payment-details hidden space-y-3 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 p-5 animate-fade-in">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📱</span>
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-900">E-Wallet</h4>
                                <p class="text-sm text-slate-600 mt-2">Transfer via e-wallet ke nomor:</p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center justify-between rounded-lg bg-white p-3 border border-purple-100">
                                        <span class="text-sm font-semibold text-slate-700">DANA</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-sm font-bold text-slate-900">085189014426</span>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-purple-100 text-xs font-bold text-purple-600 hover:bg-purple-200 transition" data-copy="085189014426" title="Salin Nomor">📋</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between rounded-lg bg-white p-3 border border-purple-100">
                                        <span class="text-sm font-semibold text-slate-700">OVO</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-sm font-bold text-slate-900">085189014426</span>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-purple-100 text-xs font-bold text-purple-600 hover:bg-purple-200 transition" data-copy="085189014426" title="Salin Nomor">📋</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between rounded-lg bg-white p-3 border border-purple-100">
                                        <span class="text-sm font-semibold text-slate-700">GoPay</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-sm font-bold text-slate-900">085189014426</span>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-purple-100 text-xs font-bold text-purple-600 hover:bg-purple-200 transition" data-copy="085189014426" title="Salin Nomor">📋</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between rounded-lg bg-white p-3 border border-purple-100">
                                        <span class="text-sm font-semibold text-slate-700">ShopeePay</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-sm font-bold text-slate-900">085189014426</span>
                                            <button type="button" class="copy-btn px-2 py-1 rounded bg-purple-100 text-xs font-bold text-purple-600 hover:bg-purple-200 transition" data-copy="085189014426" title="Salin Nomor">📋</button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 mt-3 italic">a.n. Rendy Al Diansyah</p>
                            </div>
                        </div>
                    </div>

                    <!-- QRIS Details -->
                    <div id="method-qris" class="payment-details hidden space-y-3 rounded-2xl bg-gradient-to-br from-orange-50 to-red-50 border border-orange-200 p-5 animate-fade-in">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📲</span>
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-900">QRIS</h4>
                                <p class="text-sm text-slate-600 mt-2">Scan QR code berikut:</p>
                                <div class="mt-4 flex justify-center">
                                    <?php if($qrisUrl): ?>
                                        <img src="<?php echo e($qrisUrl); ?>" alt="QRIS Code" class="w-40 h-40 rounded-xl border-2 border-orange-300 shadow-lg object-cover">
                                    <?php else: ?>
                                        <div class="w-40 h-40 rounded-xl bg-slate-200 border-2 border-slate-300 flex items-center justify-center text-sm text-slate-600">
                                            <span class="text-center">QRIS belum dikonfigurasi</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-600 mt-3 text-center italic">Scan dengan aplikasi mobile banking atau e-wallet Anda</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                        <input type="text" name="customer_name" value="<?php echo e(old('customer_name', $user->name)); ?>" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?> required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">WhatsApp</label>
                        <input type="text" name="customer_phone" value="<?php echo e(old('customer_phone', $user->phone)); ?>" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?> required>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="customer_email" value="<?php echo e(old('customer_email', $user->email)); ?>" class="w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?> required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat Pengiriman</label>
                    <textarea name="delivery_address" rows="4" class="w-full rounded-[1.5rem] border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?> required><?php echo e(old('delivery_address')); ?></textarea>
                </div>

                <!-- Payment Proof Upload - Conditional Display -->
                <div id="payment-proof-section" class="hidden space-y-3 rounded-2xl bg-amber-50 border border-amber-200 p-5 animate-fade-in">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📸</span>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Pembayaran *</label>
                            <input type="file" name="payment_proof" accept="image/*" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100 <?php $__errorArgs = ['payment_proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                            <?php $__errorArgs = ['payment_proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-2 text-xs text-slate-600">Unggah screenshot atau foto bukti pembayaran. Max 4MB.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                    <textarea name="notes" rows="3" class="w-full rounded-[1.5rem] border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100" <?php echo e($storeOpen ? '' : 'disabled'); ?>><?php echo e(old('notes')); ?></textarea>
                </div>

                <button type="submit" class="w-full rounded-2xl px-5 py-3 text-sm font-bold transition <?php echo e($storeOpen ? 'bg-gradient-to-r from-ink-950 to-brand-600 text-white hover:shadow-lg hover:scale-105' : 'cursor-not-allowed bg-slate-300 text-slate-500'); ?>" <?php echo e($storeOpen ? '' : 'disabled'); ?>>
                    <?php echo e($storeOpen ? '✓ Kirim Pesanan' : 'Toko Tutup'); ?>

                </button>
            </form>
        </div>
    </div>
</div>

<!-- Tailwind CSS Animations -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    .payment-method-btn.active {
        border-color: var(--brand-500, #f97316);
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.05), rgba(249, 115, 22, 0.1));
        box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.2);
    }

    .payment-method-btn.active .text-2xl {
        transform: scale(1.1) !important;
        transition: transform 0.3s ease-out;
    }
</style>

<!-- Interactive Payment Method System -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const methodButtons = document.querySelectorAll('.payment-method-btn');
        const paymentMethodInput = document.getElementById('payment_method');
        const paymentDetailsElements = document.querySelectorAll('.payment-details');
        const paymentProofSection = document.getElementById('payment-proof-section');
        const paymentProofInput = document.querySelector('input[name="payment_proof"]');

        // Initialize with old value or COD as default
        const selectedMethod = "<?php echo e(old('payment_method', 'cod')); ?>";
        updatePaymentMethod(selectedMethod);

        // Payment method button click handler
        methodButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const method = this.dataset.method;
                updatePaymentMethod(method);
            });
        });

        function updatePaymentMethod(method) {
            // Update hidden input
            paymentMethodInput.value = method;

            // Update button states
            methodButtons.forEach(btn => {
                if (btn.dataset.method === method) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Show/hide payment details
            paymentDetailsElements.forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('animate-fade-in');
            });

            const selectedDetails = document.getElementById(`method-${method}`);
            if (selectedDetails) {
                selectedDetails.classList.remove('hidden');
                // Trigger animation
                setTimeout(() => {
                    selectedDetails.classList.add('animate-fade-in');
                }, 10);
            }

            // Show/hide payment proof section
            // COD doesn't need proof, others do
            if (method === 'cod') {
                paymentProofSection.classList.add('hidden');
                paymentProofInput.removeAttribute('required');
            } else {
                paymentProofSection.classList.remove('hidden');
                paymentProofInput.setAttribute('required', 'required');
            }
        }

        // Keyboard navigation
        methodButtons.forEach((button, index) => {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextButton = methodButtons[Math.min(index + 1, methodButtons.length - 1)];
                    nextButton.focus();
                    nextButton.click();
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevButton = methodButtons[Math.max(index - 1, 0)];
                    prevButton.focus();
                    prevButton.click();
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views\order\create.blade.php ENDPATH**/ ?>