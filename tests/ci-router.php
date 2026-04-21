<?php
// Router para PHP built-in server en CI.
// Sirve la app como si estuviera en la raíz (BASE_URL='').
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$publicDir = __DIR__ . '/../public';

// Servir archivos estáticos directamente
$file = $publicDir . $uri;
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    $ext   = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff2'=> 'font/woff2',
    ];
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    readfile($file);
    exit;
}

// Inyectar ?url= para el router de la app
$_GET['url'] = ltrim($uri, '/');
chdir($publicDir);
require $publicDir . '/index.php';
