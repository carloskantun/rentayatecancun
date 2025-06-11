<?php
require_once 'config/database.php';
require_once 'core/funciones.php';
require_once 'core/auth.php';

if (esta_logueado()) {
    $usuario = usuario_actual();
    $rol = $usuario['rol'];
    switch ($rol) {
        case 'admin': header("Location: panel_admin/dashboard.php"); break;
        case 'afiliado': header("Location: panel_afiliado/dashboard.php"); break;
        case 'staff': header("Location: panel_staff/dashboard.php"); break;
        case 'moderador': header("Location: panel_admin/dashboard.php"); break;
        default: echo "Rol desconocido."; exit;
    }
    exit;
}
?>

<?php include('templates/header.php'); ?>
<?php include('templates/navbar.php'); ?>

<section class="hero-section text-white text-center d-flex align-items-center justify-content-center" style="background: url('assets/imgs/bg/hero.jpg') center center/cover no-repeat; min-height: 85vh;">
    <div class="container">
        <h1 class="display-4 fw-bold" style="color:#fff;">Tour en Yate para 2 Personas</h1>
        <p class="lead" style="color:#fff;">Vive una experiencia de lujo por solo <strong>$75 USD</strong>. Salidas compartidas desde Cancún.</p>
        <a href="reservar.php" class="btn btn-primary btn-lg mt-3">Reservar Ahora</a>
    </div>
</section>

<section class="py-5 text-center">
    <div class="container">
        <h2 class="mb-4">¿Qué incluye el tour?</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <i class="fa fa-ship fa-2x mb-2 text-primary"></i>
                <h5>Paseo en Yate Compartido</h5>
                <p>Hasta 2 horas de recorrido con otras parejas o grupos pequeños.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fa fa-cocktail fa-2x mb-2 text-success"></i>
                <h5>Barra Libre (opcional)</h5>
                <p>Puedes elegir entre diferentes paquetes que incluyen bebidas y snacks a bordo, con costo adicional según la opción seleccionada.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fa fa-user-shield fa-2x mb-2 text-warning"></i>
                <h5>Tripulación Profesional</h5>
                <p>Capitán y marineros certificados para tu seguridad.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="mb-4">¿Por qué elegirnos?</h2>
        <div class="row">
            <div class="col-md-3">
                <i class="fa fa-dollar-sign fa-2x text-success"></i>
                <h6>Precio Accesible</h6>
                <p>Solo $75 USD por pareja.</p>
            </div>
            <div class="col-md-3">
                <i class="fa fa-star fa-2x text-warning"></i>
                <h6>Experiencia VIP</h6>
                <p>Barcos modernos y grupos reducidos.</p>
            </div>
            <div class="col-md-3">
                <i class="fa fa-clock fa-2x text-info"></i>
                <h6>Salidas Diarias</h6>
                <p>Horarios flexibles y facilidad de reserva.</p>
            </div>
            <div class="col-md-3">
                <i class="fa fa-handshake fa-2x text-primary"></i>
                <h6>Afiliados Ganando</h6>
                <p>Promotores reciben comisión por cada venta.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-4">¿Cómo funciona?</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">1. Reserva</h5>
                        <p class="card-text">Elige fecha y paga en línea de forma segura.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">2. Navega</h5>
                        <p class="card-text">Llega al muelle y sube a bordo del yate compartido.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">3. Disfruta</h5>
                        <p class="card-text">Relájate, toma fotos, bebe algo y vive la experiencia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="text-white py-5 text-center">
    <div class="container">
        <h2 class="mb-4">Afíliate y gana comisiones</h2>
        <p>Si tienes seguidores, clientes o vendes turismo, ésta es tu oportunidad. Regístrate como promotor y genera ingresos recomendando esta experiencia.</p>
       <a href="afiliate.php" class="btn btn-primary btn-lg mt-3 rounded-pill">Quiero ser Afiliado</a>
    </div>
</section>

<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="mb-4">Contáctanos</h2>
        <p>Estamos para ayudarte por WhatsApp o correo electrónico.</p>
      <a href="https://wa.me/529844273666" target="_blank" class="btn btn-success btn-lg rounded-pill">
    <i class="fab fa-whatsapp me-2"></i> Escríbenos por WhatsApp
</a>
    </div>
</section>

<?php include('templates/footer.php'); ?>
