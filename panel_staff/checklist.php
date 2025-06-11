<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('staff');

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT s.*, 
           (SELECT COUNT(*) FROM reservas r WHERE r.salida_id = s.id) AS total_reservas
    FROM salidas s
    WHERE s.fecha = :fecha
    ORDER BY s.hora ASC
");
$stmt->execute(['fecha' => $fecha]);
$salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['asistencias'] as $reserva_id => $estado) {
        $asistio = $estado === '1' ? 1 : 0;
        $observacion = sanitize($_POST['observaciones'][$reserva_id] ?? '');

        $stmt = $pdo->prepare("UPDATE reservas SET asistencia_confirmada = :a, observacion_staff = :o WHERE id = :id");
        $stmt->execute([
            'a' => $asistio,
            'o' => $observacion,
            'id' => $reserva_id
        ]);
    }

    header("Location: checklist.php?fecha=" . $fecha);
    exit;
}

$reservas_por_salida = [];
foreach ($salidas as $s) {
    $stmt = $pdo->prepare("SELECT * FROM reservas WHERE salida_id = :salida_id ORDER BY nombre_cliente ASC");
    $stmt->execute(['salida_id' => $s['id']]);
    $reservas_por_salida[$s['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Checklist de Asistencia</h2>

    <form method="get" class="mb-4">
      <label>Seleccionar fecha:</label>
      <div class="input-group">
        <input type="date" name="fecha" class="form-control" value="<?php echo $fecha; ?>">
        <button type="submit" class="btn btn-primary">Ver</button>
      </div>
    </form>

    <?php if (empty($salidas)): ?>
      <div class="alert alert-info">No hay salidas programadas para esta fecha.</div>
    <?php else: ?>
      <?php foreach ($salidas as $s): ?>
        <h4 class="mt-4 mb-2">
          <?php echo $s['hora']; ?> - <?php echo $s['nombre_barco']; ?> (<?php echo $s['marina']; ?>)
        </h4>

        <form method="post" class="mb-4">
          <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">

          <input type="text" id="buscar" class="form-control mb-3" placeholder="Buscar cliente..." onkeyup="filtrar()">

          <div class="row g-3" id="lista-reservas">
            <?php foreach ($reservas_por_salida[$s['id']] as $r): ?>
              <div class="col-md-6 reserva <?php echo $r['asistencia_confirmada'] ? 'bg-light border-success' : 'border'; ?>" data-nombre="<?php echo strtolower($r['nombre_cliente']); ?>">
                <div class="p-3 border rounded">
                  <label class="form-check-label">
                    <input type="checkbox" class="form-check-input me-2" name="asistencias[<?php echo $r['id']; ?>]" value="1" <?php if ($r['asistencia_confirmada']) echo 'checked'; ?>>
                    <?php echo htmlspecialchars($r['nombre_cliente']); ?>
                  </label>
                  <div><small>Afiliado: <?php echo $r['slug_afiliado']; ?></small></div>
                  <textarea name="observaciones[<?php echo $r['id']; ?>]" class="form-control mt-2" rows="2" placeholder="Observaciones..."><?php echo htmlspecialchars($r['observacion_staff']); ?></textarea>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <button type="submit" class="btn btn-success mt-3"><i class="fa fa-save me-2"></i>Guardar cambios</button>
        </form>
        <hr>
      <?php endforeach; ?>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">
      <i class="fa fa-arrow-right me-2"></i>Ir a Inicio
    </a>
  </div>
</section>

<script>
function filtrar() {
  let input = document.getElementById("buscar").value.toLowerCase();
  let items = document.getElementsByClassName("reserva");
  for (let i = 0; i < items.length; i++) {
    let nombre = items[i].getAttribute("data-nombre");
    items[i].style.display = nombre.includes(input) ? "block" : "none";
  }
}
</script>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
