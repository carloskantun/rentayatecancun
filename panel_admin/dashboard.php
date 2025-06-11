<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

$usuario = usuario_actual();

// Métricas
$totalReservasHoy = $pdo->query("SELECT COUNT(*) FROM reservas WHERE DATE(fecha_reserva) = CURDATE()")->fetchColumn();

$costo_empresa = get_config('costo_base_empresa') ?? 30;
$totalComisiones = $pdo->query("SELECT SUM(monto_total - $costo_empresa) FROM reservas")->fetchColumn();
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <div class="row gx-60">
      <div class="col-lg-9">
        <div class="product-about">
          <h2 class="product-title">Bienvenido, <?php echo $usuario['nombre']; ?> <small class="text-muted">(Administrador)</small></h2>
          <p class="text mt-3">Accede a herramientas de gestión para monitorear afiliados, reservas y pagos.</p>

          <!-- Resumen rápido -->
          <div class="row mt-4">
            <div class="col-md-6">
              <div class="alert alert-primary">
                📅 <strong>Reservas para hoy:</strong> <?php echo $totalReservasHoy; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="alert alert-warning">
                💰 <strong>Comisiones generadas:</strong> $<?php echo number_format($totalComisiones, 2); ?> USD
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="product-meta">
          <div class="d-grid gap-2">
            <a href="usuarios.php" class="btn btn-outline-primary w-100"><i class="fa fa-users me-2"></i>Usuarios</a>
            <a href="aprobar_afiliados.php" class="btn btn-outline-info w-100"><i class="fa fa-user-check me-2"></i>Afiliados Pendientes</a>
            <a href="admin_generar_qr_afiliados.php" class="btn btn-outline-success w-100"><i class="fa fa-qrcode me-2"></i>Generar QR Faltantes</a>
            <a href="asignar_reservas.php" class="btn btn-outline-info w-100"><i class="fa fa-tasks me-2"></i>Asignar Reservas</a>
            <a href="salidas.php" class="btn btn-outline-secondary w-100"><i class="fa fa-ship me-2"></i>Salidas</a>
            <a href="reservas.php" class="btn btn-outline-success w-100"><i class="fa fa-calendar-check me-2"></i>Reservas</a>
            <a href="pagos.php" class="btn btn-outline-warning w-100"><i class="fa fa-dollar-sign me-2"></i>Pagos</a>
            <a href="descuentos.php" class="btn btn-outline-dark w-100"><i class="fa fa-tags me-2"></i>Descuentos</a>
            <a href="penalizaciones.php" class="btn btn-outline-danger w-100"><i class="fa fa-ban me-2"></i>Penalizaciones</a>
            <a href="productos.php" class="btn btn-outline-secondary w-100"><i class="fa fa-box me-2"></i>Productos / Ventas</a>
            <a href="configuracion.php" class="btn btn-outline-primary w-100"><i class="fa fa-cogs me-2"></i>Configuración</a>
            <a href="/auth/logout.php" class="btn btn-danger mt-3 w-100"><i class="fa fa-sign-out-alt me-2"></i>Salir</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
