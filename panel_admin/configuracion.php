<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['config'] as $clave => $valor) {
        $stmt = $pdo->prepare("UPDATE configuracion_sistema SET valor = :valor WHERE clave = :clave");
        $stmt->execute([
            'valor' => sanitize($valor),
            'clave' => sanitize($clave)
        ]);
    }
    $mensaje = "Configuraciones actualizadas correctamente.";
}

$stmt = $pdo->query("SELECT * FROM configuracion_sistema ORDER BY clave ASC");
$configuraciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Configuración del Sistema</h2>

    <?php if (!empty($mensaje)): ?>
      <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Clave</th>
              <th>Valor</th>
              <th>Descripción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($configuraciones as $conf): ?>
              <tr>
                <td><?php echo $conf['clave']; ?></td>
                <td>
                  <input type="text" name="config[<?php echo $conf['clave']; ?>]" 
                         value="<?php echo htmlspecialchars($conf['valor']); ?>" 
                         class="form-control form-control-sm">
                </td>
                <td><?php echo $conf['descripcion']; ?></td>
              </tr>
              <!-- Dentro de la tabla HTML, justo donde se listan las configuraciones -->

            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <button type="submit" class="btn btn-primary mt-3">
        <i class="fa fa-save me-2"></i>Guardar Cambios
      </button>
    </form>

    <div class="mt-4">
      <a href="dashboard.php" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left me-2"></i>Volver al Panel
      </a>
    </div>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
