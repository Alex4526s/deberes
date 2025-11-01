-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-04-2025 a las 17:08:44
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
-- Base de datos: `hornipan`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`, `descripcion`) VALUES
(1, 'pendiente', 'La orden está pendiente de ser procesada'),
(2, 'procesando', 'La orden está en proceso'),
(3, 'finalizado', 'La orden ha sido completada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `pedido` varchar(50) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `fecha`, `hora_inicio`, `hora_fin`, `tipo`, `observaciones`, `pedido`, `archivo`, `tecnico_id`, `estado`) VALUES
(5, '2025-04-14', '11:00:00', '12:00:00', 'mantenimiento', 'Quito https://maps.app.goo.gl/CLb2LGNa3BRRKhyp8', 'p000000000014', '1744485491_ANIBAL_WLADIMIR_TOCTAGUANO_IZA.pdf', 4, 'Pendiente'),
(10, '2025-04-14', '10:00:00', '10:30:00', 'instalacion', 'Latacunga https://maps.app.goo.gl/jwrauzCWr1nmpYUC7', 'p000000000012', '1744640039_Captura de pantalla 2024-10-29 164031.png', 1, 'Pendiente'),
(11, '2025-04-15', '10:00:00', '12:00:00', 'instalacion', 'Latacunga https://maps.app.goo.gl/CLb2LGNa3BRRKhyp8', 'p000000000016', '1744640291_Captura de pantalla 2024-10-30 200018.png', 4, 'Pendiente'),
(12, '2025-04-16', '07:00:00', '10:00:00', 'mantenimiento', '', 'p0000000000123', '', 1, 'Pendiente'),
(13, '2025-04-17', '16:00:00', '20:00:00', 'mantenimiento', '', 'p0000000000100', '', 1, 'Pendiente'),
(14, '2025-04-18', '15:00:00', '16:00:00', 'mantenimiento', '', 'p00000000001609', '', 1, 'Pendiente'),
(15, '2025-04-20', '15:00:00', '16:00:00', 'mantenimiento', '', 'p000000000013', '', 1, 'Pendiente'),
(16, '2025-04-20', '07:00:00', '08:00:00', 'mantenimiento', '', 'p000000000013000', '', 1, 'Pendiente'),
(17, '2025-04-15', '07:00:00', '12:00:00', 'instalacion', 'Latacunga https://maps.app.goo.gl/CLb2LGNa3BRRKhyp8', 'p000000000013000000', '1744642084_Captura de pantalla 2024-10-30 200018.png', 5, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `celular` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `nombre`, `correo`, `celular`) VALUES
(1, 'Alex  Steven Caisalitin', 'ascaisalitin@espe.edu.ec', '0995215250'),
(4, 'Steven Jaramillo', 'alexcaisalitin11@gmail.com', '0995215250'),
(5, 'Steven Tapia', 'alexstevencaisalitinjaramillo@gmail.com', '0987654322');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `primera_vez` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `primera_vez`) VALUES
(10, 'admin', '$2y$10$dROHneOiBHgpduYQ72o3AOtyF/XofnG5fWqRYS.LF/W77vtMT8KmW', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
