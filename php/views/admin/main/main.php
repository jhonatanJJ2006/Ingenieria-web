<?php
$resultado = usuario_all();
$exito = $resultado['ok'];
$usuarios = $resultado['usuarios'] ?? [];
$mensaje = $resultado['mensaje'] ?? '';
$acciones = true;

// Mensaje de la última acción (editar/eliminar); se muestra una sola vez.
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<main class="admin__main">
    <header class="admin__main-header">
        <h1 class="admin__title">Usuarios</h1>
        <p class="usuarios__count"><?= $exito ? count($usuarios) . ' registro(s)' : 'Sin datos' ?></p>
    </header>

    <?php if ($flash) : ?>
        <p class="admin__flash <?= $flash['ok'] ? 'admin__flash--ok' : 'admin__flash--error' ?>" role="<?= $flash['ok'] ? 'status' : 'alert' ?>">
            <?= e($flash['mensaje']) ?>
        </p>
    <?php endif; ?>

    <?php require __DIR__ . '/../../users/tabla.php'; ?>
</main>

<dialog class="modal" id="modal-editar" aria-labelledby="modal-editar-titulo">
    <form method="post" action="/utpl/php/views/data/usuario_accion.php" class="modal__form">
        <h2 class="modal__title" id="modal-editar-titulo">Editar usuario</h2>
        <input type="hidden" name="accion" value="editar" />
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>" />
        <input type="hidden" name="id" />

        <label class="modal__field">
            <span>Nombre</span>
            <input type="text" name="nombre" maxlength="100" required />
        </label>
        <label class="modal__field">
            <span>Email</span>
            <input type="email" name="email" maxlength="150" required />
        </label>
        <label class="modal__field">
            <span>Rol</span>
            <select name="rol" required>
                <?php foreach (roles_catalogo() as $valor => $etiqueta) : ?>
                    <option value="<?= e($valor) ?>"><?= e($etiqueta) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <div class="modal__row">
            <label class="modal__field">
                <span>Edad</span>
                <input type="number" name="edad" min="1" max="120" inputmode="numeric" />
            </label>
            <label class="modal__field">
                <span>Teléfono</span>
                <input type="tel" name="telefono" maxlength="20" pattern="[0-9+\-\s()]{6,20}" />
            </label>
        </div>
        <label class="modal__check">
            <input type="checkbox" name="activo" />
            <span>Cuenta activa</span>
        </label>

        <div class="modal__actions">
            <button type="button" class="modal__btn" data-cerrar>Cancelar</button>
            <button type="submit" class="modal__btn modal__btn--primary">Guardar cambios</button>
        </div>
    </form>
</dialog>

<dialog class="modal modal--sm" id="modal-eliminar" aria-labelledby="modal-eliminar-titulo" aria-describedby="modal-eliminar-texto">
    <form method="post" action="/utpl/php/views/data/usuario_accion.php" class="modal__form">
        <div class="modal__icon" aria-hidden="true"><i data-lucide="triangle-alert"></i></div>
        <h2 class="modal__title" id="modal-eliminar-titulo">¿Seguro que quieres realizar esta acción?</h2>
        <p class="modal__text" id="modal-eliminar-texto">
            Vas a eliminar a <strong data-nombre></strong>. Esta acción no se puede deshacer.
        </p>
        <input type="hidden" name="accion" value="eliminar" />
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>" />
        <input type="hidden" name="id" />

        <div class="modal__actions">
            <button type="button" class="modal__btn" data-cerrar autofocus>Cancelar</button>
            <button type="submit" class="modal__btn modal__btn--danger">Sí, eliminar</button>
        </div>
    </form>
</dialog>
