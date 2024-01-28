-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2024 a las 22:12:07
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `nombre`, `cedula`, `telefono`, `correo`) VALUES
(1, 'abc', '123', '123', 'abc@gmail.com'),
(2, 'aaa', '111', '111', 'aaa@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `cedula` int(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `seccion` varchar(10) DEFAULT NULL,
  `periodo` varchar(10) DEFAULT NULL,
  `trayecto` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `nombre`, `cedula`, `telefono`, `correo`, `seccion`, `periodo`, `trayecto`) VALUES
(11, 'ronnelys del jesus coronado ', 263587415, '01258963225', 'marcs@gmail.com', '1', '2012-2013', '1'),
(12, 'jose ramon perez lola ', 326589741, '04162368532', 'yorgelis@gmail.com', '1', '2018-2019', '1'),
(13, 'ian gael perez lopez', 124567895, '04162364589', 'hsygcbvd@gmail.com', '2', '2023-2024', '3'),
(14, 'jhose manuel heriquez gomez', 263589245, '04263952631', 'hola@hmai.com', '1', '2023-2024', '1'),
(15, 'albert alex esparragoza mata', 263567895, '04123957945', '', '1', '2018-2019', '1'),
(16, 'belkis pocajonta', 263548545, '04142365841', '', '1', '2023-2024', '1'),
(17, 'pedro tomas', 245132655, '04143695123', 'gomes@gmail.com', '1', '2018-2019', '1'),
(18, 'yole santil', 264857963, '04268521452', 'yorgelidddds@gmail.com', '1', '2023-2024', '1'),
(19, 'wham josoe', 23965741, '04123647866', 'marcoyggs@gmail.com', '1', '2023-2024', '1'),
(20, 'hola jbdhd', 25781212, '04127937139', 'kjnkknnb@gmail.com', '1', '2018-2019', '1'),
(21, 'ifrvvf gttgg', 26355896, '04249657411', 'gfgvgcc@gmail.com', '1', '2023-2024', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_grupos`
--

CREATE TABLE `estudiantes_grupos` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estudiantes_grupos`
--

INSERT INTO `estudiantes_grupos` (`id`, `estudiante_id`, `grupo_id`) VALUES
(13, 12, 10),
(14, 15, 10),
(15, 17, 11),
(16, 20, 12),
(17, 11, 13),
(18, 14, 14),
(19, 16, 14),
(20, 18, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `seccion` varchar(10) NOT NULL,
  `trayecto` varchar(10) NOT NULL,
  `periodo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `numero`, `seccion`, `trayecto`, `periodo`) VALUES
(13, 1, '1', '1', '2012-2013'),
(10, 1, '1', '1', '2018-2019'),
(14, 1, '1', '1', '2023-2024'),
(11, 2, '1', '1', '2018-2019'),
(12, 3, '1', '1', '2018-2019');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `tutor_academico` varchar(255) DEFAULT NULL,
  `tutor_empresarial` varchar(255) DEFAULT NULL,
  `jurado` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `titulo`, `tutor_academico`, `tutor_empresarial`, `jurado`, `fecha`, `pdf`, `grupo_id`) VALUES
(26, 'prueva', '2', 'mohana', '1', '5655-06-05', 'Microsoft Word - apuntes MatemÃ¡ticas BÃ¡sicas.doc.pdf', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(25) NOT NULL,
  `contrasena` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contrasena`) VALUES
(1, 'canela@gmail.com', 'Linda1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiantes_grupos`
--
ALTER TABLE `estudiantes_grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grupo` (`numero`,`seccion`,`trayecto`,`periodo`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `estudiantes_grupos`
--
ALTER TABLE `estudiantes_grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes_grupos`
--
ALTER TABLE `estudiantes_grupos`
  ADD CONSTRAINT `estudiantes_grupos_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  ADD CONSTRAINT `estudiantes_grupos_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
