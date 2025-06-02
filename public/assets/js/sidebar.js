document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const submenuToggles = document.querySelectorAll('[data-toggle="submenu"]');

    // Toggle sidebar en móvil
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });
    }

    // Manejar submenús
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                submenu.classList.toggle('active');
                const icon = this.querySelector('.fa-chevron-down');
                if (icon) {
                    icon.classList.toggle('fa-rotate-180');
                }
            }
        });
    });

    // Cerrar sidebar al hacer clic fuera de él en móvil
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(e.target) && 
            e.target !== toggleBtn &&
            !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });

    // Ajustar el margen del contenido principal
    function updateMainContentMargin() {
        const mainContent = document.querySelector('.main-content');
        if (mainContent && window.innerWidth > 768) {
            mainContent.style.marginLeft = sidebar.offsetWidth + 'px';
        } else if (mainContent) {
            mainContent.style.marginLeft = '0';
        }
    }

    // Actualizar al cargar y al redimensionar
    window.addEventListener('resize', updateMainContentMargin);
    updateMainContentMargin();
});
