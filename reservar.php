<?php
require_once 'config/database.php';
require_once 'core/funciones.php';

session_start();

$precio_base = get_config('precio_base_reserva') ?? 70;
$costo_empresa = get_config('costo_base_empresa') ?? 30;
$descuento_maximo = $precio_base - $costo_empresa;

$slug = $_GET['a'] ?? '';
$tipo_descuento = $_GET['p'] ?? '';
$valor_descuento = isset($_GET['d']) ? floatval($_GET['d']) : 0.0;

$afiliado = null;
if ($slug !== '') {
    $stmt = $pdo->prepare("SELECT * FROM afiliados WHERE slug_personalizado = :slug LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $afiliado = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$afiliado) die("Afiliado no válido.");
}
if (!$afiliado && $slug === '') $slug = 'casa';

if (!in_array($tipo_descuento, ['monto', 'porcentaje'])) {
    $tipo_descuento = ''; $valor_descuento = 0;
}

$aplica_descuento = false;
$monto_descuento = 0;
$precio_final = $precio_base;

if ($valor_descuento > 0 && $tipo_descuento !== '') {
    $monto_descuento = $tipo_descuento === 'monto' ? $valor_descuento : $precio_base * ($valor_descuento / 100);
    if ($monto_descuento <= $descuento_maximo) {
        $aplica_descuento = true;
        $precio_final = $precio_base - $monto_descuento;
    } else {
        $monto_descuento = 0;
    }
}

$codigo = 'RYC-' . strtoupper(substr(md5(uniqid()), 0, 6));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitize($_POST['nombre']);
    $fecha = sanitize($_POST['fecha']);
    $hora = sanitize($_POST['hora_deseada']);
    $acompanante = sanitize($_POST['acompanante']);
    $telefono = sanitize($_POST['telefono']);
    if (!preg_match('/^[0-9]{10,15}$/', $telefono)) {
        die("El número de teléfono debe contener solo dígitos (10 a 15 caracteres).");
    }
    $email = sanitize($_POST['email']);

    $total_con_extras_usd = isset($_POST['total_con_extras']) ? floatval($_POST['total_con_extras']) : $precio_final;

    // Conversión USD → MXN
    $tasa_manual = 18.00;
    $precio_en_mxn = round($total_con_extras_usd * $tasa_manual, 2);

    // Guardar en reservas_temp (en USD)
    $stmt = $pdo->prepare("INSERT INTO reservas_temp 
        (codigo_reserva, nombre_cliente, fecha_reserva, hora_deseada, acompanante_nombre, telefono_cliente, email_cliente, slug_afiliado, tipo_descuento, valor_descuento, monto_total, metodo_pago, status_pago)
        VALUES (:codigo, :nombre, :fecha, :hora, :acomp, :tel, :email, :slug, :tipo, :valor, :total, 'mercadopago', 'pendiente')");

    $stmt->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'fecha' => $fecha,
        'hora' => $hora,
        'acomp' => $acompanante,
        'tel' => $telefono,
        'email' => $email,
        'slug' => ($slug === 'casa' ? null : $slug),
        'tipo' => $tipo_descuento,
        'valor' => $monto_descuento,
        'total' => $total_con_extras_usd
    ]);

    // MercadoPago - preferencia
    $access_token = get_config('mp_access_token');
    $back_url = base_url() . "/resumen_reserva.php?codigo=$codigo";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $access_token",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'items' => [[
                'title' => 'Tour en Yate Cancún (2 personas)',
                'quantity' => 1,
                'currency_id' => 'MXN',
                'unit_price' => $precio_en_mxn
            ]],
            'back_urls' => [
                'success' => $back_url,
                'failure' => $back_url,
                'pending' => $back_url
            ],
            'auto_return' => 'approved',
            'external_reference' => $codigo
        ])
    ]);

    $response = curl_exec($curl);
    $mpData = json_decode($response, true);
    curl_close($curl);

    if (isset($mpData['init_point'])) {
        header("Location: " . $mpData['init_point']);
        exit;
    } else {
        // Debug opcional en desarrollo:
        echo "<pre>";
        echo "Respuesta de MercadoPago:\n";
        print_r($mpData);
        echo "</pre>";
        die("Error al generar link de pago.");
    }
}

