<?php

require_once __DIR__ . '/db/usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email'])) {
    header('Location: /utpl/php/login.php');
    exit;
}

$email = $_POST['email'] ?? '';
$passwordPlano = $_POST['password'] ?? '';

$resultado = usuario_verificar_login($email, $passwordPlano);

if ($resultado['ok']) {
    header('Location: /utpl/php/usuarios.php');
    exit;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$mensaje = $resultado['mensaje'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
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
            <h1 class="login__title">Login</h1>
            <div class="form__login form__login-result">
                <p class="form__login-db-msg form__login-db-msg--error"><?= e($mensaje) ?></p>
                <div class="form__login-result-actions form__login-separator">
                    <a href="/utpl/php/login.php" class="form__login-link">Volver al login</a>
                    <a href="/utpl/login/register.php" class="form__login-link">Crear cuenta</a>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
