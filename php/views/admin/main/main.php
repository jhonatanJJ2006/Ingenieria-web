<?php
$resultado = usuario_all();
$exito = $resultado['ok'];
$usuarios = $resultado['usuarios'] ?? [];
$mensaje = $resultado['mensaje'] ?? '';
?>
<main class="admin__main">
    <header class="admin__main-header">
        <h1 class="admin__title">Usuarios</h1>
        <p class="usuarios__count"><?= $exito ? count($usuarios) . ' registro(s)' : 'Sin datos' ?></p>
    </header>
    <?php require __DIR__ . '/../../users/tabla.php'; ?>
</main>
