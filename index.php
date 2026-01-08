<?php

/**
 * Front Controller para la aplicación en Heroku (y otros servidores Apache).
 *
 * Este script es el único punto de entrada para todas las peticiones que no son
 * para archivos estáticos (CSS, JS, imágenes, etc.), gracias a la configuración
 * en el archivo .htaccess.
 *
 * Su trabajo es analizar la URL solicitada y servir el contenido HTML correcto.
 */

// Obtiene la ruta de la solicitud (ej: "/", "/privacidad")
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Enrutamos a la página HTML correcta basándonos en la URI.
switch ($uri) {
    case '/privacidad':
        $file_to_serve = __DIR__ . '/privacidad.html';
        break;

    case '/terminos-de-condiciones':
        $file_to_serve = __DIR__ . '/terminos.html';
        break;

    case '/':
    case '/index.php': // Maneja el caso de que se acceda directamente a index.php
    default:
        // Para cualquier otra ruta, servimos el index.html por defecto.
        $file_to_serve = __DIR__ . '/index.html';
        break;
}

// Servimos el archivo que hemos determinado.
if (file_exists($file_to_serve)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($file_to_serve);
} else {
    // Si el archivo HTML no se encuentra, mostramos un error 404.
    // Es importante establecer el código de respuesta HTTP correcto.
    http_response_code(404);
    // Incluimos el contenido de una página 404 si la tenemos, o un mensaje simple.
    $not_found_file = __DIR__ . '/404.html';
    if (file_exists($not_found_file)) {
        readfile($not_found_file);
    } else {
        echo "<h1>Error 404: Página no encontrada</h1>";
        echo "<p>La página que buscas no existe.</p>";
    }
}

?>