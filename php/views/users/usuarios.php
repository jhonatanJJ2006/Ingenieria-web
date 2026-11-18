<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /utpl/php/views/login/login.php');
    exit;
}

require_once __DIR__ . '/../../clases/usuario.php';
require_once __DIR__ . '/../../clases/roles.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$resultado = usuario_all();
$exito = $resultado['ok'];
$usuarios = $resultado['usuarios'] ?? [];
$mensaje = $resultado['mensaje'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Usuarios registrados</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@600&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="/utpl/login/css/styles.css" />
</head>

<body>
    <main>
        <section class="container__usuarios">
            <header class="usuarios__header">
                <h1 class="login__title usuarios__title">Usuarios en la BD</h1>
                <p class="usuarios__count">
                    <?= $exito ? count($usuarios) . ' registro(s)' : 'Sin datos' ?>
                </p>
            </header>

            <?php require __DIR__ . '/tabla.php'; ?>

            <nav class="usuarios__nav form__login-separator">
                <a href="/utpl/php/views/data/logout.php" class="form__login-link">Cerrar sesión</a>
            </nav>
        </section>
    </main>
</body>

</html>
