<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/auth.php';

require_login();
require_rol('admin');

$busqueda = $_GET['q'] ?? '';
$rol = $_GET['rol'] ?? '';
$estado = $_GET['estado'] ?? '';

$query = "SELECT * FROM usuarios WHERE 1";
$params = [];

if (!empty($busqueda)) {
    $query .= " AND (nombre LIKE :q OR email LIKE :q)";
    $params['q'] = "%$busqueda%";
}

if (!empty($rol)) {
    $query .= " AND rol = :rol";
    $params['rol'] = $rol;
}

if (!empty($estado)) {
    $query .= " AND estado = :estado";
    $params['estado'] = $estado;
}

$query .= " ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../templates/header.php'); ?>

<section class="product-details space-top space-extra-bottom">
  <div class="container">
    <h2 class="product-title mb-4">Usuarios Registrados</h2>

    <form method="get" class="row g-3 mb-4">
      <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Buscar por nombre o correo" value="<?php echo htmlspecialchars($busqueda); ?>">
      </div>
      <div class="col-md-3">
        <select name="rol" class="form-select">
          <option value="">Todos los roles</option>
          <option value="afiliado" <?php if ($rol === 'afiliado') echo 'selected'; ?>>Afiliado</option>
          <option value="moderador" <?php if ($rol === 'moderador') echo 'selected'; ?>>Moderador</option>
          <option value="staff" <?php if ($rol === 'staff') echo 'selected'; ?>>Staff</option>
          <option value="admin" <?php if ($rol === 'admin') echo 'selected'; ?>>Administrador</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="estado" class="form-select">
          <option value="">Todos los estados</option>
          <option value="activo" <?php if ($estado === 'activo') echo 'selected'; ?>>Activo</option>
          <option value="pendiente" <?php if ($estado === 'pendiente') echo 'selected'; ?>>Pendiente</option>
          <option value="inactivo" <?php if ($estado === 'inactivo') echo 'selected'; ?>>Inactivo</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-2"></i>Filtrar</button>
      </div>
    </form>

    <?php if (empty($usuarios)): ?>
      <div class="alert alert-warning">No se encontraron usuarios con esos criterios.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Teléfono</th>
              <th>Rol</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['telefono']); ?></td>
                <td><?php echo ucfirst($u['rol']); ?></td>
                <td><?php echo ucfirst($u['estado']); ?></td>
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
