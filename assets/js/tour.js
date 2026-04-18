/**
 * Ajinsafro Tour Bridge - Single Tour V1 interactions
 * Scope: tabs, day chips, floating CTA scroll.
 */
(function () {
    "use strict";

    function initTabs() {
        var tabButtons = Array.prototype.slice.call(
            document.querySelectorAll(".ajtb-v1-tab-btn"),
        );
        var panels = Array.prototype.slice.call(
            document.querySelectorAll(".ajtb-v1-tab-panel"),
        );

        if (!tabButtons.length || !panels.length) {
            return;
        }

        function activateTab(tabName) {
            tabButtons.forEach(function (button) {
                var isActive = button.getAttribute("data-ajtb-tab") === tabName;
                button.classList.toggle("is-active", isActive);
                button.setAttribute("aria-selected", isActive ? "true" : "false");
            });

            panels.forEach(function (panel) {
                var shouldShow = panel.id === "ajtb-v1-panel-" + tabName;
                panel.classList.toggle("is-active", shouldShow);
                if (shouldShow) {
                    panel.removeAttribute("hidden");
                } else {
                    panel.setAttribute("hidden", "");
                }
            });
        }

        tabButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                var tabName = button.getAttribute("data-ajtb-tab");
                if (!tabName) {
                    return;
                }
                activateTab(tabName);
            });
        });
    }

    function initDayChips() {
        var chips = Array.prototype.slice.call(
            document.querySelectorAll(".ajtb-v1-day-chip"),
        );

        if (!chips.length) {
            return;
        }

        function activateChipByTarget(targetId) {
            chips.forEach(function (chip) {
                var isActive = chip.getAttribute("data-ajtb-day-target") === targetId;
                chip.classList.toggle("is-active", isActive);
            });
        }

        chips.forEach(function (chip) {
            chip.addEventListener("click", function () {
                var targetId = chip.getAttribute("data-ajtb-day-target");
                if (!targetId) {
                    return;
                }

                var target = document.getElementById(targetId);
                if (!target) {
                    return;
                }

                activateChipByTarget(targetId);
                var root = document.getElementById("ajtb-v1-page");
                var offset = 172;
                if (root) {
                    var parsed = parseInt(
                        getComputedStyle(root).getPropertyValue("--ajtb-v1-day-scroll-offset"),
                        10,
                    );
                    if (!isNaN(parsed) && parsed > 0) {
                        offset = parsed;
                    }
                }
                var y =
                    window.pageYOffset +
                    target.getBoundingClientRect().top -
                    offset;
                window.scrollTo({ top: Math.max(0, y), behavior: "smooth" });
            });
        });

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    activateChipByTarget(entry.target.id);
                });
            },
            {
                root: null,
                rootMargin: "-35% 0px -45% 0px",
                threshold: 0.05,
            },
        );

        chips.forEach(function (chip) {
            var targetId = chip.getAttribute("data-ajtb-day-target");
            var target = targetId ? document.getElementById(targetId) : null;
            if (target) {
                observer.observe(target);
            }
        });
    }

    function initFloatingButton() {
        var button = document.querySelector("[data-ajtb-action='scroll-price']");
        if (!button) {
            return;
        }

        button.addEventListener("click", function () {
            var target = document.getElementById("ajtb-v1-price-card");
            if (!target) {
                return;
            }

            target.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    }

    function initStickySearchBox() {
        var searchBox = document.getElementById("ajtb-v1-search-box");
        var pageRoot = document.getElementById("ajtb-v1-page");
        if (!searchBox || !pageRoot) {
            return;
        }

        function computeOffset() {
            var offset = 88;
            var dayOffset = 172;
            var navbar = document.getElementById("aj-navbar");
            if (!navbar) {
                navbar = document.querySelector(".aj-navbar");
            }
            if (navbar) {
                var h = Math.ceil(navbar.getBoundingClientRect().height || 0);
                if (h > 0) {
                    offset = h + 10;
                    dayOffset = offset + 84;
                }
            } else {
                var fallbackHeader = document.querySelector(".ajtb-v1-fallback-header");
                if (fallbackHeader) {
                    var hh = Math.ceil(fallbackHeader.getBoundingClientRect().height || 0);
                    if (hh > 0) {
                        offset = hh + 10;
                        dayOffset = offset + 84;
                    }
                }
            }

            if (window.innerWidth < 768) {
                dayOffset = offset + 58;
            } else if (window.innerWidth < 992) {
                dayOffset = offset + 68;
            }

            pageRoot.style.setProperty("--ajtb-v1-sticky-top", offset + "px");
            pageRoot.style.setProperty("--ajtb-v1-day-scroll-offset", dayOffset + "px");
        }

        function updateStuckState() {
            var rect = searchBox.getBoundingClientRect();
            var stickyTop = parseInt(
                getComputedStyle(pageRoot).getPropertyValue("--ajtb-v1-sticky-top"),
                10,
            );
            if (isNaN(stickyTop)) {
                stickyTop = 88;
            }
            searchBox.classList.toggle("is-stuck", rect.top <= stickyTop + 1);
        }

        computeOffset();
        updateStuckState();

        window.addEventListener("resize", computeOffset, { passive: true });
        window.addEventListener("resize", updateStuckState, { passive: true });
        window.addEventListener("scroll", updateStuckState, { passive: true });
    }

    document.addEventListener("DOMContentLoaded", function () {
        initTabs();
        initDayChips();
        initFloatingButton();
        initStickySearchBox();
    });
})();
