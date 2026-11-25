<?php
$val = static function (string $campo): string {
    return htmlspecialchars(trim((string) ($_POST[$campo] ?? '')), ENT_QUOTES, 'UTF-8');
};
$talleresElegidos = array_map('strval', (array) ($_POST['taller'] ?? []));
?>
<form class="registro" action="/utpl/php/views/register/register.php" method="post">
    <div class="registro-grid">
        <div class="col">
            <label for="nombres">Nombres</label>
            <input id="nombres" name="nombres" type="text" value="<?= $val('nombres') ?>" required />

            <label for="apellidos">Apellidos</label>
            <input id="apellidos" name="apellidos" type="text" value="<?= $val('apellidos') ?>" required />

            <label for="cedula">Cédula</label>
            <input id="cedula" name="cedula" type="text" inputmode="numeric" value="<?= $val('cedula') ?>" required />

            <label for="ruc">RUC</label>
            <input id="ruc" name="ruc" type="text" inputmode="numeric" value="<?= $val('ruc') ?>" />

            <label for="fecha">Fecha</label>
            <input id="fecha" name="fecha" type="text" value="<?= $val('fecha') ?>" required />

            <label for="pais">País</label>
            <input id="pais" name="pais" type="text" value="<?= $val('pais') ?>" required />
        </div>

        <fieldset class="col">
            <legend>Datos de la factura</legend>

            <label for="factura_nombre">Nombre</label>
            <input id="factura_nombre" name="factura_nombre" type="text" value="<?= $val('factura_nombre') ?>" required />

            <label for="factura_ruc">RUC</label>
            <input id="factura_ruc" name="factura_ruc" type="text" inputmode="numeric" value="<?= $val('factura_ruc') ?>" required />

            <label for="factura_direccion">Dirección</label>
            <input id="factura_direccion" name="factura_direccion" type="text" value="<?= $val('factura_direccion') ?>" required />

            <label for="factura_correo">Correo</label>
            <input id="factura_correo" name="factura_correo" type="email" value="<?= $val('factura_correo') ?>" required />

            <label for="factura_telefono">Teléfono</label>
            <input id="factura_telefono" name="factura_telefono" type="tel" value="<?= $val('factura_telefono') ?>" required />
        </fieldset>
    </div>

    <div class="registro-grid registro-grid--detalle">
        <fieldset class="col">
            <legend>Detalle del evento</legend>

            <label for="tipo_evento">Registro a cursos ($120)</label>
            <select id="tipo_evento" name="tipo_evento" required>
                <option value="" <?= $val('tipo_evento') === '' ? 'selected' : '' ?> disabled>Selecciona</option>
                <?php foreach (['Programación', 'Diseño', 'Marketing'] as $opcion) : ?>
                    <option <?= $val('tipo_evento') === $opcion ? 'selected' : '' ?>><?= $opcion ?></option>
                <?php endforeach; ?>
            </select>

            <label for="tipo_inscripcion">Tipo de inscripción (UTPL $120 - Docentes $130 - Externos $150)</label>
            <select id="tipo_inscripcion" name="tipo_inscripcion" required>
                <option value="" <?= $val('tipo_inscripcion') === '' ? 'selected' : '' ?> disabled>Selecciona</option>
                <?php foreach (['UTPL', 'Docentes', 'Externos'] as $opcion) : ?>
                    <option <?= $val('tipo_inscripcion') === $opcion ? 'selected' : '' ?>><?= $opcion ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <fieldset class="col">
            <legend>Registro a talleres</legend>
            <?php
            $pagos = [
                'Taller de seguridad' => 'Taller de seguridad ($150)',
                'Taller de arduino' => 'Taller de arduino ($350)',
                'Taller de sensores' => 'Taller de sensores ($200)',
                'Taller de App movil' => 'Taller de App movil ($250)',
            ];
            foreach ($pagos as $valor => $etiqueta) :
            ?>
                <label class="radio">
                    <input type="checkbox" name="taller[]" value="<?= htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') ?>" <?= in_array($valor, $talleresElegidos, true) ? 'checked' : '' ?> />
                    <?= $etiqueta ?>
                </label>
            <?php endforeach; ?>
        </fieldset>
    </div>

    <button type="submit">Registrarme</button>
</form>