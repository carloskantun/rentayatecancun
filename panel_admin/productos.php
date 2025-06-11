<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

$ruta_imagenes = '../assets/imgs/productos/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitize($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $categoria = sanitize($_POST['categoria']);
    $stock = is_numeric($_POST['stock']) ? intval($_POST['stock']) : null;
    $imagen_url = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . '.' . strtolower($ext);
        $permitidos = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $permitidos)) {
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagenes . $nombre_archivo);
            $imagen_url = $nombre_archivo;
        }
    }

    if ($nombre && $precio > 0) {
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio, categoria, imagen_url, stock, estado)
            VALUES (:n, :p, :c, :i, :s, 'activo')");
        $stmt->execute([
            'n' => $nombre,
            'p' => $precio,
            'c' => $categoria ?: null,
            'i' => $imagen_url,
            's' => $stock
        ]);
    }

    header("Location: productos.php");
    exit;
}

if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $pdo->prepare("UPDATE productos 
        SET estado = IF(estado = 'activo', 'inactivo', 'activo') 
        WHERE id = :id")->execute(['id' => $id]);

    header("Location: productos.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Productos Internos (Venta Abordo)</h2>

    <form method="post" enctype="multipart/form-data" class="row g-3 mb-5">
      <div class="col-md-3">
        <label>Nombre:</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>

      <div class="col-md-2">
        <label>Precio (USD):</label>
        <input type="number" step="0.01" name="precio" class="form-control" required>
      </div>

      <div class="col-md-3">
        <label>Categoría:</label>
        <input type="text" name="categoria" class="form-control">
      </div>

      <div class="col-md-2">
        <label>Stock (opcional):</label>
        <input type="number" name="stock" class="form-control">
      </div>

      <div class="col-md-2">
        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/png, image/jpeg" class="form-control">
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar Producto</button>
      </div>
    </form>

    <?php if (empty($productos)): ?>
      <div class="alert alert-info">No hay productos aún.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($productos as $p): ?>
              <tr>
                <td>
                  <?php if ($p['imagen_url']): ?>
                    <img src="../assets/imgs/productos/<?php echo $p['imagen_url']; ?>" width="60">
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                <td>$<?php echo number_format($p['precio'], 2); ?></td>
                <td><?php echo $p['stock'] !== null ? $p['stock'] : '—'; ?></td>
                <td><?php echo ucfirst($p['estado']); ?></td>
                <td>
                  <a href="?toggle=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-<?php echo $p['estado'] === 'activo' ? 'danger' : 'success'; ?>">
                    <?php echo $p['estado'] === 'activo' ? 'Desactivar' : 'Activar'; ?>
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
