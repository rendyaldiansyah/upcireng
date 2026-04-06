import "./bootstrap";

/**
 * ================================================================
 * Smooth scroll animations & small UI interactions
 * ================================================================
 */

const initApp = () => {
    const reduceMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    const animateOnLoad = () => {
        const elements = document.querySelectorAll("[data-animate]");

        elements.forEach((el, index) => {
            const animationType =
                el.getAttribute("data-animate") || "fade-in-up";
            const delay = Number(el.getAttribute("data-delay")) || index * 100;

            el.style.opacity = "0";
            el.classList.add(`animate-${animationType}`);

            if (!reduceMotion) {
                el.style.animationDelay = `${Math.min(delay, 500)}ms`;
            } else {
                el.style.opacity = "1";
            }
        });
    };

    const setupScrollAnimations = () => {
        const elements = document.querySelectorAll("[data-animate]");

        if (!("IntersectionObserver" in window)) {
            elements.forEach((el) => {
                el.style.opacity = "1";
            });
            return;
        }

        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const animationType =
                    entry.target.getAttribute("data-animate") || "fade-in-up";
                const delay =
                    Number(entry.target.getAttribute("data-delay")) || 0;

                window.setTimeout(() => {
                    entry.target.classList.add(`animate-${animationType}`);
                    entry.target.style.opacity = "1";
                }, delay);

                observer.unobserve(entry.target);
            });
        }, observerOptions);

        elements.forEach((el) => {
            el.style.opacity = "0";
            observer.observe(el);
        });
    };

    const setupDeleteConfirmations = () => {
        document
            .querySelectorAll("form[data-delete-confirm]")
            .forEach((form) => {
                form.addEventListener("submit", (e) => {
                    const message =
                        form.getAttribute("data-delete-confirm") ||
                        "Yakin ingin menghapus data ini?";
                    if (!window.confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
    };

    const setupAnimationDelays = () => {
        document.querySelectorAll("[data-delay]").forEach((el) => {
            const delay = Number(el.getAttribute("data-delay"));
            if (!Number.isNaN(delay)) {
                el.style.setProperty("animation-delay", `${delay}ms`);
            }
        });
    };

    const setupSmoothColorTransitions = () => {
        document
            .querySelectorAll("button[data-toggle-color]")
            .forEach((btn) => {
                btn.addEventListener("click", () => {
                    btn.style.transition =
                        "all 300ms cubic-bezier(0.4, 0, 0.2, 1)";
                });
            });
    };

    const setupAnchorScrolling = () => {
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
            anchor.addEventListener("click", (e) => {
                const href = anchor.getAttribute("href");
                if (!href || href === "#") return;

                const target = document.querySelector(href);
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            });
        });
    };

    const fadeInBody = () => {
        if (!document.body) return;

        document.body.style.opacity = "0";
        document.body.style.transition =
            "opacity 500ms cubic-bezier(0.4, 0, 0.2, 1)";

        requestAnimationFrame(() => {
            document.body.style.opacity = "1";
        });
    };

    animateOnLoad();
    setupScrollAnimations();
    setupDeleteConfirmations();
    setupAnimationDelays();
    setupSmoothColorTransitions();
    setupAnchorScrolling();
    fadeInBody();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initApp);
} else {
    initApp();
}
