<?php
// Tabla de usuarios reutilizable. Espera: $exito, $usuarios, $mensaje y la función e().
// Opcional: $acciones = true muestra los botones Editar/Eliminar (solo el panel admin).
$acciones = $acciones ?? false;
?>
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
                    <?php if ($acciones) : ?>
                        <th>Acciones</th>
                    <?php endif; ?>
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
                        <?php if ($acciones) : ?>
                            <td>
                                <div class="usuarios__acciones">
                                    <button type="button" class="usuarios__accion usuarios__accion--editar"
                                        data-accion="editar"
                                        data-id="<?= e((string) $u['id']) ?>"
                                        data-nombre="<?= e($u['nombre']) ?>"
                                        data-email="<?= e($u['email']) ?>"
                                        data-rol="<?= e(strtolower($u['rol'])) ?>"
                                        data-edad="<?= e((string) ($u['edad'] ?? '')) ?>"
                                        data-telefono="<?= e($u['telefono'] ?? '') ?>"
                                        data-activo="<?= (int) $u['activo'] ?>"
                                        aria-label="Editar a <?= e($u['nombre']) ?>">
                                        <i data-lucide="pencil" aria-hidden="true"></i>
                                        <span>Editar</span>
                                    </button>
                                    <button type="button" class="usuarios__accion usuarios__accion--eliminar"
                                        data-accion="eliminar"
                                        data-id="<?= e((string) $u['id']) ?>"
                                        data-nombre="<?= e($u['nombre']) ?>"
                                        aria-label="Eliminar a <?= e($u['nombre']) ?>">
                                        <i data-lucide="trash-2" aria-hidden="true"></i>
                                        <span>Eliminar</span>
                                    </button>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
