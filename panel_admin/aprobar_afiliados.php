<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

if (isset($_GET['aprobar'])) {
    $id = intval($_GET['aprobar']);

    // Aprobar usuario y afiliado
    $stmt = $pdo->prepare("UPDATE usuarios SET estado = 'activo' WHERE id = :id AND rol = 'afiliado'");
    $stmt->execute(['id' => $id]);

    $stmt = $pdo->prepare("UPDATE afiliados SET estado = 'activo' WHERE usuario_id = :id");
    $stmt->execute(['id' => $id]);

    // Obtener datos del afiliado aprobado
    $stmt = $pdo->prepare("SELECT slug_personalizado FROM afiliados WHERE usuario_id = :id");
    $stmt->execute(['id' => $id]);
    $afiliado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($afiliado && $afiliado['slug_personalizado']) {
        require_once '../core/phpqrcode/qrlib.php';

        $slug = $afiliado['slug_personalizado'];
        $link = base_url() . "/reservar.php?a=" . urlencode($slug);
        $rutaQR = "assets/qrcodes/" . $slug . ".png";

        // Crear carpeta si no existe
        if (!file_exists("../assets/qrcodes")) {
            mkdir("../assets/qrcodes", 0755, true);
        }

        // Generar QR
        QRcode::png($link, "../" . $rutaQR);

        // Guardar en BD
        $stmt = $pdo->prepare("UPDATE afiliados SET qr_code = :qr WHERE usuario_id = :id");
        $stmt->execute([
            'qr' => $rutaQR,
            'id' => $id
        ]);
    }

    header("Location: aprobar_afiliados.php?ok=1");
    exit;
}


$stmt = $pdo->prepare("SELECT u.id, u.nombre, u.email, a.slug_personalizado 
                       FROM usuarios u 
                       JOIN afiliados a ON u.id = a.usuario_id 
                       WHERE u.rol = 'afiliado' AND u.estado = 'inactivo'");
$stmt->execute();
$afiliados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <div class="row gx-60">
      <div class="col-lg-12">
        <h2 class="product-title mb-4">Afiliados Pendientes de Aprobación</h2>

        <?php if (count($afiliados) === 0): ?>
          <div class="alert alert-info">No hay afiliados pendientes.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Slug</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($afiliados as $afiliado): ?>
                  <tr>
                    <td><?php echo $afiliado['nombre']; ?></td>
                    <td><?php echo $afiliado['email']; ?></td>
                    <td><?php echo $afiliado['slug_personalizado']; ?></td>
                    <td>
                      <a href="?aprobar=<?php echo $afiliado['id']; ?>" class="btn btn-success btn-sm">
                        <i class="fa fa-check"></i> Aprobar
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
            <i class="fa fa-arrow-left me-2"></i> Volver al Panel
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>
