<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('staff');

$usuario_id = usuario_actual()['id'];

$stmt = $pdo->prepare("
    SELECT s.*
    FROM salidas s
    JOIN salidas_staff ss ON ss.salida_id = s.id
    WHERE ss.staff_id = :uid
    ORDER BY s.fecha DESC, s.hora ASC
");
$stmt->execute(['uid' => $usuario_id]);
$salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Mis Salidas Asignadas</h2>

    <?php if (empty($salidas)): ?>
      <div class="alert alert-warning">No tienes salidas asignadas por ahora.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Barco</th>
              <th>Marina</th>
              <th>Proveedor</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($salidas as $s): ?>
              <tr>
                <td><?php echo $s['fecha']; ?></td>
                <td><?php echo substr($s['hora'], 0, 5); ?></td>
                <td><?php echo $s['nombre_barco']; ?></td>
                <td><?php echo $s['marina']; ?></td>
                <td><?php echo $s['proveedor']; ?></td>
                <td>
                  <a href="checklist.php?fecha=<?php echo $s['fecha']; ?>" class="btn btn-sm btn-outline-primary">Checklist</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">
      <i class="fa fa-arrow-left me-2"></i>Volver al Inicio
    </a>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
