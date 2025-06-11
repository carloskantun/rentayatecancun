<?php
// Archivo: /core/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesiиоn activa
function esta_logueado() {
    return isset($_SESSION['usuario']);
}

// Obtener el usuario actual desde la base de datos
function usuario_actual() {
    global $pdo;
    if (!isset($_SESSION['usuario']['id'])) return null;

    $stmt = $pdo->prepare("SELECT id, nombre, email, rol, estado FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['usuario']['id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Verificar si el usuario tiene un rol especикfico
function tiene_rol($rol) {
    return esta_logueado() && $_SESSION['usuario']['rol'] === $rol;
}

// Redirigir si no estив logueado
function require_login() {
    if (!esta_logueado()) {
        header("Location: /auth/login.php");
        exit;
    }
}

// Redirigir si no tiene un rol especикfico
function require_rol($rol) {
    if (!tiene_rol($rol)) {
        header("Location: /auth/login.php");
        exit;
    }
}
?>