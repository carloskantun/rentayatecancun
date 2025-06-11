<?php
// Archivo: /core/funciones.php

require_once __DIR__ . '/../config/database.php';

// Obtener configuraci車n por clave
function get_config($clave) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT valor FROM configuracion_sistema WHERE clave = :clave LIMIT 1");
    $stmt->execute(['clave' => $clave]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['valor'] : null;
}

// Limpiar entrada de texto
function sanitize($valor) {
    return htmlspecialchars(strip_tags(trim($valor)));
}

// Obtener la URL base del sistema

function base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . "://" . $host;
}

// Generar slug de texto amigable para URL
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}


function get_afiliado_id_por_slug($pdo, $slug) {
    $stmt = $pdo->prepare("SELECT id FROM afiliados WHERE slug_personalizado = :slug LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetchColumn() ?: null;
}

// Obtener tasa de cambio USD → MXN con caché local por 24h
function get_tasa_cambio_actual(): float {
    $tasa_respaldo = 19.64; // valor de respaldo si falla todo
    $api_key = '6237ed3ced4c78c543c2465d'; // reemplaza con tu API key válida
    $cache_file = __DIR__ . '/../cache/tasa_cambio.json';

    // Verificar si existe un archivo de cache reciente
    if (file_exists($cache_file)) {
        $contenido = json_decode(file_get_contents($cache_file), true);
        if ($contenido && isset($contenido['tasa']) && isset($contenido['timestamp'])) {
            if ((time() - $contenido['timestamp']) < 86400) { // menos de 24h
                return floatval($contenido['tasa']);
            }
        }
    }

    // Si no hay cache válido, intenta obtener la tasa actual desde la API
    $url = "https://v6.exchangerate-api.com/v6/6237ed3ced4c78c543c2465d/latest/USD";

    try {
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['conversion_rates']['MXN'])) {
            $tasa = floatval($data['conversion_rates']['MXN']);

            // Guardar en archivo cache
            file_put_contents($cache_file, json_encode([
                'tasa' => $tasa,
                'timestamp' => time()
            ]));

            return $tasa;
        }
    } catch (Exception $e) {
        // No hacemos nada, se usará la tasa de respaldo
    }

    return $tasa_respaldo;
}


?>