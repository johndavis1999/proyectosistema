-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-08-2023 a las 18:51:01
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

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
-- Estructura de tabla para la tabla `bancos`
--

CREATE TABLE `bancos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bancos`
--

INSERT INTO `bancos` (`id`, `nombre`) VALUES
(1, 'Banco Guayaquil'),
(2, 'Banco Internacional'),
(3, 'Banco del Pacifico'),
(4, 'Banco Bolivariano'),
(5, 'Banco del Austro'),
(6, 'Banco ProCredit'),
(7, 'Banco Amazonas'),
(8, 'Banco del Litoral'),
(9, 'Banco Solidario'),
(10, 'Banco Produbanco'),
(11, 'Cooperativa de Ahorro y Crédito CREA'),
(12, 'Cooperativa de Ahorro y Crédito Mushuc Runa'),
(13, 'Cooperativa de Ahorro y Crédito JEP'),
(14, 'Cooperativa de Ahorro y Crédito Cámara de Comercio de Quito'),
(15, 'Cooperativa de Ahorro y Crédito Policía Nacional'),
(16, 'Cooperativa de Ahorro y Crédito San Francisco'),
(17, 'Cooperativa de Ahorro y Crédito Riobamba'),
(18, 'Cooperativa de Ahorro y Crédito Jardín Azuayo'),
(19, 'Otro');

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
(2, 'Supervisor'),
(3, 'Vendedor');

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
(1, 'Suministros de oficina', 1),
(2, 'Electrodomésticos', 1),
(3, 'Ropa y accesorios', 1),
(4, 'Herramientas', 1),
(5, 'Juguetes', 1),
(6, 'Electrónica', 1),
(7, 'Muebles', 1),
(8, 'Artículos deportivos', 1),
(9, 'Productos para mascotas', 1),
(10, 'Productos de belleza', 1),
(11, 'Libros y revistas', 1),
(12, 'Instrumentos musicales', 1),
(13, 'Alimentos y bebidas', 1),
(14, 'Joyería', 1),
(15, 'Productos para el hogar', 1),
(16, 'Automóviles y motocicletas', 1),
(17, 'Productos para jardín', 1),
(18, 'Arte y artesanía', 1),
(19, 'Equipo de oficina', 1),
(20, 'Productos para bebés', 1),
(21, 'Productos electrónicos', 1),
(22, 'Artículos para el cuidado pers', 1),
(23, 'Suministros para el hogar', 1),
(24, 'Equipamiento deportivo', 1),
(25, 'Juegos de mesa', 1),
(26, 'Productos para el cuidado del ', 1),
(27, 'Decoración del hogar', 1),
(28, 'Productos para bebidas alcohól', 1),
(29, 'Accesorios para automóviles', 1),
(30, 'Ropa deportiva', 1),
(31, 'Productos para la limpieza', 1),
(32, 'Instrumentos médicos', 1),
(33, 'Productos para el cuidado de l', 1),
(34, 'Artículos de papelería', 1),
(35, 'Electrodomésticos de cocina', 1),
(36, 'Productos para la jardinería', 1),
(37, 'Productos para la fotografía', 1),
(38, 'Instrumentos de dibujo', 1),
(39, 'Artículos para fiestas', 1),
(40, 'Productos para la higiene dent', 1),
(41, 'Insumos Medicos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros`
--

CREATE TABLE `cobros` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `doc_adjunto` varchar(255) DEFAULT NULL,
  `forma_pago` varchar(20) DEFAULT NULL,
  `num_movimiento` varchar(20) DEFAULT NULL,
  `id_banco` int(11) DEFAULT NULL,
  `fecha_movimiento` date DEFAULT NULL,
  `omitir_validar_mov` tinyint(4) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `valor_cotizacion` decimal(20,2) NOT NULL,
  `valor_pagado` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `num_fact` varchar(20) NOT NULL,
  `autorizacion_fact` varchar(50) NOT NULL,
  `id_per_prov` int(11) NOT NULL,
  `fecha_doc` date NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(250) NOT NULL,
  `doc_adjunto` varchar(100) DEFAULT NULL,
  `subtotal_compra` decimal(20,2) NOT NULL,
  `val_descuento` decimal(20,2) NOT NULL,
  `val_iva` decimal(20,2) NOT NULL,
  `total` decimal(20,2) NOT NULL,
  `pagado` tinyint(1) NOT NULL,
  `valor_pagado` decimal(20,2) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL,
  `num_cot` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `fecha_doc` date NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(250) NOT NULL,
  `subtotal_cotizacion` decimal(20,2) NOT NULL,
  `val_descuento` decimal(20,2) NOT NULL,
  `val_iva` decimal(20,2) NOT NULL,
  `total` decimal(20,2) NOT NULL,
  `pagado` tinyint(4) NOT NULL,
  `valor_pagado` decimal(20,2) NOT NULL,
  `aprobado` tinyint(4) NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(20,2) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `descuento` decimal(20,2) NOT NULL,
  `iva` decimal(20,2) NOT NULL,
  `subtotal` decimal(20,2) NOT NULL,
  `total` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cotizacion`
--

CREATE TABLE `detalle_cotizacion` (
  `id` int(11) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(20,2) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `descuento` decimal(20,2) NOT NULL,
  `iva` decimal(20,2) NOT NULL,
  `subtotal` decimal(20,2) NOT NULL,
  `total` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `doc_adjunto` varchar(255) DEFAULT NULL,
  `forma_pago` varchar(20) DEFAULT NULL,
  `num_movimiento` varchar(20) NOT NULL,
  `num_cheque` varchar(20) DEFAULT NULL,
  `num_transferencia` varchar(20) DEFAULT NULL,
  `id_banco` int(50) DEFAULT NULL,
  `fecha_movimiento` date DEFAULT NULL,
  `id_compra` int(11) NOT NULL,
  `valor_compra` decimal(20,2) NOT NULL,
  `valor_pagado` decimal(20,2) DEFAULT NULL
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
  `estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `identificacion`, `es_extranjero`, `nombres`, `correo`, `telefono`, `es_empleado`, `id_cargo`, `es_cliente`, `es_proveedor`, `estado`) VALUES
