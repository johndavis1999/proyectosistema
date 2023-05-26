-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2023 a las 07:14:23
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dscriptn_db_sistema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `descripcion`) VALUES
(1, 'Administrador'),
(2, 'Gerente'),
(3, 'Vendedor'),
(4, 'Jefe De ventas'),
(5, 'Contador'),
(6, 'Secretaria'),
(7, 'Supervisor'),
(8, 'Bodeguero'),
(9, 'Soporte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_producto`
--

CREATE TABLE `categoria_producto` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria_producto`
--

INSERT INTO `categoria_producto` (`id`, `descripcion`, `estado`) VALUES
(1, 'Insumos medicos', 0),
(2, 'test delete', 1),
(3, 'asdasdasd', 1),
(5, 'Equipo de computo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `num_fact` varchar(12) NOT NULL,
  `autorizacion_fact` varchar(50) NOT NULL,
  `id_per_prov` int(11) NOT NULL,
  `fecha_doc` date NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(250) NOT NULL,
  `doc_adjunto` varchar(100) NOT NULL,
  `subttl_iva12` float NOT NULL,
  `subttl_iva0` float NOT NULL,
  `val_descuento` float NOT NULL,
  `val_iva` float NOT NULL,
  `total` int(11) NOT NULL,
  `pagado` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `num_fact`, `autorizacion_fact`, `id_per_prov`, `fecha_doc`, `fecha_registro`, `descripcion`, `doc_adjunto`, `subttl_iva12`, `subttl_iva0`, `val_descuento`, `val_iva`, `total`, `pagado`, `estado`) VALUES
(1, '2', '123123', 32, '2023-05-12', '2023-05-26 02:43:25', '', '', 0, 0, 0, 0, 0, 0, 1),
(2, '213-123-1231', '', 0, '0000-00-00', '2023-05-26 02:44:12', '', '', 0, 0, 0, 0, 0, 0, 1),
(3, '001-001-0000', '111111111111111111', 32, '2023-05-14', '2023-05-26 02:46:21', 'prueba', '', 123, 456, 789, 12, 500, 0, 1),
(4, '123-131-2312', '12312312', 32, '2023-01-01', '2023-05-26 03:01:32', '', '', 123123, 123, 232, 232, 232, 0, 1),
(5, '111-111-11', '1233333333333333333333333333', 32, '2023-05-17', '2023-05-26 03:07:29', '', '', 11, 222, 333, 444, 555, 0, 1),
(6, '112-222-2222', '12345678901', 32, '2023-05-27', '2023-05-26 03:13:24', '', '', 123, 11, 11, 22, 222, 0, 1),
(7, '123-123-1234', '123123', 32, '2023-05-27', '2023-05-26 03:15:04', '', '', 33, 444, 555, 666, 777, 0, 1),
(8, '111-111-1111', '12345678901', 32, '2023-05-19', '2023-05-26 03:16:59', '', '', 123, 123, 123, 123, 123, 0, 1),
(9, '001-001-0000', '12345678901222222123333333333333333333333333333333', 32, '2023-05-18', '2023-05-26 03:17:34', '', '', 11, 11, 222, 22, 33, 0, 1),
(10, '', '', 0, '0000-00-00', '2023-05-26 03:43:28', 'qw', '', 0, 0, 0, 0, 0, 0, 1),
(11, '', '', 0, '0000-00-00', '2023-05-26 03:43:50', 'asdsad', '', 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `precio` float NOT NULL,
  `descuento` float NOT NULL,
  `iva` float NOT NULL,
  `subtotal` float NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `identificacion` varchar(10) NOT NULL,
  `es_extranjero` tinyint(1) NOT NULL,
  `nombres` varchar(250) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(13) NOT NULL,
  `es_empleado` tinyint(1) NOT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `es_cliente` tinyint(1) NOT NULL,
  `es_proveedor` tinyint(1) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `identificacion`, `es_extranjero`, `nombres`, `correo`, `telefono`, `es_empleado`, `id_cargo`, `es_cliente`, `es_proveedor`, `estado`) VALUES
(1, '0923896591', 0, 'John Marcos Davis Cedeño', 'correo1@gmail.com', '0962141185', 0, 0, 1, 0, 1),
(2, '912345678', 1, 'Fernando Cervantes1', 'correo2@gmail.com', '0962141185', 0, 2, 1, 0, 1),
(30, '1234567890', 0, 'Charles Bukowski', '123123@121.com', '89789789', 0, 0, 1, 0, 1),
(31, '9238965911', 0, 'Charles Bukowski', 'jdyglol123@gmail.com', '89789789', 0, 0, 1, 0, 1),
(32, '0907064968', 0, 'ABAD RAMIREZ MILTON MAURICIO', 'correo@milton.com', '12345677', 1, 3, 1, 1, 1),
(33, '0911127520', 0, 'prueba imagen', 'correo@milton.com', '12345677', 1, 7, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `es_inventariable` tinyint(1) NOT NULL,
  `stock` float NOT NULL,
  `porcentaje_iva` float NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_compra` float NOT NULL,
  `descuento` float NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `id_categoria`, `es_inventariable`, `stock`, `porcentaje_iva`, `precio_venta`, `precio_compra`, `descuento`, `estado`) VALUES
(1, 'MSK QJ', 'Mascarillas quirurjicas', 2, 1, 5, 12, 2.8, 22, 0, 0),
(2, 'MOUSE', 'Mouse gamer', 1, 1, 20, 12, 111, 222, 0, 0),
(4, 'KB123', 'teclado mecanico', 2, 1, 11, 0, 111, 2222, 50, 1),
(5, 'adasdas', 'asdasdas', 2, 0, 0, 0, 1231, 12123100, 1, 1),
(6, 'dsfgsdfgsd', 'sdfgdfgsdgfsfd', 2, 0, 0, 12, 56464, 4564640, 1, 1),
(7, 'fgdgdfg', 'dfgdfgdfg', 2, 0, 0, 0, 765675, 67575, 0, 1),
(8, 'prueba', 'adsadasd', 5, 0, 0, 12, 1, 2, 50, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `descripcion`) VALUES
(1, 'Admin'),
(2, 'Supervisor'),
(3, 'Auditor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `id_persona`, `id_rol`, `imagen`, `estado`) VALUES
(26, 'admin123', '$2y$10$FudUGTMTxdG9E6YP5iCuuOL5YaxpCEDeTvrT19tPH/iZ6JTonkaE.', 1, 2, '1683697626_95840d16314b9283dff0.jpg', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
