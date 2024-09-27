-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------


-- "PERTSONAK" taula

CREATE TABLE `PERTSONAK` (
  `izenAbizenak` varchar(255) NOT NULL,
  `NAN` varchar(10) NOT NULL,
  `telefonoa` INT(9) NOT NULL,
  `jaiotzeData` VARCHAR(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`NAN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- "ERABILTZAILEAK" taula

CREATE TABLE `ERABILTZAILEAK` (
  `erabiltzailea` varchar(255) NOT NULL,
  `pasahitza` CHAR(60) NOT NULL,
  `NAN` varchar(10) NOT NULL,
  PRIMARY KEY (`erabiltzailea`)
  FOREIGN KEY (`NAN`) REFERENCES `PERTSONAK`(`NAN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- "INBENTARIOA" taula

CREATE TABLE `INBENTARIOA` (
  `izena` varchar(255) NOT NULL,
  `marka` varchar(255) NOT NULL,
  `modeloa` varchar(255) NOT NULL,
  `serieZenbakia` varchar(255) NOT NULL,
  `kokalekua` varchar(255) NOT NULL,
  PRIMARY KEY (`serieZenbakia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`) VALUES
(1, 'mikel'),
(2, 'aitor');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;