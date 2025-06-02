    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="../public/assets/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personalizado -->
    <script>
        // Función para manejar el menú deslizante
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');
            const navLinks = document.querySelectorAll('.nav-link[data-toggle="submenu"]');
            const submenus = document.querySelectorAll('.submenu');

            // Manejar el toggle del menú en móvil
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('active');
                // Animación de entrada
                if (sidebar.classList.contains('active')) {
                    sidebar.style.animation = 'slideIn 0.3s ease forwards';
                } else {
                    sidebar.style.animation = 'slideOut 0.3s ease forwards';
                }
            });

            // Manejar submenus
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    if (submenu && submenu.classList.contains('submenu')) {
                        this.classList.toggle('active');
                    } else {
                        // Redirigir si no es un submenu
                        window.location.href = this.getAttribute('href');
                    }
                });
            });

            // Cerrar menú al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            });

            // Manejar el redimensionamiento de la ventana
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                }
            });

            // Abrir submenu activo al cargar
            const currentPath = window.location.pathname;
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (currentPath === href) {
                    const parent = link.closest('.nav-link[data-toggle="submenu"]');
                    if (parent) {
                        parent.classList.add('active');
                    }
                }
            });
        });
    </script>
</body>
</html>
