<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = sanitize($_POST['fecha']);
    $hora = sanitize($_POST['hora']);
    $marina = sanitize($_POST['marina']);
    $proveedor = sanitize($_POST['proveedor']);
    $nombre_barco = sanitize($_POST['nombre_barco']);
    $pax_minimo = intval($_POST['pax_minimo']);

    if ($fecha && $hora && $marina && $proveedor && $nombre_barco) {
        $stmt = $pdo->prepare("INSERT INTO salidas 
            (fecha, hora, marina, proveedor, nombre_barco, pax_minimo, estado) 
            VALUES (:f, :h, :m, :p, :b, :px, 'programada')");
        $stmt->execute([
            'f' => $fecha,
            'h' => $hora,
            'm' => $marina,
            'p' => $proveedor,
            'b' => $nombre_barco,
            'px' => $pax_minimo
        ]);
    }
    header("Location: salidas.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id = intval($_GET['id']);
    $nuevo_estado = $_GET['estado'];

    if (in_array($nuevo_estado, ['programada', 'cancelada', 'finalizada'])) {
        $stmt = $pdo->prepare("UPDATE salidas SET estado = :e WHERE id = :id");
        $stmt->execute(['e' => $nuevo_estado, 'id' => $id]);
    }

    header("Location: salidas.php");
    exit;
}

$stmt = $pdo->query("
    SELECT s.*, 
           (SELECT COUNT(*) FROM reservas r WHERE r.salida_id = s.id) AS total_reservas
    FROM salidas s
    ORDER BY fecha DESC, hora DESC
");
$salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Crear Nueva Salida</h2>

    <form method="post" class="row g-3 mb-5">
      <div class="col-md-3">
        <label>Fecha:</label>
        <input type="date" name="fecha" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label>Hora:</label>
        <input type="time" name="hora" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label>Marina:</label>
        <input type="text" name="marina" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label>Proveedor:</label>
        <input type="text" name="proveedor" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label>Nombre del Barco:</label>
        <input type="text" name="nombre_barco" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label>PAX mínimo:</label>
        <input type="number" name="pax_minimo" class="form-control" value="10" min="1" required>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-plus me-2"></i>Registrar Salida
        </button>
      </div>
    </form>

    <h3 class="mb-4">Salidas Programadas</h3>

    <?php if (empty($salidas)): ?>
      <div class="alert alert-info">No hay salidas aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Marina</th>
              <th>Proveedor</th>
              <th>Barco</th>
              <th>Reservas</th>
              <th>PAX</th>
              <th>Estado</th>
              <th>Cambiar Estado</th>
              <th>Asignar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($salidas as $s): ?>
              <tr>
                <td><?php echo $s['fecha']; ?></td>
                <td><?php echo substr($s['hora'], 0, 5); ?></td>
                <td><?php echo htmlspecialchars($s['marina']); ?></td>
                <td><?php echo htmlspecialchars($s['proveedor']); ?></td>
                <td><?php echo htmlspecialchars($s['nombre_barco']); ?></td>
                <td><?php echo $s['total_reservas']; ?></td>
                <td><?php echo $s['pax_minimo']; ?></td>
                <td><?php echo ucfirst($s['estado']); ?></td>
                <td>
                  <a href="?id=<?php echo $s['id']; ?>&estado=programada" class="btn btn-sm btn-outline-info">Programar</a><br>
                  <a href="?id=<?php echo $s['id']; ?>&estado=cancelada" class="btn btn-sm btn-outline-warning mt-1">Cancelar</a><br>
                  <a href="?id=<?php echo $s['id']; ?>&estado=finalizada" class="btn btn-sm btn-outline-success mt-1">Finalizar</a>
                </td>
                <td>
                  <a href="asignar_reservas.php?salida=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary">
                    Asignar reservas
                  </a>
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
