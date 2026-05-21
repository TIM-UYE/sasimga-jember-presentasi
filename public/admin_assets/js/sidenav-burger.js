// =========================================================
// ADMIN SIDEBAR HIDE / SHOW + MAIN EXPAND
// =========================================================

(function () {
    function initSidebarToggle() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('adminMain');
        const toggle = document.getElementById('sidebarToggle');

        if (!sidebar || !main || !toggle) {
            console.warn('Sidebar toggle element not found', {
                sidebar,
                main,
                toggle,
            });

            return;
        }

        const STORAGE_KEY = 'adminSidebarOpen';

        let savedState = localStorage.getItem(STORAGE_KEY);

        let isOpen = savedState === null
            ? true
            : savedState === 'true';

        function showSidebar() {
            isOpen = true;

            sidebar.classList.remove('sidebar-is-hidden');
            document.body.classList.remove('sidebar-closed');

            toggle.setAttribute('aria-expanded', 'true');

            localStorage.setItem(STORAGE_KEY, 'true');
        }

        function hideSidebar() {
            isOpen = false;

            sidebar.classList.add('sidebar-is-hidden');
            document.body.classList.add('sidebar-closed');

            toggle.setAttribute('aria-expanded', 'false');

            localStorage.setItem(STORAGE_KEY, 'false');
        }

        function toggleSidebar() {
            if (isOpen) {
                hideSidebar();
            } else {
                showSidebar();
            }
        }

        toggle.addEventListener('click', function (event) {
            event.preventDefault();

            toggleSidebar();
        });

        if (isOpen) {
            showSidebar();
        } else {
            hideSidebar();
        }
    }


    window.toggleSection = function (btn) {
        const submenu = btn.nextElementSibling;
        const chevron = btn.querySelector('.fa-chevron-down');

        if (!submenu) return;

        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');

            if (chevron) {
                chevron.style.transform = 'rotate(180deg)';
            }
        } else {
            submenu.classList.add('hidden');

            if (chevron) {
                chevron.style.transform = 'rotate(0deg)';
            }
        }
    };


    function openActiveSubmenus() {
        const buttons = document.querySelectorAll(
            '#sidebar button[onclick^="toggleSection"]'
        );

        buttons.forEach((button) => {
            const submenu = button.nextElementSibling;

            if (!submenu) return;

            const hasActive = submenu.querySelector('.bg-white\\/20');

            if (hasActive) {
                submenu.classList.remove('hidden');

                const chevron = button.querySelector('.fa-chevron-down');

                if (chevron) {
                    chevron.style.transform = 'rotate(180deg)';
                }
            }
        });
    }


    function lockSidebarScroll() {
        const sidebar = document.getElementById('sidebar');

        if (!sidebar) return;

        sidebar.addEventListener('wheel', function (event) {
            const canScroll = sidebar.scrollHeight > sidebar.clientHeight;

            if (!canScroll) return;

            event.preventDefault();
            event.stopPropagation();

            sidebar.scrollTop += event.deltaY;
        }, {
            passive: false,
        });
    }


    function init() {
        initSidebarToggle();
        openActiveSubmenus();
        lockSidebarScroll();
    }


    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();