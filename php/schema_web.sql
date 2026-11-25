-- Base de datos para el proyecto UTPL/web
CREATE DATABASE IF NOT EXISTS web
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE web;

DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS registro (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombres VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  cedula VARCHAR(20) NOT NULL,
  ruc VARCHAR(20) NULL,
  fecha VARCHAR(20) NOT NULL,
  pais VARCHAR(100) NOT NULL,
  factura_nombre VARCHAR(150) NOT NULL,
  factura_ruc VARCHAR(20) NOT NULL,
  factura_direccion VARCHAR(255) NOT NULL,
  factura_correo VARCHAR(150) NOT NULL,
  factura_telefono VARCHAR(20) NOT NULL,
  factura_total DECIMAL(10, 2) NOT NULL,
  tipo_evento VARCHAR(50) NOT NULL,
  tipo_inscripcion VARCHAR(50) NOT NULL,
  talleres VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_registro_cedula (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
