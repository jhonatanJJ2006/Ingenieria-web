<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/utpl/login/css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,600&family=Open+Sans:ital,wght@0,400;0,600&display=swap"
      rel="stylesheet"
    />
    <title>Login</title>
  </head>
  <body>
    <main>
      <section class="container__login">
        <h1 class="login__title">Login APP</h1>
        <form action="/utpl/php/datos.php" method="post" class="form__login">
          <div class="form__login-container">
            <label for="email" class="form__login-label">Email</label>
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
                autocomplete="current-password"
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
            <div class="form__login-remember-container">
              <input
                type="checkbox"
                id="remember"
                name="remember"
                class="form__login-remember-input"
              />
              <label for="remember" class="form__login-remember-label"
                >Remember me</label
              >
            </div>
          </div>
          <input type="hidden" name="submit" value="1" />
          <button type="submit" class="form__login-button">Login</button>
          <div class="form__login-separator">
            <a href="/utpl/login/register.php" class="form__login-link">Sign up</a>
            <div class="form__login-separator-line"></div>
            <a href="#" class="form__login-link">Forgot password?</a>
          </div>
        </form>
      </section>
    </main>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/utpl/login/js/visible.js"></script>
  </body>
</html>
