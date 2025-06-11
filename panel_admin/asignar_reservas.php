<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

$salida_id = isset($_GET['salida']) ? intval($_GET['salida']) : 0;

$stmt = $pdo->prepare("SELECT * FROM salidas WHERE id = :id");
$stmt->execute(['id' => $salida_id]);
$salida = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$salida) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Salida no encontrada.</div></div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservas'])) {
    $reservas = $_POST['reservas'];
    $in = str_repeat('?,', count($reservas) - 1) . '?';
    $sql = "UPDATE reservas SET salida_id = ? WHERE id IN ($in)";
    $params = array_merge([$salida_id], $reservas);

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header("Location: salidas.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM reservas WHERE salida_id IS NULL ORDER BY fecha_reserva DESC");
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Asignar Reservas a Salida</h2>

    <div class="mb-3">
      <p class="mb-1"><strong>Fecha:</strong> <?php echo $salida['fecha']; ?> &nbsp; | &nbsp;
      <strong>Hora:</strong> <?php echo substr($salida['hora'], 0, 5); ?> &nbsp; | &nbsp;
      <strong>Marina:</strong> <?php echo $salida['marina']; ?> &nbsp; | &nbsp;
      <strong>Proveedor:</strong> <?php echo $salida['proveedor']; ?> &nbsp; | &nbsp;
      <strong>Barco:</strong> <?php echo $salida['nombre_barco']; ?></p>
    </div>

    <?php if (empty($reservas)): ?>
      <div class="alert alert-info">No hay reservas sin asignar.</div>
    <?php else: ?>
      <form method="post">
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th></th>
                <th>Cliente</th>
                <th>Fecha de Reserva</th>
                <th>Afiliado</th>
                <th>Asistencia</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservas as $r): ?>
                <tr>
                  <td><input type="checkbox" name="reservas[]" value="<?php echo $r['id']; ?>"></td>
                  <td><?php echo htmlspecialchars($r['nombre_cliente']); ?></td>
                  <td><?php echo $r['fecha_reserva']; ?></td>
                  <td><?php echo $r['slug_afiliado']; ?></td>
                  <td><?php echo $r['asistencia_confirmada'] ? 'Sí' : 'No'; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <button type="submit" class="btn btn-primary mt-3">
          <i class="fa fa-check-circle me-2"></i>Asignar reservas seleccionadas
        </button>
      </form>
    <?php endif; ?>

    <div class="mt-4">
      <a href="salidas.php" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left me-2"></i> Volver a Salidas
      </a>
    </div>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
