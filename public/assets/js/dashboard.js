// script.js

document.addEventListener('DOMContentLoaded', function() {
    // Obtenemos las referencias a los elementos del DOM
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle'); // Botón de hamburguesa para móviles
    const sidebarCollapseToggle = document.getElementById('sidebarCollapseToggle'); // Botón de colapso para escritorio
    const body = document.body; // Referencia al elemento <body> para añadir/eliminar clases

    // --- Funcionalidad para Sidebar en Móviles ---
    // Si el botón de hamburguesa y la barra lateral existen, añadimos el listener
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            // Alterna la clase 'show' en la barra lateral.
            // Esta clase la hace visible o la oculta en pantallas pequeñas (definido en CSS).
            sidebar.classList.toggle('show');
        });
    }

    // Cierra la barra lateral móvil si se hace clic fuera de ella (solo en móviles)
    document.addEventListener('click', function(event) {
        // Esta lógica solo se aplica si el ancho de la ventana es de un dispositivo móvil (<= 768px)
        if (window.innerWidth <= 768) {
            // Verificamos si el clic ocurrió dentro de la barra lateral o en el botón de alternar
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnSidebarToggle = sidebarToggle && sidebarToggle.contains(event.target);

            // Si la barra lateral está visible y el clic NO fue dentro de ella NI en el botón de alternar
            if (sidebar.classList.contains('show') && !isClickInsideSidebar && !isClickOnSidebarToggle) {
                // Ocultamos la barra lateral
                sidebar.classList.remove('show');
            }
        }
    });

    // --- Funcionalidad para Sidebar en Escritorio (Colapsar/Expandir) ---
    // Si el botón de colapso y la barra lateral existen, añadimos el listener
    if (sidebarCollapseToggle && sidebar) {
        sidebarCollapseToggle.addEventListener('click', function() {
            // Alterna la clase 'sidebar-collapsed' en el body.
            // Esta clase CSS ajusta el ancho de la barra lateral y el margen del contenido.
            body.classList.toggle('sidebar-collapsed');
            
            // Obtenemos el ícono dentro del botón de colapso
            const icon = sidebarCollapseToggle.querySelector('i');

            // Cambia la dirección del ícono para indicar el estado (expandido/colapsado)
            if (body.classList.contains('sidebar-collapsed')) {
                // Si está colapsado, muestra la flecha hacia la derecha
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                // Si está expandido, muestra la flecha hacia la izquierda
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        });
    }

    // --- Manejo del Redimensionamiento de Ventana ---
    // Escuchamos el evento de redimensionamiento de la ventana
    window.addEventListener('resize', function() {
        // Si la ventana se redimensiona a un tamaño de escritorio (> 768px)
        if (window.innerWidth > 768) {
            // Aseguramos que la barra lateral móvil no esté en estado "mostrado"
            sidebar.classList.remove('show');
        } else {
            // Si la ventana se redimensiona a un tamaño de móvil (<= 768px)
            // Aseguramos que el estado colapsado de escritorio no esté aplicado
            body.classList.remove('sidebar-collapsed');
            // Resetear el ícono de colapso si se vuelve a móvil y luego se expande
            const icon = sidebarCollapseToggle.querySelector('i');
            if (icon && !icon.classList.contains('fa-chevron-left')) {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        }
    });
});