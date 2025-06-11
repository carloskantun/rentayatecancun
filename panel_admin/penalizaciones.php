<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

$admin_id = usuario_actual()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = intval($_POST['usuario_id']);
    $reserva_id = !empty($_POST['reserva_id']) ? intval($_POST['reserva_id']) : null;
    $monto = floatval($_POST['monto']);
    $motivo = sanitize($_POST['motivo']);

    if ($usuario_id && $monto > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO penalizaciones (reserva_id, usuario_id, motivo, monto, estado, aplicado_por)
            VALUES (:reserva, :usuario, :motivo, :monto, 'pendiente', :admin)
        ");
        $stmt->execute([
            'reserva' => $reserva_id,
            'usuario' => $usuario_id,
            'motivo' => $motivo,
            'monto' => $monto,
            'admin' => $admin_id
        ]);
    }

    header("Location: penalizaciones.php");
    exit;
}

if (isset($_GET['aplicar'])) {
    $id = intval($_GET['aplicar']);
    $stmt = $pdo->prepare("UPDATE penalizaciones SET estado = 'aplicada' WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: penalizaciones.php");
    exit;
}

$stmt = $pdo->query("
    SELECT p.*, u.nombre AS afiliado
    FROM penalizaciones p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY fecha_aplicada DESC
");
$penalizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

$afiliados = $pdo->query("SELECT id, nombre FROM usuarios WHERE rol = 'afiliado' ORDER BY nombre ASC")->fetchAll();
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Penalizaciones a Afiliados</h2>

    <form method="post" class="row g-3 mb-5">
      <div class="col-md-4">
        <label>Afiliado:</label>
        <select name="usuario_id" class="form-select" required>
          <?php foreach ($afiliados as $a): ?>
            <option value="<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['nombre']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label>ID de Reserva (opcional):</label>
        <input type="number" name="reserva_id" class="form-control" placeholder="Ej. 102">
      </div>

      <div class="col-md-2">
        <label>Monto (USD):</label>
        <input type="number" step="0.01" name="monto" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label>Motivo:</label>
        <textarea name="motivo" class="form-control" rows="1" required></textarea>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-danger">
          <i class="fa fa-ban me-2"></i>Aplicar Penalización
        </button>
      </div>
    </form>

    <h3 class="mb-3">Historial de Penalizaciones</h3>

    <?php if (empty($penalizaciones)): ?>
      <div class="alert alert-info">No hay penalizaciones registradas aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Afiliado</th>
              <th>Reserva ID</th>
              <th>Monto</th>
              <th>Motivo</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($penalizaciones as $p): ?>
              <tr>
                <td><?php echo htmlspecialchars($p['afiliado']); ?></td>
                <td><?php echo $p['reserva_id'] ?? '—'; ?></td>
                <td>$<?php echo number_format($p['monto'], 2); ?></td>
                <td><?php echo htmlspecialchars($p['motivo']); ?></td>
                <td><?php echo ucfirst($p['estado']); ?></td>
                <td><?php echo $p['fecha_aplicada']; ?></td>
                <td>
                  <?php if ($p['estado'] === 'pendiente'): ?>
                    <a href="?aplicar=<?php echo $p['id']; ?>" class="btn btn-success btn-sm">
                      <i class="fa fa-check"></i> Aplicar
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
