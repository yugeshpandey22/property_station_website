        </main>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Responsive Mobile Sidebar Toggle
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("adminSidebar");
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                sidebar.classList.toggle("show");
            });

            document.addEventListener("click", function(e) {
                if (window.innerWidth <= 991 && sidebar.classList.contains("show")) {
                    if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                        sidebar.classList.remove("show");
                    }
                }
            });
        }
    });
    </script>
</body>
</html>
