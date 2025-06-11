<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

$stmt = $pdo->query("
    SELECT r.*, u.nombre AS afiliado
    FROM reservas r
    JOIN afiliados a ON r.slug_afiliado = a.slug_personalizado
    JOIN usuarios u ON a.usuario_id = u.id
    WHERE r.tipo_descuento IS NOT NULL
    ORDER BY r.fecha_reserva DESC
");
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Reservas con Descuento Aplicado</h2>

    <?php if (empty($reservas)): ?>
      <div class="alert alert-info">No hay registros con descuento aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Afiliado</th>
              <th>Tipo Descuento</th>
              <th>Valor</th>
              <th>Total Pagado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $r): ?>
              <tr>
                <td><?php echo $r['fecha_reserva']; ?></td>
                <td><?php echo htmlspecialchars($r['nombre_cliente']); ?></td>
                <td><?php echo htmlspecialchars($r['afiliado']); ?></td>
                <td><?php echo ucfirst($r['tipo_descuento']); ?></td>
                <td>
                  <?php
                    echo $r['tipo_descuento'] === 'porcentaje'
                      ? $r['valor_descuento'] . '%'
                      : '$' . number_format($r['valor_descuento'], 2);
                  ?>
                </td>
                <td>$<?php echo number_format($r['monto_total'], 2); ?></td>
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
