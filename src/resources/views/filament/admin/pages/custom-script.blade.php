<script>
    // Preserve sidebar scroll position across navigation
    (function() {
        function saveSidebarPosition() {
            const sidebar = document.querySelector('.fi-sidebar-nav') ||
                           document.querySelector('aside nav') ||
                           document.querySelector('[aria-label="Sidebar"]');

            if (sidebar) {
                sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
            }
        }

        function restoreSidebarPosition() {
            const sidebar = document.querySelector('.fi-sidebar-nav') ||
                           document.querySelector('aside nav') ||
                           document.querySelector('[aria-label="Sidebar"]');

            if (sidebar) {
                const savedPosition = sessionStorage.getItem('sidebarScrollPosition');
                if (savedPosition) {
                    setTimeout(() => {
                        sidebar.scrollTop = parseInt(savedPosition);
                    }, 50);
                }

                // Save on scroll
                sidebar.addEventListener('scroll', saveSidebarPosition);
            }
        }

        // Initial load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', restoreSidebarPosition);
        } else {
            restoreSidebarPosition();
        }

        // After Livewire navigation (SPA mode)
        document.addEventListener('livewire:navigated', restoreSidebarPosition);

        // Before navigation
        document.addEventListener('livewire:navigate', saveSidebarPosition);

        // Fallback for non-Livewire navigation
        window.addEventListener('beforeunload', saveSidebarPosition);
    })();
</script>
