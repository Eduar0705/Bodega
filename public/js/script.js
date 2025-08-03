document.addEventListener('DOMContentLoaded', function() {
    // Toggle del menú lateral
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    });
    
    // Aquí puedes agregar más funcionalidades según necesites
});