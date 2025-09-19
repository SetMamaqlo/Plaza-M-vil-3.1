-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2025 a las 17:36:51
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
-- Base de datos: `agro_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agricultor`
--

CREATE TABLE `agricultor` (
  `id_agricultor` int(11) NOT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `id_zona` int(10) DEFAULT NULL,
  `certificaciones` varchar(150) DEFAULT NULL,
  `fotos` int(10) DEFAULT NULL,
  `metodo_entrega` varchar(20) DEFAULT NULL,
  `metodos_de_pago` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agricultor`
--

INSERT INTO `agricultor` (`id_agricultor`, `id_usuario`, `id_zona`, `certificaciones`, `fotos`, `metodo_entrega`, `metodos_de_pago`) VALUES
(4, 15, 1, NULL, NULL, NULL, NULL),
(5, 16, 1, NULL, NULL, NULL, NULL),
(6, 17, 1, NULL, NULL, NULL, NULL),
(7, 21, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calificacion` int(2) NOT NULL,
  `id_agricultor` int(10) DEFAULT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `puntuacion` int(2) DEFAULT NULL,
  `comentario` varchar(250) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_usuario`, `fecha_creacion`) VALUES
(2, 15, '2025-09-09'),
(3, 12, '2025-09-09'),
(4, 18, '2025-09-10'),
(5, 19, '2025-09-10'),
(6, 20, '2025-09-10'),
(7, 22, '2025-09-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_detalle`
--

CREATE TABLE `carrito_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_carrito` int(10) DEFAULT NULL,
  `id_producto` int(10) DEFAULT NULL,
  `cantidad` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_detalle`
--

INSERT INTO `carrito_detalle` (`id_detalle`, `id_carrito`, `id_producto`, `cantidad`) VALUES
(11, 4, 19, 3),
(13, 4, 13, 1),
(14, 4, 20, 5),
(15, 4, 18, 1),
(16, 2, 19, 1),
(19, 5, 19, 2),
(20, 5, 20, 1),
(23, 7, 13, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Verduras', 'Variedad de Verduras'),
(2, 'Tuberculos', 'Tuberculos varios'),
(5, 'Frutas', 'Las mejores Frutas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id_devolucion` int(10) NOT NULL,
  `id_pedido` int(10) DEFAULT NULL,
  `motivo` varchar(250) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(10) NOT NULL,
  `id_venta` int(10) DEFAULT NULL,
  `numero_factura` int(10) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `ruta_pdf` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(10) NOT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `id_agricultor` int(10) DEFAULT NULL,
  `mensaje` varchar(250) DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL,
  `leido` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(10) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_agricultor` int(11) DEFAULT NULL,
  `id_pedido` varchar(50) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(255) DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `id_venta` int(10) DEFAULT NULL,
  `transaccion_id` int(50) DEFAULT NULL,
  `moneda` varchar(50) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `metodo` varchar(20) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(10) NOT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `id_detalle` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `fecha`, `estado`, `id_detalle`) VALUES
(1, 20, '2025-09-18', 'pendiente', NULL),
(2, 20, '2025-09-18', 'pendiente', NULL),
(3, 20, '2025-09-18', 'pendiente', NULL),
(4, 20, '2025-09-19', 'pendiente', NULL),
(5, 20, '2025-09-19', 'pendiente', NULL),
(6, 20, '2025-09-19', 'pendiente', NULL),
(7, 20, '2025-09-19', 'pendiente', NULL),
(8, 20, '2025-09-19', 'pendiente', NULL),
(9, 20, '2025-09-19', 'pendiente', NULL),
(10, 20, '2025-09-19', 'pendiente', NULL),
(11, 20, '2025-09-19', 'pendiente', NULL),
(12, 20, '2025-09-19', 'pendiente', NULL),
(13, 20, '2025-09-19', 'pendiente', NULL),
(14, 20, '2025-09-19', 'pendiente', NULL),
(15, 20, '2025-09-19', 'pendiente', NULL),
(16, 20, '2025-09-19', 'pendiente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `id_pedido_detalle` int(10) NOT NULL,
  `id_pedido` int(10) DEFAULT NULL,
  `id_producto` int(10) DEFAULT NULL,
  `cantidad` int(10) DEFAULT NULL,
  `precio_unitario` int(20) DEFAULT NULL,
  `id_unidad` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`id_pedido_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`, `id_unidad`) VALUES
(1, 0, 19, 5, 10000, NULL),
(2, 2, 19, 5, 10000, NULL),
(3, 2, 18, 1, 10000, NULL),
(4, 3, 26, 1, 6000, NULL),
(5, 4, 13, 1, 17000, NULL),
(6, 4, 18, 1, 10000, NULL),
(7, 5, 13, 1, 17000, NULL),
(8, 5, 27, 1, 2800, NULL),
(9, 6, 19, 3, 10000, NULL),
(10, 8, 22, 1, 5000, NULL),
(11, 9, 18, 1, 10000, NULL),
(12, 10, 18, 1, 10000, NULL),
(13, 11, 19, 1, 10000, NULL),
(14, 12, 22, 1, 5000, NULL),
(15, 13, 13, 1, 17000, NULL),
(16, 14, 18, 1, 10000, NULL),
(17, 15, 18, 1, 10000, NULL),
(18, 16, 19, 1, 10000, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pqrs`
--

CREATE TABLE `pqrs` (
  `id_pqrs` int(10) NOT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `respuesta` varchar(250) DEFAULT NULL,
  `fecha_respuesta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_agricultor` int(10) DEFAULT NULL,
  `id_categoria` int(10) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `stock` int(20) DEFAULT NULL,
  `precio_unitario` int(10) DEFAULT NULL,
  `id_unidad` int(10) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_agricultor`, `id_categoria`, `descripcion`, `nombre`, `stock`, `precio_unitario`, `id_unidad`, `foto`, `fecha_publicacion`) VALUES
(13, 4, 5, 'mango manguito', 'Mango  ', 500, 17000, 3, '68be360ba62ad_mango tomi.webp', '2025-09-18'),
(18, 4, 2, 'otra papa ', 'papa', 500, 10000, 4, '68c0fab3a72f3_mucha papa.jpg', '2025-09-10'),
(19, 4, 5, 'freza directamente traída del campo, la mejor freza de la región. ', 'freza', 500, 10000, 2, '68c1184d6de9d_freza.jpeg', '2025-09-10'),
(20, 6, 2, 'los mejores frijoles bola roja que te puedes comer ', 'frijoles', 1000, 5000, 2, '68c119e129caa_3000.jpg', '2025-09-10'),
(21, 7, 5, 'Limones verdes', 'Limon', 500, 2000, 3, '68c1706eaa8a2_Limon-600x473.jpg', '2025-09-10'),
(22, 7, 1, 'Muy moradas', 'Berenjenas', 300, 5000, 3, '68c170dd7391a_Berenjena-1.jpg', '2025-09-10'),
(23, 7, 1, 'Zanahorias', 'Zanahorias', 2000, 5000, 2, '68c1712b65810_carousel1.jpg', '2025-09-10'),
(24, 7, 2, 'Bola Roja', 'Frijoles', 500, 2000, 3, '68c17175c5a3a_68c0fadc338e9_pexels-arina-krasnikova-6316686.jpg', '2025-09-10'),
(25, 7, 1, 'Esta verde', 'Brocoli', 300, 5000, 2, '68c171b00328b_68c126723ccea_pexels-cup-of-couple-7657091.jpg', '2025-09-10'),
(26, 7, 5, 'Se llaman naranjas a pesar de que son amarillas', 'Naranjas', 500, 6000, 2, '68c174f9eaeb4_carousel3.jpg', '2025-09-10'),
(27, 7, 5, 'Son rojas y ricas', 'Manzanas', 300, 2800, 3, '68c1759173cb0_Manzanas.jpg', '2025-09-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reembolsos`
--

CREATE TABLE `reembolsos` (
  `id_reembolso` int(10) NOT NULL,
  `id_pago` int(10) DEFAULT NULL,
  `monto` int(20) DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(2) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Comprador'),
(3, 'Agricultor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_de_medida`
--

CREATE TABLE `unidades_de_medida` (
  `id_unidad` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidades_de_medida`
--

INSERT INTO `unidades_de_medida` (`id_unidad`, `nombre`) VALUES
(2, 'kilogramos'),
(3, 'libra'),
(4, 'bultos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_rol` int(2) DEFAULT NULL,
  `numero_documento` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tipo_documento` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` varchar(50) DEFAULT NULL,
  `Foto` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `email`, `telefono`, `id_rol`, `numero_documento`, `username`, `password`, `tipo_documento`, `fecha_nacimiento`, `Foto`) VALUES
(12, 'admin', 'ujiht3d5@gmil.com', '35345345', 1, '1209930219', 'admin ', '$2y$10$esrEadfU98MFN5aL7DK7JutaraCsgmEXGVxAkItGUZfckGoSBrIve', 'Cédula de Ciudadanía', '2025-09-23', NULL),
(15, 'juan', 'ujiht3d5@gmail.com', '2147483647', 3, '80821459', 'juanvende', '$2y$10$62LFc9It39LEQDsF3l8blelHDyWW7PzyXNa1y/K5wHAGbQyqidVrW', 'Cédula de Ciudadanía', '2025-09-15', '68c09b902aff3_papa.jpg'),
(16, 'juan', 'dslajdla@gmail.com', '312321433', 3, '1212343214', 'Juanagro', '$2y$10$k37KuW.hQKb5clt4FoZzAOpoV08BjCDJpuiO2HKoLZw3JiW5UTF3W', 'Cédula de Ciudadanía', '2025-09-25', '68c10a8aa4f85_papa.jpg'),
(17, 'usuario1', 'asdfafds@gmail.com', '324363454', 3, '1243214214', 'usuario1', '$2y$10$2gZyn6wFGrZNTN6WcUPuUe4mIHCy80XK5s7tQlnXV4BTBqQlRYjga', 'Cédula de Ciudadanía', '2025-09-10', NULL),
(18, 'comprador', 'jsdijasiujdq2@gmail.com', '12324314', 2, '9827979842', 'comprador', '$2y$10$v.2ByRZi0CSLQzhfPBwSaewdcU1IO55gJxTwVVdWQSFqyu6T2k11G', 'Pasaporte', '2025-09-10', NULL),
(19, 'usuario2', 'asdsadas@gmail.com', '124221412', 2, '421423142', 'usuario2', '$2y$10$3P5oEuHTHYa0Da7v5jhdmOliqGnsivtvnymQnXuIb1lhMJIHqVLI.', 'Cédula de Ciudadanía', '2025-09-10', NULL),
(20, 'Juan Felipe Prado Yemayusa', 'felixxd722@gmail.com', '3003889780', 1, '1053323936', 'Felpa Admin', '$2y$10$ktpqrELucIH9bEqzJNDdpu0MOlSeL6wrpq0r4fTDBkddebWRb6gRW', 'Cédula de Ciudadanía', '2004-06-23', '68c16e6594559_68bee552b2243_perfil_2_1752074078.jpeg'),
(21, 'Felipe Prado', 'pradoyemayusafelipe@gmail.com', '3225003395', 3, '1053323936', 'Felpa Vendedor', '$2y$10$6fEK3Al34uRYT9K39ewtqujgQUTYovQr8AZ6vc2MS0GEpb81UMu.y', 'Cédula de Ciudadanía', '2022-06-23', NULL),
(22, 'Felipe Prado', 'felixxd722@gmail.com', '1053323936', 2, '1053323936', 'Felpa Comprador', '$2y$10$7Ve5F7hcGn.7FAa.9EVUrOav.5MlMgQv6Xjbw0rs93G7BHM84VaF6', 'Cédula de Ciudadanía', '2025-09-10', NULL),
(23, 'Felipe Prado', 'felixxd722@gmail.com', '3225003395', 2, '1053323936', 'Felpa Vendedor', '$2y$10$t/1zTgtlVu3OFKGcuXY0ruMx.P1LN6OOXP/znxrRFDii1KA38KBHq', 'Cédula de Ciudadanía', '2007-07-17', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(10) NOT NULL,
  `id_pedido` int(10) DEFAULT NULL,
  `id_agricultor` int(10) DEFAULT NULL,
  `id_usuario` int(10) DEFAULT NULL,
  `fecha_venta` date DEFAULT NULL,
  `subtotal` int(20) DEFAULT NULL,
  `impuestos` double(10,2) DEFAULT NULL,
  `total` double(20,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

CREATE TABLE `zona` (
  `id_zona` int(10) NOT NULL,
  `zona` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zona`
--

INSERT INTO `zona` (`id_zona`, `zona`) VALUES
(1, 'Zona por defecto');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agricultor`
--
ALTER TABLE `agricultor`
  ADD PRIMARY KEY (`id_agricultor`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_zona` (`id_zona`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_agricultor` (`id_agricultor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id_devolucion`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_agricultor` (`id_agricultor`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_agricultor` (`id_agricultor`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`id_pedido_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_unidad` (`id_unidad`);

--
-- Indices de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  ADD PRIMARY KEY (`id_pqrs`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_agricultor` (`id_agricultor`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `reembolsos`
--
ALTER TABLE `reembolsos`
  ADD PRIMARY KEY (`id_reembolso`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `unidades_de_medida`
--
ALTER TABLE `unidades_de_medida`
  ADD PRIMARY KEY (`id_unidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_agricultor` (`id_agricultor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `zona`
--
ALTER TABLE `zona`
  ADD PRIMARY KEY (`id_zona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agricultor`
--
ALTER TABLE `agricultor`
  MODIFY `id_agricultor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id_pedido_detalle` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `unidades_de_medida`
--
ALTER TABLE `unidades_de_medida`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agricultor`
--
ALTER TABLE `agricultor`
  ADD CONSTRAINT `agricultor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `agricultor_ibfk_2` FOREIGN KEY (`id_zona`) REFERENCES `zona` (`id_zona`);

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_agricultor`) REFERENCES `agricultor` (`id_agricultor`),
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD CONSTRAINT `carrito_detalle_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`),
  ADD CONSTRAINT `carrito_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `devoluciones_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_agricultor`) REFERENCES `agricultor` (`id_agricultor`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pagos_ibfk_3` FOREIGN KEY (`id_agricultor`) REFERENCES `agricultor` (`id_agricultor`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `pedido_detalle_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `pedido_detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `pedido_detalle_ibfk_3` FOREIGN KEY (`id_unidad`) REFERENCES `unidades_de_medida` (`id_unidad`);

--
-- Filtros para la tabla `pqrs`
--
ALTER TABLE `pqrs`
  ADD CONSTRAINT `pqrs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_agricultor`) REFERENCES `agricultor` (`id_agricultor`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `reembolsos`
--
ALTER TABLE `reembolsos`
  ADD CONSTRAINT `reembolsos_ibfk_1` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_agricultor`) REFERENCES `agricultor` (`id_agricultor`),
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
