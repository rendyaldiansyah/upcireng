document.addEventListener("DOMContentLoaded", () => {
    const app = document.getElementById("storefrontApp");
    const stateEl = document.getElementById("storefrontState");

    if (!app || !stateEl) {
        return;
    }

    const state = JSON.parse(stateEl.textContent || "{}");
    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || "";
    const storageKey = "upcireng_cart_v2";

    const productGrid = document.getElementById("productGrid");
    const openCartButton = document.getElementById("openCartButton");
    const closeCartButton = document.getElementById("closeCartButton");
    const checkoutButton = document.getElementById("checkoutButton");
    const cartDrawer = document.getElementById("cartDrawer");
    const cartBackdrop = document.getElementById("cartBackdrop");
    const cartItems = document.getElementById("cartItems");
    const cartTotal = document.getElementById("cartTotal");
    const cartCountBadge = document.getElementById("cartCountBadge");
    const checkoutDialog = document.getElementById("checkoutDialog");
    const checkoutForm = document.getElementById("checkoutForm");
    const closeCheckoutButton = document.getElementById("closeCheckoutButton");
    const paymentMethod = document.getElementById("paymentMethod");
    const paymentProofWrapper = document.getElementById("paymentProofWrapper");
    const paymentProof = document.getElementById("paymentProof");
    const checkoutItems = document.getElementById("checkoutItems");
    const checkoutTotal = document.getElementById("checkoutTotal");
    const toastEl = document.getElementById("toast");
    const submitOrderButton = document.getElementById("submitOrderButton");

    let cart = loadCart();

    hydrateCustomerFields();
    renderProducts();
    renderCart();
    togglePaymentProof();
    syncCheckoutAvailability();
    initCheckoutPaymentDetails();
    initCopyButtons();

    openCartButton?.addEventListener("click", openCart);
    closeCartButton?.addEventListener("click", closeCart);
    cartBackdrop?.addEventListener("click", closeCart);
    checkoutButton?.addEventListener("click", openCheckout);
    closeCheckoutButton?.addEventListener("click", closeCheckout);
    paymentMethod?.addEventListener("change", function () {
        togglePaymentProof();
        updatePaymentDetailsCheckout();
    });

    productGrid?.addEventListener("click", (event) => {
        const button = event.target.closest("[data-add-product]");

        if (!button) {
            return;
        }

        const productId = Number(button.getAttribute("data-add-product"));
        const card = button.closest("[data-product-card]");
        const quantityInput = card?.querySelector("[data-product-qty]");
        const variantSelect = card?.querySelector("[data-product-variant]");

        const quantity = Math.max(1, Number(quantityInput?.value || 1));
        const variant = variantSelect ? variantSelect.value : "";

        addToCart(productId, quantity, variant);
    });

    cartItems?.addEventListener("click", (event) => {
        const increaseButton = event.target.closest("[data-cart-increase]");
        const decreaseButton = event.target.closest("[data-cart-decrease]");
        const removeButton = event.target.closest("[data-cart-remove]");

        if (increaseButton) {
            updateCartItem(
                increaseButton.getAttribute("data-cart-increase"),
                1,
            );
        }

        if (decreaseButton) {
            updateCartItem(
                decreaseButton.getAttribute("data-cart-decrease"),
                -1,
            );
        }

        if (removeButton) {
            removeCartItem(removeButton.getAttribute("data-cart-remove"));
        }
    });

    checkoutForm?.addEventListener("submit", async (event) => {
        event.preventDefault();

        if (!state.user) {
            window.location.href = state.routes.login;
            return;
        }

        if (!cart.length) {
            toast("Keranjang masih kosong.", true);
            return;
        }

        submitOrderButton.disabled = true;
        submitOrderButton.textContent = "Mengirim...";

        const formData = new FormData(checkoutForm);
        formData.append(
            "items",
            JSON.stringify(
                cart.map((item) => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    variant: item.variant || null,
                })),
            ),
        );

        try {
            const response = await fetch(state.routes.orderStore, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: formData,
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                const errorMessage =
                    payload.message ||
                    firstValidationError(payload.errors) ||
                    "Gagal mengirim order.";
                toast(errorMessage, true);
                return;
            }

            cart = [];
            persistCart();
            renderCart();
            closeCheckout();
            toast(payload.message || "Order berhasil dikirim.");

            if (payload.redirect_url) {
                window.location.href = payload.redirect_url;
            }
        } catch (error) {
            toast("Terjadi kesalahan saat mengirim order.", true);
        } finally {
            submitOrderButton.disabled = false;
            submitOrderButton.textContent = "Kirim Order";
        }
    });

    function hydrateCustomerFields() {
        if (!state.user) {
            return;
        }

        document.getElementById("customerName").value = state.user.name || "";
        document.getElementById("customerPhone").value = state.user.phone || "";
        document.getElementById("customerEmail").value = state.user.email || "";
    }

    function renderProducts() {
        productGrid.innerHTML = (state.products || [])
            .map((product) => {
                const disabled = !state.storeOpen || !product.available;
                const variants =
                    Array.isArray(product.variants) && product.variants.length
                        ? product.variants
                        : ["Regular"];

                return `
                <article data-product-card class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lg shadow-slate-200/70">
                    <img src="${escapeHtml(product.image_url)}" alt="${escapeHtml(product.name)}" class="h-60 w-full object-cover">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-slate-950">${escapeHtml(product.name)}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600">${escapeHtml(product.description || "Tanpa deskripsi produk.")}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold ${disabled ? "bg-slate-100 text-slate-500" : "bg-emerald-100 text-emerald-700"}">
                                ${disabled ? productStatusText(product) : "Siap order"}
                            </span>
                        </div>

                        <p class="mt-5 text-3xl font-black text-slate-950">${money(product.price)}</p>

                        <div class="mt-5 grid gap-4 md:grid-cols-[1fr_120px]">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Varian</label>
                                <select data-product-variant class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100" ${disabled ? "disabled" : ""}>
                                    ${variants.map((variant) => `<option value="${escapeAttribute(variant)}">${escapeHtml(variant)}</option>`).join("")}
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Jumlah</label>
                                <input data-product-qty type="number" min="1" value="1" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100" ${disabled ? "disabled" : ""}>
                            </div>
                        </div>

                        <button type="button" data-add-product="${product.id}" class="mt-5 w-full rounded-2xl px-5 py-3 text-sm font-bold text-white transition ${disabled ? "bg-slate-300 cursor-not-allowed" : "bg-slate-950 hover:bg-orange-500"}" ${disabled ? "disabled" : ""}>
                            ${disabled ? productStatusText(product) : "Tambahkan ke keranjang"}
                        </button>
                    </div>
                </article>
            `;
            })
            .join("");
    }

    function renderCart() {
        cartItems.innerHTML = cart.length
            ? cart
                  .map(
                      (item) => `
                <div class="rounded-[1.5rem] border border-slate-200 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-slate-950">${escapeHtml(item.name)}</p>
                            <p class="mt-1 text-sm text-slate-500">${escapeHtml(item.variant || "Regular")}</p>
                            <p class="mt-3 text-sm font-semibold text-slate-900">${money(item.quantity * item.price)}</p>
                        </div>
                        <button type="button" data-cart-remove="${escapeAttribute(item.key)}" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-500 transition hover:border-rose-300 hover:text-rose-600">
                            Hapus
                        </button>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <button type="button" data-cart-decrease="${escapeAttribute(item.key)}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-lg font-bold text-slate-700 transition hover:border-orange-300 hover:text-orange-600">-</button>
                        <span class="min-w-8 text-center text-sm font-bold text-slate-950">${item.quantity}</span>
                        <button type="button" data-cart-increase="${escapeAttribute(item.key)}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-lg font-bold text-slate-700 transition hover:border-orange-300 hover:text-orange-600">+</button>
                    </div>
                </div>
            `,
                  )
                  .join("")
            : '<div class="rounded-[1.5rem] border border-dashed border-slate-300 px-4 py-12 text-center text-sm text-slate-500">Keranjang masih kosong.</div>';

        const total = cart.reduce(
            (sum, item) => sum + item.quantity * item.price,
            0,
        );
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);

        cartTotal.textContent = money(total);
        cartCountBadge.textContent = String(count);
        checkoutItems.innerHTML = cart.length
            ? cart
                  .map(
                      (item) => `
                <div class="rounded-2xl border border-white/10 px-4 py-3">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold">${escapeHtml(item.name)}</p>
                            <p class="mt-1 text-xs text-slate-300">${escapeHtml(item.variant || "Regular")} | ${item.quantity} item</p>
                        </div>
                        <p class="font-bold">${money(item.quantity * item.price)}</p>
                    </div>
                </div>
            `,
                  )
                  .join("")
            : '<p class="text-sm text-slate-300">Belum ada item.</p>';
        checkoutTotal.textContent = money(total);
        syncCheckoutAvailability();
    }

    function addToCart(productId, quantity, variant) {
        const product = (state.products || []).find(
            (item) => Number(item.id) === Number(productId),
        );

        if (!product) {
            return;
        }

        if (!state.storeOpen) {
            toast(
                `Toko tutup. Jam operasional ${state.hours.start} - ${state.hours.end}.`,
                true,
            );
            return;
        }

        if (!product.available) {
            toast("Produk ini sedang tidak tersedia.", true);
            return;
        }

        const key = itemKey(productId, variant);
        const existing = cart.find((item) => item.key === key);

        if (existing) {
            existing.quantity += quantity;
        } else {
            cart.push({
                key,
                product_id: productId,
                name: product.name,
                variant: variant || "Regular",
                price: Number(product.price),
                quantity,
            });
        }

        persistCart();
        renderCart();
        toast("Produk ditambahkan ke keranjang.");
    }

    function updateCartItem(key, delta) {
        const item = cart.find((entry) => entry.key === key);

        if (!item) {
            return;
        }

        item.quantity += delta;

        if (item.quantity <= 0) {
            cart = cart.filter((entry) => entry.key !== key);
        }

        persistCart();
        renderCart();
    }

    function removeCartItem(key) {
        cart = cart.filter((entry) => entry.key !== key);
        persistCart();
        renderCart();
    }

    function loadCart() {
        try {
            const stored = JSON.parse(localStorage.getItem(storageKey) || "[]");
            return Array.isArray(stored)
                ? stored
                      .filter((item) => item && item.product_id)
                      .map((item) => ({
                          key:
                              item.key ||
                              itemKey(item.product_id, item.variant),
                          product_id: Number(item.product_id),
                          name: item.name || "Produk",
                          variant: item.variant || "Regular",
                          price: Number(item.price || 0),
                          quantity: Math.max(1, Number(item.quantity || 1)),
                      }))
                : [];
        } catch (error) {
            return [];
        }
    }

    function persistCart() {
        localStorage.setItem(storageKey, JSON.stringify(cart));
    }

    function openCart() {
        cartDrawer.classList.remove("translate-x-full");
        cartBackdrop.classList.remove("hidden");
    }

    function closeCart() {
        cartDrawer.classList.add("translate-x-full");
        cartBackdrop.classList.add("hidden");
    }

    function openCheckout() {
        if (!state.storeOpen) {
            toast(
                `Toko tutup. Jam operasional ${state.hours.start} - ${state.hours.end}.`,
                true,
            );
            return;
        }

        if (!state.user) {
            window.location.href = state.routes.login;
            return;
        }

        if (!cart.length) {
            toast("Keranjang masih kosong.", true);
            return;
        }

        closeCart();
        checkoutDialog.showModal();
    }

    function closeCheckout() {
        if (checkoutDialog.open) {
            checkoutDialog.close();
        }
    }

    function togglePaymentProof() {
        const required = paymentMethod.value !== "cod";
        paymentProofWrapper.classList.toggle("hidden", !required);
        paymentProof.required = required;
    }

    function initCheckoutPaymentDetails() {
        // Show COD details by default
        updatePaymentDetailsCheckout();
    }

    function updatePaymentDetailsCheckout() {
        const method = paymentMethod?.value || "cod";
        const container = document.getElementById("paymentDetailsCheckout");

        if (!container) return;

        // Hide all payment details
        container.querySelectorAll("[id^='checkout-']").forEach((el) => {
            el.classList.add("hidden");
            el.classList.remove("animate-fade-in");
        });

        // Show selected payment method details
        const detailEl = document.getElementById(`checkout-${method}`);
        if (detailEl) {
            detailEl.classList.remove("hidden");
            setTimeout(() => {
                detailEl.classList.add("animate-fade-in");
            }, 10);
        }
    }

    function initCopyButtons() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(".copy-btn");
            if (!btn) return;

            e.preventDefault();
            const value = btn.getAttribute("data-copy");

            if (!value) return;

            try {
                await navigator.clipboard.writeText(value);
                const originalText = btn.textContent;
                btn.textContent = "✅ Tersalin!";
                btn.classList.remove(
                    "hover:bg-blue-200",
                    "hover:bg-purple-200",
                    "hover:bg-brand-200",
                );
                btn.classList.add("bg-green-200", "text-green-600");

                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.classList.remove("bg-green-200", "text-green-600");
                    btn.classList.add(
                        "hover:bg-blue-200",
                        "hover:bg-purple-200",
                        "hover:bg-brand-200",
                    );
                }, 2000);
            } catch (err) {
                toast("Gagal menyalin ke clipboard", true);
            }
        });
    }

    function syncCheckoutAvailability() {
        if (!checkoutButton) {
            return;
        }

        if (!state.storeOpen) {
            checkoutButton.disabled = true;
            checkoutButton.textContent = "STORE CLOSED";
            checkoutButton.classList.remove(
                "bg-slate-950",
                "text-white",
                "hover:bg-orange-500",
            );
            checkoutButton.classList.add(
                "cursor-not-allowed",
                "bg-slate-200",
                "text-slate-500",
            );
            return;
        }

        checkoutButton.disabled = false;
        checkoutButton.textContent = "Lanjut Checkout";
        checkoutButton.classList.remove(
            "cursor-not-allowed",
            "bg-slate-200",
            "text-slate-500",
        );
        checkoutButton.classList.add(
            "bg-slate-950",
            "text-white",
            "hover:bg-orange-500",
        );
    }

    function itemKey(productId, variant) {
        return `${productId}::${variant || "Regular"}`;
    }

    function productStatusText(product) {
        if (!state.storeOpen) {
            return "Toko tutup";
        }

        if (product.stock_status === "out_of_stock") {
            return "Stok habis";
        }

        if (!product.is_open) {
            return "Produk ditutup";
        }

        return "Tidak tersedia";
    }

    function firstValidationError(errors) {
        if (!errors || typeof errors !== "object") {
            return "";
        }

        const firstField = Object.keys(errors)[0];
        return Array.isArray(errors[firstField]) ? errors[firstField][0] : "";
    }

    function toast(message, isError = false) {
        toastEl.textContent = message;
        toastEl.classList.remove("hidden", "bg-slate-950", "bg-rose-500");
        toastEl.classList.add(isError ? "bg-rose-500" : "bg-slate-950");

        window.clearTimeout(toastEl.hideTimer);
        toastEl.hideTimer = window.setTimeout(() => {
            toastEl.classList.add("hidden");
        }, 3000);
    }

    function money(value) {
        return `Rp ${Number(value || 0).toLocaleString("id-ID")}`;
    }

    function escapeHtml(value) {
        return String(value ?? "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function escapeAttribute(value) {
        return escapeHtml(value).replace(/"/g, "&quot;");
    }
});
