<header class="admin__topbar">
    <img src="/utpl/php/img/logo.png" alt="UTPL" class="admin__logo">
    <div class="admin__user">
        <span class="admin__user-name"><?= e($_SESSION['usuario']['nombre']) ?></span>
        <span class="<?= e(rol_clase_css($_SESSION['usuario']['rol'])) ?>"><?= e(rol_etiqueta($_SESSION['usuario']['rol'])) ?></span>
    </div>
</header>