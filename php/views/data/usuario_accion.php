<?php

session_start();
require_once __DIR__ . '/../../clases/usuario.php';

$panel = '/utpl/php/views/admin/index.php';

if (($_SESSION['usuario']['rol'] ?? '') !== 'admin') {
    header('Location: /utpl/php/views/login/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $panel);
    exit;
}

// Token anti-CSRF: el formulario debe venir del propio panel.
if (!hash_equals($_SESSION['csrf'] ?? '', (string) ($_POST['csrf'] ?? ''))) {
    $_SESSION['flash'] = ['ok' => false, 'mensaje' => 'La sesión expiró. Vuelve a intentarlo.'];
    header('Location: ' . $panel);
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$esUnoMismo = $id === $_SESSION['usuario']['id'];

switch ($_POST['accion'] ?? '') {
    case 'editar':
        $rol = (string) ($_POST['rol'] ?? '');
        $activo = isset($_POST['activo']);

        // El admin no puede quitarse su propio rol ni desactivarse: perdería el acceso al panel.
        if ($esUnoMismo && ($rol !== 'admin' || !$activo)) {
            $resultado = ['ok' => false, 'mensaje' => 'No puedes quitarte el rol de admin ni desactivar tu propia cuenta.'];
            break;
        }

        $resultado = usuario_actualizar(
            $id,
            (string) ($_POST['nombre'] ?? ''),
            (string) ($_POST['email'] ?? ''),
            $rol,
            (string) ($_POST['edad'] ?? ''),
            (string) ($_POST['telefono'] ?? ''),
            $activo
        );

        if ($resultado['ok'] && $esUnoMismo) {
            $_SESSION['usuario']['nombre'] = trim((string) $_POST['nombre']);
            $_SESSION['usuario']['email'] = trim(strtolower((string) $_POST['email']));
        }
        break;

    case 'eliminar':
        $resultado = $esUnoMismo
            ? ['ok' => false, 'mensaje' => 'No puedes eliminar tu propia cuenta.']
            : usuario_eliminar($id);
        break;

    default:
        $resultado = ['ok' => false, 'mensaje' => 'Acción no válida.'];
}

$_SESSION['flash'] = $resultado;
header('Location: ' . $panel);
exit;
