<?php

/**
 * Router para el servidor de desarrollo incorporado de PHP.
 *
 * Este script realiza dos funciones principales:
 * 1. Si la petición del navegador es para un archivo que existe físicamente 
 *    (como un .css, .js, .jpg), le dice al servidor que lo sirva directamente.
 * 2. Si la petición no corresponde a un archivo existente (como "/", "/acerca", etc.),
 *    sirve el contenido del archivo 'index.html'.
 *
 * CÓMO USARLO:
 * 1. Coloca este archivo 'server.php' en la misma carpeta que tu 'index.html'.
 * 2. Abre una terminal en esa carpeta.
 * 3. Ejecuta el comando: php -S localhost:8000 server.php
 * 4. Abre tu navegador en http://localhost:8000
 */

// Obtiene la ruta de la solicitud (ej: "/", "/style.css", "/about")
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Construye la ruta completa al archivo solicitado en el sistema de archivos.
// __DIR__ es una constante mágica que representa el directorio del script actual.
$requested_path = __DIR__ . $uri;

// Comprobación crucial: si la solicitud es para un archivo que existe y no es un directorio...
if ($uri !== '/' && file_exists($requested_path) && is_file($requested_path)) {
    // ...deja que el servidor incorporado maneje la solicitud por sí mismo.
    // Devolver 'false' es la señal para que el servidor sirva el archivo estático.
    // Esto es lo que permite que tus archivos CSS, JS, imágenes, etc., se carguen correctamente.
    return false;
}

// Si la solicitud no era para un archivo estático existente, servimos 'index.html'.
// Esto actúa como el punto de entrada para una Single Page Application (SPA) o una web simple.
$index_path = __DIR__ . '/index.html';

if (file_exists($index_path)) {
    // Establecemos la cabecera de contenido correcta.
    header('Content-Type: text/html; charset=utf-8');
    // Leemos y mostramos el contenido de index.html.
    readfile($index_path);
} else {
    // Si no se encuentra index.html, muestra un error claro.
    http_response_code(404);
    echo "<h1>Error 404: No se encontró el archivo index.html</h1>";
    echo "<p>Asegúrate de que el archivo <code>index.html</code> exista en el mismo directorio que <code>server.php</code>.</p>";
}

?>