<?php

require_once __DIR__ . '/../../clases/usuario.php';

$buscado = false;
$registro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula'])) {
    $buscado = true;
    $registro = usuario_get_cedula((string) $_POST['cedula']);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buscador de registros</title>
    <link rel="stylesheet" href="/utpl/php/views/register/css/styles.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="/utpl/php/views/Detalle/css/styles.css">
</head>

<body>
    <header class="site-header">
        <div class="wrap header-bar">
            <a class="brand" href="/utpl/examen_parcial_1/index.php">
                <svg class="brand-mark" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" fill="#e8f6e2" />
                    <path d="M24 6a18 18 0 0 1 0 36 18 18 0 0 1 0-36z" fill="#3aa0e8" />
                    <path d="M24 6a18 18 0 0 0 0 36V6z" fill="#5dac39" />
                    <ellipse cx="24" cy="24" rx="8" ry="18" fill="none" stroke="#fff" stroke-width="1.6" />
                    <path d="M8 24h32M11 16h26M11 32h26" fill="none" stroke="#fff" stroke-width="1.4" />
                </svg>
                <span class="brand-text">
                    <strong>SOFTWARE <span>UTPL</span></strong>
                    <small>Ingeniería en Software</small>
                </span>
            </a>
            <ul class="header-contacts">
                <li>
                    <span class="contact-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M7 3h3l1.5 4-2 1.5a12 12 0 0 0 6 6L17 13l4 1.5V18a2 2 0 0 1-2 2A15 15 0 0 1 4 5a2 2 0 0 1 2-2z" />
                        </svg>
                    </span>
                    (07) 370 1444
                </li>
                <li>
                    <span class="contact-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 6h18v12H3z" fill="none" stroke="#fff" stroke-width="2" />
                            <path d="M3 7l9 7 9-7" />
                        </svg>
                    </span>
                    info@utpl.edu.ec
                </li>
                <li>
                    <span class="contact-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2a7 7 0 0 1 7 7c0 5-7 13-7 13S5 14 5 9a7 7 0 0 1 7-7zm0 9.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
                        </svg>
                    </span>
                    Loja, Ecuador
                </li>
            </ul>
        </div>
        <div class="wrap">
            <nav class="main-nav" aria-label="Principal">
                <a href="/utpl/php/views/Inicio/index.php">Inicio</a>
                <a href="#certificados">Certificados</a>
                <a href="/utpl/php/views/register/register.php" aria-current="page">Registro</a>
                <a href="#eventos">Eventos</a>
                <a href="#contacto">Contactos</a>
            </nav>
        </div>
    </header>

    <main class="wrap page h-100">
        <h1>Detalle del registro</h1>

        <div class="detalle-registro">

            <form class="form-detalle" action="/utpl/php/views/Detalle/index.php" method="post">
                <div class="form-group">
                    <label for="cedula">CEDULA</label>
                    <div class="form-group-buscador">
                        <input type="number" name="cedula" id="cedula" required placeholder="Ingrese la cedula para buscar el registro" class="form-control">
                        <button type="submit" class="btn-buscador"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
                <button class="btn-buscador-2" type="submit">Buscar</button>
            </form>

            <?php if ($buscado && !$registro) : ?>
                <p class="aviso aviso--error" role="alert">No hay un registro con esa cédula.</p>
            <?php elseif ($registro) :
                $ruc = trim((string) $registro['ruc']);
                $talleres = array_values(array_filter(array_map('trim', explode(',', (string) $registro['talleres']))));
                $total = number_format((float) $registro['factura_total'], 2);
            ?>
                <article class="ficha">
                    <header class="ficha-cabeza">
                        <p>Registro</p>
                        <h2><?= e($registro['nombres'] . ' ' . $registro['apellidos']) ?></h2>
                        <span>Cédula <?= e($registro['cedula']) ?> · <?= e($registro['fecha']) ?> · <?= e($registro['pais']) ?></span>
                    </header>

                    <div class="ficha-grid">
                        <section>
                            <h3>Datos personales</h3>
                            <dl>
                                <dt>Nombres</dt>
                                <dd><?= e($registro['nombres']) ?></dd>
                                <dt>Apellidos</dt>
                                <dd><?= e($registro['apellidos']) ?></dd>
                                <dt>Cédula</dt>
                                <dd><?= e($registro['cedula']) ?></dd>
                                <dt>RUC</dt>
                                <dd><?= $ruc === '' ? '—' : e($ruc) ?></dd>
                            </dl>
                        </section>
                        <section>
                            <h3>Datos de la factura</h3>
                            <dl>
                                <dt>Nombre</dt>
                                <dd><?= e($registro['factura_nombre']) ?></dd>
                                <dt>RUC</dt>
                                <dd><?= e($registro['factura_ruc']) ?></dd>
                                <dt>Dirección</dt>
                                <dd><?= e($registro['factura_direccion']) ?></dd>
                                <dt>Correo</dt>
                                <dd><?= e($registro['factura_correo']) ?></dd>
                                <dt>Teléfono</dt>
                                <dd><?= e($registro['factura_telefono']) ?></dd>
                            </dl>
                        </section>
                    </div>

                    <section class="ficha-evento">
                        <h3>Detalle del evento</h3>
                        <dl>
                            <dt>Curso</dt>
                            <dd><?= e($registro['tipo_evento']) ?></dd>
                            <dt>Inscripción</dt>
                            <dd><?= e($registro['tipo_inscripcion']) ?></dd>
                            <dt>Talleres</dt>
                            <dd>
                                <?php if ($talleres === []) : ?>
                                    —
                                <?php else : ?>
                                    <ul>
                                        <?php foreach ($talleres as $taller) : ?>
                                            <li><?= e($taller) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </section>

                    <footer class="ficha-total">
                        <span>Total</span>
                        <strong>$ <?= e($total) ?></strong>
                    </footer>
                </article>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer" id="contacto">
        <div class="footer-bar">
            <div class="footer-brand">
                <small>Universidad Técnica Particular de Loja</small>
                <strong>UTPL</strong>
            </div>
            <ul class="socials">
                <li><a href="https://www.facebook.com/utpl" aria-label="Facebook" target="_blank" rel="noopener">f</a></li>
                <li><a href="https://twitter.com/utpl" aria-label="Twitter" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 5.8a8 8 0 0 1-2.4.7 4.1 4.1 0 0 0 1.8-2.3 8.2 8.2 0 0 1-2.6 1 4.1 4.1 0 0 0-7 3.7A11.6 11.6 0 0 1 3 4.9a4.1 4.1 0 0 0 1.3 5.5 4 4 0 0 1-1.9-.5v.1a4.1 4.1 0 0 0 3.3 4 4 4 0 0 1-1.9.1 4.1 4.1 0 0 0 3.8 2.9A8.2 8.2 0 0 1 2 18.4 11.6 11.6 0 0 0 8.3 20c7.5 0 11.6-6.2 11.6-11.6v-.5A8.3 8.3 0 0 0 22 5.8z" />
                        </svg>
                    </a></li>
                <li><a href="https://www.instagram.com/utpl" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="4" y="4" width="16" height="16" rx="4" fill="none" stroke="#fff" stroke-width="2" />
                            <circle cx="12" cy="12" r="3.5" fill="none" stroke="#fff" stroke-width="2" />
                            <circle cx="17.2" cy="6.8" r="1" />
                        </svg>
                    </a></li>
                <li><a href="https://www.youtube.com/utpl" aria-label="YouTube" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 12.2s0-3.2-.4-4.6a3 3 0 0 0-2.1-2.1C17.9 5 12 5 12 5s-5.9 0-7.5.5a3 3 0 0 0-2.1 2.1C2 9 2 12.2 2 12.2s0 3.2.4 4.6a3 3 0 0 0 2.1 2.1C6.1 19.4 12 19.4 12 19.4s5.9 0 7.5-.5a3 3 0 0 0 2.1-2.1c.4-1.4.4-4.6.4-4.6zM10 15.5v-6.6l5.2 3.3-5.2 3.3z" />
                        </svg>
                    </a></li>
            </ul>
            <div class="footer-contact">
                <strong>Contacto</strong>
                <span>info@utpl.edu.ec</span>
                <span>(07) 370 1444</span>
                <span>San Cayetano Alto, Loja</span>
            </div>
        </div>
    </footer>
</body>

</html>