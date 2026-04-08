// ============================================================
// PATCH untuk public/js/script.js
// Tambahkan dukungan pilihan MATENG / MENTAH di keranjang
// ============================================================
//
// CARI baris di handler tombol "Tambah ke Keranjang" (data-add-product)
// yang membaca varian & qty, lalu tambahkan pembacaan kondisi.
//
// CONTOH kode yang biasanya ada di script.js:
//
//   document.addEventListener('click', function (e) {
//       const btn = e.target.closest('[data-add-product]');
//       if (!btn) return;
//       const card    = btn.closest('[data-product-card]');
//       const variant = card.querySelector('[data-product-variant]')?.value || '';
//       const qty     = parseFloat(card.querySelector('[data-product-qty]')?.value) || 1;
//       ...
//   });
//
// UBAH MENJADI:

document.addEventListener("click", function (e) {
    const btn = e.target.closest("[data-add-product]");
    if (!btn || btn.disabled) return;

    const productId = parseInt(btn.dataset.addProduct);
    const card = btn.closest("[data-product-card]");
    if (!card) return;

    // ★ Baca kondisi (mateng/mentah) — NEW
    const kondisiEl = card.querySelector("[data-product-kondisi]:checked");
    const kondisi = kondisiEl ? kondisiEl.value : "Mateng";

    // Existing
    const variantRaw =
        card.querySelector("[data-product-variant]")?.value?.trim() || "";
    const qty =
        parseFloat(card.querySelector("[data-product-qty]")?.value) || 1;

    // ★ Gabungkan kondisi ke dalam variant string
    // Contoh: "Mateng - Pedas" atau hanya "Mentah"
    const variant =
        [kondisi, variantRaw].filter((v) => v && v !== "Regular").join(" - ") ||
        kondisi;

    // Ambil data produk dari state
    const state = JSON.parse(
        document.getElementById("storefrontState")?.textContent || "{}",
    );
    const product = (state.products || []).find((p) => p.id === productId);
    if (!product) return;

    // Tambah ke cart (gunakan fungsi addToCart yang sudah ada)
    // Pastikan object item menyertakan kondisi
    addToCart({
        product_id: product.id,
        product_name: product.name,
        price: product.price,
        variant: variant, // ★ sudah include kondisi
        kondisi: kondisi, // ★ tersimpan terpisah juga
        quantity: qty,
        image_url: product.image_url,
    });
});

// ============================================================
// Fungsi renderCartItem — pastikan kondisi ditampilkan
// ============================================================
// Jika kamu punya fungsi renderCartItem, pastikan variant
// ditampilkan di keranjang, contoh:
//
// function renderCartItem(item) {
//     return `
//         <div class="cart-item ...">
//             <p class="font-bold">${item.product_name}</p>
//             <p class="text-xs text-slate-500">
//                 ${item.variant}     ← ini sudah berisi "Mateng - Pedas" dll
//             </p>
//             ...
//         </div>
//     `;
// }
//
// Dan saat submit order (POST ke /order), items akan terkirim dengan
// variant berisi kondisi, sehingga tersimpan di database order.items
// ============================================================
