<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['usuario'] ?? null;
$rol = $usuario['rol'] ?? null;

    header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Renta de Yates en Cancún</title>
    <meta name="author" content="Mi Sistema">
    <meta name="description" content="Renta de Yates y Tours personalizados">
    <meta name="keywords" content="Renta de Yates, Tours, Cancún">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/favicons/apple-icon-57x57.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicons/favicon-32x32.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Manrope:wght@200..800&family=Montez&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/styles.css?v=1"> <!-- Custom styles -->
</head>

<body>

<header class="th-header header-layout1 header-layout4">
    <!-- Header Top -->
    <div class="header-top">
        <div class="container th-container">
            <div class="row justify-content-center justify-content-xl-between align-items-center">
                <div class="col-auto d-none d-md-block">
                    <div class="header-links">
                        <ul>
                            <li class="d-none d-xl-inline-block"><i class="fa-sharp fa-regular fa-location-dot"></i>
                                <span>Cancún, Quintana Roo.</span>
                            </li>
                            <li class="d-none d-xl-inline-block"><i class="fa-regular fa-clock"></i>
                                <span>Lunes a Domingo: 8.00 am - 7.00 pm</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="header-right">
                        <div class="header-links">
                            <ul>
                                <?php if (!$usuario): ?>
                                    <li><a href="#" class="popup-content" data-bs-toggle="modal" data-bs-target="#login-form">Conéctate / Regístrate <i class="fa-regular fa-user"></i></a></li>
                                <?php else: ?>
                                    <li><i class="fa-regular fa-user"></i> Hola, <?php echo htmlspecialchars($usuario['nombre']); ?></li>
                                    <li><a href="/auth/logout.php">Cerrar sesión</a></li>
                                <?php endif; ?>
                                <li><a href="/ver_reserva.php" class="btn btn-outline-primary">Ver mi Reserva</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Menu -->
    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container th-container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <div class="header-logo">
                            <a href="/index.php"><img src="/assets/imgs/rentayatelogo.png" width="200px" alt="Mi Sistema"></a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <nav class="main-menu d-none d-xl-inline-block">
                            <ul>
                                <li><a href="/index.php">Inicio</a></li>
                                <li><a href="/reservar.php">Reservar</a></li>
                                <li><a href="/servicios.php">Servicios</a></li>
                                <li><a href="/contacto.php">Contáctanos</a></li>

                                <?php if ($usuario): ?>
                                    <?php if ($rol === 'admin'): ?>
                                        <li><a href="/panel_admin/dashboard.php">Panel Admin</a></li>
                                    <?php elseif ($rol === 'afiliado'): ?>
                                        <li><a href="/panel_afiliado/dashboard.php">Panel Afiliado</a></li>
                                    <?php elseif ($rol === 'staff'): ?>
                                        <li><a href="/panel_staff/dashboard.php">Panel Staff</a></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <button type="button" class="th-menu-toggle d-block d-xl-none"><i class="far fa-bars"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<?php include('templates/modal_login_register.php'); ?>
