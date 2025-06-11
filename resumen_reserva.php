<?php
require_once 'config/database.php';
require_once 'core/funciones.php';

$codigo = $_GET['codigo'] ?? null;
$status = $_GET['collection_status'] ?? null;

if (!$codigo) die("Código no proporcionado.");

$reserva = null;
$temporal = false;

// 1. Si fue aprobado, migramos
if ($status === 'approved') {
    $stmt = $pdo->prepare("SELECT * FROM reservas_temp WHERE codigo_reserva = :codigo LIMIT 1");
    $stmt->execute(['codigo' => $codigo]);
    $reservaTemp = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reservaTemp) {
        $stmtInsert = $pdo->prepare("INSERT INTO reservas 
            (codigo_reserva, nombre_cliente, fecha_reserva, hora_deseada, acompanante_nombre, telefono_cliente,
             email_cliente, slug_afiliado, tipo_descuento, valor_descuento, monto_total, creado_en, metodo_pago, status_pago)
            VALUES 
            (:codigo, :nombre, :fecha, :hora, :acompa, :tel, :email, :slug, :tipo_descuento, :valor_descuento, :total, NOW(), 'mercadopago', 'aprobado')");

        $stmtInsert->execute([
            'codigo' => $reservaTemp['codigo_reserva'],
            'nombre' => $reservaTemp['nombre_cliente'],
            'fecha' => $reservaTemp['fecha_reserva'],
            'hora' => $reservaTemp['hora_deseada'],
            'acompa' => $reservaTemp['acompanante_nombre'],
            'tel' => $reservaTemp['telefono_cliente'],
            'email' => $reservaTemp['email_cliente'],
            'slug' => $reservaTemp['slug_afiliado'],
            'tipo_descuento' => $reservaTemp['tipo_descuento'],
            'valor_descuento' => $reservaTemp['valor_descuento'],
            'total' => $reservaTemp['monto_total']
        ]);

        // Eliminar temporal
        $pdo->prepare("DELETE FROM reservas_temp WHERE codigo_reserva = :codigo")->execute(['codigo' => $codigo]);
    }
}

// 2. Buscar reserva definitiva
$stmt = $pdo->prepare("SELECT * FROM reservas WHERE codigo_reserva = :codigo LIMIT 1");
$stmt->execute(['codigo' => $codigo]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Si no está, buscar en temporal
if (!$reserva) {
    $stmt = $pdo->prepare("SELECT * FROM reservas_temp WHERE codigo_reserva = :codigo LIMIT 1");
    $stmt->execute(['codigo' => $codigo]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
    $temporal = true;
}

// 4. Buscar nombre de afiliado
$nombreAfiliado = 'Venta directa';
if (!empty($reserva['slug_afiliado'])) {
    $stmt = $pdo->prepare("SELECT u.nombre FROM afiliados a JOIN usuarios u ON u.id = a.usuario_id WHERE a.slug_personalizado = :slug LIMIT 1");
    $stmt->execute(['slug' => $reserva['slug_afiliado']]);
    $nombreAfiliado = $stmt->fetchColumn() ?: 'Afiliado';
}

// 5. Buscar nombre del barco si tiene salida asignada
$nombreBarco = null;
if (!$temporal && !empty($reserva['salida_id'])) {
    $stmt = $pdo->prepare("SELECT nombre_barco FROM salidas WHERE id = :id");
    $stmt->execute(['id' => $reserva['salida_id']]);
    $nombreBarco = $stmt->fetchColumn();
}
?>

<?php include 'templates/header.php'; ?>

<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="text-center mb-4">
      <h2>Resumen de tu Reserva</h2>
      <p>Gracias por reservar con nosotros. Aquí están los detalles:</p>
    </div>

    <?php if (!$reserva): ?>
        <div class="alert alert-danger text-center">
            No encontramos una reserva con el código <strong><?php echo htmlspecialchars($codigo); ?></strong>.
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-3">Código: <strong><?php echo $reserva['codigo_reserva']; ?></strong></h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nombre:</strong> <?php echo htmlspecialchars($reserva['nombre_cliente']); ?></li>
                    <li class="list-group-item"><strong>Fecha deseada:</strong> <?php echo $reserva['fecha_reserva']; ?></li>
                    <li class="list-group-item"><strong>Horario:</strong> <?php echo $reserva['hora_deseada']; ?></li>
                    <li class="list-group-item"><strong>Teléfono:</strong> <?php echo $reserva['telefono_cliente']; ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo $reserva['email_cliente']; ?></li>
                    <?php if (!empty($reserva['acompanante_nombre'])): ?>
                        <li class="list-group-item"><strong>Acompañante:</strong> <?php echo $reserva['acompanante_nombre']; ?></li>
                    <?php endif; ?>
                    <li class="list-group-item"><strong>Total:</strong> $<?php echo number_format($reserva['monto_total'], 2); ?> USD</li>
                    <li class="list-group-item"><strong>Reservado por:</strong> <?php echo htmlspecialchars($nombreAfiliado); ?></li>
                    <?php if ($nombreBarco): ?>
                        <li class="list-group-item"><strong>Yate Asignado:</strong> <?php echo htmlspecialchars($nombreBarco); ?></li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <strong>Estado de pago:</strong>
                        <?php
                            if (!$temporal && $reserva['status_pago'] === 'aprobado') {
                                echo "<span class='text-success fw-bold'>Pago aprobado</span>";
                            } elseif ($temporal) {
                                echo "<span class='text-warning fw-bold'>Pendiente de pago</span>";
                                echo "<br><a href='reservar.php?codigo=" . urlencode($codigo) . "' class='btn btn-sm btn-outline-primary mt-2'>Reintentar pago</a>";
                            } else {
                                echo "<span class='text-danger fw-bold'>Rechazado o incompleto</span>";
                            }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'templates/footer.php'; ?>
