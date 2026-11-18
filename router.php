<?php
// Router para el servidor embebido de PHP: replica el alias /utpl/ de Apache (htdocs/utpl -> web).
// Uso: php -S 127.0.0.1:8000 router.php   (desde la carpeta web)
$web = __DIR__;
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$file = str_starts_with($uri, '/utpl/') ? $web . substr($uri, 5) : $web . '/php' . $uri;
if (is_dir($file)) $file = rtrim($file, '/') . '/index.php';

$real = realpath($file);
if ($real === false || !str_starts_with($real, realpath($web)) || basename($real) === 'router.php') {
    http_response_code(404);
    echo 'Not found';
    return true;
}

if (str_ends_with($real, '.php')) {
    chdir(dirname($real));
    require $real;
    return true;
}

$tipos = ['css' => 'text/css', 'js' => 'text/javascript', 'html' => 'text/html', 'png' => 'image/png',
    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'svg' => 'image/svg+xml', 'webp' => 'image/webp', 'ico' => 'image/x-icon'];
header('Content-Type: ' . ($tipos[strtolower(pathinfo($real, PATHINFO_EXTENSION))] ?? 'application/octet-stream'));
readfile($real);
return true;
