<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

if (isset($_GET['confirmar']) || isset($_GET['cancelar'])) {
    $id = isset($_GET['confirmar']) ? intval($_GET['confirmar']) : intval($_GET['cancelar']);
    $valor = isset($_GET['confirmar']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE reservas SET asistencia_confirmada = :valor WHERE id = :id AND estado_reserva = 'pendiente'");
    $stmt->execute(['valor' => $valor, 'id' => $id]);

    header("Location: reservas.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM reservas ORDER BY fecha_reserva DESC");
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Reservas Registradas</h2>

    <?php if (count($reservas) === 0): ?>
      <div class="alert alert-info">No hay reservas aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>Fecha</th>
              <th>Afiliado</th>
              <th>Descuento</th>
              <th>Total</th>
              <th>Estado</th>
              <th>Asistencia</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $r): ?>
              <tr>
                <td><?php echo htmlspecialchars($r['nombre_cliente']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($r['fecha_reserva'])); ?></td>
                <td><?php echo $r['slug_afiliado']; ?></td>
                <td>
                  <?php 
                    echo $r['tipo_descuento'] === 'porcentaje' 
                      ? $r['descuento_aplicado'] . '%' 
                      : '$' . number_format($r['descuento_aplicado'], 2);
                  ?>
                </td>
                <td>$<?php echo number_format($r['monto_total'], 2); ?></td>
                <td><?php echo ucfirst($r['estado_reserva']); ?></td>
                <td><?php echo $r['asistencia_confirmada'] ? 'Sí' : 'No'; ?></td>
                <td>
                  <?php if ($r['estado_reserva'] === 'pendiente'): ?>
                    <?php if (!$r['asistencia_confirmada']): ?>
                      <a href="?confirmar=<?php echo $r['id']; ?>" class="btn btn-success btn-sm">Confirmar</a>
                    <?php else: ?>
                      <a href="?cancelar=<?php echo $r['id']; ?>" class="btn btn-warning btn-sm">Cancelar</a>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
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
