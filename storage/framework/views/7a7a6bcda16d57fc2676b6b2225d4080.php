<div id="toastContainer"
     class="fixed right-4 z-[80] max-w-sm space-y-3 pointer-events-none hidden"
     aria-live="polite"
     aria-atomic="true"></div>

<style>
    @keyframes toastSlideInRight {
        from {
            opacity: 0;
            transform: translateX(100px) rotateY(10deg);
        }
        to {
            opacity: 1;
            transform: translateX(0) rotateY(0);
        }
    }

    @keyframes toastSlideOutRight {
        from {
            opacity: 1;
            transform: translateX(0) rotateY(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px) rotateY(10deg);
        }
    }

    .toast-enter {
        animation: toastSlideInRight 0.4s ease-out forwards;
    }

    .toast-exit {
        animation: toastSlideOutRight 0.3s ease-in forwards;
    }

    .toast-base {
        border-radius: 0.9rem;
        padding: 1rem;
        box-shadow: 0 18px 50px rgba(15, 23, 42, 0.16);
        backdrop-filter: blur(12px);
        pointer-events: auto;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        border: 1px solid transparent;
        min-width: 280px;
        max-width: 360px;
    }

    .toast-success {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #064e3b;
    }

    .toast-error {
        background: #fef2f2;
        border-color: #fecaca;
        color: #7f1d1d;
    }

    .toast-warning {
        background: #fffbeb;
        border-color: #fde68a;
        color: #78350f;
    }

    .toast-info {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e3a8a;
    }

    .toast-icon {
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
        width: 1.5rem;
        height: 1.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background: currentColor;
        opacity: 0.12;
    }

    .toast-close {
        flex-shrink: 0;
        background: transparent;
        border: 0;
        color: inherit;
        opacity: 0.65;
        cursor: pointer;
        font-size: 1.05rem;
        line-height: 1;
        padding: 0;
        margin-left: auto;
    }

    .toast-close:hover {
        opacity: 1;
    }
</style>

<script>
    // ── Posisikan toast tepat di bawah navbar ──────────────────────────────
    function syncToastPosition() {
        const navbar    = document.getElementById('main-navbar');
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const navHeight = navbar ? navbar.offsetHeight : 64;
        // Tambah 12px jarak supaya tidak mepet
        container.style.top = (navHeight + 12) + 'px';
    }

    // Jalankan saat DOM siap dan saat resize
    document.addEventListener('DOMContentLoaded', syncToastPosition);
    window.addEventListener('resize', syncToastPosition);
    // Jalankan juga langsung (kalau script ini dimuat setelah DOMContentLoaded)
    syncToastPosition();

    class Toast {
        static instance = null;

        constructor() {
            this.container = document.getElementById('toastContainer');

            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'toastContainer';
                this.container.className = 'fixed right-4 z-[80] max-w-sm space-y-3 pointer-events-none hidden';
                this.container.setAttribute('aria-live', 'polite');
                this.container.setAttribute('aria-atomic', 'true');
                document.body.appendChild(this.container);
                syncToastPosition();
            }
        }

        static getInstance() {
            if (!Toast.instance) {
                Toast.instance = new Toast();
            }
            return Toast.instance;
        }

        show(message, type = 'info', duration = 2500) {
            this.container.classList.remove('hidden');

            const toast = this.createToastElement(message, type);
            this.container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('toast-enter');
            });

            window.setTimeout(() => {
                this.dismiss(toast);
            }, duration);

            return toast;
        }

        createToastElement(message, type) {
            const toast = document.createElement('div');
            const supportedType = ['success', 'error', 'warning', 'info'].includes(type) ? type : 'info';

            toast.className = `toast-base toast-${supportedType}`;

            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ',
            };

            toast.innerHTML = `
                <span class="toast-icon" aria-hidden="true">
                    ${icons[supportedType]}
                </span>
                <div class="flex-1">
                    <p class="text-sm font-semibold leading-6">${this.escapeHtml(String(message))}</p>
                </div>
                <button type="button" class="toast-close" data-dismiss aria-label="Tutup notifikasi">
                    ×
                </button>
            `;

            toast.querySelector('[data-dismiss]').addEventListener('click', () => {
                this.dismiss(toast);
            });

            return toast;
        }

        dismiss(toast) {
            if (!toast || !toast.isConnected) return;

            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');

            window.setTimeout(() => {
                if (toast.isConnected) {
                    toast.remove();
                }
                if (this.container.children.length === 0) {
                    this.container.classList.add('hidden');
                }
            }, 300);
        }

        success(message, duration = 2500) {
            return this.show(message, 'success', duration);
        }

        error(message, duration = 3500) {
            return this.show(message, 'error', duration);
        }

        warning(message, duration = 3000) {
            return this.show(message, 'warning', duration);
        }

        info(message, duration = 2500) {
            return this.show(message, 'info', duration);
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    window.toast = (message, type = 'info', duration) => {
        const instance = Toast.getInstance();
        if (typeof instance[type] === 'function') {
            return instance[type](message, duration);
        }
        return instance.show(message, type, duration);
    };

    window.Toast = Toast;
</script><?php /**PATH C:\Users\user\Desktop\UP Cireng\upcireng\resources\views/components/toast.blade.php ENDPATH**/ ?>