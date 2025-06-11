<?php
session_start(); // Asegúrate de que la sesión esté iniciada

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    // Si no hay usuario, no mostramos el navbar
    return; 
}
$rol = $usuario['rol'] ?? '';
?>

<nav class="navbar">
    <button class="menu-toggle" id="menu-toggle">&#9776;</button> <!-- Botón de hamburguesa -->
    <ul class="nav-list">
        <?php if ($rol === 'admin'): ?>
            <li><a href="../panel_admin/dashboard.php">Inicio Admin</a></li>
            <li><a href="../panel_admin/usuarios.php">Usuarios</a></li>
            <li><a href="../panel_admin/reservas.php">Reservas</a></li>
            <li><a href="../panel_admin/pagos.php">Pagos</a></li>
            <li><a href="../panel_admin/salidas.php">Salidas</a></li>
            <li><a href="../panel_admin/productos.php">Productos</a></li>
            <li><a href="../panel_admin/configuracion.php">Configuración</a></li>
        <?php elseif ($rol === 'afiliado'): ?>
            <li><a href="../panel_afiliado/dashboard.php">Inicio Afiliado</a></li>
            <li><a href="../panel_afiliado/mis_reservas.php">Mis Reservas</a></li>
            <li><a href="../panel_afiliado/generar_link.php">Generar Link</a></li>
        <?php elseif ($rol === 'staff'): ?>
            <li><a href="../panel_staff/dashboard.php">Inicio Staff</a></li>
            <li><a href="../panel_staff/checklist.php">Checklist</a></li>
            <li><a href="../panel_staff/ventas.php">Ventas</a></li>
        <?php endif; ?>
        <li><a href="../auth/logout.php">Cerrar sesión</a></li>
    </ul>
</nav>
<hr>


<!-- Script necesario para el funcionamiento de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
