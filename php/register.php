<?php

require_once __DIR__ . '/db/usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /utpl/login/register.php');
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$passwordPlano = $_POST['password'] ?? '';
$rol = $_POST['rol'] ?? 'usuario';

$resultado = usuario_registrar($nombre, $email, $passwordPlano, $rol);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$exito = $resultado['ok'];
$mensaje = $resultado['mensaje'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@600&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="/utpl/login/css/styles.css" />
</head>

<body>
    <main>
        <section class="container__login">
            <h1 class="login__title">Registro</h1>
            <div class="form__login form__login-result">
                <p class="form__login-db-msg<?= $exito ? ' form__login-db-msg--ok' : ' form__login-db-msg--error' ?>">
                    <?= e($mensaje) ?>
                </p>
                <div class="form__login-result-actions form__login-separator">
                    <?php if ($exito) : ?>
                        <a href="/utpl/php/login.php" class="form__login-link">Ir al login</a>
                    <?php else : ?>
                        <a href="/utpl/login/register.php" class="form__login-link">Intentar de nuevo</a>
                        <a href="/utpl/php/login.php" class="form__login-link">Ir al login</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
