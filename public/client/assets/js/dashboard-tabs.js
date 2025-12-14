// dashboard-tabs.js - Xử lý lưu và khôi phục tab/modal

(function () {
    "use strict";

    console.log("=== dashboard-tabs.js loaded ===");

    // Đợi DOM ready
    document.addEventListener("DOMContentLoaded", function () {
        console.log("=== DOMContentLoaded ===");
        initTabs();
        initModals();
    });

    function initTabs() {
        console.log("initTabs called");

        // Tìm tất cả tab buttons
        var tabButtons = document.querySelectorAll("#v-pills-tab .nav-link");
        console.log("Tab buttons found:", tabButtons.length);

        // Log từng tab
        tabButtons.forEach(function (btn, index) {
            console.log("Tab " + index + ":", btn.id);
        });

        // Lưu tab khi click
        tabButtons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                var tabId = this.id;
                console.log("Tab clicked:", tabId);

                try {
                    localStorage.setItem("activeDashboardTab", tabId);
                    localStorage.removeItem("activeModal");
                    console.log("Saved tab to localStorage:", tabId);
                } catch (e) {
                    console.error("localStorage error:", e);
                }
            });
        });

        // Khôi phục tab đã lưu
        try {
            var savedTab = localStorage.getItem("activeDashboardTab");
            console.log("Saved tab from localStorage:", savedTab);

            if (savedTab) {
                var tabBtn = document.getElementById(savedTab);
                console.log("Tab button element:", tabBtn);

                if (tabBtn) {
                    setTimeout(function () {
                        console.log("Triggering click on:", savedTab);
                        tabBtn.click();
                    }, 200);
                }
            }
        } catch (e) {
            console.error("Error restoring tab:", e);
        }
    }

    function initModals() {
        console.log("initModals called");

        var modals = document.querySelectorAll(".modal");
        console.log("Modals found:", modals.length);

        // Lưu modal khi mở
        modals.forEach(function (modal) {
            modal.addEventListener("shown.bs.modal", function () {
                var modalId = this.id;
                console.log("Modal opened:", modalId);

                try {
                    localStorage.setItem("activeModal", modalId);
                } catch (e) {}
            });

            modal.addEventListener("hidden.bs.modal", function () {
                var modalId = this.id;
                console.log("Modal closed:", modalId);

                try {
                    localStorage.removeItem("activeModal");
                } catch (e) {}
            });
        });

        // Khôi phục modal (chỉ khi không có lỗi validation)
        // Lấy từ data attribute thay vì Blade
        var hasErrors =
            document.body.getAttribute("data-has-errors") === "true";

        if (!hasErrors) {
            try {
                var savedModal = localStorage.getItem("activeModal");
                console.log("Saved modal from localStorage:", savedModal);

                if (savedModal) {
                    var modalEl = document.getElementById(savedModal);

                    if (modalEl) {
                        setTimeout(function () {
                            console.log("Opening modal:", savedModal);

                            // Thử Bootstrap 5
                            if (
                                typeof bootstrap !== "undefined" &&
                                bootstrap.Modal
                            ) {
                                var modal = new bootstrap.Modal(modalEl);
                                modal.show();
                            }
                            // Fallback jQuery
                            else if (typeof jQuery !== "undefined") {
                                jQuery("#" + savedModal).modal("show");
                            }
                        }, 500);
                    }
                }
            } catch (e) {
                console.error("Error restoring modal:", e);
            }
        }
    }

    // Global function để có thể gọi từ onclick
    window.saveTab = function (tabId) {
        console.log("saveTab called:", tabId);
        try {
            localStorage.setItem("activeDashboardTab", tabId);
            localStorage.removeItem("activeModal");
        } catch (e) {}
    };
})();
