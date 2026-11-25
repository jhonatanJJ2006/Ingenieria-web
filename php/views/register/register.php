<?php

require_once __DIR__ . '/../../db/conexion.php';

$enviado = false;
$error = '';

$talleres_precios = [
    'Taller de seguridad' => 150,
    'Taller de arduino' => 350,
    'Taller de sensores' => 200,
    'Taller de App movil' => 250,
];

$precio_registro_curso = 120;

$precio_tipo_inscripcion = [
    'UTPL' => 120,
    'Docentes' => 130,
    'Externos' => 150,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombres'])) {
    $dato = static function (string $campo): string {
        return trim((string) ($_POST[$campo] ?? ''));
    };

    $cursos = ['Programación', 'Diseño', 'Marketing'];
    $inscripciones = ['UTPL', 'Docentes', 'Externos'];
    $talleresPermitidos = [
        'Taller de seguridad',
        'Taller de arduino',
        'Taller de sensores',
        'Taller de App movil',
    ];
    $elegidos = array_values(array_intersect(
        $talleresPermitidos,
        array_map('strval', (array) ($_POST['taller'] ?? []))
    ));

    $registro = [
        'nombres' => $dato('nombres'),
        'apellidos' => $dato('apellidos'),
        'cedula' => $dato('cedula'),
        'ruc' => $dato('ruc'),
        'fecha' => $dato('fecha'),
        'pais' => $dato('pais'),
        'factura_nombre' => $dato('factura_nombre'),
        'factura_ruc' => $dato('factura_ruc'),
        'factura_direccion' => $dato('factura_direccion'),
        'factura_correo' => $dato('factura_correo'),
        'factura_telefono' => $dato('factura_telefono'),
        'tipo_evento' => $dato('tipo_evento'),
        'tipo_inscripcion' => $dato('tipo_inscripcion'),
        'talleres' => implode(', ', $elegidos),
    ];

    $precio_inscripcion = $precio_tipo_inscripcion[$registro['tipo_inscripcion']];
    $precio_talleres = array_sum(array_map(function ($taller) use ($talleres_precios) {
        return $talleres_precios[$taller];
    }, $elegidos));
    $precio_total = $precio_inscripcion + $precio_talleres + $precio_registro_curso;

    $obligatorios = $registro;
    unset($obligatorios['ruc'], $obligatorios['talleres']);

    if (in_array('', $obligatorios, true)) {
        $error = 'Completa los campos obligatorios.';
    } elseif (!filter_var($registro['factura_correo'], FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo de la factura no es válido.';
    } elseif (
        !in_array($registro['tipo_evento'], $cursos, true)
        || !in_array($registro['tipo_inscripcion'], $inscripciones, true)
        || $elegidos === []
    ) {
        $error = 'Elige un curso, un tipo de inscripción y al menos un taller.';
    } else {
        try {
            $stmt = db_conectar()->prepare(
                'INSERT INTO registro (
                    nombres, apellidos, cedula, ruc, fecha, pais,
                    factura_nombre, factura_ruc, factura_direccion, factura_correo, factura_telefono,
                    tipo_evento, tipo_inscripcion, talleres, factura_total
                ) VALUES (
                    :nombres, :apellidos, :cedula, :ruc, :fecha, :pais,
                    :factura_nombre, :factura_ruc, :factura_direccion, :factura_correo, :factura_telefono,
                    :tipo_evento, :tipo_inscripcion, :talleres, :factura_total
                )'
            );
            $registro['ruc'] = $registro['ruc'] === '' ? null : $registro['ruc'];
            $registro['factura_total'] = $precio_total;
            $stmt->execute($registro);
            $enviado = true;
            $_POST = [];
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000'
                ? 'Ya existe un registro con esa cédula.'
                : 'No se pudo guardar el registro. Revisa que MySQL esté activo.';
        }
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro para el evento</title>
    <link rel="stylesheet" href="/utpl/php/views/register/css/styles.css" />
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
                <a href="/utpl/examen_parcial_1/index.php">Inicio</a>
                <a href="#conferencias">conferencias</a>
                <a href="/utpl/php/views/detalle/index.php" aria-current="page">Buscador</a>
                <a href="/utpl/php/views/register/register.php">Registro</a>
                <a href="#contacto">Contactos</a>
            </nav>
        </div>
    </header>

    <main class="wrap page">
        <h1>Registro para el evento</h1>
        <?php if ($enviado) : ?>
            <p class="aviso" role="status">Registro guardado. Conserva tus datos de factura para el pago.</p>
        <?php elseif ($error !== '') : ?>
            <p class="aviso aviso--error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php require __DIR__ . '/form/formulario.php'; ?>
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