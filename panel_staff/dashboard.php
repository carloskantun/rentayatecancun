<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('staff');

$usuario = usuario_actual();
$usuario_id = $usuario['id'];
$hoy = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT s.*
    FROM salidas s
    JOIN salidas_staff ss ON ss.salida_id = s.id
    WHERE ss.staff_id = :uid AND s.fecha = :fecha
    ORDER BY s.hora ASC
");
$stmt->execute([
    'uid' => $usuario_id,
    'fecha' => $hoy
]);
$salidas_hoy = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <div class="row gx-60">
      <div class="col-lg-9">
        <div class="product-about">
          <h2 class="product-title">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> <small class="text-muted">(Staff)</small></h2>
          <p class="text mt-3">
            Aquí puedes acceder rápidamente a tus herramientas de asistencia, ventas y operación diaria.
          </p>

          <h4 class="mt-4 mb-3">Salidas asignadas para hoy (<?php echo $hoy; ?>):</h4>

          <?php if (empty($salidas_hoy)): ?>
            <div class="alert alert-info">No tienes salidas asignadas hoy.</div>
          <?php else: ?>
            <ul class="list-group">
              <?php foreach ($salidas_hoy as $s): ?>
                <li class="list-group-item">
                  <?php echo $s['hora']; ?> - <?php echo $s['nombre_barco']; ?> (<?php echo $s['marina']; ?>)
                  — <a href="checklist.php?fecha=<?php echo $hoy; ?>" class="btn btn-sm btn-outline-primary ms-2">Ir al Checklist</a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="product-meta">
          <div class="d-grid gap-2">
            <a href="checklist.php" class="btn btn-outline-primary">✔️ Checklist</a>
            <a href="ventas.php" class="btn btn-outline-success">🛒 Ventas Internas</a>
            <a href="salidas_asignadas.php" class="btn btn-outline-info">📋 Mis Salidas</a>
            <a href="../auth/logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
