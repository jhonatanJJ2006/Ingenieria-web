<?php

session_start();
// Solo admins con sesión iniciada; cualquier otro vuelve al login.
if (($_SESSION['usuario']['rol'] ?? '') !== 'admin') {
    header('Location: /utpl/php/views/login/login.php');
    exit;
}

require_once __DIR__ . '/../../clases/usuario.php';
require_once __DIR__ . '/../../clases/roles.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$admin = $_SESSION['usuario'];
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel de administración</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@600&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="/utpl/login/css/styles.css" />
    <link rel="stylesheet" href="/utpl/php/css/admin.css" />
</head>

<body class="admin">
    <?php require __DIR__ . '/topbar/topbar.php'; ?>
    <?php require __DIR__ . '/navbar/navbar.php'; ?>
    <?php require __DIR__ . '/main/main.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script src="/utpl/php/js/admin.js"></script>
</body>

</html>
