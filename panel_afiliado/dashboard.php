<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('afiliado');

$usuario = usuario_actual();

if ($usuario['estado'] !== 'activo') {
    echo "<div class='container mt-5'><div class='alert alert-danger'><h4>Hola, {$usuario['nombre']}</h4><p>Tu cuenta está desactivada. Contacta al administrador.</p></div></div>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM afiliados WHERE usuario_id = :id LIMIT 1");
$stmt->execute(['id' => $usuario['id']]);
$afiliado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$afiliado || $afiliado['estado'] !== 'activo') {
    echo "<div class='container mt-5'><div class='alert alert-warning'><h4>Hola, {$usuario['nombre']}</h4><p>Tu afiliación no ha sido aprobada aún.</p></div></div>";
    exit;
}

$slug = $afiliado['slug_personalizado'] ?: 'sin-slug';
$link = base_url() . "/reservar.php?a=" . urlencode($slug);
$qr = $afiliado['qr_code'];
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">

    <div class="row gx-60">
      <div class="col-lg-9">
        <h2 class="product-title">Bienvenido, <?php echo $usuario['nombre']; ?> <small class="text-muted">(Afiliado)</small></h2>

        <p class="text mt-3"><strong>Tu enlace personalizado:</strong></p>
        <div class="input-group mt-2 mb-4">
          <input type="text" value="<?php echo $link; ?>" id="linkAfiliado" class="form-control" readonly>
          <button class="btn btn-primary" onclick="copiarLink()">Copiar</button>
        </div>

        <?php if ($qr): ?>
          <p><strong>Tu código QR:</strong></p>
          <img src="../<?php echo $qr; ?>" alt="Código QR" style="max-width: 180px;">
        <?php else: ?>
          <div class="alert alert-warning">Tu código QR aún no está disponible. Contacta al soporte si el problema persiste.</div>
        <?php endif; ?>
      </div>

      <div class="col-lg-3">
        <div class="product-meta d-grid gap-2">
          <a href="generar_link.php" class="btn btn-outline-primary">🔗 Generar Link</a>
          <a href="mis_reservas.php" class="btn btn-outline-secondary">📅 Mis Reservas</a>
          <a href="configuracion_micrositio.php" class="btn btn-outline-dark">⚙️ Micrositio</a>
          <a href="editar_perfil.php" class="btn btn-outline-info">✏️ Editar Perfil</a>
          <a href="/auth/logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function copiarLink() {
  var input = document.getElementById("linkAfiliado");
  input.select();
  input.setSelectionRange(0, 99999);
  document.execCommand("copy");
  alert("¡Enlace copiado!");
}
</script>

<?php include('../templates/menu_flotante.php'); ?>
<?php include('../templates/footer.php'); ?>