// Incluir plantilla visual
include 'templates/header.php';
?>


<section class="hero-section text-white text-center d-flex align-items-center justify-content-center" style="background: url('assets/imgs/bg/yate-bg.jpg') center center/cover no-repeat; min-height: 50vh;">
  <div class="container">
    <h1 class="display-5 fw-bold" style="color:#fff;">Tour en Yate por $75 USD</h1>
    <p class="lead" style="color:#fff;">2 personas · Hasta 2 Horas · Salidas desde Cancún</p>
  </div>
</section>

<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="row flex-lg-row-reverse gx-60">

      <!-- Formulario -->
      <div class="col-lg-4">
        <div class="card shadow mb-4">
          <div class="card-body">
            <h4 class="mb-3 text-center">
              <?php echo $slug === 'casa'
                ? "Reserva directa con <strong>Renta Yate Cancun</strong>"
                : "Reserva con <strong>" . ucfirst($slug) . "</strong>"; ?>
            </h4>

            <ul class="list-group mb-3">
              <li class="list-group-item">Precio base: <strong>$<?php echo number_format($precio_base, 2); ?> USD</strong></li>
              <?php if ($aplica_descuento): ?>
                <li class="list-group-item">Descuento: <strong><?php echo $tipo_descuento === 'monto' ? "\${$valor_descuento} USD" : "{$valor_descuento}%"; ?></strong></li>
              <?php endif; ?>
              <li class="list-group-item text-success fw-bold">Total: $<?php echo number_format($precio_final, 2); ?> USD</li>
<?php $tasa_manual = 18.00; ?>
<li class="list-group-item small text-muted">
  Aproximadamente <?php echo number_format($precio_final * $tasa_manual, 2); ?> MXN (tipo de cambio estimado)
</li>

            </ul>

            <form method="post">
              <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" class="form-control" name="nombre" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Fecha deseada</label>
                <input type="date" id="fecha" class="form-control" name="fecha" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Horario preferido</label>
                <select name="hora_deseada" class="form-select" required>
                    <option value="">Selecciona una opción</option>
                    <option value="mañana">Mañana (9am)</option>
                    <option value="mediodia">Mediodía (12pm)</option>
                    <option value="tarde">Tarde (3pm)</option>
                    <option value="sunset">Sunset/Atardecer (5pm)</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">¿Quién te acompaña? (opcional)</label>
                <input type="text" class="form-control" name="acompanante">
              </div>
              <div class="mb-3">
  <label class="form-label">Teléfono de contacto</label>
  <input type="tel" class="form-control" name="telefono" required pattern="[0-9]{10,15}" maxlength="15" inputmode="numeric" title="Ingresa solo números, mínimo 10 y máximo 15 dígitos">
</div>
<div class="mb-3">
  <label class="form-label">Correo electrónico</label>
  <input type="email" class="form-control" name="email" required>
