<?php

require_once __DIR__ . '/../php/db/roles.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$roles = roles_catalogo();
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,600&family=Open+Sans:ital,wght@0,400;0,600&display=swap"
      rel="stylesheet"
    />
    <title>Registro</title>
  </head>
  <body>
    <main>
      <section class="container__login">
        <h1 class="login__title">Crear cuenta</h1>
        <form action="/utpl/php/register.php" method="post" class="form__login">
          <div class="form__login-container">
            <label for="nombre" class="form__login-label">Nombre</label>
            <div class="form__login-field">
              <span class="form__login-icon" aria-hidden="true">
                <i data-lucide="user"></i>
              </span>
              <input
                type="text"
                id="nombre"
                name="nombre"
                class="form__login-input"
                placeholder="Tu nombre"
                autocomplete="name"
                required
              />
            </div>
          </div>
          <div class="form__login-container">
            <label for="email" class="form__login-label">Correo</label>
            <div class="form__login-field">
              <span class="form__login-icon" aria-hidden="true">
                <i data-lucide="mail"></i>
              </span>
              <input
                type="email"
                id="email"
                name="email"
                class="form__login-input"
                placeholder="correo@ejemplo.com"
                autocomplete="email"
                required
              />
            </div>
          </div>
          <div class="form__login-container">
            <label for="password" class="form__login-label">Password</label>
            <div class="form__login-field">
              <span class="form__login-icon" aria-hidden="true">
                <i data-lucide="lock"></i>
              </span>
              <input
                type="password"
                id="password"
                name="password"
                class="form__login-input form__login-input--password"
                placeholder="Tu contraseña"
                autocomplete="new-password"
                required
              />
              <button
                type="button"
                class="form__login-toggle"
                id="toggle-password"
                aria-label="Mostrar contraseña"
              >
                <i data-lucide="eye"></i>
              </button>
            </div>
          </div>
          <div class="form__login-container form__login-container--rol">
            <label id="rol-label" class="form__login-label">Rol en el sistema</label>
            <input type="hidden" name="rol" id="rol" value="usuario" required />
            <div class="form__login-rol-wrap" data-rol-wrap>
            <div class="form__login-field">
              <span class="form__login-icon" aria-hidden="true">
                <i data-lucide="shield"></i>
              </span>
              <div class="form__login-rol-picker" data-rol-picker>
                <button
                  type="button"
                  class="form__login-rol-trigger"
                  id="rol-trigger"
                  aria-expanded="false"
                  aria-haspopup="listbox"
                  aria-labelledby="rol-label rol-trigger-label"
                >
                  <span id="rol-trigger-label" class="form__login-rol-trigger-label">Usuario</span>
                  <span class="form__login-rol-chevron" aria-hidden="true">
                    <i data-lucide="chevron-down"></i>
                  </span>
                </button>
              </div>
            </div>
            <ul
              class="form__login-rol-list"
              id="rol-list"
              role="listbox"
              aria-labelledby="rol-label"
              hidden
            >
              <?php foreach ($roles as $valor => $etiqueta) : ?>
                <li class="form__login-rol-item" role="none">
                  <button
                    type="button"
                    class="form__login-rol-option<?= $valor === 'usuario' ? ' is-selected' : '' ?>"
                    role="option"
                    aria-selected="<?= $valor === 'usuario' ? 'true' : 'false' ?>"
                    data-value="<?= e($valor) ?>"
                    data-label="<?= e($etiqueta) ?>"
                  >
                    <?= e($etiqueta) ?>
                  </button>
                </li>
              <?php endforeach; ?>
            </ul>
            </div>
          </div>
          <input type="hidden" name="submit" value="1" />
          <button type="submit" class="form__login-button">Registrarse</button>
          <div class="form__login-separator">
            <a href="/utpl/php/login.php" class="form__login-link">¿Ya tienes cuenta? Login</a>
          </div>
        </form>
      </section>
    </main>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="js/visible.js"></script>
    <script src="js/roles.js"></script>
  </body>
</html>
