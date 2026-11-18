<nav class="admin__navbar" aria-label="Menú de administración">
    <div class="admin__nav-links">

        <a href="/utpl/php/views/admin/index.php" class="admin__nav-link admin__nav-link--active" aria-current="page">
            <i data-lucide="users" aria-hidden="true"></i>
            <span>Usuarios</span>
        </a>
        <button type="button" class="admin__nav-link" data-accion="registrar">
            <i data-lucide="user-plus" aria-hidden="true"></i>
            <span>Registrar usuario</span>
        </button>
    </div>
    <div class="admin__nav-logout">
        <a href="/utpl/php/views/data/logout.php" class="admin__logout">
            <i data-lucide="log-out" aria-hidden="true"></i>
            <span>Cerrar sesión</span>
        </a>
    </div>
</nav>