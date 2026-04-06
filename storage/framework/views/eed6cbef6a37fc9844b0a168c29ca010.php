<div class="space-y-6 sm:space-y-8">

  
  <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">
    <div>
      <label for="name" class="mb-2 block text-sm font-semibold text-slate-800">
        Nama Produk <span class="text-rose-500">*</span>
      </label>
      <input
        id="name"
        type="text"
        name="name"
        value="<?php echo e(old('name', $product?->name ?? '')); ?>"
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        placeholder="Cireng Original Keju"
        required
        aria-describedby="name-error"
      >
      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p id="name-error" class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
          <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <?php echo e($message); ?>

        </p>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
      <label for="price" class="mb-2 block text-sm font-semibold text-slate-800">
        Harga Produk <span class="text-rose-500">*</span>
      </label>
      <div class="relative">
        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-base font-bold text-slate-500 sm:left-5 sm:text-lg">Rp</span>
        <input
          id="price"
          type="number"
          name="price"
          step="0.01"
          value="<?php echo e(old('price', $product?->price ?? '')); ?>"
          class="w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:pl-12 sm:pr-5 sm:py-4 <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
          placeholder="25000"
          required
          data-price-format
          aria-describedby="price-error"
        >
      </div>
      <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p id="price-error" class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
          <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <?php echo e($message); ?>

        </p>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="mt-1.5 text-xs text-slate-500">Harga dalam Rupiah (numeric only)</p>
    </div>
  </div>

  
  <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">
    <div>
      <label for="status" class="mb-2 block text-sm font-semibold text-slate-800">
        Status <span class="text-rose-500">*</span>
      </label>
      <select
        id="status"
        name="status"
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        required
      >
        <option value="active"   <?php echo e(old('status', $product?->status ?? 'active') == 'active'   ? 'selected' : ''); ?>>✅ Aktif</option>
        <option value="inactive" <?php echo e(old('status', $product?->status ?? '')        == 'inactive' ? 'selected' : ''); ?>>⏸️ Nonaktif</option>
      </select>
      <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1.5 text-xs font-medium text-red-600"><?php echo e($message); ?></p>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
      <label for="stock_status" class="mb-2 block text-sm font-semibold text-slate-800">
        Status Stok <span class="text-rose-500">*</span>
      </label>
      <select
        id="stock_status"
        name="stock_status"
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4 <?php $__errorArgs = ['stock_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        required
      >
        <option value="available"    <?php echo e(old('stock_status', $product?->stock_status ?? 'available') == 'available'    ? 'selected' : ''); ?>>🟢 Tersedia</option>
        <option value="out_of_stock" <?php echo e(old('stock_status', $product?->stock_status ?? '')           == 'out_of_stock' ? 'selected' : ''); ?>>🔴 Habis</option>
      </select>
      <?php $__errorArgs = ['stock_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1.5 text-xs font-medium text-red-600"><?php echo e($message); ?></p>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
  </div>

  
  <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">
    <div>
      <label for="is_open" class="mb-2 block text-sm font-semibold text-slate-800">
        Buka Order <span class="text-rose-500">*</span>
      </label>
      <select
        id="is_open"
        name="is_open"
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4 <?php $__errorArgs = ['is_open'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        required
      >
        <option value="1" <?php echo e((string)old('is_open', $product?->is_open ?? 1) == '1' ? 'selected' : ''); ?>>🟢 Ya, terima order</option>
        <option value="0" <?php echo e((string)old('is_open', $product?->is_open ?? 1) == '0' ? 'selected' : ''); ?>>🔴 Tutup sementara</option>
      </select>
      <?php $__errorArgs = ['is_open'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1.5 text-xs font-medium text-red-600"><?php echo e($message); ?></p>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
      <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-800">Urutan Tampil</label>
      <input
        id="sort_order"
        type="number"
        name="sort_order"
        min="0"
        max="999"
        value="<?php echo e(old('sort_order', $product?->sort_order ?? 0)); ?>"
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4"
        placeholder="0"
        aria-describedby="sort_order-help"
      >
      <p id="sort_order-help" class="mt-1.5 text-xs text-slate-500">Semakin kecil, semakin atas tampilan (default: 0)</p>
    </div>
  </div>

  
  <div>
    <label for="variants_input" class="mb-2 block text-sm font-semibold text-slate-800">Varian Produk</label>
    <textarea
      id="variants_input"
      name="variants_input"
      rows="3"
      class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4 <?php $__errorArgs = ['variants_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
      placeholder="Pisahkan dengan koma: Original, Keju, Pedas Level 1, Jumbo"
    ><?php echo e(old('variants_input', $product ? implode("\n", $product->availableVariants()) : '')); ?></textarea>
    <?php $__errorArgs = ['variants_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <p class="mt-1.5 text-xs font-medium text-red-600"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <div id="variants-preview" class="mt-3 flex flex-wrap gap-2">
      <?php if($product): ?>
        <?php $__currentLoopData = $product->availableVariants(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <span class="rounded-full bg-brand-100 px-3 py-1.5 text-xs font-bold text-brand-700"><?php echo e($variant); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
    <p class="mt-1.5 text-xs text-slate-500">Varian akan otomatis di-parse (koma atau baris baru)</p>
  </div>

  
  <div>
    <label for="description" class="mb-2 block text-sm font-semibold text-slate-800">Deskripsi Produk</label>
    <textarea
      id="description"
      name="description"
      rows="4"
      class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 hover:border-slate-300 hover:shadow-md sm:px-5 sm:py-4 sm:rows-5 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 ring-2 ring-red-200/50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
      placeholder="Deskripsi singkat tentang produk ini..."
    ><?php echo e(old('description', $product?->description ?? '')); ?></textarea>
    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <p class="mt-1.5 text-xs font-medium text-red-600"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <p class="mt-1.5 text-xs text-slate-500">HTML tidak di-render, max 1000 karakter</p>
  </div>

  
  <div>
    <label for="image" class="mb-2 block text-sm font-semibold text-slate-800">Gambar Produk</label>

    <?php if($product?->image_url): ?>
      <div class="mb-4">
        <img
          src="<?php echo e($product->image_url); ?>"
          alt="<?php echo e($product->name ?? 'Preview'); ?>"
          id="current-image"
          class="h-40 w-full max-w-xs rounded-2xl object-cover shadow-xl sm:h-48 sm:max-w-md"
        >
        <p class="mt-2 text-xs text-slate-500">Gambar saat ini — upload baru untuk mengganti</p>
      </div>
    <?php endif; ?>

    <input
      id="image"
      type="file"
      name="image"
      accept="image/jpeg,image/png,image/webp"
      class="w-full rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-sm font-semibold text-slate-500 transition-all duration-300 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600 focus:border-brand-400 focus:ring-4 focus:ring-brand-100/50 sm:px-5 sm:py-8 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-brand-700 file:transition file:hover:bg-brand-100 sm:file:mr-5 sm:file:px-4 sm:file:py-2 sm:file:text-sm <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
    >
    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <p class="mt-1.5 text-xs font-medium text-red-600"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <img
      id="image-preview"
      src=""
      alt="Preview"
      class="mt-4 hidden h-40 w-full max-w-xs rounded-2xl object-cover shadow-xl sm:h-48 sm:max-w-md"
    >
    <p class="mt-2 text-xs text-slate-500">JPG, PNG, WebP | Max 4MB | Rasio ideal 1:1 atau 4:3</p>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form           = document.querySelector('form');
  const priceInput     = document.querySelector('[data-price-format]');
  const imageInput     = document.getElementById('image');
  const imagePreview   = document.getElementById('image-preview');
  const currentImage   = document.getElementById('current-image');
  const variantsInput  = document.getElementById('variants_input');
  const variantsPreview = document.getElementById('variants-preview');

  // Price — select all on focus for quick replace
  if (priceInput) {
    priceInput.addEventListener('focus', function () { this.select(); });
  }

  // Image preview
  if (imageInput) {
    imageInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function (e) {
        if (currentImage) currentImage.style.display = 'none';
        imagePreview.src = e.target.result;
        imagePreview.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    });
  }

  // Variants live preview
  if (variantsInput) {
    function updateVariantsPreview() {
      const variants = variantsInput.value
        .split(/[\r\n,]+/)
        .map(v => v.trim())
        .filter(Boolean);
      variantsPreview.innerHTML = variants
        .map(v => `<span class="rounded-full bg-brand-100 px-3 py-1 text-xs font-bold text-brand-700">${v}</span>`)
        .join('');
    }
    variantsInput.addEventListener('input', updateVariantsPreview);
    updateVariantsPreview();
  }

  // Submit loading state
  if (form) {
    form.addEventListener('submit', function () {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <svg class="mx-auto h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
          </svg>
          Menyimpan...
        `;
      }
    });
  }
});
</script><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/admin/products/partials/form.blade.php ENDPATH**/ ?>