// Mostrar/Ocultar el menú con el botón de hamburguesa
document.getElementById('menu-toggle').addEventListener('click', function() {
    var navList = document.querySelector('.nav-list');
    navList.classList.toggle('show');
});