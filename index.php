<?php

/**
 * Router para el servidor de desarrollo incorporado de PHP.
 *
 * Este script realiza tres funciones principales:
 * 1. Si la petición del navegador es para un archivo que existe físicamente 
 *    (como un .css, .js, .jpg), le dice al servidor que lo sirva directamente.
 * 2. Si la petición es para una ruta específica definida (como "/privacidad" o "/terminos-de-condiciones"),
 *    sirve el archivo HTML correspondiente.
 * 3. Si la petición no corresponde a un archivo o ruta específica,
 *    sirve el contenido del archivo 'index.html' por defecto.
 *
 * CÓMO USARLO:
 * 1. Coloca este archivo 'server.php' en la misma carpeta que tus archivos HTML.
 * 2. Asegúrate de tener los archivos 'index.html', 'privacidad.html' y 'terminos.html'.
 * 3. Abre una terminal en esa carpeta.
 * 4. Ejecuta el comando: php -S localhost:8000 server.php
 * 5. Abre tu navegador en http://localhost:8000, http://localhost:8000/privacidad, etc.
 */

// Obtiene la ruta de la solicitud (ej: "/", "/style.css", "/privacidad")
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Construye la ruta completa al archivo solicitado en el sistema de archivos.
// __DIR__ es una constante mágica que representa el directorio del script actual.
$requested_path = __DIR__ . $uri;

// Comprobación 1: Si la solicitud es para un archivo estático que existe...
if ($uri !== '/' && file_exists($requested_path) && is_file($requested_path)) {
    // ...deja que el servidor incorporado maneje la solicitud.
    // Devolver 'false' es la señal para que el servidor sirva el archivo estático.
    // Esto es lo que permite que tus archivos CSS, JS, imágenes, etc., se carguen correctamente.
    return false;
}

// Comprobación 2: Si no es un archivo estático, enrutamos a la página HTML correcta.
// Usamos un switch para manejar las rutas personalizadas de forma limpia.
switch ($uri) {
    case '/privacidad':
        // Si la URI es /privacidad, servimos el archivo de política de privacidad.
        $file_to_serve = __DIR__ . '/privacidad.html';
        break;

    case '/terminos-y-condiciones':
        // Si la URI es /terminos-de-condiciones, servimos el archivo de términos.
        $file_to_serve = __DIR__ . '/terminos.html';
        break;

    default:
        // Para cualquier otra ruta, servimos el index.html por defecto.
        // Esto actúa como el punto de entrada para una Single Page Application (SPA) o una web simple.
        $file_to_serve = __DIR__ . '/index.html';
        break;
}

// Finalmente, servimos el archivo que hemos determinado.
if (file_exists($file_to_serve)) {
    // Establecemos la cabecera de contenido correcta.
    header('Content-Type: text/html; charset=utf-8');
    // Leemos y mostramos el contenido del archivo HTML correspondiente.
    readfile($file_to_serve);
} else {
    // Si el archivo HTML que se debía servir no se encuentra, muestra un error claro.
    http_response_code(404);
    echo "<h1>Error 404: Archivo no encontrado</h1>";
    echo "<p>El archivo <code>" . htmlspecialchars(basename($file_to_serve)) . "</code> no se encontró en el servidor.</p>";
    echo "<p>Asegúrate de que el archivo exista en el mismo directorio que <code>server.php</code>.</p>";
}

?>