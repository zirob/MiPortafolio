-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-04-2013 a las 14:27:23
-- Versión del servidor: 5.1.61
-- Versión de PHP: 5.3.3-1ubuntu9.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sacyr`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos`
--

CREATE TABLE IF NOT EXISTS `archivos` (
  `id_archivo` double NOT NULL AUTO_INCREMENT,
  `id_solicitud` double NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `ruta_archivo` varchar(100) DEFAULT NULL,
  `nombre_archivo` varchar(100) NOT NULL,
  `extension_archivo` varchar(10) NOT NULL,
  `estado_archivo` tinyint(1) NOT NULL,
  `usuario_carga` varchar(50) NOT NULL,
  `fecha_carga` datetime NOT NULL,
  PRIMARY KEY (`id_archivo`,`id_solicitud`,`rut_empresa`),
  KEY `id_archivo` (`id_archivo`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_carga` (`usuario_carga`,`rut_empresa`),
  KEY `id_solicitud` (`id_solicitud`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Volcar la base de datos para la tabla `archivos`
--

INSERT INTO `archivos` (`id_archivo`, `id_solicitud`, `rut_empresa`, `ruta_archivo`, `nombre_archivo`, `extension_archivo`, `estado_archivo`, `usuario_carga`, `fecha_carga`) VALUES
(46, 20, '96786880-9', '../../documentos/', 'comparativo_Camionetas_mitsubishi[OC_Especial]', 'xlsx', 0, 'admin', '2013-01-23 04:42:07'),
(47, 20, '96786880-9', '../../documentos/', 'comparativo_CANTER_[OC_Especial]', 'xlsx', 0, 'admin', '2013-01-23 04:42:07'),
(48, 20, '96786880-9', '../../documentos/', '13912_OrdenDeCompraNormal', 'xls', 1, 'admin', '2013-01-23 04:42:07'),
(49, 21, '96786880-9', '../../documentos/', 'arch_prueba_-_copia', 'txt', 1, 'vnino', '2013-01-30 00:25:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodegas`
--

CREATE TABLE IF NOT EXISTS `bodegas` (
  `cod_bodega` double NOT NULL AUTO_INCREMENT,
  `rut_empresa` varchar(10) NOT NULL,
  `descripcion_bodega` varchar(500) NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`cod_bodega`,`rut_empresa`),
  KEY `cod_bodega` (`cod_bodega`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Volcar la base de datos para la tabla `bodegas`
--

INSERT INTO `bodegas` (`cod_bodega`, `rut_empresa`, `descripcion_bodega`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, '11111111', 'bodega de prueba 2', 'vnino', '2013-01-02 03:57:06'),
(2, '1-9', 'sacyr', 'vnino', '2013-01-03 01:05:18'),
(3, '1-9', 'bodega 3', 'admin', '2013-01-04 02:46:53'),
(5, '96786880-9', 'Bodega 1 Parque Maquinarias', 'admin', '2013-01-10 00:04:00'),
(6, '96786880-9', 'Bodega 2 Parque Maquinarias', 'admin', '2013-01-10 00:04:11'),
(9, '96786880-9', 'BODEGA PRINCIPAL', 'admin', '2013-01-21 12:37:22'),
(15, '96786880-9', 'Bodega Chica', 'admin', '2013-01-31 15:41:52'),
(38, '96786880-9', ' ', 'admin', '2013-03-26 00:36:15'),
(39, '96786880-9', '123', 'admin', '2013-03-26 23:14:56'),
(40, '96786880-9', 'fsdfdsfsd', 'admin', '2013-03-26 23:25:25'),
(41, '96786880-9', 'prueba henry', 'admin', '2013-03-26 23:25:43'),
(42, '96786880-9', 'bdnbgfghf', 'admin', '2013-03-26 23:51:23'),
(43, '96786880-9', 'parque de maquinarias bodega 1', 'admin', '2013-03-28 03:19:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabeceras_ot`
--

CREATE TABLE IF NOT EXISTS `cabeceras_ot` (
  `id_ot` double NOT NULL AUTO_INCREMENT,
  `cod_producto` varchar(12) NOT NULL,
  `cod_detalle_producto` double NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `centro_costos` double NOT NULL,
  `tipo_trabajo` tinyint(1) NOT NULL,
  `concepto_trabajo` tinyint(2) DEFAULT NULL COMMENT '1=Compras; 2=Mantencion; 3=Reparacion; 4=Certificacion Equipo',
  `descripcion_ot` varchar(500) NOT NULL,
  `estado_ot` tinyint(2) NOT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`id_ot`,`cod_producto`,`rut_empresa`,`cod_detalle_producto`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `cod_producto` (`cod_producto`,`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`),
  KEY `id_ot` (`id_ot`),
  KEY `cod_detalle_producto` (`cod_detalle_producto`,`cod_producto`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `cabeceras_ot`
--

INSERT INTO `cabeceras_ot` (`id_ot`, `cod_producto`, `cod_detalle_producto`, `rut_empresa`, `centro_costos`, `tipo_trabajo`, `concepto_trabajo`, `descripcion_ot`, `estado_ot`, `observaciones`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, '120010000001', 16, '96786880-9', 15, 1, 2, '', 2, '', 'admin', '2013-01-31 14:30:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabecera_oc`
--

CREATE TABLE IF NOT EXISTS `cabecera_oc` (
  `id_oc` double NOT NULL AUTO_INCREMENT,
  `id_solicitud_compra` double NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `fecha_oc` datetime NOT NULL,
  `solicitante` varchar(50) NOT NULL,
  `concepto` varchar(100) NOT NULL,
  `centro_costos` double NOT NULL,
  `atte_oc` varchar(100) DEFAULT NULL,
  `moneda` varchar(50) NOT NULL,
  `subtotal` double NOT NULL,
  `descuento` double DEFAULT NULL,
  `valor_neto` double DEFAULT NULL,
  `iva` double DEFAULT NULL,
  `total` double NOT NULL,
  `forma_de_pago` varchar(50) DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `sometido_plan_calidad` tinyint(1) DEFAULT NULL,
  `adj_esp_tecnica` tinyint(1) DEFAULT NULL,
  `adj_propuesta_economica` tinyint(1) DEFAULT NULL,
  `vb_depto_compras` tinyint(2) DEFAULT NULL,
  `nombre_vb_depto_compras` varchar(50) DEFAULT NULL,
  `fecha_vb_depto_compras` datetime DEFAULT NULL,
  `vb_jefe_compras` tinyint(2) DEFAULT NULL,
  `nombre_vb_jefe_compras` varchar(50) DEFAULT NULL,
  `fecha_vb_jefe_compras` datetime DEFAULT NULL,
  `vb_jefe_adm` tinyint(2) DEFAULT NULL,
  `nombre_vb_jefe_adm` varchar(50) DEFAULT NULL,
  `fecha_vb_jefe_adm` datetime DEFAULT NULL,
  `vb_jefe_parque_maquinaria` tinyint(2) DEFAULT NULL,
  `nombre_vb_jefe_pm` varchar(50) DEFAULT NULL,
  `fecha_vb_jefe_pm` datetime DEFAULT NULL,
  `observaciones` varchar(700) DEFAULT NULL,
  `id_archivo` double NOT NULL,
  `estado_oc` tinyint(2) NOT NULL,
  `rut_proveedor` varchar(10) NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`id_oc`,`id_solicitud_compra`,`rut_empresa`),
  KEY `id_oc` (`id_oc`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `id_solicitud_compra` (`id_solicitud_compra`,`rut_empresa`),
  KEY `centro_costos` (`centro_costos`,`rut_empresa`),
  KEY `rut_proveedor` (`rut_proveedor`,`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`),
  KEY `id_archivo` (`id_archivo`,`id_solicitud_compra`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `cabecera_oc`
--

INSERT INTO `cabecera_oc` (`id_oc`, `id_solicitud_compra`, `rut_empresa`, `fecha_oc`, `solicitante`, `concepto`, `centro_costos`, `atte_oc`, `moneda`, `subtotal`, `descuento`, `valor_neto`, `iva`, `total`, `forma_de_pago`, `fecha_entrega`, `sometido_plan_calidad`, `adj_esp_tecnica`, `adj_propuesta_economica`, `vb_depto_compras`, `nombre_vb_depto_compras`, `fecha_vb_depto_compras`, `vb_jefe_compras`, `nombre_vb_jefe_compras`, `fecha_vb_jefe_compras`, `vb_jefe_adm`, `nombre_vb_jefe_adm`, `fecha_vb_jefe_adm`, `vb_jefe_parque_maquinaria`, `nombre_vb_jefe_pm`, `fecha_vb_jefe_pm`, `observaciones`, `id_archivo`, `estado_oc`, `rut_proveedor`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, 20, '96786880-9', '2013-01-31 00:00:00', 'Valeria NiÃ±o', '1', 10, NULL, 'pesos', 1.125, 15, 1.11, 19, 1.338, 'cheque', '2013-02-23 00:00:00', 0, 1, 0, 1, 'admin', '2013-01-31 13:19:49', 0, 'null', '0000-00-00 00:00:00', 0, 'null', '0000-00-00 00:00:00', 0, 'null', '0000-00-00 00:00:00', '...', 48, 2, '79840090-8', 'admin', '2013-01-31 13:16:50'),
(2, 20, '96786880-9', '2013-01-31 00:00:00', 'VALERIA', '1', 11, NULL, 'ASDASD', 900, 15, 885, 19, 1, 'ASDASD', '2013-02-10 00:00:00', 0, 0, 0, 0, 'null', '0000-00-00 00:00:00', 0, 'null', '0000-00-00 00:00:00', 0, 'null', '0000-00-00 00:00:00', 0, 'null', '0000-00-00 00:00:00', '...', 48, 2, '79840090-8', 'admin', '2013-01-31 13:26:47'),
(3, 20, '96786880-9', '2013-01-31 00:00:00', 'Jose Rojas', '3', 15, NULL, 'pesos', 0, 0, 367, 0, 367, 'cheque', '2013-04-01 00:00:00', 0, 0, 0, 1, 'vnino', '2013-02-02 18:55:54', 1, 'vnino', '2013-02-02 18:55:55', 0, 'null', '0000-00-00 00:00:00', 0, 'null', '0000-00-00 00:00:00', '...', 48, 0, '79840090-8', 'admin', '2013-01-31 15:33:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centros_costos`
--

CREATE TABLE IF NOT EXISTS `centros_costos` (
  `Id_cc` double NOT NULL AUTO_INCREMENT,
  `rut_empresa` varchar(10) NOT NULL,
  `codigo_cc` varchar(50) NOT NULL,
  `descripcion_cc` varchar(200) NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`Id_cc`,`rut_empresa`),
  UNIQUE KEY `codigo_cc` (`codigo_cc`),
  KEY `Id_cc` (`Id_cc`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Volcar la base de datos para la tabla `centros_costos`
--

INSERT INTO `centros_costos` (`Id_cc`, `rut_empresa`, `codigo_cc`, `descripcion_cc`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, '96786880-9', '009', 'Parque Maquinarias', 'admin', '2013-01-04 02:00:27'),
(6, '96786880-9', '210', 'Desaladora Manto Verde', 'admin', '2013-01-04 02:04:56'),
(7, '96786880-9', '150', 'Vallenar-Caldera', 'admin', '2013-01-04 02:05:03'),
(9, '96786880-9', '170', 'El Teniente', 'admin', '2013-01-09 23:20:18'),
(10, '96786880-9', '180', 'Concepcion-Cabrero', 'admin', '2013-01-09 23:20:33'),
(11, '96786880-9', '190', 'Accesos A Iquique.', 'admin', '2013-01-09 23:20:50'),
(12, '96786880-9', '220', 'El Morro', 'admin', '2013-01-09 23:21:05'),
(13, '96786880-9', '230', 'La Serena', 'admin', '2013-01-09 23:21:18'),
(14, '96786880-9', '240', 'Maitenes Confluencia', 'admin', '2013-01-09 23:21:30'),
(15, '96786880-9', '250', 'Costanera', 'admin', '2013-01-09 23:21:44'),
(16, '1-9', '11', 'prueba informatica', 'vnino', '2013-01-09 23:35:58'),
(17, '96786880-9', '0', 'Oficinas Centrales', 'admin', '2013-01-10 00:02:37'),
(25, '96786880-9', '1234', 'Prueba Informatica', 'admin', '2013-01-31 15:41:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_productos`
--

CREATE TABLE IF NOT EXISTS `detalles_productos` (
  `cod_detalle_producto` double NOT NULL AUTO_INCREMENT,
  `cod_producto` varchar(12) NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `codigo_interno` varchar(50) DEFAULT NULL,
  `especifico` tinyint(1) NOT NULL,
  `patente` varchar(50) DEFAULT NULL,
  `agno` varchar(4) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `potencia_KVA` varchar(50) DEFAULT NULL,
  `horas_mensuales` varchar(50) DEFAULT NULL,
  `consumo_nominal` varchar(50) DEFAULT NULL,
  `consumo_mensual` varchar(50) DEFAULT NULL,
  `peso_bruto` varchar(50) DEFAULT NULL,
  `referencia_sacyr` varchar(50) DEFAULT NULL,
  `vin_chasis` varchar(50) DEFAULT NULL,
  `motor` varchar(50) DEFAULT NULL,
  `centro_costo` double DEFAULT NULL,
  `asignado_a_bodega` double DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT NULL,
  `num_factura` double DEFAULT NULL,
  `fecha_factura` datetime DEFAULT NULL,
  `num_guia_despacho` double DEFAULT NULL,
  `fecha_guia_despacho` datetime DEFAULT NULL,
  `valor_unitario` double NOT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `cod_bodega_anterior` double DEFAULT NULL,
  `fecha_cambio_bodega` datetime DEFAULT NULL,
  `estado_producto` tinyint(2) NOT NULL,
  `producto_arrendado` tinyint(1) NOT NULL,
  `empresa_arriendo` varchar(100) DEFAULT NULL,
  `id_oc` double DEFAULT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`cod_detalle_producto`,`cod_producto`,`rut_empresa`),
  UNIQUE KEY `cod_detalle_producto_2` (`cod_detalle_producto`),
  KEY `cod_detalle_producto` (`cod_detalle_producto`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `cod_producto` (`cod_producto`,`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`),
  KEY `asignado_a_bodega` (`asignado_a_bodega`,`rut_empresa`),
  KEY `centro_costo` (`centro_costo`,`rut_empresa`),
  KEY `cod_bodega_anterior` (`cod_bodega_anterior`,`rut_empresa`),
  KEY `codigo_interno` (`codigo_interno`),
  KEY `patente` (`patente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Volcar la base de datos para la tabla `detalles_productos`
--

INSERT INTO `detalles_productos` (`cod_detalle_producto`, `cod_producto`, `rut_empresa`, `codigo_interno`, `especifico`, `patente`, `agno`, `color`, `marca`, `modelo`, `potencia_KVA`, `horas_mensuales`, `consumo_nominal`, `consumo_mensual`, `peso_bruto`, `referencia_sacyr`, `vin_chasis`, `motor`, `centro_costo`, `asignado_a_bodega`, `fecha_asignacion`, `num_factura`, `fecha_factura`, `num_guia_despacho`, `fecha_guia_despacho`, `valor_unitario`, `observaciones`, `cod_bodega_anterior`, `fecha_cambio_bodega`, `estado_producto`, `producto_arrendado`, `empresa_arriendo`, `id_oc`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, '210020000001', '96786880-9', '', 2, 'DRXC98', '2008', 'Blanca', '', '', '', '', '', '', '', '', '', '', 11, 5, '2013-01-23 05:29:32', 123, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', NULL, NULL, 1, 1, '', 0, 'admin', '2013-01-23 05:29:32'),
(6, '210020000001', '96786880-9', '123123', 1, 'aa1111', '', '', '', '', '', '', '', '', '', '', '', '', 7, 5, '2013-01-28 02:48:46', 0, '1969-12-31 00:00:00', 0, '1969-12-31 00:00:00', 0, '', NULL, NULL, 1, 2, '', 0, 'vnino', '2013-01-28 02:48:46'),
(12, '120010000001', '96786880-9', '', 1, 'RF 2573', '', '', '', '', '', '', '', '', '', '', '', '', 12, 6, '2013-01-30 00:07:16', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', NULL, NULL, 1, 2, '', 0, 'vnino', '2013-01-30 00:07:16'),
(16, '120010000001', '96786880-9', '', 1, 'AE2501', '', '', '', '', '', '', '', '', '', '', '', '', 10, 6, '2013-01-31 13:40:00', 0, '1969-12-31 00:00:00', 0, '1969-12-31 00:00:00', 15000, '', NULL, NULL, 2, 2, '', 0, 'admin', '2013-01-31 13:40:00'),
(17, '220040000001', '96786880-9', '', 2, '', '', '', '', '', '', '', '', '', '', '', '', '', 6, 6, '2013-01-31 15:50:38', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 35000, '', NULL, NULL, 2, 2, '', 0, 'admin', '2013-01-31 15:50:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_oc`
--

CREATE TABLE IF NOT EXISTS `detalle_oc` (
  `id_oc` double NOT NULL,
  `id_solicitud_compra` double NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `id_det_oc` double NOT NULL AUTO_INCREMENT,
  `cantidad` double NOT NULL,
  `unidad` varchar(100) DEFAULT NULL,
  `descripcion` varchar(500) NOT NULL,
  `precio` double NOT NULL,
  `total` double NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`id_det_oc`,`id_oc`,`id_solicitud_compra`,`rut_empresa`),
  KEY `id_det_oc` (`id_det_oc`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `id_solicitud_compra` (`id_solicitud_compra`,`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`),
  KEY `id_oc` (`id_oc`,`id_solicitud_compra`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcar la base de datos para la tabla `detalle_oc`
--

INSERT INTO `detalle_oc` (`id_oc`, `id_solicitud_compra`, `rut_empresa`, `id_det_oc`, `cantidad`, `unidad`, `descripcion`, `precio`, `total`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, 20, '96786880-9', 3, 15, 'unidad', 'Parachoques', 75, 1.125, 'admin', '2013-01-31 13:19:49'),
(1, 20, '96786880-9', 4, 3, 'unidad', 'porta filtro', 90, 0, 'admin', '2013-01-31 13:19:49'),
(2, 20, '96786880-9', 11, 20, 'UN', 'ASDASD', 45, 900, 'vnino', '2013-02-02 17:13:46'),
(3, 20, '96786880-9', 13, 1, 'un', 'aaaaa', 367, 367, 'vnino', '2013-02-02 18:55:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ot`
--

CREATE TABLE IF NOT EXISTS `detalle_ot` (
  `id_det_ot` double NOT NULL AUTO_INCREMENT,
  `id_ot` double NOT NULL,
  `cod_producto` varchar(12) NOT NULL,
  `cod_detalle_producto` double NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `descripcion_trabajo` varchar(500) NOT NULL,
  `fecha_trabajo` datetime NOT NULL,
  `operador_a_cargo` varchar(50) DEFAULT NULL,
  `horas_hombre` decimal(10,0) DEFAULT NULL,
  `estado_trabajo` tinyint(2) NOT NULL,
  `observaciones` varchar(700) DEFAULT NULL,
  `usuario_actualiza` varchar(50) NOT NULL,
  `fecha_actualiza` datetime NOT NULL,
  PRIMARY KEY (`id_det_ot`,`id_ot`,`cod_producto`,`cod_detalle_producto`,`rut_empresa`),
  KEY `id_det_ot` (`id_det_ot`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_actualiza` (`usuario_actualiza`,`rut_empresa`),
  KEY `id_ot` (`id_ot`,`cod_producto`,`cod_detalle_producto`,`rut_empresa`),
  KEY `cod_detalle_producto` (`cod_detalle_producto`,`cod_producto`,`rut_empresa`),
  KEY `cod_producto` (`cod_producto`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `detalle_ot`
--

INSERT INTO `detalle_ot` (`id_det_ot`, `id_ot`, `cod_producto`, `cod_detalle_producto`, `rut_empresa`, `descripcion_trabajo`, `fecha_trabajo`, `operador_a_cargo`, `horas_hombre`, `estado_trabajo`, `observaciones`, `usuario_actualiza`, `fecha_actualiza`) VALUES
(1, 1, '120010000001', 16, '96786880-9', '', '1969-12-31 00:00:00', '0', '0', 0, '', 'admin', '2013-01-31 14:30:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `rut_empresa` varchar(10) NOT NULL,
  `razon_social` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `comuna` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `telefono_1` varchar(50) NOT NULL,
  `telefono_2` varchar(50) DEFAULT NULL,
  `telefono_3` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pagina_web` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`rut_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`rut_empresa`, `razon_social`, `direccion`, `comuna`, `ciudad`, `telefono_1`, `telefono_2`, `telefono_3`, `fax`, `email`, `pagina_web`) VALUES
('1-9', 'Sacyr', 'sacyr', 'sacyr', 'sacyr', '5555', '5555', '5555', '55', 'adolfo@srb.cl', 'www.sacyrchile.cl'),
('11111111', 'prueba', 'direccion de prueba', 'santiago', 'santiago', '123456', NULL, NULL, NULL, NULL, NULL),
('96786880-9', 'Sacyr Chile S.A.', 'Av. Vitacura 2939 Piso 11 Of. 1102', 'Las Condes', 'Santiago', '2 355 68 00', '2 2319872', NULL, NULL, NULL, 'http://www.sacyrchile.cl');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE IF NOT EXISTS `especialidades` (
  `id_esp` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_esp` varchar(100) NOT NULL,
  PRIMARY KEY (`id_esp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Volcar la base de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_esp`, `descripcion_esp`) VALUES
(1, 'Repuestos'),
(2, 'Eléctricos'),
(3, 'Neumáticos'),
(4, 'Insumos'),
(5, 'Reparación'),
(6, 'Servicio'),
(7, 'Filtros'),
(8, 'Químicos'),
(9, 'Fundición'),
(10, 'Maestranza'),
(11, 'Ferretería'),
(12, 'Seguridad'),
(13, 'Implementos'),
(14, 'Hidráulico'),
(15, 'Arriendo'),
(16, 'Fabricación'),
(17, 'Internacionales'),
(18, 'Gases'),
(19, 'Pernería'),
(20, 'Lubricantes'),
(21, 'Herramientas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE IF NOT EXISTS `eventos` (
  `id_evento` double NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `tabla_evento` varchar(50) NOT NULL,
  `id_registro_tabla_evento` double NOT NULL,
  `tipo_evento` tinyint(2) NOT NULL,
  `parametros_tipo_evento` varchar(200) NOT NULL,
  `ip_origen` varchar(50) NOT NULL,
  `observaciones` varchar(700) NOT NULL,
  `estado_evento` tinyint(1) NOT NULL COMMENT '1 = Inicio de Sesion Aceptado; 2 = Password Incorrecta; 3 = Ingreso de Registro; 4 = Modificación de Registro; 5 = Eliminacion de Registro; 6 = Consulta de Registro',
  `fecha_evento` datetime NOT NULL,
  PRIMARY KEY (`id_evento`,`usuario`,`rut_empresa`),
  KEY `id_evento` (`id_evento`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario` (`usuario`,`rut_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `eventos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia`
--

CREATE TABLE IF NOT EXISTS `familia` (
  `id_familia` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_familia` varchar(150) NOT NULL,
  PRIMARY KEY (`id_familia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcar la base de datos para la tabla `familia`
--

INSERT INTO `familia` (`id_familia`, `descripcion_familia`) VALUES
(1, 'Insumos'),
(2, 'Lubricantes'),
(3, 'Quimicos'),
(4, 'Filtros'),
(5, 'Gases'),
(6, 'Reparacion'),
(7, 'Servicios'),
(8, 'Fabricacion'),
(9, 'Repuestos'),
(10, 'Eléctrico AC'),
(11, 'Eléctrico DC'),
(12, 'Herramientas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares_fisicos`
--

CREATE TABLE IF NOT EXISTS `lugares_fisicos` (
  `id_lf` double NOT NULL AUTO_INCREMENT,
  `descripcion_lf` varchar(100) NOT NULL,
  `observacion_lf` varchar(500) DEFAULT NULL,
  `id_cc` double DEFAULT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  PRIMARY KEY (`id_lf`),
  KEY `usuario_ingreso` (`usuario_ingreso`),
  KEY `rut_empresa` (`rut_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `lugares_fisicos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `petroleo`
--

CREATE TABLE IF NOT EXISTS `petroleo` (
  `dia` varchar(2) NOT NULL,
  `mes` varchar(2) NOT NULL,
  `agno` varchar(4) NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `num_factura` varchar(50) DEFAULT NULL,
  `litros` double DEFAULT NULL,
  `valor_factura` double DEFAULT NULL,
  `valor_IEF` decimal(20,4) NOT NULL,
  `valor_IEV` decimal(20,4) DEFAULT NULL,
  `total_IEF` double NOT NULL,
  `utilizado_litros` double NOT NULL,
  `utilizado_total_IE` double NOT NULL,
  `destinacion_PP_litros` double NOT NULL,
  `destinacion_PP_IE_Recuperable` double NOT NULL,
  `destinacion_VT_litros` double NOT NULL,
  `destinacion_VT_IE_no_Recuperable` double NOT NULL,
  `saldo_litros` double NOT NULL,
  `saldo_impto_especifico` double NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`dia`,`mes`,`agno`,`rut_empresa`),
  KEY `mes` (`mes`),
  KEY `dia` (`dia`),
  KEY `agno` (`agno`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `petroleo`
--

INSERT INTO `petroleo` (`dia`, `mes`, `agno`, `rut_empresa`, `num_factura`, `litros`, `valor_factura`, `valor_IEF`, `valor_IEV`, `total_IEF`, `utilizado_litros`, `utilizado_total_IE`, `destinacion_PP_litros`, `destinacion_PP_IE_Recuperable`, `destinacion_VT_litros`, `destinacion_VT_IE_no_Recuperable`, `saldo_litros`, `saldo_impto_especifico`, `usuario_ingreso`, `fecha_ingreso`) VALUES
('01', '01', '2013', '96786880-9', '0', 9500, 0, '58.0000', '0.0000', 551000, 100, 0, 100, 5800, 0, 0, 9400, 539400, 'admin', '2013-01-31 13:27:52'),
('02', '01', '2013', '96786880-9', '2', 0, 0, '57.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 9400, 0, 'admin', '2013-01-31 13:35:15'),
('03', '01', '2013', '96786880-9', '', 0, 0, '56.0000', '0.0000', 0, 200, 0, 200, 11200, 0, 0, 9200, 504000, 'admin', '2013-01-31 15:31:03'),
('11', '01', '2013', '96786880-9', '1', 1000, 1000000, '18.0000', '12234.0000', 18000, 0, 0, 0, 0, 0, 0, 10200, 18000, 'admin', '2013-02-06 21:51:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `cod_producto` varchar(12) NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `tipo_producto` tinyint(2) NOT NULL COMMENT '1=Maquinarias y Equipos; 2=Vehiculo Menor; 3=Herramientas; 4=Muebles; 5=Generador; 6=Plantas; 7=Equipos de Tunel; 8=Otros',
  `descripcion` varchar(100) DEFAULT NULL,
  `activo_fijo` tinyint(1) NOT NULL,
  `critico` tinyint(1) NOT NULL,
  `pasillo` varchar(3) DEFAULT NULL,
  `casillero` varchar(3) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`cod_producto`,`rut_empresa`),
  KEY `cod_producto` (`cod_producto`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `productos`
--

INSERT INTO `productos` (`cod_producto`, `rut_empresa`, `tipo_producto`, `descripcion`, `activo_fijo`, `critico`, `pasillo`, `casillero`, `observaciones`, `usuario_ingreso`, `fecha_ingreso`) VALUES
('110010000001', '96786880-9', 1, 'Camion Grua', 1, 1, 'A', '1', '', 'vnino', '2013-01-29 23:39:51'),
('110020000001', '96786880-9', 2, 'test', 1, 1, '02', '02', 'asdadsads', 'admin', '2013-01-31 00:44:54'),
('120010000001', '96786880-9', 1, 'BOMBA HORMIGON CEPSA', 1, 2, 'A', '2', '', 'vnino', '2013-01-29 23:51:13'),
('210020000001', '96786880-9', 2, 'NISSAN TERRANO', 2, 1, 'AE', '9', '', 'admin', '2013-01-23 05:28:10'),
('220040000001', '96786880-9', 4, 'silla', 2, 2, 'ED', '3', '', 'admin', '2013-01-31 15:49:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE IF NOT EXISTS `proveedores` (
  `rut_proveedor` varchar(10) NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `razon_social` varchar(200) NOT NULL,
  `domicilio` varchar(200) NOT NULL,
  `comuna` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `telefono_1` varchar(50) NOT NULL,
  `telefono_2` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `contacto` varchar(50) DEFAULT NULL,
  `fono_contacto` int(11) DEFAULT NULL,
  `email_contacto` varchar(50) DEFAULT NULL,
  `domicilio_sucursal` varchar(200) DEFAULT NULL,
  `comuna_sucursal` varchar(100) DEFAULT NULL,
  `ciudad_sucursal` varchar(100) DEFAULT NULL,
  `telefono_sucursal` int(11) DEFAULT NULL,
  `fax_sucursal` int(11) DEFAULT NULL,
  `contacto_sucursal` varchar(150) DEFAULT NULL,
  `fono_contacto_sucursal` int(11) DEFAULT NULL,
  `email_contacto_sucursal` varchar(50) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `id_esp` int(11) NOT NULL,
  `Sub_Especializacion` varchar(200) NOT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`rut_proveedor`,`rut_empresa`),
  KEY `rut_proveedor` (`rut_proveedor`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`),
  KEY `id_esp` (`id_esp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`rut_proveedor`, `rut_empresa`, `razon_social`, `domicilio`, `comuna`, `ciudad`, `telefono_1`, `telefono_2`, `fax`, `celular`, `mail`, `contacto`, `fono_contacto`, `email_contacto`, `domicilio_sucursal`, `comuna_sucursal`, `ciudad_sucursal`, `telefono_sucursal`, `fax_sucursal`, `contacto_sucursal`, `fono_contacto_sucursal`, `email_contacto_sucursal`, `observaciones`, `id_esp`, `Sub_Especializacion`, `usuario_ingreso`, `fecha_ingreso`) VALUES
('79840090-8', '96786880-9', 'NISSAN AUGUSTO NOGUEIRA Y CIA', 'Avda. Salvador NÂº 887', 'Providencia', 'Santiago', '2096631', '', '2232574', '', 'repuestos@nissanane.cl', 'CARLOS NAVARRO', 123456, '', '', '', '', 0, 0, '', 0, '', '', 1, 'Camiones y Camionetas', 'Admin2', '2013-01-23 02:33:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_petroleo`
--

CREATE TABLE IF NOT EXISTS `salida_petroleo` (
  `id_salida_petroleo` double NOT NULL AUTO_INCREMENT,
  `rut_empresa` varchar(10) NOT NULL,
  `dia` varchar(2) NOT NULL,
  `mes` varchar(2) NOT NULL,
  `agno` varchar(4) NOT NULL,
  `cod_producto` varchar(12) NOT NULL,
  `cod_detalle_producto` double NOT NULL,
  `num_doc` double NOT NULL,
  `litros` double NOT NULL,
  `centro_costo` double DEFAULT NULL,
  `persona_autoriza` varchar(50) NOT NULL,
  `persona_retira` varchar(50) NOT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`id_salida_petroleo`,`rut_empresa`),
  UNIQUE KEY `num_doc` (`num_doc`),
  KEY `id_salida_petroleo` (`id_salida_petroleo`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`),
  KEY `cod_detalle_producto` (`cod_detalle_producto`,`cod_producto`,`rut_empresa`),
  KEY `cod_producto` (`cod_producto`,`rut_empresa`),
  KEY `dia` (`dia`,`mes`,`agno`,`rut_empresa`),
  KEY `mes` (`mes`,`dia`,`agno`,`rut_empresa`),
  KEY `agno` (`agno`,`dia`,`mes`,`rut_empresa`),
  KEY `centro_costo` (`centro_costo`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `salida_petroleo`
--

INSERT INTO `salida_petroleo` (`id_salida_petroleo`, `rut_empresa`, `dia`, `mes`, `agno`, `cod_producto`, `cod_detalle_producto`, `num_doc`, `litros`, `centro_costo`, `persona_autoriza`, `persona_retira`, `observacion`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(1, '96786880-9', '01', '01', '2013', '120010000001', 12, 1, 100, 12, 'VALERIA', 'VALERIA', '', 'admin', '2013-01-31 13:28:16'),
(2, '96786880-9', '03', '01', '2013', '120010000001', 16, 52, 200, 10, 'Jose', 'Juan', '', 'admin', '2013-01-31 15:32:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_compra`
--

CREATE TABLE IF NOT EXISTS `solicitudes_compra` (
  `id_solicitud_compra` double NOT NULL AUTO_INCREMENT,
  `rut_empresa` varchar(10) NOT NULL,
  `id_ot` double DEFAULT NULL,
  `descripcion_solicitud` varchar(1000) NOT NULL,
  `tipo_solicitud` tinyint(1) NOT NULL COMMENT '1 = Nacional ; 0 = Internacional',
  `concepto` tinyint(2) NOT NULL COMMENT '1=Compra; 2=Mantencion, 3=Reparacion; 4=Certificacion Equipo',
  `estado` tinyint(2) NOT NULL COMMENT '1=Abierta; 2=En Espera de Informacion; 3=Autorizada; 4=Anulada; 5=Cerrada',
  `observaciones` varchar(500) DEFAULT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`id_solicitud_compra`,`rut_empresa`),
  KEY `id_solicitud_compra` (`id_solicitud_compra`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `usuario_ingreso` (`usuario_ingreso`,`rut_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcar la base de datos para la tabla `solicitudes_compra`
--

INSERT INTO `solicitudes_compra` (`id_solicitud_compra`, `rut_empresa`, `id_ot`, `descripcion_solicitud`, `tipo_solicitud`, `concepto`, `estado`, `observaciones`, `usuario_ingreso`, `fecha_ingreso`) VALUES
(20, '96786880-9', 0, '1 Parachoque y 1 Porta Filtro Aceite', 1, 3, 3, 'Se solicita cotizaciones a proveedores, se queda en espera de la informaciÃ³n.', 'admin', '2013-01-23 04:40:46'),
(21, '96786880-9', 9, 'asdasdasdasd', 1, 2, 3, '', 'vnino', '2013-01-30 00:25:43'),
(22, '96786880-9', 0, 'solicitud de prueba', 1, 2, 0, '', 'admin', '2013-01-31 15:43:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subfamilia`
--

CREATE TABLE IF NOT EXISTS `subfamilia` (
  `id_subfamilia` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_subfamilia` varchar(150) NOT NULL,
  `id_familia` int(11) NOT NULL,
  PRIMARY KEY (`id_subfamilia`),
  KEY `id_familia` (`id_familia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Volcar la base de datos para la tabla `subfamilia`
--

INSERT INTO `subfamilia` (`id_subfamilia`, `descripcion_subfamilia`, `id_familia`) VALUES
(1, 'Limpieza', 1),
(2, 'Perneria', 1),
(3, 'Ferreteria', 1),
(4, 'Electricos', 1),
(5, 'Motor', 2),
(6, 'Hidraulico', 2),
(7, 'Engranajes', 2),
(8, 'Grasas', 2),
(9, 'Fluidos', 2),
(10, 'Diluyentes', 3),
(11, 'Desincrsutante', 3),
(12, 'Solvente', 3),
(13, 'Aire Prim', 4),
(14, 'Aire Sec', 4),
(15, 'Combustible', 4),
(16, 'Secado Aire', 4),
(17, 'Decantador', 4),
(18, 'Hidraulico', 4),
(19, 'Racor', 4),
(20, 'Linea', 4),
(21, 'Aceite', 4),
(22, 'Antipolen', 4),
(23, 'Cabina', 4),
(24, 'Oxigeno', 5),
(25, 'Argon', 5),
(26, 'Acetileno', 5),
(27, 'Licuado', 5),
(28, 'Hidraulica', 6),
(29, 'Mecanica', 6),
(30, 'Electrica', 6),
(31, 'Mantenciones', 7),
(32, 'Alineamiento', 7),
(33, 'Montaje', 7),
(34, 'Ajustes', 7),
(35, 'Torneria', 7),
(36, 'Hidraulico', 8),
(37, 'Mecanica', 8),
(38, 'Electrica', 8),
(39, 'Motor', 9),
(40, 'Chasis', 9),
(41, 'Estructural', 9),
(42, 'Frenos', 9),
(43, 'Electricos', 9),
(44, 'Hidraulicos', 9),
(45, 'Iluminación', 10),
(46, 'Control', 10),
(47, 'Fuerza', 10),
(48, 'Iluminación', 11),
(49, 'Control', 11),
(50, 'Otros', 11),
(51, 'Eléctrica', 12),
(52, 'Torque', 12),
(53, 'Mecánica', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario` varchar(50) NOT NULL,
  `rut_empresa` varchar(10) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `depto` varchar(50) NOT NULL,
  `cod_bodega` double NOT NULL,
  `tipo_usuario` tinyint(2) NOT NULL,
  `estado_usuario` tinyint(2) NOT NULL COMMENT '1 = Usuario Habilitado; 2 = Usuario Deshabilitado; 3 = usuario inactivo',
  `permisos` double NOT NULL,
  `cambio_password` tinyint(1) NOT NULL,
  `key_password` varchar(100) NOT NULL,
  `Jefatura` tinyint(2) DEFAULT NULL COMMENT 'Depto. Compras, Jefe de Compras, Jefe de Administración, Jefe Parque Maquinarias, Jefe Grupo Obras, Gerente General',
  `Backup_Jefatura` tinyint(2) DEFAULT NULL COMMENT 'Depto. Compras, Jefe de Compras, Jefe de Administración, Jefe Parque Maquinarias, Jefe Grupo Obras, Gerente General',
  `nombre_arch_fd` varchar(200) DEFAULT NULL,
  `ruta_arch_fd` varchar(200) DEFAULT NULL,
  `ext_arch_fd` varchar(10) DEFAULT NULL,
  `user_insert_fd` varchar(40) DEFAULT NULL,
  `fecha_insert_fd` date DEFAULT NULL,
  `usuario_ingreso` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  PRIMARY KEY (`usuario`,`rut_empresa`),
  KEY `usuario` (`usuario`),
  KEY `rut_empresa` (`rut_empresa`),
  KEY `cod_bodega` (`cod_bodega`,`rut_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario`, `rut_empresa`, `contrasena`, `nombre`, `email`, `cargo`, `depto`, `cod_bodega`, `tipo_usuario`, `estado_usuario`, `permisos`, `cambio_password`, `key_password`, `Jefatura`, `Backup_Jefatura`, `nombre_arch_fd`, `ruta_arch_fd`, `ext_arch_fd`, `user_insert_fd`, `fecha_insert_fd`, `usuario_ingreso`, `fecha_ingreso`) VALUES
('admin', '96786880-9', 'Live1', 'Administrador', 'adolfo@srb.cl', 'Administrador Sitio Web', 'Informatica', 5, 1, 1, 1, 2, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2013-01-02 00:00:00'),
('Admin2', '96786880-9', '123456789', 'Administrador', 'admin@srbcorp.net', 'Administrador Sistema', 'Informatica', 15, 1, 2, 0, 2, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2013-01-23 01:26:50'),
('ccarrillo', '96786880-9', '123456789', 'Carlos Carrillo', 'ccarrillo@srbcorp.net', 'Administrador', 'SRB', 5, 1, 1, 0, 1, '', 5, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2013-01-21 01:44:04'),
('jperez', '1-9', '123456789', 'Juan Perezz', 'jperez', 'Operador', 'MantenciÃ³n', 5, 9, 1, 0, 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2013-01-08 23:58:39'),
('jperez', '96786880-9', '123456789', 'Juan Perezz', 'jperez', 'Operador', 'MantenciÃ³n', 5, 9, 1, 0, 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admin2', '2013-01-25 03:37:59'),
('vnino', '1-9', '123456789', 'Valeria NiÃ±o', 'vnino@srbcorp.net', 'Ing. Informatica', 'Desarrollo', 5, 1, 1, 0, 2, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2013-01-04 00:36:00'),
('vnino', '11111111', '123456789', 'Valeria NiÃ±o', 'vnino@srbcorp.net', 'Ing. Informatica', 'Desarrollo', 5, 1, 1, 1, 2, '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vnino', '2013-01-02 00:00:00'),
('vnino', '96786880-9', 'informatica', 'Valeria NiÃ±o', 'vnino@srbcorp.net', 'Ing. Informatica', 'Desarrollo', 5, 1, 1, 0, 2, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2013-01-21 01:07:39');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD CONSTRAINT `archivos_ibfk_9` FOREIGN KEY (`usuario_carga`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `archivos_ibfk_7` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_compra` (`id_solicitud_compra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `archivos_ibfk_8` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `bodegas`
--
ALTER TABLE `bodegas`
  ADD CONSTRAINT `bodegas_ibfk_6` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `bodegas_ibfk_5` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cabeceras_ot`
--
ALTER TABLE `cabeceras_ot`
  ADD CONSTRAINT `cabeceras_ot_ibfk_2` FOREIGN KEY (`cod_detalle_producto`) REFERENCES `detalles_productos` (`cod_detalle_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabeceras_ot_ibfk_3` FOREIGN KEY (`rut_empresa`) REFERENCES `detalles_productos` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabeceras_ot_ibfk_4` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabeceras_ot_ibfk_5` FOREIGN KEY (`cod_producto`) REFERENCES `detalles_productos` (`cod_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cabecera_oc`
--
ALTER TABLE `cabecera_oc`
  ADD CONSTRAINT `cabecera_oc_ibfk_1` FOREIGN KEY (`id_solicitud_compra`) REFERENCES `solicitudes_compra` (`id_solicitud_compra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabecera_oc_ibfk_2` FOREIGN KEY (`rut_empresa`) REFERENCES `solicitudes_compra` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabecera_oc_ibfk_3` FOREIGN KEY (`centro_costos`) REFERENCES `centros_costos` (`Id_cc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabecera_oc_ibfk_4` FOREIGN KEY (`rut_proveedor`) REFERENCES `proveedores` (`rut_proveedor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cabecera_oc_ibfk_5` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `centros_costos`
--
ALTER TABLE `centros_costos`
  ADD CONSTRAINT `centros_costos_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `centros_costos_ibfk_2` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  ADD CONSTRAINT `detalles_productos_ibfk_1` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalles_productos_ibfk_2` FOREIGN KEY (`rut_empresa`) REFERENCES `productos` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalles_productos_ibfk_4` FOREIGN KEY (`centro_costo`) REFERENCES `centros_costos` (`Id_cc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalles_productos_ibfk_5` FOREIGN KEY (`asignado_a_bodega`) REFERENCES `bodegas` (`cod_bodega`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalles_productos_ibfk_6` FOREIGN KEY (`cod_bodega_anterior`) REFERENCES `bodegas` (`cod_bodega`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalles_productos_ibfk_8` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_oc`
--
ALTER TABLE `detalle_oc`
  ADD CONSTRAINT `detalle_oc_ibfk_1` FOREIGN KEY (`id_oc`) REFERENCES `cabecera_oc` (`id_oc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_oc_ibfk_2` FOREIGN KEY (`id_solicitud_compra`) REFERENCES `cabecera_oc` (`id_solicitud_compra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_oc_ibfk_3` FOREIGN KEY (`rut_empresa`) REFERENCES `cabecera_oc` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_oc_ibfk_4` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_ot`
--
ALTER TABLE `detalle_ot`
  ADD CONSTRAINT `detalle_ot_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `cabeceras_ot` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_ot_ibfk_2` FOREIGN KEY (`id_ot`) REFERENCES `cabeceras_ot` (`id_ot`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_ot_ibfk_4` FOREIGN KEY (`cod_detalle_producto`) REFERENCES `cabeceras_ot` (`cod_detalle_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_ot_ibfk_5` FOREIGN KEY (`usuario_actualiza`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_ot_ibfk_6` FOREIGN KEY (`cod_producto`) REFERENCES `detalles_productos` (`cod_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`rut_empresa`) REFERENCES `usuarios` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lugares_fisicos`
--
ALTER TABLE `lugares_fisicos`
  ADD CONSTRAINT `lugares_fisicos_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `lugares_fisicos_ibfk_2` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `petroleo`
--
ALTER TABLE `petroleo`
  ADD CONSTRAINT `petroleo_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `petroleo_ibfk_2` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_ibfk_6` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD CONSTRAINT `proveedores_ibfk_3` FOREIGN KEY (`id_esp`) REFERENCES `especialidades` (`id_esp`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `proveedores_ibfk_2` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `salida_petroleo`
--
ALTER TABLE `salida_petroleo`
  ADD CONSTRAINT `salida_petroleo_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `petroleo` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_10` FOREIGN KEY (`cod_producto`) REFERENCES `detalles_productos` (`cod_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_3` FOREIGN KEY (`cod_detalle_producto`) REFERENCES `detalles_productos` (`cod_detalle_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_4` FOREIGN KEY (`centro_costo`) REFERENCES `detalles_productos` (`centro_costo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_5` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_7` FOREIGN KEY (`mes`) REFERENCES `petroleo` (`mes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_8` FOREIGN KEY (`agno`) REFERENCES `petroleo` (`agno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `salida_petroleo_ibfk_9` FOREIGN KEY (`dia`) REFERENCES `petroleo` (`dia`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `solicitudes_compra`
--
ALTER TABLE `solicitudes_compra`
  ADD CONSTRAINT `solicitudes_compra_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `solicitudes_compra_ibfk_3` FOREIGN KEY (`usuario_ingreso`) REFERENCES `usuarios` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `subfamilia`
--
ALTER TABLE `subfamilia`
  ADD CONSTRAINT `subfamilia_ibfk_1` FOREIGN KEY (`id_familia`) REFERENCES `familia` (`id_familia`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rut_empresa`) REFERENCES `empresa` (`rut_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`cod_bodega`) REFERENCES `bodegas` (`cod_bodega`) ON DELETE NO ACTION ON UPDATE NO ACTION;