(1, '0923896591', 1, 'John Davis', 'prueba@gmail.com', '1234567895', 1, 1, 1, 1, 1),
(2, '0000000000', 0, 'Consumidor Final', 'consumidorfinal@correo.com', '0000000000', 0, NULL, 1, 0, 1),
(3, '0923896591', 0, 'DAVIS CEDEÑO JOHN MARCOS', 'jmdavis@gmail.com', '0962141185', 1, NULL, 0, 0, 1),
(4, '0907064968', 0, 'ABAD RAMIREZ MILTON MAURICIO', 'correo@milton.com', '12345677', 1, 3, 1, 1, 1),
(5, '0901023291', 0, 'ACOSTA VERGARA CLARA JACINTA', 'acosta15@gmail.com', '0987456321', 0, NULL, 1, 0, 1),
(6, '0501262687', 0, ' ALMACHE ALMACHE NELSON RODRIGO', 'almache25@gmail.com', '0985263147', 0, NULL, 0, 1, 1),
(7, '0922333059', 0, ' ALMEIDA CIGUENZA LUIS GERARDO ', 'almeida10@gmail.com', '0963258741', 1, 2, 0, 0, 1),
(8, '0921280947', 1, 'ALVARADO VASQUEZ CESAR EDUARDO', 'alvarado63@gmail.com', '0974125863', 0, NULL, 1, 0, 1),
(9, '0605024132', 0, ' ALVAREZ NAULA WILSON ENRIQUE', 'alvarez11@gmail.com', '0914785236', 1, 3, 0, 0, 1),
(10, '0923909162', 1, ' ARANA VILLALVA DEISY VEIKY', 'arana12@gmail.com', '0915874236', 0, NULL, 0, 1, 1),
(11, '0923909170', 0, 'ARANA VILLALVA PEDRO LEONARDO', 'arana@gmail.com', '0965231478', 0, NULL, 1, 0, 1),
(12, '0927734715', 1, 'AREVALO CHIQUITO IRENE MARLENE', 'arevalo555@gmail.com', '0911122233', 0, NULL, 0, 1, 1),
(13, '1203314040', 1, ' AREVALO QUISPE CARLOTA DEL ROCIO', 'quispe105@gmail.com', '098887412563', 1, 3, 0, 0, 1),
(14, '0922484175', 1, ' AREVALO QUISPE LUIS GERARDO', 'arevalo@gmail.com', '0987456321', 0, NULL, 0, 1, 1),
(15, '1203967110', 1, ' ARIAS CEDEÑO GALO VICTOR', 'cedeno55@gmail.com', '0914785236', 1, 2, 0, 0, 1),
(16, '0907300727', 0, ' AVILA MARTINEZ MARIANA DE LOURDES', 'martinez111@gmail.com', '0958741236', 0, NULL, 1, 0, 1),
(17, '0925981524', 0, ' AYALA JIMENEZ MARCOS ROBERTO', 'ayalaj10@gmail.com', '0968745123', 0, NULL, 0, 1, 1),
(18, '1102020854', 1, 'BARAHONA ALARCON EDUARDO', 'alarcon18@gmail.com', '0910235478', 0, NULL, 1, 0, 1),
(19, '0919140145', 1, 'BARRETO ESCOBAR DIEGO ALEJANDRO', 'escobar28@gmail.com', '0923104568', 1, 1, 0, 0, 1),
(20, '1202542823', 1, 'BARRETO RIVERA JUAN ALFONSO', 'barreto25@gmail.com', '0963214587', 0, NULL, 0, 1, 1),
(21, '0923484026', 0, ' BAUTISTA SOLORZANO RUTH ELIZABETH', 'bautista10@gmail.com', '0945871236', 0, NULL, 1, 0, 1),
(22, '0604660613', 0, ' BRIONES ESCOBAR MARIUXI ALEXANDRA', 'briones14@gmail.com', '0987456321', 1, 2, 0, 0, 1),
(23, '0901870337', 0, ' BRIONES VILLAVICENCIO FRANCISCO CLEMENTE', 'brionesv17@gmail.com', '0914785236', 1, 3, 0, 0, 1),
(24, '0941578726', 0, ' BULGARIN PERALTA LILIANA KATHERINE', 'peralta15@gmail.com', '0952634178', 1, 3, 0, 0, 1),
(25, '0928182120', 1, 'BUÑAY REINO ANA PAOLA', 'bunay20@gmail.com', '0985236417', 0, NULL, 0, 1, 1),
(26, '2150204028', 0, ' BURGOS ALVARADO THALIA DAMARIS', 'burgos55@gmail.com', '0914523687', 1, 1, 0, 0, 1),
(27, '0917110926', 1, ' BUSTAMANTE BENITEZ NARCISA DE JESUS', 'bustamante10@gmail.com', '0965231478', 0, NULL, 0, 1, 1),
(28, '1201884762', 0, ' CABRERA GUEVARA HAYDEE', 'cabrera11@gmail.com', '0945123687', 1, 2, 0, 0, 1),
(29, '1202109516', 1, ' CABRERA GUEVARA RUDIGER ALEXY', 'cabrerag10@gmail.com', '0925641387', 0, NULL, 1, 0, 1),
(30, '0925972432', 1, 'CABRERA JUANAZO GENESIS ESTEFANIA', 'juanazo@gmail.com', '0932568741', 0, NULL, 0, 1, 1),
(31, '0913851556', 0, 'CADENA BERRONES WASHINGTON DAVID', 'cadena25@gmail.com', '0964152387', 1, 3, 0, 0, 1),
(32, '0940156821', 1, ' CAISAGUANO GUACHUN BYRON JEFFERSON', 'byroncg@gmail.com', '0964523178', 0, NULL, 1, 0, 1),
(33, '0927242602', 1, 'CALDERON PLUA BELEN STEFANY', 'calderonplua14@gmail.com', '0963254178', 0, NULL, 1, 0, 1),
(34, '0953400421', 0, 'CERVANTES BURGOS JIMMY FERNANDO', 'jfcervantes@gmail.com', '0981226710', 1, 2, 0, 0, 1),
(35, '0924283575', 1, ' CANACUAN ROSALES MONICA YANETH', 'rosales11@gmail.com', '0954621387', 0, NULL, 1, 0, 1),
(36, '1707764831', 0, ' CANTUÑA ORTEGA EDISON PATRICIO', 'ortega40@gmail.com', '0987256314', 1, 1, 0, 0, 1),
(37, '0903561017', 1, 'CARRANZA ESPINOZA VICTOR MANUEL', 'carranza10@gmail.com', '0955663214', 0, NULL, 0, 1, 1),
(38, '0929601318', 1, 'CARRASCO MOYANO KARINA MARIA', 'carrasco44@gmail.com', '0911236547', 0, NULL, 1, 0, 1),
(39, '0924778517', 1, ' CARRASCO ZAMORA ALVARO ANTONIO', 'carrasco12@gmail.com', '0988745610', 0, NULL, 1, 0, 1),
(40, '0922334271', 0, ' CASTILLO CORDOVA MARIELA JACQUELINE', 'castillo11@gmail.com', '0966541237', 1, 3, 0, 0, 1),
(41, '0923178933', 1, ' CASTILLO LOPEZ LADY GISELLE', 'castilloll@gmail.com', '0922354168', 0, NULL, 0, 1, 1),
(42, '0904638608', 1, 'CASTRO CUEVA JULIO CESAR', 'castro25@gmail.com', '0944587692', 1, 3, 0, 0, 1),
(43, '0919011718', 0, ' CAYAMBE GARZON FRANCISCO ETELBOT', 'cayambe10@gmail.com', '0977856412', 1, 1, 0, 0, 1),
(44, '1201397658', 0, ' CEAS ANGULO GONZALO MAURO', 'ceas12@gmail.com', '0991562234', 0, NULL, 1, 0, 1),
(45, '1305672097', 1, ' CEDEÑO CEDENO ELSA EDUVIGES', 'cedenoelsa11@gmail.com', '0987412563', 1, 1, 0, 0, 1),
(46, '0603853409', 0, ' CELA GUASHPA JESSYCA LEONELA', 'cela25@gmail.com', '0996352147', 1, 2, 0, 0, 1),
(47, '0965074388', 0, ' CHAVEZ ATOCHE OMAR ALEXSANDER', 'chavez@gmail.com', '0966321457', 0, NULL, 1, 0, 1),
(48, '1714498852', 0, 'CHICAIZA PAUCAR ANGEL MARIO', 'chicaizapau64@gmail.com', '0997745862', 1, 1, 0, 0, 1),
(49, '0921631644', 1, 'CISNEROS MONTIEL JORGE LUIS', 'cisnerosmontiel11@gmail.com', '0988547123', 0, NULL, 0, 1, 1),
(50, '9999999999', 1, 'CONSUMIDOR FINAL', 'xxxxxxx@gmail.com', '0000000000', 1, 3, 1, 1, 1),
(51, '0918450685', 0, 'COQUE TOBAR NELLY KATHERINE', 'coque10@gmail.com', '0994563217', 0, NULL, 1, 0, 1),
(52, '0926713843', 0, ' CORDOVA QUIÑONEZ RICHARD ABRAHAM', 'cordovaqr@gmail.com', '0996587412', 1, 3, 0, 0, 1),
(53, '0918221755', 0, ' CORMACHI ACAN SEGUNDO MANUEL', 'cormachiacan@gmail.com', '0987754663', 0, NULL, 0, 1, 1),
(54, '1202504732', 0, ' CORONEL MOYA HECTOR RODRIGO', 'coronel11@gmail.com', '0977885632', 0, NULL, 1, 0, 1),
(55, '0941787848', 0, ' COSTAVALO MOYA NINOSKA KATHRINA', 'costavalo10@gmail.com', '0996325541', 0, NULL, 0, 1, 1),
(56, '0916762503', 0, ' COSTAVALO SAVERIO FRANKLIN VICENTE', 'saveriofv@gmail.com', '0984456874', 1, 1, 0, 0, 1),
(57, '0919617688', 0, 'CUZCO CARANQUI MARCO ANTONIO', 'cuzcocm@gmail.com', '0985567412', 1, 2, 0, 0, 1),
(58, '0905007589', 0, 'DAGER RIVERA ENRIQUE RAMON', 'dagerrivera10@gmail.com', '0966325874', 1, 2, 0, 0, 1),
(59, '0925712549', 0, ' ELIZALDE GRANDES MARCO ANTONIO', 'elizalde55@gmail.com', '0988556633', 0, NULL, 1, 0, 1),
(60, '0906878053', 0, 'ERAZO ALBAN BELGICA TEOLINDA', 'erazo11@gmail.com', '0988412365', 0, NULL, 1, 0, 1),
(61, '0908746415', 0, ' ESPINOZA GUEVARA NIXON GLOVER', 'espinozagn14@gmail.com', '0966258710', 1, 1, 0, 1, 1),
(62, '0917038697', 0, 'ESPINOZA GUEVARA OMAR JAVIER', 'espinozago@gmail.com', '0977885203', 0, NULL, 0, 1, 1),
(63, '0914208723', 0, 'ESTRADA LOMBEIDA ALFREDO ', 'estradalom@gmail.com', '0999963524', 1, 3, 0, 0, 1),
(64, '0104412218', 0, 'FAJARDO MOLINA MIGUEL AURELIO ', 'fajardo@gmail.com', '0998564712', 1, 2, 0, 0, 1),
(65, '0912167392', 0, ' FERNANDEZ MARTINEZ GASTON MARCOS', 'fernandez11@gmail.com', '0999997854', 1, 2, 0, 0, 1),
(66, '0940812662', 0, ' FERNANDEZ RIVERA ANDREA MILENA', 'rivera10@gmail.com', '0981112356', 0, NULL, 0, 1, 1),
(67, '0925008930', 0, 'FLOR CORTEZ LUIS MIGUEL', 'flor13@gmail.com', '0978885412', 1, 2, 0, 0, 1),
(68, '0917385353', 0, ' FONSECA VEGA JESSICA BERTHA', 'fonseca14@gmail.com', '0998744562', 0, NULL, 1, 0, 1),
(69, '0916765894', 0, ' FREIRE CASTRO PATRICIA ALEXANDRA', 'freirec10@gmail.com', '0922223654', 1, 2, 0, 0, 1),
(70, '0916053952', 0, 'FREIRE FLORES FABIAN FABRICIO', 'floresff@gmail.com', '0987775563', 0, NULL, 0, 1, 1),
(71, '0919309005', 0, ' FREIRE PAZMIÑO ROXANA DOLORES', 'roxana@gmail.com', '0997415562', 1, 2, 0, 0, 1),
(72, '0923486435', 0, ' GAIBOR SORIANO SAMANTHA LETICIA', 'gaiboremelec10@gmail.com', '0977775413', 0, NULL, 1, 0, 1),
(73, '0901181131', 0, ' GALEAS SANCHEZ MARCELINO ARMANDO', 'galeas25@gmail.com', '0994123654', 0, NULL, 0, 1, 1),
(74, '0912552288', 0, ' GALEAS VASQUEZ ARMANDO GERMAN', 'galeasv10@gmail.com', '0944445214', 1, 1, 0, 0, 1),
(75, '0919067660', 0, ' GARCIA RODRIGUEZ ALEX JAVIER', 'garcia25@gmail.com', '0988881452', 1, 3, 0, 0, 1),
(76, '0911590131', 0, ' GONSALES GUEVARA CARLOS ESTEBAN', 'gonsalesgc@gmail.com', '0995562314', 0, NULL, 0, 1, 1),
(77, '1311715716', 0, ' GONZALEZ CEDEÑO GINO ALEJANDRO', 'gcgino@gmail.com', '0987542361', 0, NULL, 0, 1, 1),
(78, '0928476282', 0, 'GONZALEZ PIGUAVE KARLA DEL PILAR', 'gppilar@gmail.com', '0998553321', 1, 2, 0, 0, 1),
(79, '0302401047', 0, ' GONZALEZ RODRIGUEZ MARIA FERNANDA', 'grfernanda@gmail.com', '0997456622', 1, 2, 0, 0, 1),
(80, '1207106319', 0, 'GOROTIZA DUTAN NEY HUMBERTO', 'gdney@gmail.com', '0995556487', 0, NULL, 1, 0, 1),
(81, '0913810230', 0, ' GRANDES NARANJO LURDES ELIZABETT', 'gnlurdes@gmail.com', '0996663354', 0, NULL, 1, 0, 1),
(82, '0926479767', 0, ' GUAMAN GARCES JAVIER ALEJANDRO', 'ggjavier@gmail.com', '0998755663', 1, 3, 0, 0, 1),
(83, '0603058710', 0, 'GUAMAN YUPA JORGE EDUARDO', 'gyjorge@gmail.com', '0999965544', 1, 3, 0, 0, 1),
(84, '0606335370', 0, ' GUEVARA MOYANO GISSELA BRIGGITH', 'gmgissela@gmail.com', '0998875632', 1, 2, 0, 0, 1),
(85, '0921183042', 0, ' GUEVARA MOYANO MARTHA MARISELA', 'gmmartha@gmail.com', '0988856321', 1, 2, 0, 0, 1),
(86, '0921183620', 0, 'GUEVARA VELOZ JIMMY MAURICIO', 'gvjimmy@gmail.com', '0981336541', 0, NULL, 1, 0, 1),
(87, '0929323343', 1, 'GUEVARA VELOZ VICTOR MANUEL', 'gvvictor@gmail.com', '0995564772', 0, NULL, 0, 1, 1),
(88, '0301075818', 0, ' HEREDIA HEREDIA JOSE JHONSON', 'hhjose@gmail.com', '0987775563', 1, 1, 0, 0, 1),
(89, '0604733345', 0, ' HERNANDEZ SEGOVIA FRESIA VERONICA', 'hsfresia@gmail.com', '0996665412', 1, 2, 0, 0, 1),
(90, '0920850344', 0, ' HERNANDEZ SEGOVIA MERY GISSELA', 'hsmery@gmail.com', '0963335210', 0, NULL, 0, 1, 1),
(91, '0950211458', 0, 'JAIME SARMIENTO LILIAN GABRIELA', 'jslilian@gmail.com', '0978886544', 0, NULL, 1, 0, 1),
(92, '0913801130', 0, ' JARA MALO SILVIA ALEXANDRA', 'jmsilvia@gmail.com', '0997856632', 0, NULL, 1, 0, 1),
(93, '0917882557', 0, ' KUONQUI PEÑAFIEL RONNY MAURICIO', 'kpronny@gmail.com', '0966665412', 1, 3, 0, 0, 1),
(94, '0605640432', 0, ' LEMA CUJILEMA HUMBERTO ALADINO', 'lchumberto@gmail.com', '0997785410', 0, NULL, 1, 0, 1),
(95, '0917717274', 0, 'LEMA NAULA SEGUNDO MARTIN', 'lnsegundo@gmail.com', '0981225463', 0, NULL, 1, 0, 1),
(96, '1801753821', 0, ' LLERENA CHIPANTIZA MARIA MELIDA', 'lcmaria@gmail.com', '0981225478', 1, 3, 0, 0, 1),
(97, '0910821719', 0, ' LOPEZ MENESES EDGAR ELEUTERIO', 'lmedgar@gmail.com', '0981224896', 0, NULL, 1, 0, 1),
(98, '0927423558', 0, ' MARIDUEÑA CARDENAS DIANA STEFANIA', 'mcdiana@gmail.com', '0956321456', 0, NULL, 0, 1, 1),
(99, '1801297498', 0, 'MARTINEZ ORTIZ HECTOR LUIS', 'mohector@gmail.com', '0987412236', 1, 2, 0, 0, 1),
(100, '1202324164', 0, ' MARTINEZ PINTO ELSA EDITH', 'mpelsa@gmail.com', '0958887456', 1, 3, 0, 0, 1),
(101, '1200555967', 0, ' MARTINEZ PINTO SONIA DEL PILAR', 'mppilar@gmail.com', '0998874563', 0, NULL, 1, 0, 1),
(102, '0942257924', 0, ' MEDINA ZAMBRANO ROSA ANGELICA', 'mzrosa@gmail.com', '0986663215', 1, 2, 0, 0, 1),
(103, '1600113235', 0, 'MENA VALLE MANUEL DE JESUS', 'nvmanuel@gmail.com', '0998885466', 0, NULL, 1, 0, 1),
(104, '0912253366', 0, ' MENDIETA OLEAS ROXANA ALEXANDRA', 'moroxana@gmail.com', '0998756320', 1, 1, 0, 0, 1),
(105, '0924585474', 0, ' MENDIETA VARGAS MAYBE MADELAINE', 'mvmaybe@gmail.com', '0985552320', 1, 3, 0, 0, 1),
(106, '1203225444', 0, 'MENDOZA DUQUE JULIETA IVONNY', 'mdjulieta@gmail.com', '0952311023', 0, NULL, 1, 0, 1),
(107, '0907236541', 0, ' MENESES MORENO JORGE ALADINO', 'mmjorge@gmail.com', '0966321047', 0, NULL, 0, 1, 1),
(108, '1201306816', 0, ' MENESES SANCHEZ MABLINA SUSANA', 'msmablina@gmail.com', '0988756321', 1, 1, 0, 0, 1),
(109, '1312735663', 0, 'MIRANDA ALCIVAR FERNANDO', 'mafernando@gmail.com', '0987776354', 1, 1, 0, 0, 1),
(110, '0905161311', 0, ' MOLINA LOPEZ ANGEL OSWALDO', 'mlangel@gmail.com', '0987456632', 0, NULL, 0, 1, 1),
(111, '0901357186', 0, 'MOLINA LOPEZ LILIA LUCILA', 'mllilia@gmail.com', '0987556321', 1, 3, 0, 0, 1),
(112, '0905126777', 0, ' MOLINA LOPEZ LUIS GUSTAVO', 'mlluis@gmail.com', '0998745562', 0, NULL, 1, 0, 1),
(113, '0907438907', 0, ' MOLINA LOPEZ MILTON HUGO', 'mlmilton@gmail.com', '0988755231', 0, NULL, 0, 1, 1),
(114, '0928180025', 0, ' MOLINA MUÑIZ CHRISTIAN DANIEL', 'mmdaniel@gmail.com', '0995631125', 1, 3, 0, 0, 1),
(115, '0919068411', 0, ' MOLINA VELASTEGUI MARIA ELISA', 'mvmaria@gmail.com', '0992223641', 1, 1, 0, 0, 1),
(116, '0928067248', 0, ' MONAR GUADALUPE SELENA MABEL', 'mgselena@gmail.com', '0988745030', 1, 2, 0, 0, 1),
(117, '0924041148', 0, ' MONTECEL GARCES GINA VANESSA', 'mggina@gmail.com', '0998003652', 1, 3, 0, 0, 1),
(118, '1203379001', 0, ' MONTES ALVARIO VICENTE ANTONIO', 'mavicente@gmail.com', '0977856030', 0, NULL, 0, 1, 1),
(119, '1201704416', 0, 'MORA VASQUEZ RAFAEL CIRILO', 'mvrafael@gmail.com', '0977856630', 1, 1, 0, 0, 1),
(120, '1500626633', 0, 'MURILLO HARO RONALD ANTONIO', 'mhronald@gmail.com', '0990446180', 0, NULL, 1, 0, 1),
(121, '0910489285', 0, ' NARANJO NEGRETE JORGE EFRAIN', 'nnjorge@gmail.com', '0966321452', 1, 1, 0, 0, 1),
(122, '0927738914', 0, ' NARANJO TORRES GEOMARA ESTELA', 'ntgeomara@gmail.com', '0985555025', 1, 1, 0, 0, 1),
(123, '0956857114', 0, 'NARVAEZ SALAZAR JEIMY NATHALY', 'nsjeimy@gmail.com', '0992031010', 1, 3, 0, 0, 1),
(124, '0927002709', 0, 'NARVAEZ VERA YESENIA TAMARA', 'nvyessenia@gmail.com', '0966632020', 1, 2, 0, 0, 1),
(125, '1203050271', 0, 'NAULA BRAVO SEGUNDO RAFAEL', 'nbsegundo@gmail.com', '0995050426', 1, 2, 0, 0, 1),
(126, '0915458616', 0, 'NEIRA REYES IVAN ALEXANDER', 'nrivan@gmail.com', '0994152260', 0, NULL, 0, 1, 1),
(127, '0922731245', 0, ' OÑA CHANGO CHRISTIAN PAUL', 'ocpaul@gmail.com', '0988023654', 0, NULL, 1, 0, 1),
(128, '0940743925', 0, 'ONOFRE BAREN CARLA YULEISI', 'obcarla@gmail.com', '0987774526', 1, 2, 0, 0, 1),
(129, '0916763550', 0, ' ORELLANA VILLAGOMEZ IRMA JACQUELINE', 'ovirma@gmail.com', '0988563312', 1, 3, 0, 0, 1),
(130, '1203071624', 0, ' ORELLANA VILLAGOMEZ ROSA YOLANDA', 'ovrosa@gmail.com', '0978885230', 1, 1, 0, 0, 1),
(131, '0903583318', 0, 'OROZCO OROZCO GUILLERMO WASHINGTON', 'ooguillo@gmail.com', '0912223652', 1, 1, 0, 0, 1),
(132, '0908681729', 0, 'OSORIO SANCHEZ DAVID JACINTO', 'osdavid@gmail.com', '0988556633', 1, 2, 0, 0, 1),
(133, '0928068923', 0, ' PADILLA LEON CHRISTIAN ABELARDO', 'plabelardo@gmail.com', '0997774523', 0, NULL, 1, 0, 1),
(134, '0940124480', 0, ' PAGUAY ICAZA FRANKLIN LEONIDAS', 'pileonidas@gmail.com', '0966321020', 0, NULL, 0, 1, 1),
(135, '0925852931', 0, 'PAREDES GUEVARA JOSE LUIS', 'pgluis@gmail.com', '0995201045', 1, 2, 0, 0, 1),
(136, '0914060314', 0, ' PAREDES MORENO WASHINGTON GIOVANNY', 'pmgiovanny@gmail.com', '0996332010', 1, 1, 0, 0, 1),
(137, '0925407363', 0, ' PARRALES COELLO FERNANDA ESTEFANIA', 'pcfernanda@gmail.com', '0996314456', 1, 2, 0, 0, 1),
(138, '0922647060', 0, 'PERALTA ROJAS VICENTE IVAN', 'prvicente@gmail.com', '0987774526', 0, NULL, 1, 0, 1),
(139, '1717192767', 0, ' PEREZ ORTIZ JUAN DAVID', 'pojuan@gmail.com', '0996541230', 0, NULL, 0, 1, 1),
(140, '0908628167', 0, ' PEREZ ZAVALA MARIA CARLOTA', 'pzmaria@gmail.com', '0978523302', 1, 3, 0, 0, 1),
(141, '0928183706', 0, ' PESANTES MARQUEZ JOSELYN AYDEE', 'pmjoselyn@gmail.com', '0996352014', 1, 3, 0, 0, 1),
(142, '0927877464', 0, ' PITA BARAHONA STEEVEN REINALDO', 'pbreinaldo@gmail.com', '0998523652', 1, 2, 0, 0, 1),
(143, '0918862806', 0, ' PLAZA SANCHEZ MARLON FABIAN', 'psmarlon@gmail.com', '0985412365', 0, NULL, 0, 1, 1),
(144, '1201807334', 0, ' POAQUIZA MAYORGA LUIS ERNESTO', 'pmluis@gmail.com', '0987456321', 1, 2, 0, 0, 1),
(145, '0602340069', 0, 'POMAQUIZA SEFLA JUAN', 'psjuan@gmail.com', '0987563214', 0, NULL, 1, 0, 1),
(146, '0911187318', 0, ' PORTUGAL COSTALES NESTOR BERNARDO', 'pcnestor@gmail.com', '0941256387', 1, 2, 0, 0, 1),
(147, '1200982617', 0, 'PUNIN TELLO FELIX ARTURO', 'ptfelix@gmail.com', '0963214785', 0, NULL, 0, 1, 1),
(148, '1201365903', 0, 'PUNIN TELLO GIL RICARDO', 'ptgil@gmail.com', '0985236417', 0, NULL, 1, 0, 1),
(149, '1201834908', 0, ' QUEZADA LEON VICTOR ALEXANDER', 'qlvictor@gmail.com', '0985200364', 0, NULL, 1, 0, 1),
(150, '0907269740', 0, 'QUIÑONEZ ROMERO CLIMACO ZODABEL', 'qrclimaco@gmail.com', '0987456320', 1, 3, 0, 0, 1),
(151, '0926472481', 0, 'QUIÑONEZ SALDAÑA JEFFERSON OMAR', 'qsomar@gmail.com', '0985236410', 1, 1, 0, 0, 1),
(152, '0921631669', 0, ' QUINTUÑA MORENO JUAN PABLO', 'qmjuan@gmail.com', '0963211456', 0, NULL, 1, 0, 1),
(153, '0916219751', 0, 'RAMOS CASTRO NELSON SIDNEY', 'rcnelson@gmail.com', '0995231456', 0, NULL, 0, 1, 1),
(154, '0912604477', 0, 'REINOSO ORTIZ RICARDO ARTURO', 'roricardo@gmail.com', '0998875263', 0, NULL, 0, 1, 1),
(155, '0914909213', 0, ' REMACHE MALA LAURA MARGARITA', 'rmlaura@gmail.com', '0987445632', 0, NULL, 0, 1, 1),
(156, '1203560766', 0, 'RIVERA NAREA YILDA NINETH', 'rnyilda@gmail.com', '0992233145', 1, 3, 0, 0, 1),
(157, '0901187583', 0, ' RIVERA ROBALINO CARLOS RODOLFO', 'rrrodolfo@gmail.com', '0998552300', 0, NULL, 1, 0, 1),
(158, '0926401068', 0, 'RIVERA SARMIENTO MARIUXI TAMARA', 'rsmariuxi@gmail.com', '0998566321', 1, 1, 0, 0, 1),
(159, '1308998200', 0, ' RODRIGUEZ PILAY RODRIGO BONIFACIO', 'rprodrigo@gmail.com', '0963524810', 1, 3, 0, 0, 1),
(160, '0911762128', 0, 'ROSAS NOBOA LENIN STALIN', 'rnlenin@gmail.com', '0941558630', 0, NULL, 1, 0, 1),
(161, '0921636270', 0, ' SAAVEDRA FREIRE JESSENIA VANNESA', 'sfvannesa@gmail.com', '0988547123', 0, NULL, 0, 1, 1),
(162, '0919018317', 0, 'SALAZAR YEPEZ RICARDO JORGE', 'syricardo@gmail.com', '0988523641', 0, NULL, 1, 0, 1),
(163, '0919068940', 0, 'SANCHEZ LOPEZ DARWIN ORLANDO', 'sldarwin@gmail.com', '0963524177', 1, 3, 0, 0, 1),
(164, '0912474962', 0, 'SANCHEZ ORTIZ LUIS ENRIQUE', 'soluis@gmail.com', '0955632102', 1, 2, 0, 0, 1),
(165, '1201933015', 0, ' SANCHEZ QUINTANILLA EULALIA DORILA', 'sqeulalia@gmail.com', '0987456321', 1, 3, 0, 0, 1),
(166, '0912578713', 0, ' SANCHEZ QUINTANILLA MANUEL DE LOS ANGELES', 'sqmanuel@gmail.com', '0998745632', 0, NULL, 0, 1, 1),
(167, '0910341155', 0, ' SANCHEZ RAMIREZ VERNARDO NAPOLEON', 'srvernardo@gmail.com', '0985231120', 0, NULL, 1, 0, 1),
(168, '0918866237', 0, ' SARMIENTO ALVARADO ESTEFANI DEL ROCIO', 'saestefani@gmail.com', '0955201136', 1, 2, 0, 0, 1),
(169, '0916868268', 0, ' SEDAMANOS BENALCAZAR ENRIQUE ALBERTO', 'sbenrique@gmail.com', '0977556321', 1, 2, 0, 0, 1),
(170, '0911078335', 0, ' SEMINARIO LITARDO KAFFA ROSA ESTHER', 'slkaffa@gmail.com', '0998524123', 1, 1, 0, 0, 1),
(171, '0928733385', 0, ' SILVA GOROTIZA NELSON AMADO', 'sgamado@gmail.com', '0978563210', 0, NULL, 1, 0, 1),
(172, '0908758865', 0, ' SILVA MENDOZA CECILIA NARCISA', 'smcecilia@gmail.com', '0974586321', 0, NULL, 0, 1, 1),
(173, '0919016667', 0, 'SILVA VALLEJO CELSO GENARO ', 'svcelso@gmail.com', '0966332210', 1, 3, 0, 0, 1),
(174, '1201851589', 0, ' SOLIS MINDIOLA NICOLAS LENAR', 'smnicolas@gmail.com', '0985214452', 1, 3, 0, 0, 1),
(175, '0908534712', 0, ' SOLIS VELIZ FLAVIO AMADOR', 'svamador@gmail.com', '0963112042', 1, 1, 0, 0, 1),
(176, '0918869215', 0, 'SOTOMAYOR MORENO AMADA PIEDAD', 'smamada@gmail.com', '0985412033', 1, 1, 0, 0, 1),
(177, '0904707080', 0, 'SUAREZ NUÑEZ LUPE GLADYS', 'snlupe@gmail.com', '0966332102', 0, NULL, 1, 0, 1),
(178, '1205790858', 0, ' SUAREZ ZAMBRANO JONATHAN HENRY', 'szhenry@gmail.com', '0996321145', 0, NULL, 0, 1, 1),
(179, '0919032441', 0, 'SUCUZHAÑAY LANDY ANGEL HUMBERTO', 'slangel@gmail.com', '0988415263', 1, 2, 0, 0, 1),
(180, '0300434990', 0, ' SUCUZHAÑAY SUCUZHAÑAY MANUEL MARIA', 'ssmanuel@gmail.com', '0998845632', 0, NULL, 1, 0, 1),
(181, '0602166068', 0, 'TAPAY TAPAICELA FRANCISCO', 'ttfrancisco@gmail.com', '0996355412', 1, 1, 0, 0, 1),
(182, '0919617068', 0, 'TAPAY TAPICELA ROSA MARIA', 'ttrosa@gmail.com', '0997774523', 0, NULL, 0, 1, 1),
(183, '0605262708', 0, ' TENESACA TENEMAZA FRANKLIN JONNATHAN', 'ttfran@gmail.com', '0966332245', 1, 2, 0, 0, 1),
(184, '0942070582', 0, ' TIGRE HUALPA GABRIELA ESTEFANIA', 'thgabriela@gmail.com', '0955412230', 0, NULL, 0, 1, 1),
(185, '0940673965', 0, 'TIGRE MENDEZ JOHN KEVIN', 'tmkevin@gmail.com', '0974556310', 0, NULL, 1, 0, 1),
(186, '0940813017', 0, 'TIGRE MENDEZ DAMARIS DANA', 'tmdamaris@gmail.com', '0980806654', 0, NULL, 0, 1, 1),
(187, '0918306903', 0, ' TITO GUERRERO ABDON BOLIVAR', 'tgabdon@gmail.com', '0995050021', 1, 1, 0, 0, 1),
(188, '0927783605', 0, 'TORAL VILELA CRISTOBAL ISAURO', 'tvisauro@gmail.com', '0977506030', 1, 2, 0, 0, 1),
(189, '0913162004', 0, 'TORRES CASTRO JANETH MARITZA', 'tcmaritza@gmail.com', '0997070231', 1, 3, 0, 0, 1),
(190, '0940230576', 0, ' TORRES RIVERA CARLA MARISOL', 'trcarla@gmail.com', '0997412356', 0, NULL, 0, 1, 1),
(191, '0928067727', 0, ' TRUJILLO SANDOYA JENNIFER KATHERINE', 'tskatherine@gmail.com', '0980805022', 0, NULL, 0, 1, 1),
(192, '0101820975', 0, ' TUALOMBO LOPEZ PABLO HUMBERTO', 'tlpablo@gmail.com', '0998855200', 1, 3, 0, 0, 1),
(193, '0501427975', 0, 'UNAPUCHA GUANOPATIN MARIA ISOLINA', 'ugmaria@gmail.com', '0987774520', 0, NULL, 1, 0, 1),
(194, '0906251145', 0, 'VALENCIA PRECIADO TEMISTOCLE ANTONIO', 'vpantonio@gmail.com', '0996325871', 1, 1, 0, 0, 1),
(195, '1705589545', 0, 'VALENZUELA SAMPEDRO JOSE GALO', 'vsjose@gmail.com', '0996662023', 0, NULL, 1, 0, 1),
(196, '0921501060', 0, ' VALENZUELA SARABIA VIVIANA DEL ROCIO', 'vsviviana@gmail.com', '0855263214', 1, 3, 0, 0, 1),
(197, '0916696990', 0, ' VALLEJO MUÑOZ PEDRO JOSE', 'vmpedro@gmail.com', '0963214587', 0, NULL, 0, 1, 1),
(198, '1200177697', 0, 'VALLEJO SIMONS CARLOS ARCANGEL', 'vscarlos@gmail.com', '0952223044', 0, NULL, 0, 1, 1),
(199, '0901426403', 0, ' VARGAS PARRA SEGUNDO JACINTO', 'vpsegundo@gmail.com', '0988852020', 1, 3, 0, 0, 1),
(200, '0940816572', 0, 'VARGAS VILLALTA FERNANDO DAVID', 'vvfernando@gmail.com', '0932221010', 0, NULL, 1, 0, 1),
(201, '0917928582', 0, 'VASQUEZ FAJARDO CARLOS EFRAIN', 'vfcarlos@gmail.com', '0985223310', 0, NULL, 1, 0, 1),
(202, '0956526834', 0, ' VASQUEZ MARTINEZ CARLOS ANDRES', 'vmcarlos@gmail.com', '0978523012', 1, 2, 0, 0, 1),
(203, '0956526784', 0, 'VASQUEZ MARTINEZ MARIA EDUARDA', 'vmmaria@gmail.com', '0985211110', 0, NULL, 1, 0, 1),
(204, '0905923256', 0, 'VEGA CONTRERAS ESTHER CLEMENCIA', 'vcclemencia@gmail.com', '0965552147', 1, 2, 0, 0, 1),
(205, '0920737749', 0, ' VELASTEGUI ACOSTA JORGE IVAN', 'vajorge@gmail.com', '0964441230', 0, NULL, 1, 0, 1),
(206, '0942071804', 0, ' VERA FREIRE JEAN PIERRE', 'vfjean@gmail.com', '0997775236', 0, NULL, 0, 1, 1),
(207, '0912531654', 0, ' VERA GORDON GERARDO DALMACIO', 'vgdalmacio@gmail.com', '0996663321', 1, 2, 0, 0, 1),
(208, '0917714677', 0, ' VILLALTA BELTRAN WILLIAM ANTONIO', 'vbantonio@gmail.com', '0981226710', 1, 3, 0, 0, 1),
(209, '1201166913', 0, ' VILLAMAR SANCHEZ CHARITO NARCISA', 'vsnarcisa@gmail.com', '0412356620', 0, NULL, 1, 0, 1),
(210, '0940353097', 0, ' VILLAVICENCIO BARCOS DAMIAN STEVEN', 'vbdamian@gmail.com', '0254236584', 1, 1, 0, 0, 1),
(211, '1200533634', 0, ' VITE BALON WASHINGTON ANDRES', 'vbandres@gmail.com', '0974742020', 1, 2, 0, 0, 1),
(212, '0922988134', 0, ' YANEZ CORDOVA SENDIC JOYCE', 'ycsendic@gmail.com', '0562314785', 1, 3, 0, 0, 1),
(213, '0200215945', 0, ' YANEZ DEL POZO VICTOR LUIS', 'ypvictor@gmail.com', '0632125054', 1, 1, 0, 0, 1),
(214, '0103857025', 0, 'YANZAGUANO MOLINA CARLOS MAURICIO', 'ymcarlos@gmail.com', '0652521030', 0, NULL, 0, 1, 1),
(215, '0921632436', 0, 'YUNGAZACA BARRERA NATALY PATRICIA', 'ybpatricia@gmail.com', '0452364410', 0, NULL, 0, 1, 1),
(216, '0913847828', 0, ' ZAMBRANO IRRAZABAL PERFECTO FRANCISCO', 'zifrancisco@gmail.com', '0996542323', 0, NULL, 1, 0, 1),
(217, '1203300759', 0, ' ZAMORA PEREZ MERCY ROSALBA', 'zpmercy@gmail.com', '0998885050', 0, NULL, 0, 1, 1),
(218, '0906878038', 0, ' ZAVALA ACOSTA EVA ELVIRA', 'zaeva@gmail.com', '0997776300', 0, NULL, 1, 0, 1),
(219, '0916638653', 0, ' ZAVALA ERAZO FATIMA CORINA', 'zefatima@gmail.com', '0125553030', 0, NULL, 0, 1, 1),
(220, '0941425936', 0, 'ZHUMI PEREZ LUIS ALFREDO', 'zpluis@gmail.com', '0996325414', 1, 1, 0, 0, 1),
(221, '0928474972', 0, ' NAULA SAAVEDRA JOHN ALEXANDER', 'nsalex@gmail.com', '0955632120', 1, 2, 0, 0, 1);

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
  `stock` decimal(20,2) NOT NULL,
  `porcentaje_iva` decimal(20,2) NOT NULL,
  `precio_venta` decimal(20,2) NOT NULL,
  `precio_compra` decimal(20,2) NOT NULL,
  `descuento` decimal(20,2) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `id_categoria`, `es_inventariable`, `stock`, `porcentaje_iva`, `precio_venta`, `precio_compra`, `descuento`, `estado`) VALUES
