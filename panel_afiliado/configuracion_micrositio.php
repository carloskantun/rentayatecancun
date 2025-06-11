<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('afiliado');

$usuario = usuario_actual();

if ($usuario['estado'] !== 'activo') {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Tu cuenta aún no ha sido aprobada.</div></div>";
    exit;
}

$stmt = $pdo->prepare("SELECT slug_personalizado FROM afiliados WHERE usuario_id = :id LIMIT 1");
$stmt->execute(['id' => $usuario['id']]);
$afiliado = $stmt->fetch(PDO::FETCH_ASSOC);
$slug = $afiliado ? $afiliado['slug_personalizado'] : '';

if (!$slug) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>No se encontró tu identificador de afiliado.</div></div>";
    exit;
}

$stmt = $pdo->prepare("SELECT nombre_cliente, fecha_reserva, estado_reserva, asistencia_confirmada 
                       FROM reservas 
                       WHERE slug_afiliado = :slug 
                       ORDER BY fecha_reserva DESC");
$stmt->execute(['slug' => $slug]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Mis Reservas</h2>

    <?php if (count($reservas) === 0): ?>
      <div class="alert alert-info">No tienes reservas registradas aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Asistencia</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $res): ?>
              <tr>
                <td><?php echo htmlspecialchars($res['nombre_cliente']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($res['fecha_reserva'])); ?></td>
                <td><?php echo ucfirst($res['estado_reserva']); ?></td>
                <td><?php echo $res['asistencia_confirmada'] ? 'Sí' : 'No'; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <div class="mt-4">
      <a href="dashboard.php" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left me-2"></i>Volver al Panel
      </a>
    </div>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
