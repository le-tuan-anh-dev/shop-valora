// dashboard-tabs.js - Xử lý lưu và khôi phục tab/modal

(function () {
    "use strict";

    // Đợi DOM ready
    document.addEventListener("DOMContentLoaded", function () {
        initTabs();
        initModals();
    });

    function initTabs() {
        // Tìm tất cả tab buttons
        var tabButtons = document.querySelectorAll("#v-pills-tab .nav-link");

        // Log từng tab
        tabButtons.forEach(function (btn, index) {});

        // Lưu tab khi click
        tabButtons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                var tabId = this.id;

                try {
                    localStorage.setItem("activeDashboardTab", tabId);
                    localStorage.removeItem("activeModal");
                } catch (e) {}
            });
        });

        // Khôi phục tab đã lưu
        try {
            var savedTab = localStorage.getItem("activeDashboardTab");

            if (savedTab) {
                var tabBtn = document.getElementById(savedTab);

                if (tabBtn) {
                    setTimeout(function () {
                        tabBtn.click();
                    }, 200);
                }
            }
        } catch (e) {}
    }

    function initModals() {
        var modals = document.querySelectorAll(".modal");

        // Lưu modal khi mở
        modals.forEach(function (modal) {
            modal.addEventListener("shown.bs.modal", function () {
                var modalId = this.id;

                try {
                    localStorage.setItem("activeModal", modalId);
                } catch (e) {}
            });

            modal.addEventListener("hidden.bs.modal", function () {
                var modalId = this.id;

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

                if (savedModal) {
                    var modalEl = document.getElementById(savedModal);

                    if (modalEl) {
                        setTimeout(function () {
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
            } catch (e) {}
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
