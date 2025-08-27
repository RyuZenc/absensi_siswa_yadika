// Global error handler untuk mencegah crash
window.addEventListener("error", function (event) {
    console.error("JavaScript Error:", event.error);

    // Log error details untuk debugging
    const errorInfo = {
        message: event.message,
        filename: event.filename,
        lineno: event.lineno,
        colno: event.colno,
        error: event.error,
    };

    console.error("Error Details:", errorInfo);

    // Prevent default error handling untuk beberapa error yang bisa di-handle
    if (
        event.message.includes("Cannot read property") ||
        event.message.includes("Cannot read properties")
    ) {
        event.preventDefault();
        return true;
    }
});

// Handle unhandled promise rejections
window.addEventListener("unhandledrejection", function (event) {
    console.error("Unhandled Promise Rejection:", event.reason);

    // Prevent default untuk fetch errors yang sudah di-handle di tempat lain
    if (
        event.reason &&
        event.reason.message &&
        event.reason.message.includes("fetch")
    ) {
        event.preventDefault();
    }
});

// Utility function untuk safe element selection
window.safeQuerySelector = function (selector) {
    try {
        return document.querySelector(selector);
    } catch (error) {
        console.warn("Error selecting element:", selector, error);
        return null;
    }
};

window.safeQuerySelectorAll = function (selector) {
    try {
        return document.querySelectorAll(selector);
    } catch (error) {
        console.warn("Error selecting elements:", selector, error);
        return [];
    }
};

// Safe addEventListener
window.safeAddEventListener = function (element, event, handler) {
    if (element && typeof element.addEventListener === "function") {
        try {
            element.addEventListener(event, handler);
            return true;
        } catch (error) {
            console.warn("Error adding event listener:", error);
            return false;
        }
    }
    return false;
};

// Safe fetch with error handling
window.safeFetch = async function (url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                ...options.headers,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response;
    } catch (error) {
        console.error("Fetch error:", error);
        throw error;
    }
};
