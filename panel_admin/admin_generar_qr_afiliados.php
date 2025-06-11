<?php
require_once '../config/database.php';
require_once '../core/funciones.php';
require_once '../core/phpqrcode/qrlib.php';

// Verificar que solo admin o moderador pueda usarlo
session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] !== 'admin' && $_SESSION['usuario']['rol'] !== 'moderador')) {
    die("Acceso restringido.");
}

$mensaje = '';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Función para limpiar slug
function limpiar_slug($texto) {
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    $texto = trim($texto, '-');
    return $texto;
}

// Buscar afiliados SIN qr_code
$stmt = $pdo->query("SELECT id, slug_personalizado FROM afiliados WHERE (qr_code IS NULL OR qr_code = '') AND slug_personalizado IS NOT NULL");
$afiliados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalGenerados = 0;
$errores = [];

foreach ($afiliados as $afiliado) {
    $slugOriginal = $afiliado['slug_personalizado'];
    $slug = limpiar_slug($slugOriginal);

    $qr_dir = 'assets/qrcodes/';
    $qr_filename = $slug . '.png';
    $qr_path = $qr_dir . $qr_filename;
    $link = base_url() . "/reservar.php?a=" . urlencode($slugOriginal);

    if (!is_dir(__DIR__ . '/../' . $qr_dir)) {
        mkdir(__DIR__ . '/../' . $qr_dir, 0755, true);
    }

    try {
        QRcode::png($link, __DIR__ . '/../' . $qr_path);

        $stmtUpdate = $pdo->prepare("UPDATE afiliados SET qr_code = :qr WHERE id = :id");
        $stmtUpdate->execute([
            'qr' => $qr_path,
            'id' => $afiliado['id']
        ]);

        $totalGenerados++;
    } catch (Exception $e) {
        $errores[] = $slugOriginal;
    }
}
?>


<?php include('../templates/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Generación de Códigos QR</h2>

    <?php if ($totalGenerados > 0): ?>
        <div class="alert alert-success text-center">
            ✅ Se generaron <?php echo $totalGenerados; ?> códigos QR correctamente.
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            ℹ️ No había afiliados pendientes de QR o ocurrió un error.
        </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger mt-4">
            ❌ No se pudieron generar QR para los siguientes slugs:
            <ul>
                <?php foreach ($errores as $slugError): ?>
                    <li><?php echo htmlspecialchars($slugError); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
    </div>
</div>

<?php include('../templates/footer.php'); ?>
