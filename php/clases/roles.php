<?php

/**
 * @return array<string, string> valor en BD => etiqueta visible
 */
function roles_catalogo(): array
{
    return [
        'usuario' => 'Usuario',
        'editor' => 'Editor',
        'admin' => 'Administrador',
    ];
}

/** @return list<string> */
function roles_valores_permitidos(): array
{
    return array_keys(roles_catalogo());
}

function rol_etiqueta(string $rol): string
{
    $rol = strtolower(trim($rol));
    $catalogo = roles_catalogo();

    return $catalogo[$rol] ?? ucfirst($rol);
}

function rol_clase_css(string $rol): string
{
    $rol = strtolower(trim($rol));
    $permitidos = ['usuario', 'editor', 'admin', 'moderador'];

    return in_array($rol, $permitidos, true)
        ? 'usuarios__badge usuarios__badge--' . $rol
        : 'usuarios__badge';
}
