<?php
// Archivo: /config/database.php

$host = 'localhost';
$dbname = 'rentayat_escancun';
$user = 'rentayat_escancun';
$pass = '+1]kx*BGrWwq';

try {
    // Aquí se establece la conexión correctamente
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    // Modo de errores: excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>