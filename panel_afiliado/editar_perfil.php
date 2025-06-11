<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('afiliado');

$usuario = usuario_actual();
$id = $usuario['id'];
$mensaje = '';

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT nombre, email, telefono, foto_ine FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_nombre = sanitize($_POST['nombre']);
    $telefono = sanitize($_POST['telefono']);
    $nueva_pass = $_POST['password'];
    $foto_ine = $datos['foto_ine'];

    // Subir nueva INE si se envía
    if (!empty($_FILES['foto_ine']['tmp_name'])) {
        $dir = '../assets/ine/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = uniqid('ine_') . '.' . pathinfo($_FILES['foto_ine']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['foto_ine']['tmp_name'], $dir . $filename);
        $foto_ine = 'assets/ine/' . $filename;
    }

    // Preparar query
    $sql = "UPDATE usuarios SET nombre = :nombre, telefono = :tel, foto_ine = :foto";
    $params = ['nombre' => $nuevo_nombre, 'tel' => $telefono, 'foto' => $foto_ine];

    // Si cambia contraseña
    if (!empty($nueva_pass)) {
        $sql .= ", password = :pass";
        $params['pass'] = password_hash($nueva_pass, PASSWORD_DEFAULT);
    }

    $sql .= " WHERE id = :id";
    $params['id'] = $id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $mensaje = "✅ Datos actualizados correctamente.";
    $_SESSION['usuario']['nombre'] = $nuevo_nombre;
}
?>

<?php include('../templates/header.php'); ?>

<section class="space-top space-extra-bottom">
  <div class="container">
    <h2 class="mb-4">✏️ Editar Perfil</h2>

    <?php if ($mensaje): ?>
      <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="card shadow p-4" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($datos['nombre']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($datos['telefono']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••••">
        </div>

        <div class="mb-3">
            <label class="form-label">Foto de INE (opcional)</label>
            <input type="file" name="foto_ine" class="form-control">
            <?php if (!empty($datos['foto_ine'])): ?>
                <small class="d-block mt-2">INE actual: <a href="../<?php echo $datos['foto_ine']; ?>" target="_blank">Ver imagen</a></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
    </form>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-2"></i>Volver al panel</a>
    </div>
  </div>
</section>

<?php include('../templates/footer.php'); ?>
