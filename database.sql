-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `euskadigital`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ERABILTZAILEAK`
--

CREATE TABLE `ERABILTZAILEAK` (
  `izenAbizenak` VARCHAR(255) NOT NULL,
  `NAN` VARCHAR(10) NOT NULL PRIMARY KEY,
  `telefonoa` INT(9) NOT NULL,
  `jaiotzeData` DATE NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  -- `erabiltzailea` VARCHAR(255) NOT NULL,
  -- `pasahitza` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`izenAbizenak`, `NAN`, `telefonoa`, `jaiotzeData`, `email`) VALUES
('Mikel Aranburu', '12345678A', 666666666, '1999-01-01', 'mikel.aranburu@gmail.com'),
('Gaizka Carmona', '12345678B', 666666666, '1999-01-01', 'gaizka.carmona@gmail.com'),
('Aitor Etxebarria', '12345678C', 666666666, '1999-01-01', 'aitor.etxebarria@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
-- ALTER TABLE `usuarios`
--  ADD PRIMARY KEY (`id`);
--COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
