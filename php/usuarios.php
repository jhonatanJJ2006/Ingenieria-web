<?php

require_once __DIR__ . '/db/usuario.php';
require_once __DIR__ . '/db/roles.php';

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

            <?php if (!$exito) : ?>
                <p class="form__login-db-msg form__login-db-msg--error"><?= e($mensaje) ?></p>
            <?php elseif (count($usuarios) === 0) : ?>
                <p class="usuarios__empty">Aún no hay usuarios. Regístralos desde el login.</p>
            <?php else : ?>
                <div class="usuarios__table-wrap">
                    <table class="usuarios__table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Password (MD5)</th>
                                <th>Rol</th>
                                <th>Edad</th>
                                <th>Teléfono</th>
                                <th>Activo</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u) : ?>
                                <tr>
                                    <td><?= e((string) $u['id']) ?></td>
                                    <td><?= e($u['nombre']) ?></td>
                                    <td><?= e($u['email']) ?></td>
                                    <td class="usuarios__hash" title="<?= e($u['password']) ?>">
                                        <?= e(substr($u['password'], 0, 12)) ?>…
                                    </td>
                                    <td>
                                        <span class="<?= e(rol_clase_css($u['rol'])) ?>"><?= e(rol_etiqueta($u['rol'])) ?></span>
                                    </td>
                                    <td><?= e($u['edad'] !== null ? (string) $u['edad'] : '—') ?></td>
                                    <td><?= e($u['telefono'] ?? '—') ?></td>
                                    <td><?= (int) $u['activo'] === 1 ? 'Sí' : 'No' ?></td>
                                    <td><?= e($u['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <nav class="usuarios__nav form__login-separator">
                <a href="/utpl/php/login.php" class="form__login-link">Ir al login</a>
            </nav>
        </section>
    </main>
</body>

</html>