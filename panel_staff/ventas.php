<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('staff');

$usuario_id = usuario_actual()['id'];

$productos = $pdo->query("SELECT id, nombre, precio FROM productos ORDER BY nombre ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);
    $nombre_cliente = sanitize($_POST['nombre_cliente']);
    $forma_pago = $_POST['forma_pago'];
    $precio_unitario = floatval($_POST['precio_unitario']);
    $total = $cantidad * $precio_unitario;

    $stmt = $pdo->prepare("
        INSERT INTO ventas_internas (producto_id, nombre_cliente, cantidad, precio_unitario, total, forma_pago, vendido_por)
        VALUES (:prod, :cliente, :cant, :precio, :total, :pago, :usuario)
    ");
    $stmt->execute([
        'prod' => $producto_id,
        'cliente' => $nombre_cliente,
        'cant' => $cantidad,
        'precio' => $precio_unitario,
        'total' => $total,
        'pago' => $forma_pago,
        'usuario' => $usuario_id
    ]);

    header("Location: ventas.php");
    exit;
}

$hoy = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT v.*, p.nombre AS producto
    FROM ventas_internas v
    JOIN productos p ON v.producto_id = p.id
    WHERE DATE(v.vendido_en) = :hoy
    ORDER BY v.vendido_en DESC
");
$stmt->execute(['hoy' => $hoy]);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Registrar Venta Interna</h2>

    <form method="post" class="row g-3 mb-5">
      <div class="col-md-4">
        <label>Producto:</label>
        <select name="producto_id" id="productoSelect" class="form-select" required onchange="actualizarPrecio()">
          <option value="">-- Selecciona --</option>
          <?php foreach ($productos as $p): ?>
            <option value="<?php echo $p['id']; ?>" data-precio="<?php echo $p['precio']; ?>">
              <?php echo htmlspecialchars($p['nombre']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label>Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="1" min="1" class="form-control" required>
      </div>

      <div class="col-md-3">
        <label>Precio unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" class="form-control" required>
      </div>

      <div class="col-md-3">
        <label>Cliente (opcional):</label>
        <input type="text" name="nombre_cliente" class="form-control">
      </div>

      <div class="col-md-4">
        <label>Forma de pago:</label>
        <select name="forma_pago" class="form-select">
          <option value="tarjeta">Tarjeta</option>
          <option value="qr">QR</option>
          <option value="efectivo">Efectivo</option>
        </select>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-success mt-3"><i class="fa fa-cash-register me-2"></i>Registrar Venta</button>
      </div>
    </form>

    <h4 class="mb-3">Ventas del día (<?php echo $hoy; ?>)</h4>

    <?php if (empty($ventas)): ?>
      <div class="alert alert-info">No hay ventas aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Hora</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Cliente</th>
              <th>Total</th>
              <th>Pago</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ventas as $v): ?>
              <tr>
                <td><?php echo substr($v['vendido_en'], 11, 5); ?></td>
                <td><?php echo htmlspecialchars($v['producto']); ?></td>
                <td><?php echo $v['cantidad']; ?></td>
                <td><?php echo $v['nombre_cliente'] ?: '—'; ?></td>
                <td>$<?php echo number_format($v['total'], 2); ?></td>
                <td><?php echo ucfirst($v['forma_pago']); ?></td>
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

<script>
function actualizarPrecio() {
  let select = document.getElementById("productoSelect");
  let precio = select.options[select.selectedIndex].getAttribute("data-precio");
  document.getElementById("precio_unitario").value = precio || '';
}
</script>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
