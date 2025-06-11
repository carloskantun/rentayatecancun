<?php
if (!isset($rol) && isset($_SESSION['usuario']['rol'])) {
    $rol = $_SESSION['usuario']['rol'];
}
?>

<?php if (in_array($rol, ['admin', 'afiliado', 'staff'])): ?>
<div class="fixed-bottom bg-white shadow p-2 d-flex justify-content-around border-top d-md-none">
  <?php if ($rol === 'admin'): ?>
    <a href="/panel_admin/dashboard.php" class="btn btn-sm btn-outline-primary text-center">
      <i class="fa fa-home"></i><br><small>Inicio</small>
    </a>
    <a href="/panel_admin/reservas.php" class="btn btn-sm btn-outline-success text-center">
      <i class="fa fa-calendar-check"></i><br><small>Reservas</small>
    </a>
    <a href="/panel_admin/pagos.php" class="btn btn-sm btn-outline-warning text-center">
      <i class="fa fa-dollar-sign"></i><br><small>Pagos</small>
    </a>
    <a href="/auth/logout.php" class="btn btn-sm btn-outline-danger text-center">
      <i class="fa fa-sign-out-alt"></i><br><small>Salir</small>
    </a>

  <?php elseif ($rol === 'afiliado'): ?>
    <a href="/panel_afiliado/dashboard.php" class="btn btn-sm btn-outline-primary text-center">
      <i class="fa fa-home"></i><br><small>Inicio</small>
    </a>
    <a href="/panel_afiliado/mis_reservas.php" class="btn btn-sm btn-outline-secondary text-center">
      <i class="fa fa-calendar"></i><br><small>Reservas</small>
    </a>
    <a href="/panel_afiliado/generar_link.php" class="btn btn-sm btn-outline-info text-center">
      <i class="fa fa-link"></i><br><small>Link</small>
    </a>
    <a href="/auth/logout.php" class="btn btn-sm btn-outline-danger text-center">
      <i class="fa fa-sign-out-alt"></i><br><small>Salir</small>
    </a>

  <?php elseif ($rol === 'staff'): ?>
    <a href="/panel_staff/dashboard.php" class="btn btn-sm btn-outline-primary text-center">
      <i class="fa fa-home"></i><br><small>Inicio</small>
    </a>
    <a href="/panel_staff/checklist.php" class="btn btn-sm btn-outline-secondary text-center">
      <i class="fa fa-check-square"></i><br><small>Checklist</small>
    </a>
    <a href="/panel_staff/ventas.php" class="btn btn-sm btn-outline-success text-center">
      <i class="fa fa-shopping-cart"></i><br><small>Ventas</small>
    </a>
    <a href="/auth/logout.php" class="btn btn-sm btn-outline-danger text-center">
      <i class="fa fa-sign-out-alt"></i><br><small>Salir</small>
    </a>
  <?php endif; ?>
</div>
<?php endif; ?>
