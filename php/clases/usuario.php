<?php

require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/roles.php';

function usuario_hash_password(string $passwordPlano): string
{
    return md5($passwordPlano);
}

/** GETS Y SETS */

function usuario_get_cedula(string $cedula): array|false
{
    $stmt = db_conectar()->prepare('SELECT * FROM registro WHERE cedula = :cedula LIMIT 1');
    $stmt->execute([':cedula' => trim($cedula)]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/** 
 * @return array{ok: bool, mensaje: string}
 */

/** $excluirId: id que no cuenta como duplicado (el propio usuario al editar). */
function usuario_existe_nombre_o_email(string $nombre, string $email, int $excluirId = 0): bool
{
    $pdo = db_conectar();
    $stmt = $pdo->prepare(
        'SELECT id FROM user
         WHERE (email = :email OR LOWER(nombre) = LOWER(:nombre)) AND id <> :excluir
         LIMIT 1'
    );
    $stmt->execute([
        ':email' => $email,
        ':nombre' => $nombre,
        ':excluir' => $excluirId,
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
 * @return array{ok: bool, mensaje: string, usuario?: array{id: int, nombre: string, email: string, rol: string}}
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
        $stmt = $pdo->prepare('SELECT id, nombre, email, rol, password FROM user WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $fila = $stmt->fetch();

        if (!$fila) {
            return ['ok' => false, 'mensaje' => 'No existe un usuario con ese correo.'];
        }

        if (!hash_equals($fila['password'], usuario_hash_password($passwordPlano))) {
            return ['ok' => false, 'mensaje' => 'Contraseña incorrecta.'];
        }

        return [
            'ok' => true,
            'mensaje' => 'Inicio de sesión correcto.',
            'usuario' => [
                'id' => (int) $fila['id'],
                'nombre' => $fila['nombre'],
                'email' => $fila['email'],
                'rol' => strtolower($fila['rol']),
            ],
        ];
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

/**
 * Edita los datos de un usuario (no la contraseña). Edad y teléfono vacíos se guardan como NULL.
 *
 * @return array{ok: bool, mensaje: string}
 */
function usuario_actualizar(int $id, string $nombre, string $email, string $rol, string $edad, string $telefono, bool $activo): array
{
    $nombre = trim($nombre);
    $email = trim(strtolower($email));
    $rol = strtolower(trim($rol));
    $edad = trim($edad);
    $telefono = trim($telefono);

    if ($id <= 0) {
        return ['ok' => false, 'mensaje' => 'Usuario no válido.'];
    }

    if ($nombre === '' || mb_strlen($nombre) > 100) {
        return ['ok' => false, 'mensaje' => 'El nombre es obligatorio (máx. 100 caracteres).'];
    }

    if ($email === '' || mb_strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'mensaje' => 'El correo no es válido.'];
    }

    if (!in_array($rol, roles_valores_permitidos(), true)) {
        return ['ok' => false, 'mensaje' => 'El rol seleccionado no es válido.'];
    }

    if ($edad !== '' && filter_var($edad, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]) === false) {
        return ['ok' => false, 'mensaje' => 'La edad debe ser un número entre 1 y 120.'];
    }

    if ($telefono !== '' && !preg_match('/^[0-9+\-\s()]{6,20}$/', $telefono)) {
        return ['ok' => false, 'mensaje' => 'El teléfono no es válido (6 a 20 dígitos, +, -, espacios o paréntesis).'];
    }

    try {
        if (usuario_existe_nombre_o_email($nombre, $email, $id)) {
            return ['ok' => false, 'mensaje' => 'El nombre o el correo ya están en uso por otro usuario.'];
        }

        $stmt = db_conectar()->prepare(
            'UPDATE user
             SET nombre = :nombre, email = :email, rol = :rol, edad = :edad, telefono = :telefono, activo = :activo
             WHERE id = :id'
        );
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':rol' => $rol,
            ':edad' => $edad === '' ? null : (int) $edad,
            ':telefono' => $telefono === '' ? null : $telefono,
            ':activo' => $activo ? 1 : 0,
            ':id' => $id,
        ]);

        return ['ok' => true, 'mensaje' => 'Usuario actualizado correctamente.'];
    } catch (PDOException) {
        return ['ok' => false, 'mensaje' => 'No se pudo actualizar el usuario. Revisa que MySQL esté activo.'];
    }
}

/**
 * @return array{ok: bool, mensaje: string}
 */
function usuario_eliminar(int $id): array
{
    if ($id <= 0) {
        return ['ok' => false, 'mensaje' => 'Usuario no válido.'];
    }

    try {
        $stmt = db_conectar()->prepare('DELETE FROM user WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() === 1
            ? ['ok' => true, 'mensaje' => 'Usuario eliminado correctamente.']
            : ['ok' => false, 'mensaje' => 'El usuario ya no existe.'];
    } catch (PDOException) {
        return ['ok' => false, 'mensaje' => 'No se pudo eliminar el usuario. Revisa que MySQL esté activo.'];
    }
}
