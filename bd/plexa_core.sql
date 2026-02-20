-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 20-02-2026 a las 12:32:00
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
-- Estructura de tabla para la tabla `1_carrusel`
--

CREATE TABLE `1_carrusel` (
  `id` int(11) NOT NULL,
  `imagen` text NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `boton` varchar(30) DEFAULT NULL,
  `orden` int(2) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cedula` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `1_carrusel`
--

INSERT INTO `1_carrusel` (`id`, `imagen`, `titulo`, `subtitulo`, `url`, `boton`, `orden`, `estado`, `fecha`, `cedula`) VALUES
(1, 'http://127.0.0.1:8000/imagenes_carrusel/1769517831_VID_20260122_213015.mp4', 'ENERGÍA QUE AVANZA', 'Mediante una logística oportuna, transportamos los Líquidos y Gases Combustibles que se transforman en la Energía para su proceso productivo.', '#', 'Leer Más', 1, 1, '2025-12-30 09:25:00', 1047418839),
(2, 'http://plexa.co/wp-content/uploads/2025/04/DJI_0719-scaled.jpg', 'Comercialización', 'En la Comercialización, Plexa brinda la Solución Energética que su negocio necesita. Desde la fuente hasta el punto de entrega.', 'comerci', 'Leer Más', 3, 1, '2025-12-30 11:05:51', 1047418839),
(3, 'http://plexa.co/wp-content/uploads/2025/04/DJI_0706-scaled.jpg', 'Logística', 'OPERACIÓN LOGÍSTICA La extensa trayectoria en el desarrollo del mercado de Gas Licuado del Petróleo (GLP)', 'logistica', 'Leer Más', 4, 1, '2025-12-30 11:10:17', 1047418839),
(4, 'http://plexa.co/wp-content/uploads/2025/04/plexaport.jpg', 'Plexaport', 'Capacidad de almacenamiento por 1.080.000 GL de propano, isobutano, butano ', 'plexaport', 'Leer Más', 5, 1, '2025-12-30 11:10:17', 1047418839),
(5, 'https://yegoecot.com/wp-content/uploads/2024/01/AND09802.jpg', 'Yego Eco-T', 'Yego Ecot es una empresa visionaria dedicada al transporte masivo y semimasivo en Colombia. Con una flota propia de vehículos a GNV, lideramos la transición hacia combustibles amigables con el medio ambiente. Descubre nuestra historia, compromiso y visión', 'https://yegoecot.com/', 'Leer Más', 6, 1, '2025-12-30 11:12:29', 1047418839),
(6, 'https://yego.com.co/wp-content/uploads/2024/12/DSC01368-1.jpg', 'Yego AutoGLP', 'Beneficios de transformarte a AutoGLP · 35% de ahorro frente al combustible tradicional.', 'https://yego.com.co/', 'Leer Más', 7, 1, '2025-12-30 11:12:29', 1047418839),
(7, 'http://plexa.co/wp-content/uploads/2026/01/plexachilefondo.png', 'Chile', 'Mediante una logística oportuna, transportamos los Líquidos y Gases Combustibles que se transforman en la Energía para su proceso productivo.', 'chile', 'Leer Más', 2, 1, '2025-12-30 09:25:00', 1047418839),
(9, 'http://127.0.0.1:8000/imagenes_carrusel/1769175676_imagen__7_.jpg', 'prueba', 'solucion', '#', 'Leer Más', 10, 0, '2026-01-23 08:12:16', 1041972451);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `2_precios_glp`
--

CREATE TABLE `2_precios_glp` (
  `id` int(11) NOT NULL,
  `archivo_pdf` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `2_precios_glp`
--

INSERT INTO `2_precios_glp` (`id`, `archivo_pdf`, `fecha_inicio`, `fecha_fin`, `user_id`, `estado`) VALUES
(1, '1 Enero de 2026', '2026-01-20', '2026-02-20', 1041972451, 1),
(26, '1769173397_1_Enero_de_2026.pdf', '2026-01-23', '2026-02-23', 1041972451, 1),
(27, '1769174903_10_Marzo_2026.pdf', '2026-01-23', '2026-02-23', 1041972451, 1),
(28, '1769175375_1_Enero_de_2026.pdf', '2026-01-23', '2026-02-23', 1041972451, 1),
(29, '10_Marzo_2026.pdf', '2026-01-23', '2026-02-23', 1041972451, 1),
(30, '1_Enero_de_2026.pdf', '2026-01-23', '2026-02-23', 1041972451, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `3_tickets`
--

CREATE TABLE `3_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `beneficiario_nombre` varchar(255) DEFAULT NULL,
  `beneficiario_cedula` varchar(255) DEFAULT NULL,
  `beneficiario_fecha_nac` date DEFAULT NULL,
  `origen` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `tipo_viaje` varchar(20) DEFAULT 'ida',
  `fecha_viaje` date NOT NULL,
  `fecha_regreso` date DEFAULT NULL,
  `archivo_tikete` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `3_tickets`
--

INSERT INTO `3_tickets` (`id`, `user_id`, `beneficiario_nombre`, `beneficiario_cedula`, `beneficiario_fecha_nac`, `origen`, `destino`, `tipo_viaje`, `fecha_viaje`, `fecha_regreso`, `archivo_tikete`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(30, '1041972451', NULL, NULL, NULL, 'Cartagena', 'Francia', 'Ida y Vuelta', '2026-01-30', '2026-02-06', '1769782822_TIK_30.pdf', '.......', 0, '2026-01-30 13:55:16', '2026-01-30 14:21:01'),
(31, '1041972451', NULL, NULL, NULL, 'bogotaa', 'alemania', 'Ida y Vuelta', '2026-02-20', '2026-02-26', '1770307978_TIK_31.pdf', 'descanso', 1, '2026-02-05 16:11:59', '2026-02-05 16:12:58'),
(32, '1041972451', NULL, NULL, NULL, 'bogotaa', 'rusia', 'Ida y Vuelta', '2026-02-02', '2026-02-17', NULL, ',.', 0, '2026-02-09 16:07:52', '2026-02-09 16:55:08'),
(33, '1041972451', NULL, NULL, NULL, 'Francia', 'PIE DE PEPE', 'Ida y Vuelta', '2026-02-24', '2026-02-27', NULL, 'Prueba', 0, '2026-02-12 17:54:50', '2026-02-13 14:38:59'),
(34, '1047418839', NULL, NULL, NULL, 'Francia', 'Japon', 'Ida y Vuelta', '2026-02-13', '2026-02-20', NULL, 'Prueba', 0, '2026-02-13 14:18:21', '2026-02-16 13:10:47'),
(35, '1041972451', NULL, NULL, NULL, 'Cartagena', 'Medellin', 'Solo Ida', '2026-02-20', NULL, NULL, '..', 0, '2026-02-19 17:39:11', '2026-02-19 17:39:37'),
(36, '1041972451', NULL, NULL, NULL, 'Cartagena', 'Barranca', 'Solo Ida', '2026-02-20', NULL, NULL, '...', 2, '2026-02-19 17:42:08', '2026-02-19 17:42:08'),
(37, '1041972451', 'JUAN', '45789654123', '2026-02-19', 'Cartagena', 'Barranca', 'Ida y Vuelta', '2026-02-20', '2026-02-23', NULL, 'vacaciones', 2, '2026-02-19 18:07:56', '2026-02-19 18:07:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

CREATE TABLE `conductores` (
  `cedula` int(10) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `celular` varchar(30) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `estado` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`cedula`, `nombre`, `apellido`, `email`, `celular`, `tipo`, `estado`) VALUES
(-73574852, 'CESAR LUIS', 'NORIEGA ACOSTA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(55555, 'HUMBERTO', 'BALLESTA', NULL, NULL, 'CONDUCTOR', 1),
(321654, 'LUIS EDUARDO', 'FONSECA OCHOA', NULL, NULL, 'CONDUCTOR', 1),
(796659, 'MIGUEL ', 'SUAREZ', NULL, NULL, 'CONDUCTOR', 0),
(2231760, 'PEDRO JAVIER', 'ORTIZ VELASQUEZ', NULL, NULL, 'CONDUCTOR', 1),
(2746928, 'DIEGO NARVAEZ VERA ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(3090225, 'MIGUEL ARCANGEL', 'MALAVER SANDOVAL ', NULL, NULL, 'CONDUCTOR', 1),
(3108779, 'Daniel Humberto', 'González gaitá', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(3131731, 'EGLY TOVAR MAHECHA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(3142992, 'GABRIEL ANGEL', 'DIAZ CHACON', NULL, NULL, 'CONDUCTOR', 1),
(3199802, 'JUAN GEOVANY', 'MARTINEZ MUÑETON', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(3199828, 'JOSE RAUL SARMIENTO GARCIA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(3214467, 'HECTOR MANUEL', 'BEJARANO BEJARANO', NULL, NULL, 'CONDUCTOR', 1),
(3649478, 'Tulio Mario', 'Hernandez', 'tuliomariohernandez@hotmail.com', NULL, 'CONDUCTOR', 1),
(3906344, 'EINAR ENRIQUE', 'RUIZ PAJARO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(4234942, 'LUIS ALBERTO', 'RAMIREZ PARRA', NULL, NULL, 'CONDUCTOR', 1),
(4255448, ' Carlos Arturo Toncon Sepulved', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(4277739, 'Jose Mauricio Corredor Molano', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(4292864, 'Luis Alfredo ', 'Merchan Medina', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(4438199, 'DUVIER ARLEX', 'MOLINA MARIN', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(4932735, 'ORLANDO MOTTA', NULL, NULL, NULL, 'CONDUCTOR', 1),
(5031572, 'JAINER FERNEY', 'PEREZ HERNANDEZ', NULL, NULL, 'CONDUCTOR', 1),
(5031893, 'REINALDO ', 'DIAZ GALVIS', 'RDIAZGALVIS@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(5046861, 'VICTOR MANUEL', 'GUERRERO MORA', NULL, NULL, 'CONDUCTOR', 1),
(5136157, 'DIOGENES', 'ARRIETA GAMEZ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(5470706, 'CARLOS DANIEL', 'PARADA LEON', 'Carlosdani08@ook.com', NULL, 'CONDUCTOR', 1),
(5622048, 'JORGE EDUARDO', 'BAEZ VASQUEZ', 'Jorbava_70@hotmail.com', NULL, 'CONDUCTOR', 1),
(5625653, 'HERMES', 'BAUTISTA NARANJO', NULL, NULL, 'CONDUCTOR', 1),
(5670802, 'ARNULFO', 'ORTEGA', NULL, '6292026', 'CONDUCTOR', 1),
(5671853, 'HECTOR ', 'SILVA VESGA', 'hs4007821@gmail.com', NULL, 'CONDUCTOR', 1),
(5784803, 'CESAR EDMUNDO', 'RIOS ESTEVEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(5796972, 'FRANCISCO JAVIER', 'OTERO QUIJANO', 'FRANJOTERO@HOTMAIL.COM', NULL, 'CONDUCTOR', 1),
(5823969, 'LUIS CAMILO ', 'LEON NARANJO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(5874457, 'Edwin Ramírez Betancourt', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(6013826, 'RUBEN', 'LOPEZ SALINAS', NULL, '6292026', 'CONDUCTOR', 1),
(6030613, 'JORGE ENRIQUE PINZON BAQUERO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(6392524, 'Diego Seid Ramirez  Garciaz', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(6776158, 'HERNAN REYES SUAREZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(6801594, 'FERNANDO', 'BEJARANO PULIDO', NULL, NULL, 'CONDUCTOR', 1),
(6889035, 'ANIBAL MANUEL', 'AYAZOS ORTEGA', NULL, NULL, 'CONDUCTOR', 1),
(7141438, 'JOSE GREGORIO', 'ARRIETA PEREZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7142979, 'JOSE MIGUEL', 'SANCHEZ LLINAZ', NULL, NULL, 'CONDUCTOR', 1),
(7166152, 'HECTOR', 'LOPEZ CARDENAS', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(7185266, 'William Uriel ', 'Torres González', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7217414, 'VICTOR', 'SENON TORRES', NULL, NULL, 'CONDUCTOR', 1),
(7229281, 'FRANKLIN', 'PRIETO', 'franklinprieto0173@gmail.com', NULL, 'CONDUCTOR', 1),
(7229295, 'HERNANDO', 'NIÑO LEON', NULL, NULL, 'CONDUCTOR', 1),
(7252406, 'CARLOS ALBERTO', 'BASTIDAS ORTIZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7253539, 'Jhon Fredy', 'Toro Puertas', NULL, NULL, 'CONDUCTOR', 1),
(7253623, 'JORGE ENRIQUE', 'RIVILLA ROMERO', NULL, NULL, 'CONDUCTOR', 1),
(7254172, 'GONZALO', 'BERMUDEZ PERDOMO', NULL, NULL, 'CONDUCTOR', 1),
(7254468, 'CARLOS JULIO', 'MURILLO BLANDON ', 'carlosjuliomurilloblandon@gmail.com', NULL, 'CONDUCTOR', 1),
(7335882, 'Néstor Sánchez Junco', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7595145, 'LUIS BELTRAN ACOSTA ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7604581, 'CRISTIAN', 'RUEDA ESTUPIÑAN', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7632698, 'JORGE ISAAC', 'TORRES MONTERROSA', NULL, NULL, 'CONDUCTOR', 1),
(7634810, 'HUMBERTO RAFAEL', 'BALLESTAS MESTRE', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(7706609, 'EDINSON', 'MUÑOZ', NULL, NULL, 'CONDUCTOR', 1),
(7777777, 'JOSÉ GREGORIO', 'CASTRO CASTELLARES', NULL, NULL, 'CONDUCTOR', 1),
(8012504, 'César Augusto', 'Osorio Lopera ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(8505416, 'CARLOS JAVIER', 'CARMONA VILLA', NULL, '6292026', 'CONDUCTOR', 1),
(8506504, 'luis ', 'polo orozco', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(8566715, 'DEYMAR JOSÉ', 'RESTREPO OTÁLVARO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(8782571, 'VALTIÑO', 'SARAVIA GOMEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(8854538, 'Roger', 'Altamiranda Jimenez', NULL, NULL, 'CONDUCTOR', 1),
(8870664, 'Javier Fernando', 'Arrellano Castillo', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9097264, 'JORGE ISAAC', 'MORELO SANCHEZ', NULL, NULL, 'CONDUCTOR', 1),
(9102498, 'SILVIO M', 'REDONDE GUZMAN', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(9287153, 'EMILIO DE JESUS', 'AGUIRRE VERGARA', NULL, NULL, 'CONDUCTOR', 1),
(9288165, 'BENJAMIN', 'MENDOZA RUIZ ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(9291667, 'MARIO CESAR', 'MENDEZ MIELES ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9293622, 'Sidrad', 'Suarez Segovia', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9293754, 'LUIS CARLOS', 'LAMBIS SALCEDO', NULL, '6292026', 'CONDUCTOR', 1),
(9295754, 'CHRISTIAN GERONIMO', 'OCHOA CHAMORRO', NULL, NULL, 'CONDUCTOR', 1),
(9298436, 'CESAR AUGUSTO', 'ELLES TEHERAN', 'CAETJR@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(9434731, 'Carlos  Julio Guzman Rodriguez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9527974, 'Jose Cosme Rodriguez Hernande', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9694292, 'MARIO FERNANDO ALONSO BUSTOS', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9738009, 'Johnatan Caicedo Rojas', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(9876807, 'LISANDER RAFAEL', 'GUERRA GUTIERREZ', NULL, '6292026', 'CONDUCTOR', 1),
(9976131, 'FABIO NELSON', 'MOSQUERA DUQUE', NULL, NULL, 'CONDUCTOR', 1),
(10168389, 'JOSE DEL CARMEN', 'HURTADO IBAÑEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(10174039, 'JOSE RAMIRO', 'ROMERO BUSTOS', NULL, NULL, 'CONDUCTOR', 1),
(10541669, 'WILLIAM', 'DUARTE ZUÑIGA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(10951921, 'JOSE GUZMAN ROMERO', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(11201633, 'Edwin Gonzalez Leon', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(11230312, 'FELIX SANIN', 'PEÑA AGUILERA', NULL, NULL, 'CONDUCTOR', 1),
(11231505, 'ANTONIO MARIA', 'PULIDO SALCEDO', NULL, '6292026', 'CONDUCTOR', 1),
(11231966, 'LUIS', 'ALMANZA AVELLANEDA', NULL, '6292026', 'CONDUCTOR', 1),
(11297522, 'Manuel Vicente ', 'Melo Lozano', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(11304869, 'HECTOR', 'GARRIDO GONZALEZ', NULL, '6292026', 'CONDUCTOR', 1),
(11342377, 'SANTIAGO ANTONIO ', 'VERDUGO PAEZ', NULL, NULL, 'CONDUCTOR', 1),
(11348208, 'RAMIRO', 'VARGAS TARAZONA ', 'knegrette@plexa.co', NULL, 'CONDUCTOR', 1),
(11386412, 'ORLANDO', 'CABALLERO ROMERO', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(11436552, 'WILMER HORACIO', 'BURITICA GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(11439049, 'JULIO CESAR', 'CHAPARRO SANCHEZ', NULL, NULL, 'CONDUCTOR', 1),
(11441168, 'FÉLIX ARNALDO', 'ORTIZ BENITEZ', NULL, NULL, 'CONDUCTOR', 1),
(11442548, 'HENRY OSWALDO', 'LOPEZ VILLADIEGO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(11519583, 'Pedro  Jairo', 'Morales Vanegas', 'jairom_117@hotmail.com', NULL, 'CONDUCTOR', 1),
(12195315, 'Wilson ', 'Garavito Calderón', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(12279270, 'Abla Gastón Triana', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(12282009, 'JORGE HUMBERTO', 'MEDINA SALAZAR', NULL, NULL, 'CONDUCTOR', 1),
(12458681, 'RODOLFO LIZCANO GÓMEZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(12558801, 'DAIRO ALFONSO', 'MARTINEZ CUELLO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(12634026, 'JOSE AMAVIL', 'GUAL MOLINA', NULL, NULL, 'CONDUCTOR', 1),
(12642656, 'CASTULO MIGUEL CORREA SOTO', NULL, 'correacastulo@gmail.com', NULL, 'CONDUCTOR', 1),
(12643681, 'EZEQUIEL', 'CONTRERA ENSUNCHU', NULL, NULL, 'CONDUCTOR', 1),
(12685412, 'Edinson Manuel', 'Jimenez Castillo', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13175607, 'YAMID ALFONSO ', 'VARGAS CABRALES ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13443958, 'JOSE DANIEL', 'CARRILLO CONTRERAS', 'jdcc140459@outlook.es', NULL, 'CONDUCTOR', 1),
(13483232, 'ROQUE JULIO', 'PORTILLA CASTELLANOS', NULL, NULL, 'CONDUCTOR', 1),
(13489704, 'LUIS', 'CADENA CONTRERA', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(13498407, 'JAIRO ALFONSO', 'GALVIS SANCHEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13511055, 'GERARDO ALEXANDER', 'ORDOÑEZ SANDOVAL', NULL, NULL, 'CONDUCTOR', 1),
(13514682, 'JOSE BERCELY', 'GALEANO GALVIS', NULL, '6292026', 'CONDUCTOR', 1),
(13535671, 'OSCAR', 'SERRANO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13565482, 'CARLOS IGNACIO', 'ARANGO DE LA TORRE', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13566096, 'Jorge Wilman', 'Morales Rincón ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13566671, 'ALEXANDER', 'TORRES BRUGES', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13568151, 'ARMANDO', 'QUINTERO CARREÑO', NULL, '6292026', 'CONDUCTOR', 1),
(13570671, 'Jason', 'Orozco Bedoya ', NULL, NULL, 'CONDUCTOR', 1),
(13570821, 'MARIO FERNADO', 'PEREZ FLORES', NULL, NULL, 'CONDUCTOR', 1),
(13636316, 'FRANCISCO', 'MUÑOZ ARIZA', NULL, NULL, 'CONDUCTOR', 0),
(13702896, 'JUAN JIOVANNI', 'FLOREZ ARCHILA', NULL, NULL, 'CONDUCTOR', 1),
(13703630, 'FREDY VICENTE', 'FLOREZ ARCHILA', NULL, NULL, 'CONDUCTOR', 1),
(13715248, 'EDWIN RENE', 'HERRERA HERNANDEZ', NULL, '6292026', 'CONDUCTOR', 1),
(13717641, 'LUIS ALBERTO', 'PAEZ DIAZ', 'betopaezd@gmail.com', '6292026', 'CONDUCTOR', 1),
(13717900, 'FABIO', 'BELLO ABELLO', NULL, NULL, 'CONDUCTOR', 1),
(13720518, 'JUAN DE DIOS', 'GARCIA GALVIS', 'garciagalvisjuan@gmail.com', '6292026', 'CONDUCTOR', 1),
(13742446, 'JOHN RODRIGO', 'CUADROS ALVAREZ', NULL, NULL, 'CONDUCTOR', 1),
(13743294, 'JHON ALEXANDER', 'GELVEZ AYALA', NULL, '6292026', 'CONDUCTOR', 1),
(13744770, 'Román Heberto Sanabria Barrera', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13839315, 'OLIMPO LOPEZ RIVERA', NULL, 'myepes@plexa.co', NULL, 'CONDUCTOR', 1),
(13850035, 'JAIME', 'ARRIETA URIETA', 'arrietaurietajaime@gmail.com', '6292026', 'CONDUCTOR', 1),
(13854469, 'LEONARDO ', 'MARTINEZ TELLEZ', 'LEONARDOMARTINEZQUINTERO1982@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(13860626, 'JHON JAIRO', 'LIZCANO ALDANA', NULL, NULL, 'CONDUCTOR', 1),
(13865398, 'EDWIN ', 'TORRES GARCIA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(13873345, 'NOLBERTO', 'MANTILLA VALENZUELA', NULL, NULL, 'CONDUCTOR', 1),
(13874415, 'ROBERTO', 'RANGEL ROMERO', NULL, '6292026', 'CONDUCTOR', 1),
(13885218, 'NABOR', 'FORERO DUARTE', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(14236171, 'LUIS CARLOS', 'VARON ACOSTA', NULL, NULL, 'CONDUCTOR', 1),
(14320709, 'Luis Alberto Lozano Rodriguez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(14565310, 'CESAR AUGUSTO', 'PESCADOR RAMIREZ', NULL, NULL, 'CONDUCTOR', 1),
(14896361, 'JAIRO ALBERTO', 'DIAZ RIAÑO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(15174695, 'JAIDER', 'GARCIA PEÑA', 'jaidergarcia2045@gmail.com', NULL, 'CONDUCTOR', 0),
(15303242, 'EULICES COBOS MEJIA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(15430439, 'OSCAR', 'RESTREPO GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(15666607, 'JORGE LUIS ', 'TORRES ARGUELLO', NULL, NULL, 'CONDUCTOR', 1),
(16076757, 'John Henry maya taborda ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(16266790, 'HOOVER', 'URIBE RIOS', NULL, NULL, 'CONDUCTOR', 1),
(16268225, 'CARLOS JULIO CORREA SOTO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(16897421, 'MOISES', 'GONZALEZ', 'Moises850429@gmail.com', NULL, 'CONDUCTOR', 1),
(17324520, 'JOSE ALVARO', 'RODRIGUEZ CASALLAS', NULL, NULL, 'CONDUCTOR', 1),
(17339134, 'EDGAR', 'RODRIGUEZ FLOREZ', NULL, NULL, 'CONDUCTOR', 1),
(17339853, 'Jesus  Ivan Ramirez Alturo', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(17354768, 'JOSE FIDEL', 'SARAY GONZALEZ', 'f.gonzaper11@gmail.com', '6292026', 'CONDUCTOR', 1),
(17356497, 'NORBEY ', 'MARIN JUANIAS', NULL, NULL, 'CONDUCTOR', 1),
(17390108, 'WILFRED', 'ROJAS ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(17586190, 'FREDY', 'MANZANO', NULL, '6292026', 'CONDUCTOR', 1),
(17959303, 'JAINER', 'DAZA PARODI', NULL, NULL, 'CONDUCTOR', 1),
(18393845, 'Leonardo fabio ', 'Mosquera Rojas', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(18403225, 'Jhonathan Arenas Sepúlveda', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(18904331, 'Edgar Alfonso Ramírez Suárez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(18922620, 'JAIME RAFAEL', 'GAVIRIA CHINCHILLA', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(18923068, 'EDGAR ', 'CALDERON ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(18925833, 'Fernández Vega', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(18929486, 'FERNANDO', 'GUTIERREZ CHONA', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(18971324, 'LIBARDO DE JESUS ', 'MENESES MARIN', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(18972875, 'ALVARO MENESES', NULL, '2PDW32E4D@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(19158115, 'JAIRO', 'TEJADA TORRES', NULL, NULL, 'CONDUCTOR', 1),
(19401156, 'ALBERTO', 'SEGURA CASTILLO', NULL, '6292026', 'CONDUCTOR', 1),
(19442269, 'NESTOR', 'BENAVIDEZ GUIO', NULL, '6292026', 'CONDUCTOR', 1),
(19462946, 'JUAN CARLOS', 'ARAQUE BETANCOURT', NULL, NULL, 'CONDUCTOR', 1),
(19619448, 'JOSE MANUEL', 'ORTEGA ALVAREZ', NULL, NULL, 'CONDUCTOR', 1),
(19619767, 'Alexander jesus charris Vargas', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(19708277, 'BALVIR DE JESUS', 'LARIOS HERNADEZ ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(55555555, 'JUAN JOSE', 'CASTAÑEDA VARGAS', NULL, NULL, 'CONDUCTOR', 1),
(70113368, 'JUAN ANTONIO', 'PANIAGUA YEPES', NULL, NULL, 'CONDUCTOR', 1),
(71940291, 'Marcelino amorocho ladinez ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(72001619, 'MANUEL POSADA', NULL, 'MANUEL.POSADA32@HOTMAIL.COM', NULL, 'CONDUCTOR', 1),
(72146726, 'MOISES', 'CARREÑO OLARTE', 'MOISESCO_@HOTMAIL.COM', '6292026', 'CONDUCTOR', 1),
(72155628, 'ALFREDO JOSE', 'ESCORCIA CIENFUEGOS', 'alfredo-escorcia@hotmail.com', NULL, 'CONDUCTOR', 1),
(72163213, 'YORK ENRIQUE', 'ROMERO DEL VALLE', NULL, NULL, 'CONDUCTOR', 1),
(72172977, 'ROBERTO CARLOS', 'STEEVENSON SICILIANO', NULL, NULL, 'CONDUCTOR', 1),
(72178093, 'DAGOBERTO', 'RODRIGUEZ MALDONADO', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(72214240, 'MANUEL ENRRIQUE', 'ROMERO ALONSO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(72233606, 'ALEXANDER', 'GUTIERREZ CARRILLO', 'alexandercarrillo434@gmail.com', NULL, 'CONDUCTOR', 1),
(72262869, 'Carlos Pineda', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(72296263, 'JOHAN SEBASTIAN SEGURA ESPINOS', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(72325997, 'CARLOS ORLANDO BARON ARIAS', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73006586, 'ARNEL JOSE', 'DOMINGUEZ MERLANO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73125738, 'NARCISO JOSE', 'MORALES', NULL, NULL, 'CONDUCTOR', 1),
(73128698, 'PEDRO', 'GARCIA CABALLERO', NULL, '6292026', 'CONDUCTOR', 1),
(73129032, 'CESAR ', 'CABARCAS MORALES ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(73130729, 'Rodrigo Arturo Herrera Anderso', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73131516, 'JORGE LUIS', 'ORTEGA OSPINA', NULL, NULL, 'CONDUCTOR', 1),
(73132908, 'MARLON', 'SOTO PEREZ', NULL, '6292026', 'CONDUCTOR', 1),
(73145342, 'EDINSON ', 'LOPEZ OZUNA ', NULL, NULL, 'CONDUCTOR', 1),
(73149718, 'LUIS EDINSON', 'BELEÑO CARRILLO', 'EDINSONCARRILLO1310@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(73151399, 'EDWIN ', 'BELTRAN BELTRAN', 'CTGBELTRANB@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(73156450, 'joaquin guillermo ', 'diaz caseres', 'joacodiaz1972@gmail.com', NULL, 'CONDUCTOR', 1),
(73157599, 'LEXSON ', 'PALENCIA HERNANDEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73158545, 'Juan Carlos correa olivo ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73160232, 'YADER YADID', 'PORTO PAUTT', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73164605, 'Yoni Segundo Mercado Rodríguez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73167659, 'BERNARDO', 'RICO INCER', 'ricoincerbernardo@gmail.com', '6292026', 'CONDUCTOR', 1),
(73183836, 'HUGUER', 'CABEZA ESCANDON ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(73194441, 'MARIANO', 'NUÑEZ VELEZ', 'marianonunezvelez@gmail.com', NULL, 'CONDUCTOR', 1),
(73196055, 'RONALD DAVID ', 'GAMBOA BETTIN ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73208749, 'ARNULFO ', 'FIGUEROA CASTRO', 'ARNULFO4507@HOTMAIL.COM', NULL, 'CONDUCTOR', 1),
(73215066, 'MIGUEL', 'SUAREZ', NULL, NULL, 'CONDUCTOR', 1),
(73230691, 'ELICER RAFAEL', 'POSSO JARAMILLO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73572477, 'RODOLFO MANUEL', 'JARABA MANJAREZ', NULL, NULL, 'CONDUCTOR', 1),
(73574852, 'Cesar Luis Noriega Acosta', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73575214, 'ROGER SOTO MIRANDA ', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(73579202, 'ENEIS', 'SANTANA PINEDA', 'ENEISSANTANAPINEDA@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(73580643, 'OSWALDO', 'SARMIENTO RUIZ', 'josc1031@gmail.com', '6292026', 'CONDUCTOR', 1),
(73593708, 'IDELBER', 'PEREZ BARRERA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(73594504, 'PEDRO HERNADEZ MEJIA ', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(73594998, 'JULIO CESAR', 'BUSTOS ALVARADO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74083857, 'Oscar Preciado Alarcon', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74183130, 'Jorge alberto', 'Orrego', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74352373, 'Humberto  Dueñas Rojas', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74359139, 'JOSE GELVER', 'GOMEZ', 'jogego26711@gmail.com', NULL, 'CONDUCTOR', 1),
(74362121, 'GERMAN PRECIADO', NULL, NULL, NULL, 'CONDUCTOR', 1),
(74369596, 'NELSON', 'RINCON BASTIDAS', NULL, NULL, 'CONDUCTOR', 1),
(74369694, 'ALVARO', 'CARO FONSECA', NULL, NULL, 'CONDUCTOR', 1),
(74372083, 'Germán Ricardo Cristancho Hern', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74374743, 'Pedro Alejandro Guatibonza Tor', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74378733, 'JOSE JAVIER', 'AVILA TORRES', NULL, '6292026', 'CONDUCTOR', 1),
(74753780, 'Fernando Ramírez Salamanca', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(74847231, 'LUIS LEONARDO', 'CORREA AVELLA', NULL, NULL, 'CONDUCTOR', 0),
(77032272, 'ALEX JAVIER', 'AVILA MESTRE', NULL, NULL, 'CONDUCTOR', 1),
(77081919, 'RODRIGO', 'CALDERON HERRERA', 'rodrigocalderon1976@gmail.com', NULL, 'CONDUCTOR', 1),
(77102623, 'JAIRO MOJICA GARCIA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(77130701, 'RODOLFO', 'MERCHAN RUSINQUE', 'KNEGRETTE@PLEXA.CO', NULL, 'CONDUCTOR', 1),
(77131949, 'HELBERT JOSE', 'GUERRA', NULL, NULL, 'CONDUCTOR', 1),
(77140085, 'ALBEIRO', 'MALDONADO SANCHEZ', 'albeiromaldonado@hotmail.com', NULL, 'CONDUCTOR', 1),
(77149430, 'JUAN CARLOS', 'CHAPETA', NULL, NULL, 'CONDUCTOR', 1),
(77154496, 'Wilfredo Martínez Fonseca', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(77164810, 'LUIS ALFONSO', 'ESPAÑA CARRILLO', NULL, NULL, 'CONDUCTOR', 1),
(77167074, 'JHONY DE JESUS', 'SORACA GUZMAN', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(77167102, 'JOSE JOAQUIN', 'MARTINEZ RODRIGUE', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(77167409, 'ARLEZ MAURICIO', 'MAZO RIVERA', NULL, NULL, 'CONDUCTOR', 1),
(77179842, 'WILFREDO', 'GARCIA QUINTERO', NULL, NULL, 'CONDUCTOR', 1),
(77195742, 'MANUEL JOSE', 'BAUTISTA CANO', NULL, NULL, 'CONDUCTOR', 1),
(78026113, 'CARLOS ARTURO ', 'OLIVARES HERRERA', NULL, NULL, 'CONDUCTOR', 1),
(78699089, 'Frey José Fernández galarcio', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79062442, 'LUIS ALFREDO ', 'CAJAMARCA GUZMAN', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79100434, 'JOSE HUMBERTO', 'RODRIGUEZ AREVALO', NULL, NULL, 'CONDUCTOR', 1),
(79118034, 'JAIRO', 'MENA MORA', NULL, '6292026', 'CONDUCTOR', 1),
(79132903, 'CARLOS', 'RINCON SANCHEZ', NULL, NULL, 'CONDUCTOR', 1),
(79156010, 'LUIS ALBERTO', 'NIÑO CASTRO', NULL, '6292026', 'CONDUCTOR', 1),
(79169140, 'LEUDYS', 'MANTILLA CARMARGO', NULL, NULL, 'CONDUCTOR', 1),
(79180340, 'NELSON ALBERTO', 'SANCHEZ VARGAS', 'nelson800901@gmail.com', NULL, 'CONDUCTOR', 1),
(79201204, 'LUIS', 'SANCHEZ MONTENEGRO', NULL, '6292026', 'CONDUCTOR', 1),
(79206496, 'JUAN ALFREDO', 'ARIAS OSPINA', NULL, NULL, 'CONDUCTOR', 1),
(79244956, 'EDGAR', 'RODRIGUEZ RINCON', NULL, '6292026', 'CONDUCTOR', 1),
(79245525, 'MARIO', 'BOHORQUEZ RODRIGUEZ', NULL, NULL, 'CONDUCTOR', 1),
(79296744, 'JUAN MANUEL', 'ZAMBRANO', NULL, NULL, 'CONDUCTOR', 1),
(79325954, 'PEDRO FERNANDO', 'GALVIS BELTRAN', NULL, '6292026', 'CONDUCTOR', 1),
(79355197, ' LUIS ANTONIO', 'DIAZ LANCHEROS', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(79374847, 'Ricardo Sanchez Morales', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79383042, 'OSCAR RUBEN', 'ALVAREZ YATE', NULL, NULL, 'CONDUCTOR', 1),
(79411298, 'WILLIAM ERNESTO', 'GUERRERO LOPEZ', NULL, NULL, 'CONDUCTOR', 1),
(79415582, 'OMAR AUGUSTO', 'SANCHEZ LEON', NULL, NULL, 'CONDUCTOR', 1),
(79424601, 'Jose Neftalí   ', 'Gonzalez García', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79428688, 'Uriel Duarte Sierra', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79443609, 'RIGOBERTO', 'AREVALO VILLARRAGA', NULL, NULL, 'CONDUCTOR', 1),
(79447853, 'LUIS  ALFREDO ', 'GOMEZ BALESTERO', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(79458457, 'JAIRO MONTAÑA GONZALES ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79464381, 'JOSE FERNANDO PRADA ', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(79470752, 'Jabier Danilo ', 'Rodriguez  Villamizar', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79481458, 'JOSE VITELMO', 'AGUDELO REY', NULL, NULL, 'CONDUCTOR', 1),
(79485012, 'MAURICIO', 'GALEANO RODRIGUEZ', NULL, '6292026', 'CONDUCTOR', 1),
(79490989, 'ORLANDO', 'OROZCO BARRERO', NULL, NULL, 'CONDUCTOR', 1),
(79491038, 'JUAN MANUEL', 'RATIVA BELTRAN', NULL, '6292026', 'CONDUCTOR', 1),
(79508382, 'EDIXON', 'AVILA BARRERA', NULL, '6292026', 'CONDUCTOR', 1),
(79511121, 'JHON FREDDY CASTEBLANCO BERNAL', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79520298, 'HAROLD WILSON', 'RAMOS GOMEZ', NULL, '6292026', 'CONDUCTOR', 1),
(79521362, 'OSCAR', 'DUARTE SAENZ', NULL, '6292026', 'CONDUCTOR', 1),
(79529769, 'DIEGO EFREY', 'SALAZAR PORTILIA', 'diegoefreys@gmail.com ', NULL, 'CONDUCTOR', 1),
(79560893, 'JOSE LUIS', 'CAMPOS RUIZ', NULL, NULL, 'CONDUCTOR', 1),
(79575373, 'MIGUEL ANTONIO', 'ROBAYO ARIAS', NULL, '6292026', 'CONDUCTOR', 1),
(79575575, 'William Monroy Cortes', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79617112, 'MILTON  CESAR', 'SALAZAR AHUMADA', NULL, NULL, 'CONDUCTOR', 1),
(79636714, 'JUAN', 'RODRIGUEZ MOLINA', NULL, '6292026', 'CONDUCTOR', 1),
(79697145, 'WILLIAM', 'MARTINEZ AVILA', NULL, '6292026', 'CONDUCTOR', 1),
(79703239, 'MAURICIO', 'PINEDA GALVIS', NULL, '6292026', 'CONDUCTOR', 1),
(79711123, 'JOSE HERNANDO', 'QUIJANO BOHORQUEZ', NULL, NULL, 'CONDUCTOR', 1),
(79736280, 'JUSTINO', 'BARRETO GOMEZ', NULL, '6292026', 'CONDUCTOR', 1),
(79747910, 'LEONARDO ANDRES', NULL, 'SAMUELANDRES.SAMB@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(79747942, 'Jorge casteblanco Molina', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79764077, 'EMERSON', 'PORRAS HENAO', NULL, '6292026', 'CONDUCTOR', 1),
(79770695, 'CARLOS DARIO', 'DUARTE SIERRA', NULL, NULL, 'CONDUCTOR', 1),
(79785624, 'WYTINTO', 'GUERRERO DUEÑAS', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(79821549, 'Raúl Bareño Reyes', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79839693, 'JHON', 'VASQUEZ CASTAÑEDA', NULL, '6292026', 'CONDUCTOR', 1),
(79888480, 'Iván Andrés Hernández', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(79897389, 'DANIEL EDUARDO', 'CARDENAS TRIANA', NULL, NULL, 'CONDUCTOR', 1),
(79899974, 'JOSE FREDY', 'DUARTE HERNANDEZ', 'FREDUHER79@HOTMAIL.COM ', NULL, 'CONDUCTOR', 1),
(79906571, 'LUIS ALEJADRO', 'LEMUS FORERO', 'alejolefo2877@gmail.com', NULL, 'CONDUCTOR', 1),
(79959886, 'JHON ALEXANDER', 'MARTIN COLLAZOS', NULL, NULL, 'CONDUCTOR', 1),
(79960022, 'YHON', 'MENDOZA', NULL, NULL, 'CONDUCTOR', 1),
(79974348, 'LUIS  FERNANDO', 'PARRADO BELTRAN', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(80000557, 'Yonh Marlon ', 'Durán Durán', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80004718, 'HAROLD ORLANDO', 'MEDINA MANCILLA', NULL, NULL, 'CONDUCTOR', 1),
(80020639, 'Andres  Salamanca ', NULL, 'Analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80035545, 'MANUEL ALBERTO SAMACA TELAGUY', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80055228, 'FABIAN', 'PACHON MUÑOZ', NULL, NULL, 'CONDUCTOR', 1),
(80134753, 'FREDY NORBERTO', 'ALVARADO BUSTOS', NULL, NULL, 'CONDUCTOR', 1),
(80158025, 'Esneider chitiva', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80158594, 'OSCAR MAURICIO', 'BOHORQUEZ VALLEJO', NULL, NULL, 'CONDUCTOR', 1),
(80206613, 'JUAN MORALES OSPINA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80222676, 'Luis Fernando romero ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80225738, 'YESID', 'CIFUENTES AMAYA', 'cifuentesamayayesid@hotmail.com', NULL, 'CONDUCTOR', 1),
(80252266, 'FRANKIL YAIR', 'CARDENAS BUITRAGO', NULL, NULL, 'CONDUCTOR', 1),
(80260762, 'EDILBERTO HERNANDEZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80268586, 'Julio Ramon Velosa Pérez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80271614, 'António Peña Castillo', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80272845, 'Miguel angel cespedes soler', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80311873, 'JEFERSON', 'SANCHEZ SERRANO', NULL, '6292026', 'CONDUCTOR', 1),
(80322640, 'CESAR HUMBERTO BARRAGAN RUBIO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80355239, 'FERNANDO', 'HERNANDEZ RODRIGUEZ', NULL, NULL, 'CONDUCTOR', 1),
(80365109, 'José Ricardo Rodríguez Ñustes', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80384510, 'RAUL ISNARDO', 'MAYORGA GONZALEZ', NULL, NULL, 'CONDUCTOR', 1),
(80400664, 'CARLOS', 'DE LA TORRE MELGAREJO', NULL, '6292026', 'CONDUCTOR', 1),
(80405598, 'Daniel Camacho', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80406808, 'Nelson Arcadio', 'Ramos Jurado', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80421996, 'JOSE', 'GONZALEZ GONZALEZ', NULL, '6292026', 'CONDUCTOR', 1),
(80422451, 'RICHARD', 'REYES SOLER', 'PORTCHIPS@HOTMAIL.COM', '6292026', 'CONDUCTOR', 1),
(80429206, 'GABRIEL ANTONIO', 'ZAPATA SALAZAR', 'GABRIELZAPATA01@LIVE.COM', NULL, 'CONDUCTOR', 1),
(80435090, 'WILSON HERNANDO', 'BARON SANDOVAL', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80436112, 'ALEXANDER', 'LINARES CABALLERO', NULL, NULL, 'CONDUCTOR', 1),
(80441570, 'OMAR', 'CASTAÑEDA TRIVIÑO', NULL, NULL, 'CONDUCTOR', 1),
(80451892, 'JOSE GERMAN BONILLA MUÑOZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(80543543, 'JOSE URIEL', 'CEPEDA', 'JOSECEPEDA@GMAIL.COM ', NULL, 'CONDUCTOR', 1),
(80743320, 'JOSE VICENTE', 'SANCHEZ PINEDA', NULL, NULL, 'CONDUCTOR', 1),
(80810921, 'MAIKOL', 'ARDILA PARDO', NULL, '6292026', 'CONDUCTOR', 1),
(80826019, 'Julio César ', 'Rengifo   Hernández', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(84075639, 'LIBARDO', 'HERNANDEZ VELASQUEZ', NULL, NULL, 'CONDUCTOR', 1),
(85272202, 'LEONARD', 'GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(85440219, 'SAMUEL CAAMAÑO', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(85446008, 'CARLOS ANDRES', 'RUIZ BOJANINI', NULL, '6292026', 'CONDUCTOR', 1),
(85456201, 'LUIS LEANDRO', 'CALDERON HENRIQUEZ', NULL, NULL, 'CONDUCTOR', 1),
(85461437, 'JAIME', 'GUTIERREZ TERRAZA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(85468441, 'GERARDO ALEXANDER', 'PARRA BAEZ', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(85487950, 'JAIDER ', 'CALDERON OSPINO', 'jeym-1981@hotmail.com', NULL, 'CONDUCTOR', 1),
(86056340, 'JADER ALBERTO', 'SARMIENTO RIVERA', NULL, NULL, 'CONDUCTOR', 1),
(87067276, 'FREDY ', 'BENAVIDES CHALPARTAR', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88191901, 'edgar dueñez blanco', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88203302, 'LUIS EDU  VILLADA VELEZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88227900, 'LUIWEN EDUARDO', 'RODRIGUEZ SALAZAR', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88232456, 'José Julian Mora Castro ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88236928, 'WILMAN OSWALDO ', 'GRISALES SERNA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88240010, 'DIEGO DIAZ CONTRERAS', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(88252176, 'MARCOS MAURICIO', 'SUAREZ FLOREZ', NULL, '6292026', 'CONDUCTOR', 1),
(89007560, 'JAMES LEONARDO', 'FERNANDEZ ARANGO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91011730, 'Fidel', 'Villamil ', NULL, NULL, 'CONDUCTOR', 1),
(91012003, 'Otoniel Ardila', NULL, 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(91014312, 'NILSON ROLANDO', 'MATEUS ARIZA', NULL, NULL, 'CONDUCTOR', 1),
(91045207, 'EDISON MAURICIO', 'BAUTISTA ACELAS', NULL, NULL, 'CONDUCTOR', 1),
(91046158, 'Javier Reinaldo ', 'Rodriguez Niño', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91046706, 'ENOC GOMEZ ALMEYDA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91069407, 'JOSE JOAQUIN', 'VELASQUEZ MUÑOS', 'joaquinvelasquez64@hotmail.com', NULL, 'CONDUCTOR', 1),
(91070038, 'OMAR', 'CHACON GOMEZ', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(91072575, 'EDUARDO', 'ANGEL ESPINOSA', NULL, NULL, 'CONDUCTOR', 1),
(91073003, 'Gilberto ', 'velasquez Duarte', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91076019, 'HECTOR ELIAS', 'ARENAS LOPEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91103098, 'LUIS ALFREDO', 'ARCILA OLARTE', 'keiler7894@hotmail.com', NULL, 'CONDUCTOR', 1),
(91106794, 'GERMAN', 'SANTAMARIA FONSECA', NULL, NULL, 'CONDUCTOR', 1),
(91109798, 'Oscar Mauricio', 'Bastilla', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91134857, 'Carlos Gilberto ', 'Quejada Bermud', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91152201, 'JUAN CARLOS', 'QUINTERO REYES', 'carlosreyes0064@gmail.com', NULL, 'CONDUCTOR', 1),
(91156968, 'SAMUEL', 'SANCHEZ CELIS', 'samuelsanchezcelis@gmail.com ', NULL, 'CONDUCTOR', 1),
(91158389, 'JORGE ELIECER', 'DIAZ PEDRAZA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91181352, 'EVINSON', 'ARIZA MOSQUERA', NULL, '6292026', 'CONDUCTOR', 1),
(91183059, 'JOVANNY', 'NOCOBE RUEDA', NULL, NULL, 'CONDUCTOR', 1),
(91183525, 'JULIAN ALFREDO ', 'CUADROS GONZALES', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91214114, 'CHRISTIAM ALBERTO', 'RAMIREZ AMOROCHO', NULL, '6292026', 'CONDUCTOR', 1),
(91220437, 'RUPERTO', 'SANABRIA DELGADO', NULL, NULL, 'CONDUCTOR', 1),
(91222393, 'BENIGNO  HERRERA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91223639, 'ALEX FERNANDO', 'OSMA MONCADA', NULL, NULL, 'CONDUCTOR', 1),
(91224649, 'CARLOS', 'MARTINEZ', NULL, NULL, 'CONDUCTOR', 1),
(91225478, 'JAIME ENRIQUE', 'MEJIA RUBIO', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(91231269, 'ALEJANDRO', 'CHACON RIVERA', NULL, NULL, 'CONDUCTOR', 1),
(91236597, 'ALFONSO', 'PORTILLA ORTEGA', NULL, NULL, 'CONDUCTOR', 1),
(91236979, 'JAIME ', 'ARIAS MENDOZA', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(91248089, 'EVER DARIO', 'VILLAMIZAR GARCIA', NULL, '6292026', 'CONDUCTOR', 1),
(91248184, 'TOMAS', 'CALA', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(91249379, 'FREDERMAN', 'VEGA GOMEZ', NULL, '6292026', 'CONDUCTOR', 1),
(91249838, 'GERMAN', 'ARENALES MORA', 'german.pan.65@hotmail.com', NULL, 'CONDUCTOR', 1),
(91250996, 'ERIBERTO ', 'MUÑOZ VELASQUEZ ', 'eribertomunoz1967@gmail.com', NULL, 'CONDUCTOR', 1),
(91252337, 'NELSON ', 'CASTRO ALVARADO', NULL, NULL, 'CONDUCTOR', 1),
(91253546, 'Santiago Prada Prada', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91255211, 'Wilson Ernesto ', 'Alza Herrera', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91257823, 'HERNAN', 'MEDINA CASTAÑO', NULL, NULL, 'CONDUCTOR', 1),
(91260148, 'JESUS', 'HURTADO GAVIRIA', NULL, NULL, 'CONDUCTOR', 1),
(91260421, 'Jaime Santamaría Robayo', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91260978, 'JAMES', 'DUCON CHACON', NULL, NULL, 'CONDUCTOR', 1),
(91261965, 'RICARDO ', 'SARMIENTO ORDUZ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(91262713, 'GUILLERMO', 'GONZALEZ CORTES', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91263835, 'HECTOR', 'DELGADO SALAZAR', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(91264076, 'JOSE ORLANDO', 'BARCENAS MORENO', NULL, NULL, 'CONDUCTOR', 1),
(91264082, 'WILSON', 'ROJAS VELANDIA', NULL, NULL, 'CONDUCTOR', 1),
(91264700, 'JAVIER', 'MORANTES CASTRO', NULL, NULL, 'CONDUCTOR', 1),
(91268063, 'GUSTAVO CASTILLO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91271638, 'ANTONIO', 'REYES MONROY', NULL, NULL, 'CONDUCTOR', 1),
(91271783, 'MILTON  MAURICIO', 'MEJIA ROJAS', NULL, NULL, 'CONDUCTOR', 1),
(91273748, 'OSCAR OMAR', 'TRUJILLO HERRERA', NULL, NULL, 'CONDUCTOR', 1),
(91277060, 'ALCIDES', 'MANTILLA RUEDA', NULL, NULL, 'CONDUCTOR', 1),
(91278296, 'JOSEFITO', NULL, NULL, NULL, 'CONDUCTOR', 1),
(91278812, 'JAVIER ORLANDO', 'RODRIGUEZ CABEZA', NULL, NULL, 'CONDUCTOR', 0),
(91279714, 'Helber', 'Niño Flores', NULL, NULL, 'CONDUCTOR', 1),
(91281887, 'ALBEIRO', 'CHACON REYES', NULL, NULL, 'CONDUCTOR', 1),
(91282286, 'EDUARDO', 'RIVERA PARRA', NULL, NULL, 'CONDUCTOR', 1),
(91283351, 'ALEXANDER', 'SANCHEZ TAPIAS', NULL, '6292026', 'CONDUCTOR', 1),
(91288115, 'JOSE', 'CARDENAS RAMIREZ', NULL, NULL, 'CONDUCTOR', 1),
(91288417, 'Henry Flavio ', 'Alza Herrera', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91290614, 'JOSE ANTONIO', 'PEREZ GAMBOA', 'jose_perez_1973@hotmail.com', NULL, 'CONDUCTOR', 1),
(91290723, 'WELTON ANIBAL', 'MURCIA RODRIGUEZ', NULL, NULL, 'CONDUCTOR', 1),
(91291380, 'ORLANDO', 'GONZALES SANTIAGO', NULL, '6292026', 'CONDUCTOR', 1),
(91293014, 'GILMER ALFONSO', 'MANTILLA PARADA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91296272, 'Augusto Rosado García', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91298535, 'LINTON', 'MORENO CORZO', NULL, NULL, 'CONDUCTOR', 1),
(91299453, 'WILIMTON', 'ORTIZ', 'Wilimtonortizflorez@gmail.com', NULL, 'CONDUCTOR', 1),
(91341363, 'HUMBERTO', 'ARIAS', NULL, NULL, 'CONDUCTOR', 1),
(91342872, 'CAYETANO', 'MEJIA HERNANDEZ', NULL, NULL, 'CONDUCTOR', 1),
(91343162, 'ALONSO SERRANO DURAN', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91344921, 'MANUEL', 'OVIEDO HERNANDEz', NULL, NULL, 'CONDUCTOR', 1),
(91349176, 'JHON ALEXANDER', 'DIAZ MONSALVE', NULL, NULL, 'CONDUCTOR', 1),
(91349276, 'NELSON ALIRIO', 'PARDO MUÑOZ', NULL, NULL, 'CONDUCTOR', 1),
(91354086, 'PEDRO MANUEL', 'OVALLE DALLOS', 'Povalle_32da@hotmail.com', '6292026', 'CONDUCTOR', 1),
(91354193, 'FERNANDO CAMILO', 'POVEDA PEDRAZA', NULL, '6292026', 'CONDUCTOR', 1),
(91355344, 'TEOFILO MANOSALVA DUARTE ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91355538, 'LUIS FERNANDO ', 'REY GARCIA ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91356679, 'JHON ELVER', 'PITA PITA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91420711, 'ISAAC', 'GARCIA DUARTE', '2pdw32e4d@gmail.com', '6292026', 'CONDUCTOR', 1),
(91424527, 'ORLANDO', 'PLATA GOMEZ', NULL, '6292026', 'CONDUCTOR', 1),
(91431717, 'JORGE ELIECER', 'CABRERA TORRES', NULL, NULL, 'CONDUCTOR', 1),
(91431760, 'JOHN DAIRO', 'CANO BEDOYA', NULL, '6292026', 'CONDUCTOR', 1),
(91432055, 'José Eduardo', 'Jiménez Merchán', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91432069, 'HENRY ', 'CAMARGO CALA', NULL, NULL, 'CONDUCTOR', 1),
(91433362, 'TULIO', 'ALEMAN DIAZ', 'tulio_aleman@hotmail.com ', NULL, 'CONDUCTOR', 1),
(91435499, 'SALOMON', 'GONZALEZ GUERRERO', NULL, '6292026', 'CONDUCTOR', 1),
(91438105, 'ISNARDO', 'PARADA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91438579, 'HUMBERTO', 'URRUTIA MATTOS', 'Humberto.urrutia@outlook.com', '6292026', 'CONDUCTOR', 1),
(91439876, 'OSCAR', 'PACHECO ROSAS', NULL, '6292026', 'CONDUCTOR', 1),
(91442896, 'RAUL', 'PAEZ GARCIA', 'raul.0702@outlook.com', NULL, 'CONDUCTOR', 1),
(91445774, 'ELKIS', 'CRUZ SOLORZANO', 'apoyologistico@plexa.co', '6292026', 'CONDUCTOR', 1),
(91446328, 'JAVIER ANTONIO', 'TORRES GARCIA', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(91447264, 'WILFREDO', 'TORRES SANABRIA ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91449304, 'JHON EDWIN', 'SAENZ', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(91457430, 'Misael Jerez Martinez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91461157, 'CRISTIAN', 'RUEDA GARZON', NULL, '6292026', 'CONDUCTOR', 1),
(91464227, 'CARLOS DANIEL', 'ILLERAS GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(91470636, 'GERARDO', 'ARDILA FUENTES', NULL, NULL, 'CONDUCTOR', 1),
(91471889, 'OSWALD CRUZ ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91472174, 'HENRY', 'VERA MALDONADO', NULL, NULL, 'CONDUCTOR', 1),
(91474894, 'ISRAEL', 'FRANDY JEREZ', NULL, '6292026', 'CONDUCTOR', 1),
(91476077, 'LUIS ENRIQUE', 'RIAÑO GALVIS', NULL, NULL, 'CONDUCTOR', 1),
(91487182, 'JAVIER', 'PALACIO SARMIENTO', NULL, NULL, 'CONDUCTOR', 1),
(91488115, 'JUAN CARLOS', 'QUINTERO GAMBOA', NULL, NULL, 'CONDUCTOR', 1),
(91494885, 'MELQUICEDEC JULIO PABON', NULL, 'Mjuliop@hotmail.com', NULL, 'CONDUCTOR', 1),
(91495992, 'JOHN JAIRO JEREZ JIMENEZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91499287, 'LUIS MANUEL', 'MENDEZ CORZO', NULL, NULL, 'CONDUCTOR', 1),
(91506899, 'Jhon Braulio Domínguez Barbosa', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91513315, 'LEONARDO ROJAS VALBUENA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91516849, 'Edwin Javier ', 'Osorio Rincón', NULL, NULL, 'CONDUCTOR', 1),
(91518503, 'JORGE ARMANDO', 'DURAN GALVIS', NULL, NULL, 'CONDUCTOR', 1),
(91519336, 'RAFAEL ANTONIO', 'SERRANO CACERES', NULL, '6292026', 'CONDUCTOR', 1),
(91519643, 'Alexis Arguello oviedo', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91525490, 'JOSE ORLEY', 'DEVIA VILLALOBOS', NULL, NULL, 'CONDUCTOR', 1),
(91533742, 'HENRY', 'SOLANO BARBOSA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91536815, 'RAFAEL ERNESTO', 'PORTILLA RODRIGUEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(91539182, 'FERNEY', 'BENITEZ BARRAGAN', 'ferneybenitezconductor@outlook.com', NULL, 'CONDUCTOR', 1),
(91540491, 'OSCAR FABIAN', 'MENDEZ BASTIDAS', NULL, NULL, 'CONDUCTOR', 1),
(92032962, 'JESUS DAVID', 'DE LA OSSA PEREZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(93134673, 'JUAN CARLOS', 'ORTIZ VARGAS', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(93335275, 'RODRIGO', 'OSPITIA', NULL, NULL, 'CONDUCTOR', 1),
(93359965, 'EDGAR', 'GARCIA MORENO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(93386917, 'LUIS', 'MUÑOZ TIQUE', NULL, '6292026', 'CONDUCTOR', 1),
(93395124, 'IVAN MAURICIO', 'RODRIGUEZ PINILLA', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(93438956, 'RUBEN DARIO', 'MARTINEZ ARANA', NULL, NULL, 'CONDUCTOR', 1),
(93461482, 'SAMUEL ANGEL', 'CASTIBLANCO NIETO', NULL, NULL, 'CONDUCTOR', 1),
(93472260, 'Carlos Augusto Chavarro Gomez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(94268231, 'EFRAIN', 'SHUWERY PINEDA', NULL, NULL, 'CONDUCTOR', 1),
(94312847, 'Javier Pérez Correa', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(94456108, 'EDUIN ALBERTO', 'PASTAS VILLEGAS', NULL, NULL, 'CONDUCTOR', 1),
(94461759, 'ALEXANDER', 'GONZALEZ GARCIA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(94463985, 'NODIER', 'VELASQUEZ ', NULL, NULL, 'CONDUCTOR', 1),
(94506942, 'Edgar Patricio', 'Cortes Arismendy', NULL, NULL, 'CONDUCTOR', 1),
(96354319, 'Solim ', 'Sanchez Gonzales', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(97446969, 'Angel Antonio', 'Diaz Osorio', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(98341657, 'JOSE ELISEO', 'AGUIRRE AGUIRRE', 'marceci70@hotmail.es ', NULL, 'CONDUCTOR', 1),
(98342148, 'DIEGO RAMON', 'AGUIRRE AGUIRRE', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(98395137, 'ARMANDO', 'VALLEJO VILLOTA', NULL, '6292026', 'CONDUCTOR', 1),
(98658389, 'ANDRES', 'HERNANDEZ SANCHEZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(98658993, 'CESAR ANÍBAL RAMÍREZ ARANGO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(552434444, 'HERNANDO', 'ESCANIO MORENO', NULL, NULL, 'CONDUCTOR', 1),
(1001876577, 'SAMUEL ', 'HERNANDEZ CALDERON', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1002193788, 'GUSTAVO', 'JIMENEZ RAMOS', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1003312739, 'OSCAR', 'CORREA GIRALDO', 'ocorreagiraldo0619@gmail.com', NULL, 'CONDUCTOR', 1),
(1004360312, 'LUIS EDUARDO', 'MARULANDA OSORIO', NULL, NULL, 'CONDUCTOR', 1),
(1005564911, 'Edinson', 'Hernandez Herrera ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1010167900, 'JEFFERSON', 'CHARRY MESA', NULL, NULL, 'CONDUCTOR', 1),
(1010171581, 'DIEGO MAURICIO BELTRAN RODRIGU', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1012332772, 'JEISSON  DAVID ', 'GONZALEZ GARCIA', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(1015468580, 'JUAN SEBASTIAN ', 'ORDUÑA MARIN', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1016021585, 'GUSTAVO ADOLFO CAMARGO HERRERA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1018442233, 'CAMILO ALEJANDRO', 'CASTAÑEDA SUAREZ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1022331554, 'GELBER ACOSTA ACOSTA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1022333635, 'Ángel Custodio ', 'Cuervo Plazas', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1022344021, 'Pedro Luis Sarmiento', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1022931976, 'Sergio Antonio', 'Ramírez Triana', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1022982388, 'OSCAR YAIR', 'SALAMANCA MEDINA', NULL, '6292026', 'CONDUCTOR', 1),
(1023971452, 'LUIS  FERNANDO', 'PARRADO  MORENA', 'aberrio@plexa.co', NULL, 'CONDUCTOR', 1),
(1024484842, 'Wilson Alfredo Ayala Jiménez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1030592268, ' Nelson', ' Sanabria Padilla', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1030600618, 'RAFAEL LEONARDO ', 'PAEZ AVILA ', 'rpaez@yegoecot.com', NULL, 'CONDUCTOR', 1),
(1033712506, 'CRISTIAN FERNANDO', 'PICÓN QUINTERO', 'analistaplexa@gmail.com', 'cristian892001@gmail', 'CONDUCTOR', 1),
(1042444045, 'ISRAEL', 'MARIÑO CALA', NULL, NULL, 'CONDUCTOR', 1),
(1044640701, 'EDWIM DAVID', 'ALTAMAR GARIZABALO', NULL, NULL, 'CONDUCTOR', 1),
(1047372036, 'JAVIER AYALA OROZCO', NULL, '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(1047391433, 'AMIN ANIBAL ATIA  MADRID', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1047398385, 'Wilson Sierra Lopez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1047409809, 'JHAN KEVIN', 'MONTALVO FONTALVO', 'Jhank2010@hotmail.com', NULL, 'CONDUCTOR', 1),
(1047414351, 'BRYAN ', 'OSORIO VAZQUES', 'Coby-bryan0401@hotmail.com', NULL, 'CONDUCTOR', 1),
(1047423250, 'Cesar Augusto', 'Jordan Sarmienhto', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1049607940, 'CESAR HUMBERTO', 'DUARTE MORA', NULL, NULL, 'CONDUCTOR', 1),
(1049652723, 'CRISTIAN IVAN', 'ROJAS TORRES', NULL, NULL, 'CONDUCTOR', 1),
(1050947219, 'JHON ALFRED', 'JIMENEZ CARMONA', NULL, NULL, 'CONDUCTOR', 1),
(1050959901, 'RONALD YESID', 'CASTILLA GONZALEZ', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(1051824940, 'CARLOS MIGUEL', 'POLO CARO', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1054800196, 'ALVARO AUGUSTO CASTEBLANCO MOL', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1056781054, 'MIGUEL ANGEL', 'OROZCO CASTAÑO', NULL, '3108165726', 'CONDUCTOR', 1),
(1062906990, 'IGDALIAS', 'CHACON ARIAS', NULL, NULL, 'CONDUCTOR', 1),
(1064117000, 'CARLOS MARIO', 'SANCHEZ RODRIGUEZ ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1064797998, 'HECTOR', 'NIÑO CASTILLA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1065123584, 'EDWIN STALYN', 'MAZO RIVIERA', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(1065126360, 'ANTHONY MERCADO LEIVA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1065135217, 'Darwin Eduardo Puello Fontalvo', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1065576746, 'JAVIER ENRIQUE', 'ARAGON BARBOSA', 'analistaplexa@gmail.com', '6292026', 'CONDUCTOR', 1),
(1065873175, 'Andrey fernando ', 'sanchez Bautista', 'andreysanchez113@gmail.com', NULL, 'CONDUCTOR', 1),
(1065907066, 'CLAUDIO ARCHILA PORTILLO', NULL, 'kbautista@plexa.co', NULL, 'CONDUCTOR', 1),
(1067716404, 'LUIS ALBERTO', 'VILLAREAL CHARRY', NULL, '6292026', 'CONDUCTOR', 1),
(1069305378, 'JULIAN DAVID', 'BOTIVA BAQUERO', NULL, NULL, 'CONDUCTOR', 1),
(1069745023, 'Wilmar Camilo Bernal Martinez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1071330273, 'BOJACA JOYA', 'RAFAEL RICARDO', NULL, NULL, 'CONDUCTOR', 1),
(1073513886, 'ANTONY', 'YANES RAMOS', 'antony.yanez@hotmail.com', NULL, 'CONDUCTOR', 1),
(1075662196, 'miguel alfredo herrera nieto ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1081797529, 'LUIS PUELLO FONTALVO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1082850181, 'Luis Manuel ', 'Cervera Álvarez ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1082862788, 'JORGE LUIS PEREZ MARTINEZ ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1082880903, 'JOSE LUIS', 'RUEDA OLARTE', NULL, NULL, 'CONDUCTOR', 1),
(1082957105, 'ESNEIDER', 'QUINTERO GRAJALES', 'Esneider1992@outlook.es', NULL, 'CONDUCTOR', 1),
(1087486110, 'Giovanny Andres Ramírez Gutiér', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1091533627, 'Ronald Salvador Santana Parra', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1091666946, 'GEINER CONTRERAS PEDROZA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1093781759, 'DUVAN ALBERTO', 'CADENA GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(1094268778, 'RAFAEL ', 'PABON PORTILLA ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1095817194, 'MARLON', 'PAEZ OSORIO', 'PAEZ9404@HOTMAIL.COM', NULL, 'CONDUCTOR', 1),
(1095915587, 'JOSE ANTONIO', 'JIMENEZ DUARTE', NULL, NULL, 'CONDUCTOR', 1),
(1095919238, 'JHON JAIRO CASTAÑEDA PINZON', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1095942121, 'Ricardo Pedraza serrano ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1095956841, 'CRISTHIAN ALBERTO', 'CENTENO GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(1096208600, 'CARLOS EDUARDO', 'BRAND MORALES', NULL, NULL, 'CONDUCTOR', 1),
(1096211790, 'JONATAN CALED ', 'MENDOZA GUEVARA', NULL, NULL, 'CONDUCTOR', 1),
(1096226146, 'JOHAN', 'RATIVA AGUILLON', NULL, NULL, 'CONDUCTOR', 1),
(1096231722, 'Rodrigo ', 'Alvarez Alvarez', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1096243624, 'LEONARDO ', 'CELIS MEJIA', 'LEONARDOCELISMEJIA2@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(1097389198, 'JORGE BLANCO ORTIZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1097393811, 'CARLOS CRUZ HURTADO', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1098612904, 'ANDRES REYNALDO', 'ROBLES JAIMES', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1098617468, 'JHON JAIRO', 'MELGAREJO MORA', NULL, NULL, 'CONDUCTOR', 1),
(1098618348, 'HERNADO ', 'MENDOZA ROJAS ', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1098622980, 'MIGUEL ', 'GONZALEZ FUENTES ', NULL, NULL, 'CONDUCTOR', 1),
(1098625674, 'ALVARO IVAN', 'BOHORQUEZ R', NULL, NULL, 'CONDUCTOR', 1),
(1098632179, 'WILLIAM', 'MARIN ROJAS', NULL, NULL, 'CONDUCTOR', 1),
(1098642413, 'ALVARO JOSE', 'RIAÑO DURAN', NULL, NULL, 'CONDUCTOR', 1),
(1098642846, 'JOSE GREGORIO', 'GOMEZ GARCIA', NULL, NULL, 'CONDUCTOR', 1),
(1098659351, 'EDINSON FERNANDO', 'RODRIGUEZ OREJARENA', NULL, NULL, 'CONDUCTOR', 1),
(1098662912, 'MIGUEL ANDRES', 'QUINTERO SUAREZ', NULL, NULL, 'CONDUCTOR', 1),
(1098677221, 'ERNESTO', 'PINZON TOLOZA', 'mopripinzon@gmail.com', NULL, 'CONDUCTOR', 1),
(1098681730, 'DEIMER ', 'ARDILA ORTIZ', NULL, NULL, 'CONDUCTOR', 1),
(1098683470, 'JORGE', 'LANDINES', NULL, NULL, 'CONDUCTOR', 1),
(1098685032, 'Romel armando Callejas Gómez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1098688089, 'RODRIGO', 'CUADROS ALVAREZ', NULL, NULL, 'CONDUCTOR', 1),
(1098706937, 'YEELSIHT FERNEY', 'GOMEZ CASTILLO', NULL, NULL, 'CONDUCTOR', 1),
(1098718114, 'JAVIER', 'RANGEL', NULL, NULL, 'CONDUCTOR', 1),
(1098756694, 'Miguel Angel Gallo', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1098799570, 'Yan Carlos Merchan Narvaez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1099205638, 'Armando Moreno Aguilera', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1100952092, 'Felipe Antonio', 'Garzon Moreno', 'kbautista@plexa.co', NULL, 'CONDUCTOR', 1),
(1101546215, 'JOSE ', 'SANCHEZ AGREDO', '2pdw32e4d@gmail.com', NULL, 'CONDUCTOR', 1),
(1102351650, 'Elkin Fabian', 'Gonzalez Bueno', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1102359619, 'LUIS FERNANDO', 'MONTAGUT ORTEGA', NULL, NULL, 'CONDUCTOR', 1),
(1102370700, 'CARLOS', 'ESCOBAR SUAREZ', NULL, NULL, 'CONDUCTOR', 1),
(1102380288, 'EVER JULIAN', 'AGUDELO FLORES', 'julianagudelo288@gmail.com', NULL, 'CONDUCTOR', 1),
(1102548711, 'Edgar Eduardo Buitrago Olarte ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1102549046, 'CESAR AUGUSTO', 'BUITRAGO OLARTE', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1102715085, 'FREDY ALBEIRO', 'VARGAS CASTRO', NULL, '6292026', 'CONDUCTOR', 1),
(1104127540, 'LUIS', 'JAIMES VASQUEZ', 'luishernando1810@gmail.com', NULL, 'CONDUCTOR', 1);
INSERT INTO `conductores` (`cedula`, `nombre`, `apellido`, `email`, `celular`, `tipo`, `estado`) VALUES
(1104129723, 'HUGHES', 'QUINTERO VILLAR', NULL, NULL, 'CONDUCTOR', 1),
(1109415393, 'JUAN CAMILO ENCISO ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1109417886, 'OBEIMAR MONTOYA MOGOLLON', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1110117346, 'FRANCISCO JAVIER RODRIGUEZ COL', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1115914461, 'Sebastian Vargas sanchez', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1116158317, 'HILMAR JAVIER CABRERA CRUZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1118529925, 'Maria Eugenia', 'Gutierrez Camaño', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1121911838, 'JEISSON ANDRES', 'SANCHEZ CEBALLOS', 'LCARDONAV@GMAIL.COM', NULL, 'CONDUCTOR', 1),
(1124362408, 'ANDRES AUGUSTO', 'RUIZ CORDOBA', 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1128402743, 'SAUL ANDRES REYES HERNANDEZ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1129541927, 'JOSE RAUL', 'BERMUDEZ MENDOZA', NULL, NULL, 'CONDUCTOR', 1),
(1131108619, 'Sebastián Vélez  Hernadez ', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1143325890, 'HAROLD MANUEL', 'RIOS MAQUILON', NULL, '6292026', 'CONDUCTOR', 1),
(1143329888, 'HECTOR ENRIQUE', 'CARRASCAL PATIÑO', NULL, NULL, 'CONDUCTOR', 1),
(1143347304, 'GABRIEL FERNANDO BUSTAMANTE RI', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1232888211, 'GOBER ANDRES', 'PAEZ DIAZ', NULL, NULL, 'CONDUCTOR', 1),
(1233690948, 'CAMILO ANDRES SALAMANCA', NULL, 'analistaplexa@gmail.com', NULL, 'CONDUCTOR', 1),
(1234567890, 'Prueba', NULL, 'henjoca20@gmail.com', NULL, 'CONDUCTOR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `numero` int(2) NOT NULL,
  `estado` varchar(10) NOT NULL,
  `conductor` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`numero`, `estado`, `conductor`) VALUES
(1, 'Disponible', NULL),
(2, 'Disponible', NULL),
(3, 'Disponible', NULL),
(4, 'Disponible', NULL),
(5, 'Disponible', NULL),
(6, 'Disponible', NULL),
(7, 'Disponible', NULL),
(8, 'Disponible', NULL),
(9, 'Disponible', NULL),
(10, 'Disponible', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones_historial`
--

CREATE TABLE `habitaciones_historial` (
  `id` int(11) NOT NULL,
  `habitacion` int(2) NOT NULL,
  `estado` varchar(10) NOT NULL,
  `conductor` int(10) NOT NULL,
  `usuario` int(10) NOT NULL,
  `fecha` datetime NOT NULL,
  `c_conductor` varchar(255) DEFAULT NULL,
  `n_conductor` varchar(255) DEFAULT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `usuario_check_in` varchar(255) DEFAULT NULL,
  `usuario_check_out` varchar(255) DEFAULT NULL,
  `tiempo_uso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `habitaciones_historial`
--

INSERT INTO `habitaciones_historial` (`id`, `habitacion`, `estado`, `conductor`, `usuario`, `fecha`, `c_conductor`, `n_conductor`, `check_in`, `check_out`, `usuario_check_in`, `usuario_check_out`, `tiempo_uso`) VALUES
(2, 8, 'Ocupada', 73580643, 1047418839, '2025-12-26 16:38:31', '73580643', 'OSWALDO', '2025-12-26 20:38:31', '2025-12-26 21:40:04', 'Henry Jose Castro Ricardo', 'Henry Jose Castro Ricardo', 3694),
(3, 2, 'Ocupada', 77140085, 1047418839, '2025-12-26 16:47:41', '77140085', 'ALBEIRO', '2025-12-26 21:47:41', '2025-12-29 13:44:01', 'Henry Jose Castro Ricardo', 'Henry Jose Castro Ricardo', 230180),
(6, 10, 'Ocupada', 1065873175, 1041972451, '2026-02-20 07:44:13', '1065873175', 'Andrey fernando ', '2026-02-20 12:44:13', '2026-02-20 12:46:09', 'Roys Rafael Simarra Gomez', 'Roys Rafael Simarra Gomez', 117),
(7, 1, 'Ocupada', 4255448, 1041972451, '2026-02-20 07:44:23', '4255448', ' Carlos Arturo Toncon Sepulved', '2026-02-20 12:44:23', '2026-02-20 12:46:01', 'Roys Rafael Simarra Gomez', 'Roys Rafael Simarra Gomez', 99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indicadores`
--

CREATE TABLE `indicadores` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `valor_anterior` decimal(10,2) DEFAULT 0.00,
  `tendencia` varchar(10) DEFAULT 'igual',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `indicadores`
--

INSERT INTO `indicadores` (`id`, `codigo`, `nombre`, `valor`, `valor_anterior`, `tendencia`, `updated_at`) VALUES
(1, 'dolar', 'Dólar TRM', 3652.89, 3670.47, 'baja', '2026-02-16 12:59:33'),
(2, 'euro', 'Euro', 4353.66, 4353.66, 'igual', '2026-02-16 12:59:33'),
(3, 'glp', 'GLP (Gal)', 1800.00, 1750.00, 'sube', '2026-01-28 17:16:21'),
(4, 'gnc', 'GNC (m3)', 1200.00, 1150.00, 'sube', '2026-01-28 17:16:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indicadors`
--

CREATE TABLE `indicadors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `tendencia` varchar(255) NOT NULL DEFAULT 'igual',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `permiso` varchar(30) NOT NULL,
  `observación` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2025_10_20_133058_add_checkin_checkout_to_habitaciones_table', 2),
(6, '0001_01_01_000000_create_users_table', 3),
(7, '0001_01_01_000001_create_cache_table', 3),
(8, '0001_01_01_000002_create_jobs_table', 3),
(9, '2025_10_23_083421_create_historial_habitaciones_table', 3),
(10, '2025_10_28_104352_add_campos_detalle_to_habitaciones_historial', 3),
(11, '2026_01_26_105706_create_3_tickets_table', 4),
(13, '2026_01_28_102619_create_indicadors_table', 5),
(14, '2026_02_19_130055_add_datos_viajero_to_3_tickets_table', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `paginas` int(11) NOT NULL,
  `permiso` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `paginas`, `permiso`) VALUES
(3, 3, 'Actualizar Conductores'),
(4, 7, 'Hotel'),
(5, 4, 'Desasignar'),
(6, 4, 'Asignar'),
(7, 14, 'Aprobar Viajes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_modulos`
--

CREATE TABLE `permisos_modulos` (
  `id` int(11) NOT NULL,
  `roles` int(2) NOT NULL,
  `paginas` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos_modulos`
--

INSERT INTO `permisos_modulos` (`id`, `roles`, `paginas`) VALUES
(1, 1, 'Dashboard'),
(2, 1, 'Páginas'),
(3, 1, 'Tabla Conductores'),
(4, 1, 'Hotel'),
(5, 1, 'Historial Habitacion'),
(6, 4, 'Usuarios'),
(7, 1, 'Usuarios'),
(8, 1, 'Roles'),
(9, 2, 'Roles'),
(12, 1, 'Precio GLP'),
(13, 1, 'Carrusel'),
(14, 1, 'Solicitar Viaje'),
(15, 1, 'Gestion Viajes'),
(16, 1, 'Solicitar Viaje'),
(17, 2, 'Gestion Viajes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_roles`
--

CREATE TABLE `permisos_roles` (
  `id` int(2) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos_roles`
--

INSERT INTO `permisos_roles` (`id`, `nombre`) VALUES
(1, 'Super Administrador'),
(2, 'Administradores'),
(3, 'Despachadores'),
(4, 'Trafico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fvbYM44nfGtp6dtVdzbW67AYPHjC4NVX5xkSlZ30', 1041972451, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoia3FmZHRQeWY2Qlo2WVRhQVQ3RG1WMklOQ29MQUpXbW05QVJwaUZuUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c3VhcmlvcyI7czo1OiJyb3V0ZSI7czo4OiJ1c3VhcmlvcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEwNDE5NzI0NTE7czoxODoibW9kdWxvc19wZXJtaXRpZG9zIjthOjEyOntpOjA7czo5OiJEYXNoYm9hcmQiO2k6MTtzOjg6IlDDoWdpbmFzIjtpOjI7czoxNzoiVGFibGEgQ29uZHVjdG9yZXMiO2k6MztzOjU6IkhvdGVsIjtpOjQ7czoyMDoiSGlzdG9yaWFsIEhhYml0YWNpb24iO2k6NTtzOjg6IlVzdWFyaW9zIjtpOjY7czo1OiJSb2xlcyI7aTo3O3M6MTA6IlByZWNpbyBHTFAiO2k6ODtzOjg6IkNhcnJ1c2VsIjtpOjk7czoxNToiU29saWNpdGFyIFZpYWplIjtpOjEwO3M6MTQ6Ikdlc3Rpb24gVmlhamVzIjtpOjExO3M6MTU6IlNvbGljaXRhciBWaWFqZSI7fXM6MTk6InBlcm1pc29zX3Blcm1pdGlkb3MiO2E6NTp7aTowO3M6MjI6IkFjdHVhbGl6YXIgQ29uZHVjdG9yZXMiO2k6MTtzOjU6IkhvdGVsIjtpOjI7czoxMDoiRGVzYXNpZ25hciI7aTozO3M6NzoiQXNpZ25hciI7aTo0O3M6MTQ6IkFwcm9iYXIgVmlhamVzIjt9fQ==', 1771605944);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cedula` int(20) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `cel` varchar(30) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` int(2) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cedula`, `Nombre`, `Apellido`, `email`, `cel`, `contraseña`, `rol`, `estado`) VALUES
(1041972451, 'Roys Rafael', 'Simarra Gomez', 'roisroisomg@gmail.com', '3245145599', 'Plexa2025', 1, 1),
(1042975487, 'plexa_core', 'aja', 'bueno@gmail.com', NULL, 'Plexa2025', 1, 1),
(1047418839, 'Henry Jose', 'Castro Ricardo', 'hcastro@plexa.co', NULL, 'Plexa2025', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `1_carrusel`
--
ALTER TABLE `1_carrusel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `2_precios_glp`
--
ALTER TABLE `2_precios_glp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `3_tickets`
--
ALTER TABLE `3_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`numero`);

--
-- Indices de la tabla `habitaciones_historial`
--
ALTER TABLE `habitaciones_historial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `indicadores`
--
ALTER TABLE `indicadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `indicadors`
--
ALTER TABLE `indicadors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `indicadors_codigo_unique` (`codigo`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`paginas`),
  ADD KEY `id_2` (`id`),
  ADD KEY `modulos` (`paginas`);

--
-- Indices de la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`roles`);

--
-- Indices de la tabla `permisos_roles`
--
ALTER TABLE `permisos_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `1_carrusel`
--
ALTER TABLE `1_carrusel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `2_precios_glp`
--
ALTER TABLE `2_precios_glp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `3_tickets`
--
ALTER TABLE `3_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habitaciones_historial`
--
ALTER TABLE `habitaciones_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `indicadores`
--
ALTER TABLE `indicadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `indicadors`
--
ALTER TABLE `indicadors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `permisos_roles`
--
ALTER TABLE `permisos_roles`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`paginas`) REFERENCES `permisos_modulos` (`id`);

--
-- Filtros para la tabla `permisos_modulos`
--
ALTER TABLE `permisos_modulos`
  ADD CONSTRAINT `permisos_modulos_ibfk_1` FOREIGN KEY (`roles`) REFERENCES `permisos_roles` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `permisos_roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
