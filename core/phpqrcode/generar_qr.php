<?php
require_once '../config/database.php';
require_once '../core/phpqrcode/qrlib.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die('ID no válido');
}

$stmt = $pdo->prepare("SELECT * FROM afiliados WHERE usuario_id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$afiliado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$afiliado || $afiliado['estado'] !== 'activo') {
    die('Afiliado no válido o no activo');
}

$slug = $afiliado['slug_personalizado'];
$link = base_url() . "/reservar.php?a=" . urlencode($slug);
$nombreArchivo = "assets/qrcodes/" . $slug . ".png";

// Crear directorio si no existe
if (!file_exists("assets/qrcodes")) {
    mkdir("assets/qrcodes", 0755, true);
}

// Generar y guardar el QR
QRcode::png($link, $nombreArchivo);

// Guardar en la base de datos
$stmt = $pdo->prepare("UPDATE afiliados SET qr_code = :qr WHERE usuario_id = :id");
$stmt->execute([
    'qr' => $nombreArchivo,
    'id' => $id
]);

echo "Código QR generado correctamente.";
