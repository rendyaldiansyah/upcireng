/**
 * REALTIME ORDER NOTIFICATION SYSTEM
 * ──────────────────────────────────────────────────────────
 *
 * Features:
 * - Listens for new order via WebSocket (Laravel Echo)
 * - Anti-spam: Only plays sound ONCE per new order
 * - Stores last order ID in localStorage
 * - No delay, no repeated notifications
 * - Production-grade security & performance
 *
 * Usage: Include in admin dashboard view
 * @requires Laravel Echo / Pusher / Reverb
 */

(function () {
    "use strict";

    const CONFIG = {
        soundFile: "/sounds/chord.mp3",
        storageKey: "upcireng_last_order_id",
        channel: "orders",
        event: "OrderCreated",
        logPrefix: "[ORDER NOTIFICATIONS]",
    };

    const State = {
        lastOrderId: parseInt(localStorage.getItem(CONFIG.storageKey) || 0),
        isAudioReady: false,
        audioCache: null,
    };

    /**
     * Initialize audio element (preload for instant playback)
     */
    function initializeAudio() {
        if (State.isAudioReady) return;

        try {
            const audio = new Audio(CONFIG.soundFile);
            audio.preload = "auto";
            audio.volume = 0.7; // 70% volume (safe for notifications)

            State.audioCache = audio;
            State.isAudioReady = true;

            console.log(`${CONFIG.logPrefix} Audio ready for playback`);
        } catch (error) {
            console.warn(
                `${CONFIG.logPrefix} Audio initialization failed:`,
                error,
            );
        }
    }

    /**
     * Play notification sound (anti-spam logic)
     * Only plays if order ID > last stored order ID
     */
    function playNotificationSound(orderId) {
        if (!State.isAudioReady || !State.audioCache) {
            console.warn(`${CONFIG.logPrefix} Audio not ready`);
            return;
        }

        if (orderId <= State.lastOrderId) {
            console.log(
                `${CONFIG.logPrefix} Skipped: Order #${orderId} already notified`,
            );
            return;
        }

        try {
            // Reset audio to start for reliable playback
            State.audioCache.currentTime = 0;

            // Play sound
            const playPromise = State.audioCache.play();
            if (playPromise) {
                playPromise.catch((error) => {
                    console.warn(
                        `${CONFIG.logPrefix} Audio playback failed:`,
                        error,
                    );
                });
            }

            console.log(
                `${CONFIG.logPrefix} ✓ Sound played for order #${orderId}`,
            );
        } catch (error) {
            console.error(`${CONFIG.logPrefix} Error during playback:`, error);
        }
    }

    /**
     * Update last order ID in localStorage
     */
    function updateLastOrderId(orderId) {
        if (!Number.isInteger(orderId) || orderId <= 0) return;

        localStorage.setItem(CONFIG.storageKey, String(orderId));
        State.lastOrderId = orderId;

        console.log(`${CONFIG.logPrefix} Last order ID updated: ${orderId}`);
    }

    /**
     * Handle new order received from WebSocket
     */
    function handleNewOrder(eventData) {
        if (!eventData || !eventData.id) {
            console.warn(`${CONFIG.logPrefix} Invalid order data:`, eventData);
            return;
        }

        const orderId = eventData.id;

        console.log(
            `${CONFIG.logPrefix} New order received: #${orderId} (${eventData.customer_name}, Rp ${eventData.total_price})`,
        );

        // Play sound (with anti-spam check)
        playNotificationSound(orderId);

        // Update state
        updateLastOrderId(orderId);

        // Optional: Update UI (you can customize this)
        updateDashboardNotification(eventData);
    }

    /**
     * Update dashboard UI with new order notification
     */
    function updateDashboardNotification(orderData) {
        // Dispatch custom event for dashboard to listen
        const event = new CustomEvent("newOrderNotified", {
            detail: orderData,
        });
        document.dispatchEvent(event);

        // Optional: Flash notification badge
        const badge = document.getElementById("pending-orders-badge");
        if (badge) {
            badge.classList.add("animate-pulse");
            setTimeout(() => badge.classList.remove("animate-pulse"), 2000);
        }
    }

    /**
     * Setup WebSocket listener via Laravel Echo
     */
    function setupWebSocketListener() {
        // Check if Echo is available
        if (typeof window.Echo === "undefined") {
            console.warn(
                `${CONFIG.logPrefix} Laravel Echo not available - realtime disabled`,
            );
            return false;
        }

        try {
            console.log(`${CONFIG.logPrefix} Setting up WebSocket listener...`);

            window.Echo.channel(CONFIG.channel).listen(
                CONFIG.event,
                (eventData) => {
                    handleNewOrder(eventData);
                },
            );

            console.log(
                `${CONFIG.logPrefix} ✓ WebSocket listener active on channel: ${CONFIG.channel}`,
            );
            return true;
        } catch (error) {
            console.error(`${CONFIG.logPrefix} WebSocket setup failed:`, error);
            return false;
        }
    }

    /**
     * Setup polling fallback if WebSocket unavailable
     * (Lower priority - only if Echo fails)
     */
    function setupPollingFallback() {
        if (
            typeof configuration === "undefined" ||
            !configuration.rtPollingFallback
        ) {
            return;
        }

        console.warn(
            `${CONFIG.logPrefix} Using polling fallback (less efficient)`,
        );

        // Anti-throttle: Only poll every 5 seconds minimum
        setInterval(async () => {
            try {
                const response = await fetch("/adminup/api/realtime-orders", {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                    },
                    credentials: "include",
                });

                if (!response.ok) return;

                const data = await response.json();
                if (data.orders && data.orders.length > 0) {
                    // Get latest order
                    const latestOrder = data.orders[0];
                    if (latestOrder.id > State.lastOrderId) {
                        handleNewOrder(latestOrder);
                    }
                }
            } catch (error) {
                console.debug(
                    `${CONFIG.logPrefix} Polling fetch error:`,
                    error,
                );
            }
        }, 5000); // 5 second interval
    }

    /**
     * Initialize the entire system
     */
    function initialize() {
        console.log(
            `${CONFIG.logPrefix} Initializing realtime notification system...`,
        );
        console.log(
            `${CONFIG.logPrefix} Last known order: ${State.lastOrderId}`,
        );

        // 1. Setup audio
        initializeAudio();

        // 2. Setup WebSocket (primary)
        const wsReady = setupWebSocketListener();

        // 3. Setup polling fallback (if needed)
        if (!wsReady) {
            setupPollingFallback();
        }

        console.log(`${CONFIG.logPrefix} ✓ System initialized`);
    }

    /**
     * Auto-initialize when DOM is ready
     */
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initialize);
    } else {
        initialize();
    }

    // Expose for manual testing
    window.realtimeNotifications = {
        playSound: () => playNotificationSound(State.lastOrderId + 1),
        getLastOrderId: () => State.lastOrderId,
        setLastOrderId: (id) => updateLastOrderId(id),
        test: (id) =>
            handleNewOrder({ id, customer_name: "Test", total_price: 50000 }),
    };

    console.log(
        `${CONFIG.logPrefix} Exposed API: window.realtimeNotifications`,
    );
})();
