<?php

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/roles.php';

function usuario_hash_password(string $passwordPlano): string
{
    return md5($passwordPlano);
}

function usuario_existe_nombre_o_email(string $nombre, string $email): bool
{
    $pdo = db_conectar();
    $stmt = $pdo->prepare(
        'SELECT id FROM user
         WHERE email = :email OR LOWER(nombre) = LOWER(:nombre)
         LIMIT 1'
    );
    $stmt->execute([
        ':email' => $email,
        ':nombre' => $nombre,
    ]);

    return (bool) $stmt->fetch();
}

/**
 * @return array{ok: bool, mensaje: string}
 */
function usuario_registrar(string $nombre, string $email, string $passwordPlano, string $rol): array
{
    $nombre = trim($nombre);
    $email = trim(strtolower($email));
    $rol = strtolower(trim($rol));

    if ($nombre === '') {
        return ['ok' => false, 'mensaje' => 'El nombre es obligatorio.'];
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'mensaje' => 'El correo no es válido.'];
    }

    if ($passwordPlano === '') {
        return ['ok' => false, 'mensaje' => 'La contraseña es obligatoria.'];
    }

    if (!in_array($rol, roles_valores_permitidos(), true)) {
        return ['ok' => false, 'mensaje' => 'El rol seleccionado no es válido.'];
    }

    if (usuario_existe_nombre_o_email($nombre, $email)) {
        return ['ok' => false, 'mensaje' => 'El nombre o el correo ya están registrados.'];
    }

    try {
        $pdo = db_conectar();
        $sql = 'INSERT INTO user (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => usuario_hash_password($passwordPlano),
            ':rol' => $rol,
        ]);

        return ['ok' => true, 'mensaje' => 'Usuario creado correctamente. Ya puedes iniciar sesión.'];
    } catch (PDOException) {
        return [
            'ok' => false,
            'mensaje' => 'No se pudo registrar. Revisa que MySQL esté activo.',
        ];
    }
}

/**
 * @return array{ok: bool, mensaje: string}
 */
function usuario_verificar_login(string $email, string $passwordPlano): array
{
    $email = trim(strtolower($email));

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'mensaje' => 'El correo no es válido.'];
    }

    if ($passwordPlano === '') {
        return ['ok' => false, 'mensaje' => 'La contraseña es obligatoria.'];
    }

    try {
        $pdo = db_conectar();
        $stmt = $pdo->prepare('SELECT id, password FROM user WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $fila = $stmt->fetch();

        if (!$fila) {
            return ['ok' => false, 'mensaje' => 'No existe un usuario con ese correo.'];
        }

        if (!hash_equals($fila['password'], usuario_hash_password($passwordPlano))) {
            return ['ok' => false, 'mensaje' => 'Contraseña incorrecta.'];
        }

        return ['ok' => true, 'mensaje' => 'Inicio de sesión correcto.'];
    } catch (PDOException) {
        return [
            'ok' => false,
            'mensaje' => 'No se pudo validar el login. Revisa que MySQL esté activo.',
        ];
    }
}

function usuario_all(): array
{
    try {
        $pdo = db_conectar();

        $sql = 'SELECT id, nombre, email, password, rol, edad, telefono, activo, created_at, updated_at
                FROM user
                ORDER BY id DESC';
        $stmt = $pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['ok' => true, 'usuarios' => $usuarios];
    } catch (PDOException) {
        return [
            'ok' => false,
            'mensaje' => 'No se pudo obtener los usuarios.',
        ];
    }
}
