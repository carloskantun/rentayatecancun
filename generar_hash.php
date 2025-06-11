<?php
// Contraseña en texto plano
$password = 'Hampton'; // Cambia aquí por la contraseña que deseas
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Mostrar el hash generado
echo "Hash para '{$password}': " . $hashed_password;
?>