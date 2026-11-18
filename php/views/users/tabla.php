<?php
// Tabla de usuarios reutilizable. Espera: $exito, $usuarios, $mensaje y la función e().
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
