-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 23-11-2026 a las 15:42:22
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plexa_core`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `silogtran_pqrs`
--

CREATE TABLE `silogtran_pqrs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `radicado` varchar(30) NOT NULL,
  `nit_cliente` varchar(20) DEFAULT NULL,
  `tipo` varchar(20) NOT NULL,
  `categoria` varchar(60) NOT NULL,
  `prioridad` varchar(10) NOT NULL DEFAULT 'Media',
  `nombre` varchar(150) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `manifiesto` varchar(50) DEFAULT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `adjunto` varchar(255) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Radicado',
  `respuesta` text DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `silogtran_pqrs`
--

INSERT INTO `silogtran_pqrs` (`id`, `radicado`, `nit_cliente`, `tipo`, `categoria`, `prioridad`, `nombre`, `tipo_documento`, `documento`, `email`, `telefono`, `manifiesto`, `asunto`, `descripcion`, `adjunto`, `estado`, `respuesta`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'PQRS-P-20261118-0001', NULL, 'Peticion', 'Plataforma Silogtran', 'Media', 'Roys Rafael Simarra Gomez', 'CC', '1041972451', 'roisroisomg@gmail.com', '3245145599', 'e', 'eeeew', 'ffeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', NULL, 'Radicado', NULL, '1041972451', '2026-11-18 15:58:39', '2026-11-18 15:58:39'),
(2, 'PQRS-Q-20261118-0002', NULL, 'Queja', 'Plataforma Silogtran', 'Alta', 'Roys Rafael Simarra Gomez', 'CC', '1041972451', 'roisroisomg@gmail.com', '3245145599', NULL, 'qqqqqqqqqqqqqqqqqqqqqqqqw', 'qdwqwdqwdqwdqwdqwdqwr', NULL, 'Radicado', NULL, '1041972451', '2026-11-18 17:26:39', '2026-11-18 17:26:39'),
(3, 'PQRS-S-20261118-0003', NULL, 'Sugerencia', 'Operación de transporte', 'Media', 'Henry', 'CC', '104789101112', 'henrycastro@gmail.com', '1234567891011', '102365426587', 'prueba', 'tu sabes como es, haz limonada y comprate un pan de 500', NULL, 'Radicado', NULL, '1041972451', '2026-11-18 17:29:07', '2026-11-18 17:29:07');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `silogtran_pqrs`
--
ALTER TABLE `silogtran_pqrs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `silogtran_pqrs_radicado_unique` (`radicado`),
  ADD KEY `silogtran_pqrs_nit_cliente_index` (`nit_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `silogtran_pqrs`
--
ALTER TABLE `silogtran_pqrs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
