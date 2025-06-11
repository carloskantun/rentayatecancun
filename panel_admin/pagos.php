<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

if (isset($_GET['pagar'])) {
    $af_id = intval($_GET['pagar']);

    $stmt = $pdo->prepare("UPDATE reservas
        SET pago_realizado = 1, fecha_pago = NOW()
        WHERE asistencia_confirmada = 1
          AND slug_afiliado = (SELECT slug_personalizado FROM afiliados WHERE usuario_id = :uid)
          AND pago_realizado = 0");

    $stmt->execute(['uid' => $af_id]);
    header("Location: pagos.php");
    exit;
}

$stmt = $pdo->query("SELECT r.*, a.usuario_id, u.nombre AS afiliado_nombre
                     FROM reservas r
                     JOIN afiliados a ON r.slug_afiliado = a.slug_personalizado
                     JOIN usuarios u ON a.usuario_id = u.id
                     WHERE r.asistencia_confirmada = 1
                     ORDER BY r.fecha_reserva DESC");

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$comisiones = [];
$costo_empresa = get_config('costo_base_empresa') ?? 30;

foreach ($reservas as $res) {
    $af_id = $res['usuario_id'];

    if (!isset($comisiones[$af_id])) {
        $comisiones[$af_id] = [
            'afiliado' => $res['afiliado_nombre'],
            'total_reservas' => 0,
            'comision_total' => 0,
            'pagado' => true
        ];
    }

    $comision = $res['monto_total'] - $costo_empresa;
    $comisiones[$af_id]['total_reservas'] += 1;
    $comisiones[$af_id]['comision_total'] += $comision;

    if ($res['pago_realizado'] == 0) {
        $comisiones[$af_id]['pagado'] = false;
    }
}
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Resumen de Comisiones</h2>

    <?php if (empty($comisiones)): ?>
      <div class="alert alert-info">No hay datos aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Afiliado</th>
              <th>Reservas Confirmadas</th>
              <th>Total Comisión</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($comisiones as $af_id => $info): ?>
              <tr>
                <td><?php echo $info['afiliado']; ?></td>
                <td><?php echo $info['total_reservas']; ?></td>
                <td>$<?php echo number_format($info['comision_total'], 2); ?> USD</td>
                <td><?php echo $info['pagado'] ? 'Pagado' : 'Pendiente'; ?></td>
                <td>
                  <?php if (!$info['pagado']): ?>
                    <a href="?pagar=<?php echo $af_id; ?>" class="btn btn-success btn-sm">
                      <i class="fa fa-check"></i> Marcar como pagado
                    </a>
                  <?php else: ?>
                    <span class="text-muted">✓</span>
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
