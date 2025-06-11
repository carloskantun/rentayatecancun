<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('afiliado');

$usuario = usuario_actual();

if ($usuario['estado'] !== 'activo') {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Tu cuenta aún no está aprobada.</div></div>";
    exit;
}

$stmt = $pdo->prepare("SELECT slug_personalizado FROM afiliados WHERE usuario_id = :id LIMIT 1");
$stmt->execute(['id' => $usuario['id']]);
$afiliado = $stmt->fetch(PDO::FETCH_ASSOC);
$slug = $afiliado ? $afiliado['slug_personalizado'] : 'sin-slug';

$max_descuento = get_config('descuento_maximo') ?? 15;

$enlace_generado = '';
$link_base = base_url() . "/reservar.php?a=" . urlencode($slug);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] === 'porcentaje' ? 'porcentaje' : 'monto';
    $valor = floatval($_POST['valor']);

    if ($valor <= 0 || $valor > $max_descuento) {
        $error = "El descuento debe ser mayor a 0 y menor o igual a $max_descuento.";
    } else {
        $enlace_generado = base_url() . "/reservar.php?a=" . urlencode($slug) . 
                           "&d=" . $valor . "&p=" . $tipo;
    }
}
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Generar Link con Descuento</h2>

    <div class="mb-3">
      <label><strong>Tu slug:</strong> <?php echo $slug; ?></label><br>
      <label><strong>Link base sin descuento:</strong></label>
      <input type="text" class="form-control mb-3" value="<?php echo $link_base; ?>" readonly>
    </div>

    <p><strong>Descuento máximo permitido:</strong> <?php echo $max_descuento; ?> (USD o %)</p>

    <form method="post" class="row g-3 mb-4">
      <div class="col-md-4">
        <label>Tipo de descuento:</label>
        <select name="tipo" class="form-select">
          <option value="porcentaje">Porcentaje (%)</option>
          <option value="monto">Monto fijo (USD)</option>
        </select>
      </div>
      <div class="col-md-4">
        <label>Valor del descuento:</label>
        <input type="number" name="valor" step="0.01" class="form-control" required>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100"><i class="fa fa-link me-2"></i>Generar Link</button>
      </div>
    </form>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($enlace_generado): ?>
      <div class="alert alert-success">
        <strong>Link generado:</strong><br>
        <input type="text" class="form-control" value="<?php echo $enlace_generado; ?>" readonly>
      </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-3">
      <i class="fa fa-arrow-left me-2"></i>Volver al Panel
    </a>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