</div>
             <div id="detallesYextras">
  <!-- Descripción institucional del tour -->
  <div class="mb-3">
    <h5 class="text-center">Detalles del Tour</h5>
    <ul class="list-unstyled text-center">
      <li>🚤 Paseo en yate</li>
      <li>⏱️ 2 horas aproximadamente</li>
      <li>👥 2 personas</li>
      <li>📍 Salida desde Cancún</li>
    </ul>
  </div>

  <!-- Extras opcionales -->
  <div class="mb-3">
    <h6 class="text-center">Agrega extras opcionales</h6>
    <div class="form-check">
      <input class="form-check-input extra-servicio" type="checkbox" value="50" data-nombre="Bebidas a bordo" id="bebidas">
      <label class="form-check-label" for="bebidas">🍹 Bebidas a bordo ($50 USD)</label>
    </div>
    <div class="form-check">
      <input class="form-check-input extra-servicio" type="checkbox" value="50" data-nombre="Fotos y video" id="fotos">
      <label class="form-check-label" for="fotos">📷 Fotos y video ($50 USD)</label>
    </div>
    <div class="form-check">
      <input class="form-check-input extra-servicio" type="checkbox" value="50" data-nombre="Snorkel" id="snorkel">
      <label class="form-check-label" for="snorkel">🏊 Nado con snorkel ($50 USD)</label>
    </div>
  </div>

  <!-- Total dinámico -->
  <div class="mb-3 text-center fw-bold">
    Total actualizado: <span id="totalFinal">$<?php echo number_format($precio_final, 2); ?> USD</span>
    <input type="hidden" name="total_con_extras" id="total_con_extras">
  </div>
</div>

<div id="bloqueCodigoOrden" class="mb-3 text-center">
  <label class="form-label fw-bold d-block mb-2">Número de orden</label>
  <div>
    <span class="badge bg-secondary fs-6"><?php echo $codigo; ?></span>
  </div>
</div>

              <div class="form-check mb-3 text-start">
  <input class="form-check-input" type="checkbox" id="acepta_terminos" required>
  <label class="form-check-label" for="acepta_terminos">
    Al reservar acepta los<br>
    <a href="terminos.php" target="_blank">Términos y condiciones de uso</a>
  </label>
</div>
              <button type="submit" class="btn btn-primary w-100">Pagar y Reservar</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Info visual -->
      
        <h5 class="mt-4">Recomendaciones</h5>
        <ul>
          <li>🩱 Lleva traje de baño y toalla</li>
          <li>🧴 Usa bloqueador biodegradable</li>
          <li>💳 Lleva efectivo o tarjeta para extras</li>
        </ul>

        <div class="row g-3 mt-4">
          <div class="col-md-4"><img src="assets/imgs/gallery/gallery1.jpg" class="img-fluid rounded" alt="Tour 1"></div>
          <div class="col-md-4"><img src="assets/imgs/gallery/gallery2.jpg" class="img-fluid rounded" alt="Tour 2"></div>
          <div class="col-md-4"><img src="assets/imgs/gallery/gallery3.jpg" class="img-fluid rounded" alt="Tour 3"></div>
        </div>

        <h5 class="mt-4">Términos</h5>
        <p>El servicio no es reembolsable el mismo día del tour. Puedes reprogramar con 48h de anticipación. Impuesto de muelle no incluido ($15-25 USD por persona).</p>
      </div>
    </div>
  </div>$tasa_cambio = get_tasa_cambio_actual();

  </section>

<style>
.badge.bg-secondary {
  position: static !important;
  float: none !important;
  display: inline-block;
}
</style>

</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const fechaInput = document.getElementById("fecha");
    const hoy = new Date().toISOString().split("T")[0];
    fechaInput.setAttribute("min", hoy);
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const extras = document.querySelectorAll('.extra-servicio');
    const totalSpan = document.getElementById('totalFinal');
    const hiddenInput = document.getElementById('total_con_extras');
    const baseTotal = <?php echo $precio_final; ?>;

    function actualizarTotal() {
      let extraTotal = 0;
      extras.forEach(e => {
        if (e.checked) extraTotal += parseFloat(e.value);
      });
      const totalFinal = baseTotal + extraTotal;
      totalSpan.textContent = `$${totalFinal.toFixed(2)} USD`;
      hiddenInput.value = totalFinal.toFixed(2);
    }

    extras.forEach(e => e.addEventListener('change', actualizarTotal));
    actualizarTotal();
  });
</script>


<?php include 'templates/footer.php'; ?>
