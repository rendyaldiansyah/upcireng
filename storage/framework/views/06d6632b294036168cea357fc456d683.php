<?php $__env->startSection('title', 'UP Cireng | Order Modern'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isOpen            = $storeOpen ?? false;
    $waPhone           = preg_replace('/\D+/', '', $storeProfile['phone']);
    $featuredImage     = $products->first()?->image_url ?? asset('assets/assets/logo.png');
    $availableProducts = $products->filter(fn ($p) => $p->isAvailable())->count();
    $storefrontState   = [
        'user' => $user ? [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ] : null,
        'storeOpen' => $isOpen,
        'hours'     => $hours,
        'routes'    => [
            'login'                  => route('login'),
            'checkout'               => route('api.checkout'),
            'ordersMy'               => route('orders.my'),
            'checkDistance'          => route('api.check.distance'),
            'checkDistanceByCoords'  => route('api.check.distance.coords'),
        ],
        'products' => $products->map(fn ($product) => [
            'id'           => $product->id,
            'name'         => $product->name,
            'description'  => $product->description,
            'price'        => (float) $product->price,
            'image_url'    => $product->image_url,
            'available'    => $product->isAvailable(),
            'status'       => $product->status,
            'stock_status' => $product->stock_status,
            'is_open'      => $product->is_open,
            'variants'     => $product->availableVariants(),
        ])->values(),
        'delivery' => [
            'cod_free_km'       => (float) ($deliverySettings['cod_free_km'] ?? 5),
            'cod_extra_per_km'  => (float) ($deliverySettings['cod_extra_per_km'] ?? 5000),
            'store_has_coords'  => !empty($deliverySettings['store_lat']) && !empty($deliverySettings['store_lng']),
        ],
    ];
?>

<div id="storefrontApp" class="pb-10 pt-6 sm:pt-10 lg:pt-12">

    
    <section class="relative mx-auto mb-14 max-w-7xl px-4 sm:px-6 lg:mb-20 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:gap-12">

            
            <div>
                <div class="mb-5 inline-flex items-center gap-2.5 rounded-full border border-brand-200 bg-brand-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.23em] text-brand-600 animate-slideInUp sm:mb-6 sm:gap-3 sm:py-2.5" style="animation-delay:0ms">
                    <span class="h-2 w-2 rounded-full sm:h-2.5 sm:w-2.5 <?php echo e($isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'); ?>"></span>
                    <?php echo e($isOpen ? 'TOKO BUKA' : 'TOKO TUTUP'); ?>

                </div>

                <h1 class="display-font mb-5 text-3xl font-extrabold leading-tight text-ink-950 animate-slideInUp sm:mb-6 sm:text-4xl lg:text-5xl xl:text-6xl" style="animation-delay:100ms">
                    <?php echo e($heroContent['headline']); ?>

                </h1>

                <p class="mb-7 max-w-2xl text-sm leading-relaxed text-slate-600 animate-slideInUp sm:mb-8 sm:text-base sm:leading-relaxed lg:text-lg lg:leading-8" style="animation-delay:200ms">
                    <?php echo e($heroContent['description']); ?>

                </p>

                <div class="mb-8 flex flex-wrap gap-3 animate-slideInUp sm:mb-10" style="animation-delay:300ms">
                    <a href="#menu"
                       class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-ink-950 to-brand-600 px-6 py-3 text-sm font-bold text-white shadow-lg transition duration-300 hover:scale-105 hover:shadow-xl sm:px-7 sm:py-3.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Lihat Menu
                    </a>
                    <a href="https://wa.me/<?php echo e($waPhone); ?>"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition duration-300 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600 sm:px-7 sm:py-3.5">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z" />
                        </svg>
                        WhatsApp Kami
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-2 animate-fadeIn sm:gap-4" style="animation-delay:400ms">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 sm:rounded-2xl sm:p-4 lg:p-5">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:text-xs">Operasional</p>
                        <p class="text-base font-extrabold text-ink-950 sm:text-xl lg:text-2xl"><?php echo e($hours['start']); ?>-<?php echo e($hours['end']); ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 sm:rounded-2xl sm:p-4 lg:p-5">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:text-xs">Produk</p>
                        <p class="text-base font-extrabold text-ink-950 sm:text-xl lg:text-2xl"><?php echo e($availableProducts); ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 sm:rounded-2xl sm:p-4 lg:p-5">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:text-xs">Status</p>
                        <p class="text-base font-extrabold sm:text-xl lg:text-2xl <?php echo e($isOpen ? 'text-emerald-500' : 'text-rose-500'); ?>">
                            <?php echo e($isOpen ? 'BUKA' : 'TUTUP'); ?>

                        </p>
                    </div>
                </div>

                <?php if (! ($isOpen)): ?>
                    <div class="mt-6 flex items-start gap-3 rounded-2xl border-2 border-rose-200 bg-rose-50 px-4 py-4 sm:mt-8 sm:px-5">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-2-4a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2h4a2 2 0 002-2V1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-rose-700">Toko sedang tutup</p>
                            <p class="mt-1 text-sm text-rose-600">Buka kembali pukul <?php echo e($hours['start']); ?> WIB. Checkout tersedia saat toko buka.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="relative animate-fadeIn" style="animation-delay:200ms">
                <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-brand-500 to-ink-950 p-5 shadow-2xl sm:rounded-3xl sm:p-6">
                    <div class="absolute inset-0 bg-mesh opacity-30"></div>
                    <div class="relative">
                        <div class="mb-4">
                            <p class="mb-1.5 text-xs font-bold uppercase tracking-wider text-brand-200 sm:text-sm">
                                Tentang <?php echo e($storeProfile['name']); ?>

                            </p>
                            <h2 class="display-font text-xl font-extrabold text-white sm:text-2xl lg:text-3xl">
                                <?php echo e($storeProfile['name']); ?>

                            </h2>
                            <p class="mt-2 text-xs leading-relaxed text-white/80 sm:mt-3 sm:text-sm sm:leading-relaxed">
                                Menyediakan cireng segar dengan sistem order yang modern dan mudah. Semua pesanan langsung terkelola dengan baik.
                            </p>
                        </div>

                        <div class="mb-4 overflow-hidden rounded-xl border-4 border-white/20 sm:mb-5 sm:rounded-2xl">
                            <img src="<?php echo e($featuredImage); ?>" alt="Produk unggulan"
                                 class="h-48 w-full object-cover sm:h-64">
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-white/90 sm:gap-3">
                            <div class="rounded-lg bg-white/10 p-2.5 backdrop-blur-sm sm:p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider opacity-75">Telepon</p>
                                <p class="mt-1 text-xs font-bold sm:text-sm lg:text-base"><?php echo e($storeProfile['phone']); ?></p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-2.5 backdrop-blur-sm sm:p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider opacity-75">Email</p>
                                <p class="mt-1 truncate text-xs font-bold sm:text-sm lg:text-base"><?php echo e($storeProfile['email']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section id="menu" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 sm:mt-20 lg:px-8">

        <div class="mb-8 animate-slideInUp sm:mb-12">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:text-sm"><?php echo e($heroContent['subheadline']); ?></p>
            <h2 class="display-font mb-3 text-2xl font-extrabold text-ink-950 sm:mb-4 sm:text-3xl lg:text-4xl">
                Pilih produk favorit mu
            </h2>
            <p class="max-w-2xl text-sm text-slate-600 sm:text-base">
                Semua produk dibuat segar dan siap dikirim ke alamat kamu. Pesan sekarang dan nikmati!
            </p>
        </div>

        
        <div class="mb-6 flex flex-col gap-3 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-slate-600"><?php echo e($products->count()); ?> produk tersedia</p>

            <button
                id="openCartButton"
                type="button"
                class="inline-flex w-full items-center justify-center gap-3 rounded-full px-6 py-3 text-sm font-bold transition duration-300 sm:w-auto
                       <?php echo e($isOpen ? 'bg-gradient-to-r from-ink-950 to-brand-600 text-white hover:scale-105 hover:shadow-lg' : 'cursor-not-allowed bg-slate-200 text-slate-500'); ?>"
                <?php echo e($isOpen ? '' : 'disabled'); ?>

            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Keranjang</span>
                <span id="cartCountBadge" class="inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-white/20 px-2 py-0.5 text-xs font-bold">0</span>
            </button>
        </div>

        
        
        
        <div id="productGrid" class="grid grid-cols-2 gap-2 sm:gap-4 lg:grid-cols-3 xl:grid-cols-4">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $productVariants = $product->availableVariants();
                    $productEnabled  = $isOpen && $product->isAvailable();

                    /* Label status — pendek untuk mobile */
                    $productStatus = !$isOpen
                        ? 'Tutup'
                        : ($product->stock_status === 'out_of_stock'
                            ? 'Habis'
                            : ((!$product->is_open || $product->status !== 'active') ? 'Tutup' : 'Siap'));

                    $allVariants = !empty($productVariants) ? $productVariants : ['Regular'];
                ?>

                <article
                    data-product-card
                    data-delay="<?php echo e($loop->index * 50); ?>"
                    class="product-card group flex flex-col overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm animate-fadeIn sm:rounded-2xl sm:shadow-md"
                >
                    
                    <div class="relative h-28 shrink-0 overflow-hidden bg-slate-100 sm:h-48 lg:h-52">
                        <img src="<?php echo e($product->image_url); ?>"
                             alt="<?php echo e($product->name); ?>"
                             class="product-card__img h-full w-full object-cover">
                        <div class="product-card__overlay absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                        
                        <div class="absolute left-1.5 top-1.5 sm:left-2.5 sm:top-2.5">
                            <span class="inline-block rounded-full bg-white/95 px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wide text-slate-700 shadow-sm sm:px-2.5 sm:py-1 sm:text-[10px]">
                                <?php echo e($productStatus); ?>

                            </span>
                        </div>
                        <div class="absolute right-1.5 top-1.5 sm:right-2.5 sm:top-2.5">
                            <span class="inline-block rounded-full bg-brand-500 px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wide text-white shadow-sm sm:px-2.5 sm:py-1 sm:text-[10px]">
                                Live
                            </span>
                        </div>
                    </div>

                    
                    <div class="flex flex-1 flex-col p-2 sm:p-4">

                        
                        <h3 class="display-font line-clamp-1 text-[13px] font-bold leading-snug text-ink-950 sm:line-clamp-2 sm:text-base lg:text-lg">
                            <?php echo e($product->name); ?>

                        </h3>

                        
                        <p class="mt-0.5 hidden line-clamp-1 text-[11px] text-slate-500 sm:mt-1 sm:block sm:text-sm">
                            <?php echo e($product->description ?: 'Cireng berkualitas UP Cireng'); ?>

                        </p>

                        
                        <p class="mt-1.5 text-[15px] font-extrabold text-ink-950 sm:mt-2 sm:text-xl lg:text-2xl">
                            <?php echo e($product->formatPrice()); ?>

                        </p>

                        
                        <div class="mt-2 space-y-1.5 sm:mt-3 sm:space-y-2">

                            
                            <div>
                                <label class="mb-1 block text-[8px] font-bold uppercase tracking-wide text-slate-400 sm:text-[10px]">Varian</label>

                                <?php if(count($allVariants) >= 2): ?>
                                    <div class="grid grid-cols-2 gap-1" data-variant-group="<?php echo e($product->id); ?>">
                                        <?php $__currentLoopData = $allVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vIdx => $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button
                                                type="button"
                                                data-variant-btn="<?php echo e($variant); ?>"
                                                class="variant-btn truncate rounded-md border-2 py-1 text-[9px] font-semibold leading-tight transition-all sm:rounded-lg sm:py-1.5 sm:text-[11px]
                                                       <?php echo e($vIdx === 0
                                                           ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                           : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-brand-300 hover:text-brand-600'); ?>"
                                                <?php echo e($productEnabled ? '' : 'disabled'); ?>

                                            ><?php echo e($variant); ?></button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <select data-product-variant class="hidden" <?php echo e($productEnabled ? '' : 'disabled'); ?>>
                                        <?php $__currentLoopData = $allVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($variant); ?>"><?php echo e($variant); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                <?php else: ?>
                                    <select
                                        data-product-variant
                                        class="product-field w-full rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-700 outline-none sm:rounded-lg sm:px-3 sm:py-1.5 sm:text-sm"
                                        <?php echo e($productEnabled ? '' : 'disabled'); ?>

                                    >
                                        <?php $__currentLoopData = $allVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($variant); ?>"><?php echo e($variant); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                <?php endif; ?>
                            </div>

                            
                            <div>
                                <label class="mb-1 block text-[8px] font-bold uppercase tracking-wide text-slate-400 sm:text-[10px]">Jumlah</label>
                                <input
                                    data-product-qty
                                    type="number"
                                    min="1"
                                    value="1"
                                    class="product-field w-full rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-700 outline-none sm:rounded-lg sm:px-3 sm:py-1.5 sm:text-sm"
                                    <?php echo e($productEnabled ? '' : 'disabled'); ?>

                                >
                            </div>
                        </div>

                        
                        <button
                            type="button"
                            data-add-product="<?php echo e($product->id); ?>"
                            class="product-btn mt-2 w-full rounded-lg py-1.5 text-[10px] font-bold sm:mt-3 sm:py-2.5 sm:text-sm
                                   <?php echo e($productEnabled
                                       ? 'bg-gradient-to-r from-ink-950 to-brand-600 text-white'
                                       : 'cursor-not-allowed bg-slate-200 text-slate-400'); ?>"
                            <?php echo e($productEnabled ? '' : 'disabled'); ?>

                        >
                            <?php if($productEnabled): ?>
                                <span class="hidden sm:inline">➕ Tambahkan ke keranjang</span>
                                <span class="sm:hidden">➕ Tambah</span>
                            <?php else: ?>
                                <?php echo e($productStatus); ?>

                            <?php endif; ?>
                        </button>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    
    <section class="mx-auto mt-20 max-w-7xl px-4 sm:px-6 sm:mt-24 lg:px-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-ink-950 via-slate-900 to-brand-900 px-6 py-12 sm:rounded-3xl sm:px-10 lg:px-16 lg:py-16">

            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-brand-500/10"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-brand-600/10"></div>
            <div class="pointer-events-none absolute right-1/3 bottom-0 h-32 w-32 rounded-full bg-white/5"></div>

            <div class="relative mb-10 text-center sm:mb-12">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-400">Cara Bayar</p>
                <h2 class="text-2xl font-extrabold text-white sm:text-3xl lg:text-4xl">
                    Metode <span class="text-brand-400">Pembayaran</span>
                </h2>
                <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-white/60 sm:text-base">
                    Pilih yang nyaman buat kamu. Kalau transfer/e-wallet, upload bukti agar cepat diverifikasi.
                </p>
            </div>

            <div class="relative grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
                
                <div class="group rounded-xl border border-white/10 bg-white/10 p-4 transition-all duration-300 hover:-translate-y-1 hover:border-brand-400/40 hover:bg-white/15 sm:rounded-2xl sm:p-5 lg:p-6">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/20 transition-colors group-hover:bg-brand-500/30 sm:mb-4 sm:h-12 sm:w-12">
                        <svg class="h-5 w-5 text-brand-400 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="mb-1 text-sm font-extrabold text-white sm:mb-1.5 sm:text-base">COD</h3>
                    <p class="text-xs leading-relaxed text-white/60 sm:text-sm">Bayar di tempat saat pesanan tiba</p>
                </div>

                
                <div class="group rounded-xl border border-white/10 bg-white/10 p-4 transition-all duration-300 hover:-translate-y-1 hover:border-brand-400/40 hover:bg-white/15 sm:rounded-2xl sm:p-5 lg:p-6">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/20 transition-colors group-hover:bg-brand-500/30 sm:mb-4 sm:h-12 sm:w-12">
                        <svg class="h-5 w-5 text-brand-400 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <h3 class="mb-1 text-sm font-extrabold text-white sm:mb-1.5 sm:text-base">Transfer Bank</h3>
                    <p class="text-xs leading-relaxed text-white/60 sm:text-sm">Sea Bank / Bank Jago / BCA / BRI</p>
                </div>

                
                <div class="group rounded-xl border border-white/10 bg-white/10 p-4 transition-all duration-300 hover:-translate-y-1 hover:border-brand-400/40 hover:bg-white/15 sm:rounded-2xl sm:p-5 lg:p-6">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/20 transition-colors group-hover:bg-brand-500/30 sm:mb-4 sm:h-12 sm:w-12">
                        <svg class="h-5 w-5 text-brand-400 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="mb-1 text-sm font-extrabold text-white sm:mb-1.5 sm:text-base">E-Wallet</h3>
                    <p class="text-xs leading-relaxed text-white/60 sm:text-sm">DANA / OVO / GoPay / ShopeePay</p>
                </div>

                
                <div class="group rounded-xl border border-white/10 bg-white/10 p-4 transition-all duration-300 hover:-translate-y-1 hover:border-brand-400/40 hover:bg-white/15 sm:rounded-2xl sm:p-5 lg:p-6">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/20 transition-colors group-hover:bg-brand-500/30 sm:mb-4 sm:h-12 sm:w-12">
                        <svg class="h-5 w-5 text-brand-400 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="mb-1 text-sm font-extrabold text-white sm:mb-1.5 sm:text-base">QRIS</h3>
                    <p class="text-xs leading-relaxed text-white/60 sm:text-sm">Scan QR untuk pembayaran instan</p>
                </div>
            </div>

            <div class="relative mt-8 text-center">
                <p class="text-xs font-medium text-white/40">Pembayaran Instan!</p>
            </div>
        </div>
    </section>

    
    <section id="testimoni" class="mx-auto mt-20 max-w-7xl px-4 sm:px-6 sm:mt-24 lg:px-8">

        <div class="mb-8 animate-slideInUp sm:mb-12">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-500 sm:text-sm">Kepuasan Pelanggan</p>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="display-font mb-2 text-2xl font-extrabold text-ink-950 sm:mb-3 sm:text-3xl lg:text-4xl">
                        Apa kata pelanggan kami
                    </h2>
                    <p class="max-w-2xl text-sm text-slate-600 sm:text-base">
                        Testimoni asli dari pelanggan setia yang puas dengan produk dan layanan kami
                    </p>
                </div>
                <a href="<?php echo e(route('testimonial.index')); ?>"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-full border-2 border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition duration-300 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600 sm:w-auto sm:px-6 sm:py-3">
                    Lihat Semua Testimoni
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
            <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article
                    data-delay="<?php echo e($loop->index * 50); ?>"
                    class="animate-fadeIn rounded-2xl border border-white/80 bg-white p-5 shadow-md transition duration-300 hover:shadow-lg sm:p-6"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex gap-1">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 <?php echo e($i < (int)$testimonial->rating_stars ? 'fill-current text-yellow-400' : 'text-slate-300'); ?>" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-xs font-bold text-slate-400"><?php echo e($testimonial->created_at->translatedFormat('d M Y')); ?></span>
                    </div>
                    <h3 class="display-font mb-2 text-base font-bold text-ink-950 sm:text-lg">
                        <?php echo e($testimonial->customer_name); ?>

                    </h3>
                    <p class="line-clamp-3 text-sm leading-relaxed text-slate-600">
                        "<?php echo e($testimonial->message); ?>"
                    </p>
                    <a href="<?php echo e(route('testimonial.index')); ?>"
                       class="mt-3 inline-flex items-center text-xs font-bold text-brand-500 hover:text-brand-600">
                        Baca selengkapnya →
                    </a>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-1 rounded-2xl border-2 border-dashed border-slate-300 bg-gradient-to-br from-slate-50 to-white px-6 py-14 text-center sm:col-span-2 sm:py-16 lg:col-span-3">
                    <svg class="mx-auto mb-3 h-10 w-10 text-slate-400 sm:h-12 sm:w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-500">Belum ada testimoni</p>
                    <p class="mt-1 text-xs text-slate-400">Jadilah yang pertama memberikan testimoni</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    
    <section id="kontak" class="mx-auto mb-16 mt-20 max-w-7xl px-4 sm:px-6 sm:mb-20 sm:mt-24 lg:px-8">
        <div class="grid gap-5 sm:gap-6 lg:grid-cols-2">

            <div class="animate-slideInUp rounded-2xl bg-gradient-to-br from-ink-950 to-brand-700 p-7 text-white shadow-xl sm:rounded-3xl sm:p-10">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.3em] text-brand-200 sm:mb-3 sm:text-sm">Butuh Bantuan?</p>
                <h2 class="display-font mb-3 text-2xl font-extrabold sm:mb-4 sm:text-3xl lg:text-4xl">Hubungi Kami</h2>
                <p class="mb-7 text-sm leading-relaxed text-white/90 sm:mb-8 sm:text-base sm:leading-relaxed">
                    Ada pertanyaan tentang produk? Ingin komplain atau repeat order? Tim kami siap membantu 24/7 melalui WhatsApp atau email.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="https://wa.me/<?php echo e($waPhone); ?>" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-ink-950 transition duration-300 hover:bg-white/90 sm:px-6 sm:py-3">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.411-2.39-1.477-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.967 1.523 9.875 9.875 0 006.868 16.05 9.872 9.872 0 005.546-16.728 9.87 9.87 0 00-7.443-.845z" />
                        </svg>
                        WhatsApp
                    </a>
                    <a href="mailto:<?php echo e($storeProfile['email']); ?>"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-white px-5 py-2.5 text-sm font-bold text-white transition duration-300 hover:bg-white/10 sm:px-6 sm:py-3">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                        Email
                    </a>
                </div>
            </div>

            <div class="animate-fadeIn space-y-4 sm:space-y-4" style="animation-delay:200ms">
                <?php
                    $infoCards = [
                        [
                            'label' => 'Lokasi',
                            'value' => $storeProfile['address'],
                            'sub'   => null,
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        ],
                        [
                            'label' => 'Jam Buka',
                            'value' => $hours['start'] . ' - ' . $hours['end'] . ' WIB',
                            'sub'   => 'Senin - Minggu',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        [
                            'label' => 'Media Sosial',
                            'value' => $storeProfile['instagram'] ?? 'Follow kami di Instagram',
                            'sub'   => null,
                            'icon'  => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>',
                        ],
                    ];
                ?>

                <?php $__currentLoopData = $infoCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-2xl border border-white/80 bg-white p-5 shadow-md transition hover:shadow-lg sm:p-6">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="shrink-0 rounded-lg bg-brand-50 p-2.5 sm:p-3">
                                <svg class="h-5 w-5 text-brand-600 sm:h-6 sm:w-6"
                                     fill="<?php echo e(str_contains($card['icon'], 'stroke-linecap') ? 'none' : 'currentColor'); ?>"
                                     stroke="<?php echo e(str_contains($card['icon'], 'stroke-linecap') ? 'currentColor' : 'none'); ?>"
                                     viewBox="0 0 24 24">
                                    <?php echo $card['icon']; ?>

                                </svg>
                            </div>
                            <div>
                                <p class="mb-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-500 sm:mb-1 sm:text-xs">
                                    <?php echo e($card['label']); ?>

                                </p>
                                <p class="text-sm font-semibold leading-relaxed text-slate-700"><?php echo e($card['value']); ?></p>
                                <?php if($card['sub']): ?>
                                    <p class="mt-1 text-xs text-slate-500"><?php echo e($card['sub']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <footer class="mx-auto mt-12 max-w-7xl px-4 pb-8 text-center sm:px-6 lg:px-8">
        <p class="text-xs font-medium text-slate-400">
           
        </p>
    </footer>

    
    <aside id="cartDrawer"
           class="fixed right-0 top-0 z-[90] flex h-full w-full max-w-sm translate-x-full flex-col border-l border-white/70 bg-white shadow-panel transition duration-300 sm:max-w-md lg:max-w-lg">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6 sm:py-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-500">Cart</p>
                <h2 class="display-font mt-1 text-xl font-extrabold text-ink-950 sm:mt-2 sm:text-2xl">Keranjang Pesanan</h2>
            </div>
            <button id="closeCartButton" type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-base font-black text-slate-600 transition hover:border-brand-200 hover:text-brand-600 sm:h-11 sm:w-11 sm:rounded-2xl sm:text-lg">
                ✕
            </button>
        </div>
        <div id="cartItems" class="flex-1 space-y-4 overflow-y-auto px-5 py-5 sm:px-6 sm:py-6"></div>
        <div class="border-t border-slate-100 px-5 py-4 sm:px-6 sm:py-5">
            <div class="mb-4 flex items-center justify-between text-sm font-bold text-slate-500">
                <span>Total</span>
                <span id="cartTotal" class="text-lg font-extrabold text-ink-950 sm:text-xl">Rp 0</span>
            </div>
            <button
                id="checkoutButton"
                type="button"
                class="w-full rounded-xl py-3 text-sm font-bold transition sm:rounded-2xl
                       <?php echo e($isOpen ? 'bg-ink-950 text-white hover:bg-brand-500' : 'cursor-not-allowed bg-slate-200 text-slate-500'); ?>"
                <?php echo e($isOpen ? '' : 'disabled'); ?>

            >
                <?php echo e($isOpen ? 'Lanjut Checkout' : 'STORE CLOSED'); ?>

            </button>
        </div>
    </aside>

    <div id="cartBackdrop" class="fixed inset-0 z-[80] hidden bg-slate-950/50 backdrop-blur-sm"></div>

    
    <dialog id="checkoutDialog" class="w-[calc(100%-2rem)] max-w-4xl rounded-2xl border border-white/70 p-0 shadow-panel sm:rounded-[2rem]">
        <form id="checkoutForm" class="overflow-hidden rounded-2xl bg-white sm:rounded-[2rem]">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6 sm:py-5">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-500">Checkout</p>
                <h2 class="display-font mt-1.5 text-xl font-extrabold text-ink-950 sm:mt-2 sm:text-2xl">Konfirmasi Data Pengiriman</h2>
            </div>

            <div class="grid gap-6 px-5 py-5 sm:px-6 sm:py-6 lg:grid-cols-[1fr_0.84fr] lg:gap-8">
                <div class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama</label>
                            <input type="text" name="customer_name" id="customerName"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                                   required>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">WhatsApp</label>
                            <input type="text" name="customer_phone" id="customerPhone"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Email</label>
                        <input type="email" name="customer_email" id="customerEmail"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                               required>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Alamat Pengiriman</label>
                        <textarea name="delivery_address" id="deliveryAddress" rows="3"
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-[1.5rem] sm:py-3"
                                  required></textarea>

                        <div class="mt-2 space-y-2">
                            <div class="flex flex-wrap gap-2">
                                <button type="button" id="getLocationBtn"
                                        class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100 disabled:cursor-not-allowed disabled:opacity-50">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06z"/>
                                    </svg>
                                    <span id="getLocationBtnText">📍 Ambil Lokasi Saya</span>
                                </button>

                                <button type="button" id="checkDistanceBtn"
                                        class="inline-flex items-center gap-2 rounded-xl border border-brand-200 bg-brand-50 px-4 py-2 text-xs font-bold text-brand-600 transition hover:bg-brand-100 disabled:cursor-not-allowed disabled:opacity-50">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span id="checkDistanceBtnText">🗺️ Cek Jarak & Ongkir</span>
                                </button>
                            </div>

                            <div id="distanceResultBox" class="mt-1 hidden rounded-xl border p-3 text-sm">
                                <div class="flex items-start gap-2">
                                    <span id="distanceIcon" class="text-base">📍</span>
                                    <div class="flex-1">
                                        <p id="distanceText" class="font-bold text-slate-800"></p>
                                        <p id="distanceFeeText" class="mt-0.5 text-xs text-slate-600"></p>
                                        <p id="distanceAddressText" class="mt-0.5 text-xs italic text-slate-500"></p>
                                        <p id="distanceCoverageText" class="mt-1 text-xs font-semibold"></p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="delivery_fee" id="deliveryFeeInput" value="0">
                            <input type="hidden" name="delivery_distance_km" id="deliveryDistanceInput" value="">
                            <input type="hidden" id="customerLatInput" value="">
                            <input type="hidden" id="customerLngInput" value="">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Metode Pembayaran</label>
                        <select name="payment_method" id="paymentMethod"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-2xl sm:py-3"
                                required>
                            <option value="cod">Cash on Delivery (COD)</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div id="paymentDetailsCheckout" class="space-y-3">
                        <div id="checkout-cod" class="payment-detail-card animate-fade-in hidden rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-emerald-600">💵 Cash On Delivery</p>
                            <p class="text-sm text-slate-700">Bayar langsung ke kurir saat barang tiba.</p>
                            <div id="codDeliveryInfo" class="mt-2 hidden rounded-lg border border-emerald-100 bg-white p-2">
                                <p class="text-xs text-slate-600">
                                    🛵 Antar area: gratis s/d <strong id="codFreeKmDisplay">5</strong> km dari toko.<br>
                                    Lebih dari itu dikenakan biaya tambahan <strong id="codExtraRateDisplay">Rp 5.000</strong>/km.
                                </p>
                            </div>
                        </div>

                        <div id="checkout-bank_transfer" class="payment-detail-card animate-fade-in hidden space-y-2 rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-cyan-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-blue-600">🏦 Transfer Bank</p>
                            <div class="space-y-2">
                                <?php $__currentLoopData = [['Bank Jago','105 3012 9xxx'],['SeaBank','901 067 9xxx'],['BCA','789 123 4xxx'],['BRI','456 789 0xxx']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$bank, $no]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between rounded-lg border border-blue-100 bg-white p-2">
                                        <span class="text-xs font-semibold text-slate-700"><?php echo e($bank); ?></span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-slate-900"><?php echo e($no); ?></span>
                                            <button type="button" class="copy-btn rounded bg-brand-100 px-2 py-1 text-xs font-bold text-brand-600 transition hover:bg-brand-200" data-copy="<?php echo e($no); ?>" title="Salin">📋</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <p class="text-xs text-slate-600">a.n. Rendy Al Diansyah</p>
                        </div>

                        <div id="checkout-ewallet" class="payment-detail-card animate-fade-in hidden rounded-2xl border border-purple-200 bg-gradient-to-br from-purple-50 to-pink-50 p-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-purple-600">📱 E-Wallet</p>
                            <div class="flex items-center justify-between rounded-lg border border-purple-100 bg-white p-2">
                                <span class="text-xs font-semibold text-slate-700">DANA • OVO • GoPay • ShopeePay</span>
                                <button type="button" class="copy-btn rounded bg-brand-100 px-2 py-1 text-xs font-bold text-brand-600 transition hover:bg-brand-200" data-copy="085189014426" title="Salin">📋</button>
                            </div>
                            <p class="mt-1 font-mono text-xs font-bold text-slate-900">085189014426</p>
                            <p class="mt-2 text-xs text-slate-600">a.n. Rendy Al Diansyah</p>
                        </div>

                        <div id="checkout-qris" class="payment-detail-card animate-fade-in hidden rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 to-red-50 p-4">
                            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-orange-600">📲 QRIS</p>
                            <div class="mb-3 overflow-hidden rounded-xl border border-orange-100 bg-white p-2">
                                <img src="<?php echo e(asset('assets/assets/qris.jpg')); ?>"
                                     alt="QRIS UP Cireng"
                                     class="mx-auto h-48 w-auto object-contain sm:h-56">
                            </div>
                            <p class="text-xs text-slate-600">*Setelah bayar, upload bukti pembayaran ya.</p>
                        </div>
                    </div>

                    <div id="paymentProofWrapper" class="hidden">
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" id="paymentProof" accept="image/*"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 sm:rounded-2xl sm:py-3">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Catatan</label>
                        <textarea name="notes" id="orderNotes" rows="3"
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-300 focus:bg-white sm:rounded-[1.5rem] sm:py-3"></textarea>
                    </div>
                </div>

                <div class="rounded-2xl bg-ink-950 p-4 text-white sm:rounded-[1.8rem] sm:p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-brand-300">Ringkasan Order</p>
                    <div id="checkoutItems" class="mt-4 space-y-3 text-sm text-slate-200"></div>

                    <div id="deliveryFeeRow" class="mt-3 hidden flex items-center justify-between border-t border-white/10 pt-3 text-sm">
                        <span class="text-slate-300">Ongkos Kirim</span>
                        <span id="checkoutDeliveryFee" class="font-bold text-emerald-400">Gratis</span>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4 text-base font-extrabold">
                        <span>Total Bayar</span>
                        <span id="checkoutTotal">Rp 0</span>
                    </div>
                    <p class="mt-4 text-xs leading-6 text-slate-400">
                        Periksa kembali pesanan sebelum dikirim. Konfirmasi dan estimasi akan dikirim setelahnya.
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:justify-end sm:px-6 sm:py-5">
                <button type="button" id="closeCheckoutButton"
                        class="w-full rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-brand-200 hover:text-brand-600 sm:w-auto sm:rounded-2xl sm:py-3">
                    Batal
                </button>
                <button type="submit" id="submitOrderButton"
                        class="w-full rounded-xl bg-brand-500 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-ink-950 sm:w-auto sm:rounded-2xl sm:py-3">
                    Kirim Order
                </button>
            </div>
        </form>
    </dialog>

    
    <div id="toast"
         class="pointer-events-none fixed bottom-4 left-1/2 z-[95] hidden -translate-x-1/2 rounded-full bg-ink-950 px-5 py-3 text-sm font-bold text-white shadow-panel">
    </div>

    <script id="storefrontState" type="application/json"><?php echo json_encode($storefrontState, 15, 512) ?></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-delay]').forEach(function (el) {
            const delay = el.getAttribute('data-delay');
            if (delay) el.style.setProperty('animation-delay', delay + 'ms');
        });

        document.querySelectorAll('form[data-delete-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (!confirm(this.getAttribute('data-delete-confirm'))) e.preventDefault();
            });
        });

        // ── Variant button grid ──
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-variant-btn]');
            if (!btn) return;
            const group = btn.closest('[data-variant-group]');
            if (!group) return;

            group.querySelectorAll('[data-variant-btn]').forEach(function (b) {
                b.classList.remove('border-brand-500', 'bg-brand-50', 'text-brand-700');
                b.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-600');
            });
            btn.classList.add('border-brand-500', 'bg-brand-50', 'text-brand-700');
            btn.classList.remove('border-slate-200', 'bg-slate-50', 'text-slate-600');

            const card = btn.closest('[data-product-card]');
            if (card) {
                const hiddenSelect = card.querySelector('select[data-product-variant]');
                if (hiddenSelect) hiddenSelect.value = btn.dataset.variantBtn;
            }
        });

        // ── Distance check ──
        const state              = JSON.parse(document.getElementById('storefrontState').textContent);
        const delivery           = state.delivery || {};
        const codFreeKm          = delivery.cod_free_km || 5;
        const codExtraPerKm      = delivery.cod_extra_per_km || 5000;
        const checkDistUrl       = state.routes.checkDistance;
        const checkDistCoordsUrl = state.routes.checkDistanceByCoords;
        const storeHasCoords     = delivery.store_has_coords || false;

        const codFreeKmDisplay    = document.getElementById('codFreeKmDisplay');
        const codExtraRateDisplay = document.getElementById('codExtraRateDisplay');
        if (codFreeKmDisplay)    codFreeKmDisplay.textContent    = codFreeKm;
        if (codExtraRateDisplay) codExtraRateDisplay.textContent = 'Rp ' + codExtraPerKm.toLocaleString('id-ID');

        const paymentMethodSel = document.getElementById('paymentMethod');
        function toggleCodInfo() {
            const codInfo = document.getElementById('codDeliveryInfo');
            if (!codInfo) return;
            codInfo.classList.toggle('hidden', paymentMethodSel.value !== 'cod');
        }
        if (paymentMethodSel) {
            paymentMethodSel.addEventListener('change', toggleCodInfo);
            toggleCodInfo();
        }

        const checkDistBtn     = document.getElementById('checkDistanceBtn');
        const getLocationBtn   = document.getElementById('getLocationBtn');
        const distResultBox    = document.getElementById('distanceResultBox');
        const distText         = document.getElementById('distanceText');
        const distFeeText      = document.getElementById('distanceFeeText');
        const distAddressText  = document.getElementById('distanceAddressText');
        const distCovText      = document.getElementById('distanceCoverageText');
        const distIcon         = document.getElementById('distanceIcon');
        const deliveryFeeInput = document.getElementById('deliveryFeeInput');
        const deliveryDistInp  = document.getElementById('deliveryDistanceInput');
        const customerLatInput = document.getElementById('customerLatInput');
        const customerLngInput = document.getElementById('customerLngInput');
        const btnText          = document.getElementById('checkDistanceBtnText');
        const gpsText          = document.getElementById('getLocationBtnText');
        const deliveryFeeRow   = document.getElementById('deliveryFeeRow');
        const checkoutFeeEl    = document.getElementById('checkoutDeliveryFee');
        const csrfToken        = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (!storeHasCoords) {
            [checkDistBtn, getLocationBtn].forEach(btn => {
                if (!btn) return;
                btn.disabled = true;
                btn.title    = 'Admin belum mengatur koordinat toko';
                btn.classList.add('opacity-40');
            });
        }

        function renderDistanceResult(data, addressHint) {
            const km     = parseFloat(data.distance_km).toFixed(1);
            const fee    = parseInt(data.delivery_fee, 10);
            const within = data.within_coverage;
            deliveryFeeInput.value = fee;
            deliveryDistInp.value  = km;
            distResultBox.className = 'mt-1 rounded-xl border p-3 text-sm ' +
                (within ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50');
            distIcon.textContent = within ? '✅' : '⚠️';
            distText.textContent = `Jarak ke toko: ${km} km`;
            distFeeText.textContent = fee === 0
                ? `Ongkos kirim: GRATIS (≤ ${codFreeKm} km)`
                : `Ongkos kirim: Rp ${fee.toLocaleString('id-ID')} (+${(km - codFreeKm).toFixed(1)} km × Rp ${codExtraPerKm.toLocaleString('id-ID')}/km)`;
            const addr = data.display_name || addressHint || '';
            if (addr && distAddressText)
                distAddressText.textContent = '📌 ' + addr.substring(0, 80) + (addr.length > 80 ? '…' : '');
            distCovText.textContent = within ? '✓ Dalam jangkauan antar COD' : 'Di luar area gratis — biaya tambahan berlaku';
            distCovText.className   = 'mt-1 text-xs font-semibold ' + (within ? 'text-emerald-600' : 'text-amber-700');
            distResultBox.classList.remove('hidden');
            if (deliveryFeeRow && checkoutFeeEl) {
                deliveryFeeRow.classList.remove('hidden');
                checkoutFeeEl.textContent = fee === 0 ? 'Gratis' : 'Rp ' + fee.toLocaleString('id-ID');
                checkoutFeeEl.className   = fee === 0 ? 'font-bold text-emerald-400' : 'font-bold text-amber-300';
            }
        }

        function renderDistanceError(message) {
            distResultBox.className = 'mt-1 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm';
            distIcon.textContent    = '❌';
            distText.textContent    = message || 'Gagal mengecek jarak.';
            distFeeText.textContent = 'Coba tulis alamat lebih lengkap, contoh: Jl. Merdeka No.1, Purbalingga, Jawa Tengah';
            if (distAddressText) distAddressText.textContent = '';
            distCovText.textContent = '';
            distResultBox.classList.remove('hidden');
        }

        function resetDistance() {
            if (distResultBox)    distResultBox.classList.add('hidden');
            if (deliveryFeeInput) deliveryFeeInput.value = 0;
            if (deliveryDistInp)  deliveryDistInp.value  = '';
            if (deliveryFeeRow)   deliveryFeeRow.classList.add('hidden');
            if (customerLatInput) customerLatInput.value = '';
            if (customerLngInput) customerLngInput.value = '';
        }

        if (getLocationBtn && storeHasCoords) {
            getLocationBtn.addEventListener('click', function () {
                if (!navigator.geolocation) { alert('Browser kamu tidak mendukung GPS. Masukkan alamat manual.'); return; }
                gpsText.textContent     = '⏳ Mengambil lokasi...';
                getLocationBtn.disabled = true;
                distResultBox.classList.add('hidden');
                navigator.geolocation.getCurrentPosition(
                    async function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        if (customerLatInput) customerLatInput.value = lat;
                        if (customerLngInput) customerLngInput.value = lng;
                        gpsText.textContent = '⏳ Menghitung ongkir...';
                        try {
                            const resp = await fetch(checkDistCoordsUrl, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                body: JSON.stringify({ lat, lng }),
                            });
                            const data = await resp.json();
                            if (!resp.ok || data.error) throw new Error(data.message || 'Gagal menghitung jarak.');
                            const addressField = document.getElementById('deliveryAddress');
                            if (addressField && data.display_name && !addressField.value.trim())
                                addressField.value = data.display_name;
                            renderDistanceResult(data, data.display_name);
                        } catch (err) { renderDistanceError(err.message); }
                        finally { gpsText.textContent = '📍 Ambil Lokasi Saya'; getLocationBtn.disabled = false; }
                    },
                    function (error) {
                        gpsText.textContent     = '📍 Ambil Lokasi Saya';
                        getLocationBtn.disabled = false;
                        const messages = { 1: 'Akses lokasi ditolak.', 2: 'Lokasi tidak tersedia.', 3: 'Timeout.' };
                        renderDistanceError(messages[error.code] || 'Gagal mendapatkan lokasi.');
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            });
        }

        if (checkDistBtn && storeHasCoords) {
            checkDistBtn.addEventListener('click', async function () {
                const address = document.getElementById('deliveryAddress').value.trim();
                if (!address) { alert('Isi dulu alamat pengiriman.'); return; }
                btnText.textContent   = '⏳ Mengecek jarak...';
                checkDistBtn.disabled = true;
                distResultBox.classList.add('hidden');
                try {
                    const resp = await fetch(checkDistUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ address }),
                    });
                    const data = await resp.json();
                    if (!resp.ok || data.error) throw new Error(data.message || 'Alamat tidak ditemukan.');
                    renderDistanceResult(data, address);
                } catch (err) { renderDistanceError(err.message); }
                finally { btnText.textContent = '🗺️ Cek Jarak & Ongkir'; checkDistBtn.disabled = false; }
            });
        }

        const deliveryAddressTA = document.getElementById('deliveryAddress');
        if (deliveryAddressTA) deliveryAddressTA.addEventListener('input', resetDistance);
    });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }

        /* ── Variant buttons ── */
        .variant-btn {
            transition: border-color .15s ease, background .15s ease, color .15s ease, transform .15s ease;
        }
        .variant-btn:not(:disabled):hover  { transform: scale(1.04); }
        .variant-btn:not(:disabled):active { transform: scale(0.96); }

        /* ── Product card ── */
        .product-card {
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s cubic-bezier(.4,0,.2,1);
            will-change: transform;
        }
        .product-card:hover:not(:has(.product-field:focus)) {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(15,23,42,.18);
        }
        .product-card:has(.product-field:focus) { transform: none !important; }
        .product-card:hover:not(:has(.product-field:focus)) .product-card__img { transform: scale(1.07); }
        .product-card__img     { transition: transform .5s cubic-bezier(.4,0,.2,1); }
        .product-card__overlay { opacity: 0; transition: opacity .3s ease; }
        .product-card:hover:not(:has(.product-field:focus)) .product-card__overlay { opacity: 1; }

        /* ── Form fields ── */
        .product-field {
            transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
            position: relative; z-index: 1;
        }
        .product-field:hover:not(:disabled) { border-color: #fdba74; background-color: #fff; }
        .product-field:focus { border-color: #fb923c; background-color: #fff; box-shadow: 0 0 0 3px rgba(249,115,22,.12); }

        /* ── Add to cart button ── */
        .product-btn {
            transition: transform .2s cubic-bezier(.4,0,.2,1), box-shadow .2s cubic-bezier(.4,0,.2,1);
        }
        .product-btn:not(:disabled):hover  { transform: scale(1.03); box-shadow: 0 8px 20px -6px rgba(249,115,22,.4); }
        .product-btn:not(:disabled):active { transform: scale(0.97); }
    </style>

    <script src="<?php echo e(asset('js/script.js')); ?>" defer></script>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/order/index.blade.php ENDPATH**/ ?>