(1, 'IMP001', 'Impresora Matricial', 1, 1, 0.00, 12.00, 220.00, 180.00, 0.00, 1),
(2, 'LAP001', 'Laptop', 6, 1, 0.00, 12.00, 1500.00, 1200.00, 10.00, 1),
(3, 'CEL001', 'Celular', 6, 1, 0.00, 0.00, 800.00, 700.00, 5.00, 1),
(4, 'TV001', 'Televisor', 6, 1, 0.00, 12.00, 1200.00, 1000.00, 15.00, 1),
(5, 'CAM001', 'Cámara Fotográfica', 37, 1, 0.00, 0.00, 500.00, 400.00, 20.00, 1),
(6, 'PEN001', 'Lápiz', 14, 0, 0.00, 12.00, 2.00, 1.00, 0.00, 1),
(7, 'LIB001', 'Libreta', 11, 0, 0.00, 12.00, 5.00, 3.00, 0.00, 1),
(8, 'COC001', 'Cocina de Inducción', 35, 1, 0.00, 12.00, 350.00, 300.00, 0.00, 1),
(9, 'PAR001', 'Parlante Bluetooth', 6, 1, 0.00, 12.00, 80.00, 60.00, 10.00, 1),
(10, 'CUB001', 'Cubo Rubik', 5, 0, 0.00, 0.00, 15.00, 10.00, 5.00, 1),
(11, 'CAM002', 'Cámara de Seguridad', 37, 1, 0.00, 12.00, 100.00, 80.00, 0.00, 1),
(12, 'AUD001', 'Audífonos Inalámbricos', 6, 1, 0.00, 12.00, 50.00, 40.00, 5.00, 1),
(13, 'BLU001', 'Blu-ray Player', 6, 1, 5.00, 12.00, 120.00, 100.00, 0.00, 1),
(14, 'MES001', 'Mesa de Centro', 7, 0, 0.00, 12.00, 200.00, 150.00, 0.00, 1),
(15, 'RAT001', 'Ratón Inalámbrico', 1, 1, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(16, 'TEL001', 'Teléfono Fijo', 6, 1, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(17, 'COL001', 'Collar de Plata', 14, 0, 0.00, 12.00, 80.00, 60.00, 10.00, 1),
(18, 'DVD001', 'DVD Player', 6, 1, 0.00, 12.00, 70.00, 50.00, 5.00, 1),
(19, 'ALT001', 'Altavoz Bluetooth', 6, 1, 0.00, 12.00, 60.00, 45.00, 0.00, 1),
(20, 'CAN001', 'Candado de Seguridad', 4, 0, 0.00, 12.00, 10.00, 8.00, 0.00, 1),
(21, 'SIL001', 'Silbato Deportivo', 8, 0, 0.00, 0.00, 5.00, 3.00, 0.00, 1),
(22, 'BIC001', 'Bicicleta de Montaña', 16, 1, 0.00, 12.00, 500.00, 400.00, 0.00, 1),
(23, 'PAN001', 'Panificadora', 35, 1, 0.00, 12.00, 150.00, 120.00, 10.00, 1),
(24, 'MOU001', 'Mousepad', 1, 0, 0.00, 12.00, 8.00, 5.00, 0.00, 1),
(25, 'TEL002', 'Teléfono Inteligente', 6, 1, 0.00, 0.00, 1000.00, 900.00, 5.00, 1),
(26, 'CHA001', 'Chaqueta de Cuero', 3, 0, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(27, 'VID001', 'Videocámara', 37, 1, 0.00, 12.00, 300.00, 250.00, 0.00, 1),
(28, 'ALT002', 'Altavoz Portátil', 6, 1, 0.00, 12.00, 40.00, 30.00, 0.00, 1),
(29, 'CAM003', 'Cámara de Acción', 37, 1, 0.00, 0.00, 120.00, 100.00, 10.00, 1),
(30, 'MON001', 'Monitor LED', 6, 1, 0.00, 12.00, 250.00, 200.00, 0.00, 1),
(31, 'ALT003', 'Altavoz Bluetooth Resistente a', 6, 1, 0.00, 12.00, 70.00, 55.00, 0.00, 1),
(32, 'CAM004', 'Cámara de Seguridad Inalámbric', 37, 1, 0.00, 12.00, 90.00, 75.00, 0.00, 1),
(33, 'LIB002', 'Libro de Ciencia Ficción', 11, 0, 0.00, 12.00, 15.00, 10.00, 0.00, 1),
(34, 'TV002', 'Televisor Smart 4K', 6, 1, 0.00, 12.00, 1000.00, 800.00, 10.00, 1),
(35, 'AUD002', 'Audífonos con Cancelación de R', 6, 1, 0.00, 12.00, 120.00, 100.00, 5.00, 1),
(36, 'PAN002', 'Panini Press', 35, 1, 0.00, 12.00, 80.00, 65.00, 0.00, 1),
(37, 'CUB002', 'Cubiertos de Acero Inoxidable', 5, 0, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(38, 'CAM005', 'Cámara Réflex', 37, 1, 0.00, 12.00, 800.00, 650.00, 0.00, 1),
(39, 'SEC001', 'Secador de Pelo Profesional', 22, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(40, 'CHA002', 'Chaleco Salvavidas', 9, 0, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(41, 'LAP002', 'Laptop Gaming', 6, 1, 0.00, 12.00, 2000.00, 1800.00, 0.00, 1),
(42, 'SEC002', 'Secadora de Ropa', 22, 1, 0.00, 12.00, 300.00, 250.00, 5.00, 1),
(43, 'PAN003', 'Panera de Madera', 35, 1, 0.00, 12.00, 40.00, 30.00, 0.00, 1),
(44, 'LIB003', 'Libro de Cocina', 11, 0, 0.00, 12.00, 20.00, 15.00, 0.00, 1),
(45, 'TEL003', 'Teléfono Inalámbrico', 6, 1, 0.00, 0.00, 50.00, 40.00, 0.00, 1),
(46, 'CAM006', 'Cámara de Video Full HD', 37, 1, 0.00, 12.00, 150.00, 120.00, 0.00, 1),
(47, 'ALT004', 'Altavoz Bluetooth Portátil', 6, 1, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(48, 'TV003', 'Televisor OLED', 6, 1, 0.00, 12.00, 1500.00, 1300.00, 0.00, 1),
(49, 'CHA003', 'Chaqueta Deportiva', 3, 0, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(50, 'PRO001', 'Proyector Multimedia', 6, 1, 0.00, 12.00, 400.00, 350.00, 0.00, 1),
(51, 'MOB001', 'Mochila Escolar', 10, 0, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(52, 'ALT005', 'Altavoz Bluetooth Subwoofer', 6, 1, 0.00, 12.00, 100.00, 80.00, 0.00, 1),
(53, 'CAM007', 'Cámara Deportiva 4K', 37, 1, 0.00, 12.00, 80.00, 65.00, 0.00, 1),
(54, 'TV004', 'Televisor QLED', 6, 1, 0.00, 12.00, 1800.00, 1600.00, 0.00, 1),
(55, 'AUD003', 'Audífonos Deportivos Inalámbri', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(56, 'PAN004', 'Panera de Acero Inoxidable', 35, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(57, 'CAM008', 'Cámara de Vigilancia IP', 37, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(58, 'ALT006', 'Altavoz Bluetooth Retro', 6, 1, 0.00, 12.00, 40.00, 30.00, 0.00, 1),
(59, 'DVD002', 'Reproductor de DVD Compacto', 6, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(60, 'CHA004', 'Chaleco Reflectante', 9, 0, 0.00, 12.00, 15.00, 10.00, 0.00, 1),
(61, 'LAP003', 'Laptop Ultraligera', 6, 1, 0.00, 12.00, 1500.00, 1300.00, 0.00, 1),
(62, 'SEC003', 'Secadora de Cabello Profesiona', 22, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(63, 'PAN005', 'Panera de Bambú', 35, 1, 0.00, 12.00, 35.00, 30.00, 0.00, 1),
(64, 'LIB004', 'Libro de Autoayuda', 11, 0, 0.00, 12.00, 18.00, 15.00, 0.00, 1),
(65, 'TEL004', 'Teléfono con Contestadora', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(66, 'CAM009', 'Cámara de Video Profesional', 37, 1, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(67, 'ALT007', 'Altavoz Bluetooth con Luces LE', 6, 1, 0.00, 12.00, 80.00, 65.00, 0.00, 1),
(68, 'TV005', 'Televisor Curvo', 6, 1, 0.00, 12.00, 2000.00, 1800.00, 0.00, 1),
(69, 'CHA005', 'Chaqueta de Invierno', 3, 0, 0.00, 12.00, 120.00, 100.00, 0.00, 1),
(70, 'PRO002', 'Proyector HD', 6, 1, 0.00, 12.00, 300.00, 250.00, 0.00, 1),
(71, 'MOB002', 'Mochila de Viaje', 10, 0, 0.00, 12.00, 35.00, 30.00, 0.00, 1),
(72, 'ALT008', 'Altavoz Inalámbrico Resistente', 6, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(73, 'CAM010', 'Cámara de Seguridad CCTV', 37, 1, 0.00, 12.00, 120.00, 100.00, 0.00, 1),
(74, 'TV006', 'Televisor 8K', 6, 1, 0.00, 12.00, 3000.00, 2800.00, 0.00, 1),
(75, 'AUD004', 'Audífonos Bluetooth Deportivos', 6, 1, 0.00, 12.00, 90.00, 75.00, 0.00, 1),
(76, 'PAN006', 'Panera de Plástico', 35, 1, 0.00, 12.00, 20.00, 15.00, 0.00, 1),
(77, 'CAM011', 'Cámara de Video 360°', 37, 1, 0.00, 12.00, 150.00, 130.00, 0.00, 1),
(78, 'ALT009', 'Altavoz Bluetooth Retro Vintag', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(79, 'DVD003', 'Reproductor de DVD con Puerto ', 6, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(80, 'CHA006', 'Chaleco Térmico', 9, 0, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(81, 'LAP004', 'Laptop Convertible', 6, 1, 0.00, 12.00, 1800.00, 1600.00, 0.00, 1),
(82, 'SEC004', 'Secadora de Ropa Compacta', 22, 1, 0.00, 12.00, 150.00, 130.00, 0.00, 1),
(83, 'PAN007', 'Panera Metálica', 35, 1, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(84, 'LIB005', 'Libro de Historia', 11, 0, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(85, 'TEL005', 'Teléfono Fijo con Identificado', 6, 1, 0.00, 12.00, 40.00, 30.00, 0.00, 1),
(86, 'CAM012', 'Cámara de Video HD', 37, 1, 0.00, 12.00, 180.00, 160.00, 0.00, 1),
(87, 'ALT010', 'Altavoz Bluetooth con Micrófon', 6, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(88, 'TV007', 'Televisor Smart TV', 6, 1, 0.00, 12.00, 1200.00, 1000.00, 0.00, 1),
(89, 'CHA007', 'Chaqueta de Cuero', 3, 0, 0.00, 12.00, 150.00, 130.00, 0.00, 1),
(90, 'PRO003', 'Proyector Portátil', 6, 1, 0.00, 12.00, 250.00, 200.00, 0.00, 1),
(91, 'MOB003', 'Mochila Antirrobo', 10, 0, 0.00, 12.00, 45.00, 40.00, 0.00, 1),
(92, 'ALT011', 'Altavoz Bluetooth Resistente a', 6, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(93, 'CAM013', 'Cámara de Vigilancia WiFi', 37, 1, 0.00, 12.00, 100.00, 90.00, 0.00, 1),
(94, 'TV008', 'Televisor OLED 8K', 6, 1, 0.00, 12.00, 4000.00, 3800.00, 0.00, 1),
(95, 'AUD005', 'Audífonos Inalámbricos con Can', 6, 1, 0.00, 12.00, 120.00, 100.00, 0.00, 1),
(96, 'PAN008', 'Panera de Plástico Apilable', 35, 1, 0.00, 12.00, 15.00, 10.00, 0.00, 1),
(97, 'CAM014', 'Cámara de Video 4K con Estabil', 37, 1, 0.00, 12.00, 250.00, 230.00, 0.00, 1),
(98, 'ALT012', 'Altavoz Bluetooth con Radio FM', 6, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(99, 'DVD004', 'Reproductor de DVD con HDMI', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(100, 'CHA008', 'Chaleco de Lana', 9, 0, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(101, 'LAP005', 'Laptop de Alta Gama', 6, 1, 0.00, 12.00, 2500.00, 2300.00, 0.00, 1),
(102, 'SEC005', 'Secadora de Ropa de Gas', 22, 1, 0.00, 12.00, 400.00, 350.00, 0.00, 1),
(103, 'PAN009', 'Panera de Madera con Tapa', 35, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(104, 'LIB006', 'Libro de Ciencia Ficción', 11, 0, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(105, 'TEL006', 'Teléfono Inalámbrico con Conte', 6, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(106, 'CAM015', 'Cámara de Video Deportiva 4K', 37, 1, 0.00, 12.00, 120.00, 100.00, 0.00, 1),
(107, 'ALT013', 'Altavoz Bluetooth con Luces de', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(108, 'TV009', 'Televisor LED HD', 6, 1, 0.00, 12.00, 800.00, 700.00, 0.00, 1),
(109, 'CHA009', 'Chaqueta de Mezclilla', 3, 0, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(110, 'PRO004', 'Proyector Full HD', 6, 1, 0.00, 12.00, 400.00, 350.00, 0.00, 1),
(111, 'MOB004', 'Mochila para Laptop', 10, 0, 0.00, 12.00, 40.00, 35.00, 0.00, 1),
(112, 'ALT014', 'Altavoz Inalámbrico con Power ', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(113, 'CAM016', 'Cámara de Vigilancia con Segui', 37, 1, 0.00, 12.00, 150.00, 130.00, 0.00, 1),
(114, 'TV010', 'Televisor QLED 4K', 6, 1, 0.00, 12.00, 1800.00, 1600.00, 0.00, 1),
(115, 'AUD006', 'Audífonos Intraurales Deportiv', 6, 1, 0.00, 12.00, 40.00, 30.00, 0.00, 1),
(116, 'PAN010', 'Panera de Bambú con Tapa', 35, 1, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(117, 'CAM017', 'Cámara de Video con Visión Noc', 37, 1, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(118, 'ALT015', 'Altavoz Bluetooth Portátil', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(119, 'DVD005', 'Reproductor de DVD Portátil', 6, 1, 0.00, 12.00, 100.00, 90.00, 0.00, 1),
(120, 'CHA010', 'Chaleco de Plumas', 9, 0, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(121, 'MOB005', 'Mochila para Excursionismo', 10, 0, 0.00, 12.00, 55.00, 50.00, 0.00, 1),
(122, 'ALT016', 'Altavoz Bluetooth con Subwoofe', 6, 1, 0.00, 12.00, 100.00, 90.00, 0.00, 1),
(123, 'CAM018', 'Cámara de Vigilancia con Detec', 37, 1, 0.00, 12.00, 130.00, 120.00, 0.00, 1),
(124, 'TV011', 'Televisor Curvo 4K', 6, 1, 0.00, 12.00, 2500.00, 2300.00, 0.00, 1),
(125, 'AUD007', 'Audífonos Bluetooth Plegables', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(126, 'PAN011', 'Panera de Metal con Tapa', 35, 1, 0.00, 12.00, 35.00, 30.00, 0.00, 1),
(127, 'CAM019', 'Cámara de Video Deportiva Full', 37, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(128, 'ALT017', 'Altavoz Bluetooth con Karaoke', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(129, 'DVD006', 'Reproductor de DVD con USB y B', 6, 1, 0.00, 12.00, 120.00, 110.00, 0.00, 1),
(130, 'CHA011', 'Chaqueta de Cuero Sintético', 3, 0, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(131, 'LAP006', 'Laptop para Gaming', 6, 1, 0.00, 12.00, 2800.00, 2600.00, 0.00, 1),
(132, 'SEC006', 'Secadora de Ropa de Condensaci', 22, 1, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(133, 'PAN012', 'Panera de Vidrio con Tapa', 35, 1, 0.00, 12.00, 40.00, 35.00, 0.00, 1),
(134, 'LIB007', 'Libro de Poesía', 11, 0, 0.00, 12.00, 20.00, 15.00, 0.00, 1),
(135, 'TEL007', 'Teléfono Inalámbrico con Panta', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(136, 'CAM020', 'Cámara de Video con Estabiliza', 37, 1, 0.00, 12.00, 180.00, 160.00, 0.00, 1),
(137, 'ALT018', 'Altavoz Inalámbrico Sumergible', 6, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(138, 'TV012', 'Televisor Smart TV 8K', 6, 1, 0.00, 12.00, 5000.00, 4800.00, 0.00, 1),
(139, 'CHA012', 'Chaleco de Terciopelo', 9, 0, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(140, 'PRO005', 'Proyector 3D', 6, 1, 0.00, 12.00, 350.00, 320.00, 0.00, 1),
(141, 'MOB006', 'Mochila con Panel Solar', 10, 0, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(142, 'ALT019', 'Altavoz Bluetooth con Luces LE', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(143, 'CAM021', 'Cámara de Vigilancia PTZ', 37, 1, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(144, 'TV013', 'Televisor LED 4K', 6, 1, 0.00, 12.00, 1000.00, 900.00, 0.00, 1),
(145, 'AUD008', 'Audífonos Inalámbricos Deporti', 6, 1, 0.00, 12.00, 50.00, 40.00, 0.00, 1),
(146, 'PAN013', 'Panera de Acero Inoxidable con', 35, 1, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(147, 'CAM022', 'Cámara de Video con Micrófono ', 37, 1, 0.00, 12.00, 150.00, 130.00, 0.00, 1),
(148, 'ALT020', 'Altavoz Bluetooth con Radio FM', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(149, 'DVD007', 'Reproductor de DVD con Pantall', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(150, 'CHA013', 'Chaqueta de Algodón', 3, 0, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(151, 'MOB007', 'Mochila Antirrobo con Puerto U', 10, 0, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(152, 'ALT021', 'Altavoz Bluetooth Resistente a', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(153, 'CAM023', 'Cámara de Vigilancia Wi-Fi HD', 37, 1, 0.00, 12.00, 120.00, 110.00, 0.00, 1),
(154, 'TV014', 'Televisor OLED 4K', 6, 1, 0.00, 12.00, 3000.00, 2800.00, 0.00, 1),
(155, 'AUD009', 'Audífonos Inalámbricos con Can', 6, 1, 0.00, 12.00, 120.00, 110.00, 0.00, 1),
(156, 'PAN014', 'Panera de Plástico con Tapa', 35, 1, 0.00, 12.00, 20.00, 15.00, 0.00, 1),
(157, 'CAM024', 'Cámara de Video con Visión Pan', 37, 1, 0.00, 12.00, 180.00, 160.00, 0.00, 1),
(158, 'ALT022', 'Altavoz Bluetooth con Micrófon', 6, 1, 0.00, 12.00, 100.00, 90.00, 0.00, 1),
(159, 'DVD008', 'Reproductor de DVD con Karaoke', 6, 1, 0.00, 12.00, 110.00, 100.00, 0.00, 1),
(160, 'CHA014', 'Chaleco de Lana', 9, 0, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(161, 'LAP007', 'Laptop Ultraligera', 6, 1, 0.00, 12.00, 3500.00, 3300.00, 0.00, 1),
(162, 'SEC007', 'Secadora de Ropa de Condensaci', 22, 1, 0.00, 12.00, 250.00, 230.00, 0.00, 1),
(163, 'PAN015', 'Panera de Madera con Tapa', 35, 1, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(164, 'LIB008', 'Libro de Ciencia Ficción', 11, 0, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(165, 'TEL008', 'Teléfono Inalámbrico con Conte', 6, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(166, 'CAM025', 'Cámara de Video con Estabiliza', 37, 1, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(167, 'ALT023', 'Altavoz Bluetooth con Luces RG', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(168, 'TV015', 'Televisor QLED 8K', 6, 1, 0.00, 12.00, 5500.00, 5300.00, 0.00, 1),
(169, 'CHA015', 'Chaleco de Plumas', 9, 0, 0.00, 12.00, 110.00, 100.00, 0.00, 1),
(170, 'PRO006', 'Proyector HD', 6, 1, 0.00, 12.00, 300.00, 280.00, 0.00, 1),
(171, 'MOB008', 'Mochila con Ruedas para Viaje', 10, 0, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(172, 'ALT024', 'Altavoz Bluetooth Portátil', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(173, 'CAM026', 'Cámara de Vigilancia con Audio', 37, 1, 0.00, 12.00, 150.00, 140.00, 0.00, 1),
(174, 'TV016', 'Televisor LED HD', 6, 1, 0.00, 12.00, 800.00, 700.00, 0.00, 1),
(175, 'AUD010', 'Audífonos Inalámbricos Deporti', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(176, 'PAN016', 'Panera de Bambú con Tapa', 35, 1, 0.00, 12.00, 35.00, 30.00, 0.00, 1),
(177, 'CAM027', 'Cámara de Video con Zoom Óptic', 37, 1, 0.00, 12.00, 220.00, 200.00, 0.00, 1),
(178, 'ALT025', 'Altavoz Bluetooth con Batería ', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(179, 'DVD009', 'Reproductor de DVD Portátil', 6, 1, 0.00, 12.00, 150.00, 140.00, 0.00, 1),
(180, 'CHA016', 'Chaqueta de Piel', 3, 0, 0.00, 12.00, 120.00, 110.00, 0.00, 1),
(181, 'MOB009', 'Mochila para Portátil', 10, 0, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(182, 'ALT026', 'Altavoz Bluetooth con Subwoofe', 6, 1, 0.00, 12.00, 100.00, 90.00, 0.00, 1),
(183, 'CAM028', 'Cámara de Vigilancia Exterior', 37, 1, 0.00, 12.00, 130.00, 120.00, 0.00, 1),
(184, 'TV017', 'Televisor Curvo 4K', 6, 1, 0.00, 12.00, 2500.00, 2300.00, 0.00, 1),
(185, 'AUD011', 'Audífonos Inalámbricos con Est', 6, 1, 0.00, 12.00, 110.00, 100.00, 0.00, 1),
(186, 'PAN017', 'Panera de Metal con Tapa', 35, 1, 0.00, 12.00, 25.00, 20.00, 0.00, 1),
(187, 'CAM029', 'Cámara de Video con Estabiliza', 37, 1, 0.00, 12.00, 160.00, 150.00, 0.00, 1),
(188, 'ALT027', 'Altavoz Bluetooth con Radio AM', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(189, 'DVD010', 'Reproductor de DVD con USB y H', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(190, 'CHA017', 'Chaleco de Cuero', 9, 0, 0.00, 12.00, 100.00, 90.00, 0.00, 1),
(191, 'LAP008', 'Laptop Gaming', 6, 1, 0.00, 12.00, 4000.00, 3800.00, 0.00, 1),
(192, 'SEC008', 'Secadora de Ropa de Condensaci', 22, 1, 0.00, 12.00, 280.00, 260.00, 0.00, 1),
(193, 'PAN018', 'Panera de Plástico Transparent', 35, 1, 0.00, 12.00, 20.00, 15.00, 0.00, 1),
(194, 'LIB009', 'Libro de Fantasía', 11, 0, 0.00, 12.00, 30.00, 25.00, 0.00, 1),
(195, 'TEL009', 'Teléfono Inalámbrico con Ident', 6, 1, 0.00, 12.00, 60.00, 50.00, 0.00, 1),
(196, 'CAM030', 'Cámara de Video con Detección ', 37, 1, 0.00, 12.00, 170.00, 160.00, 0.00, 1),
(197, 'ALT028', 'Altavoz Bluetooth Resistente a', 6, 1, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(198, 'TV018', 'Televisor OLED 8K', 6, 1, 0.00, 12.00, 6000.00, 5800.00, 0.00, 1),
(199, 'CHA018', 'Chaleco de Algodón', 9, 0, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(200, 'PRO007', 'Proyector 4K', 6, 1, 0.00, 12.00, 500.00, 480.00, 0.00, 1),
(201, 'MOB010', 'Mochila para Excursiones', 10, 0, 0.00, 12.00, 90.00, 80.00, 0.00, 1),
(202, 'ALT029', 'Altavoz Bluetooth Portátil Res', 6, 1, 0.00, 12.00, 70.00, 60.00, 0.00, 1),
(203, 'CAM031', 'Cámara de Vigilancia con Giro ', 37, 1, 0.00, 12.00, 200.00, 180.00, 0.00, 1),
(204, 'TV019', 'Televisor LED Full HD', 6, 1, 0.00, 12.00, 1000.00, 900.00, 0.00, 1),
(205, 'AUD012', 'Audífonos Inalámbricos Deporti', 6, 1, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(206, 'PAN019', 'Panera de Acero Inoxidable con', 35, 1, 0.00, 12.00, 40.00, 35.00, 0.00, 1),
(207, 'CAM032', 'Cámara de Video con Visión Noc', 37, 1, 0.00, 12.00, 190.00, 170.00, 0.00, 1),
(208, 'ALT030', 'Altavoz Bluetooth con Control ', 6, 1, 0.00, 12.00, 110.00, 100.00, 0.00, 1),
(209, 'DVD011', 'Reproductor de DVD con Karaoke', 6, 1, 0.00, 12.00, 120.00, 110.00, 0.00, 1),
(210, 'CHA019', 'Chaqueta de Mezclilla', 3, 0, 0.00, 12.00, 80.00, 70.00, 0.00, 1),
(211, '0000001', 'FIGURA ANIME HENTAI', 5, 1, 0.00, 12.00, 100.00, 60.00, 10.00, 1),
(212, 'MSK01', 'Mascarillas', 1, 0, 0.00, 0.00, 0.25, 1.00, 0.00, 1);

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
(3, 'Vendedor');

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
(1, 'admin123', '$2y$10$FudUGTMTxdG9E6YP5iCuuOL5YaxpCEDeTvrT19tPH/iZ6JTonkaE.', 1, 1, '1683697626_95840d16314b9283dff0.jpg', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `cobros`
--
ALTER TABLE `cobros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cobros_bancos1` (`id_banco`),
  ADD KEY `fk_cobros_clientes1` (`id_cliente`),
  ADD KEY `fk_cobros_vendedor1` (`id_vendedor`),
  ADD KEY `fk_cobros_cotizaciones1` (`id_cotizacion`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compras_personas_prov1` (`id_per_prov`);

--
-- Indices de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cotizaciones_cliente1` (`id_cliente`),
  ADD KEY `fk_cotizaciones_vendedor1` (`id_vendedor`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detallescompras_compras1` (`id_compra`),
  ADD KEY `fk_detallescompras_productos1` (`id_producto`);

--
-- Indices de la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detallescotizacion_cotizacion1` (`id_cotizacion`),
  ADD KEY `fk_detallescotizacion_productos1` (`id_producto`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pagos_personaproveedor1` (`id_proveedor`),
  ADD KEY `fk_pagos_bancos1` (`id_banco`),
  ADD KEY `fk_pagos_compras1` (`id_compra`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_personas_cargos1` (`id_cargo`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_productos_categorias1` (`id_categoria`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_personas1` (`id_persona`),
  ADD KEY `fk_usuarios_roles1` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bancos`
--
ALTER TABLE `bancos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `cobros`
--
ALTER TABLE `cobros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cobros`
--
ALTER TABLE `cobros`
  ADD CONSTRAINT `fk_cobros_bancos1` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id`),
  ADD CONSTRAINT `fk_cobros_clientes1` FOREIGN KEY (`id_cliente`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `fk_cobros_cotizaciones1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `fk_cobros_vendedor1` FOREIGN KEY (`id_vendedor`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_compras_personas_prov1` FOREIGN KEY (`id_per_prov`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD CONSTRAINT `fk_cotizaciones_cliente1` FOREIGN KEY (`id_cliente`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `fk_cotizaciones_vendedor1` FOREIGN KEY (`id_vendedor`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `fk_detallescompras_compras1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `fk_detallescompras_productos1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD CONSTRAINT `fk_detallescotizacion_cotizacion1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `fk_detallescotizacion_productos1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_bancos1` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id`),
  ADD CONSTRAINT `fk_pagos_compras1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `fk_pagos_personaproveedor1` FOREIGN KEY (`id_proveedor`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `fk_personas_cargos1` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categorias1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_producto` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_personas1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `fk_usuarios_roles1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
