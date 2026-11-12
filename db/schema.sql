/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO' */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`admin_data`@`%` PROCEDURE `registrar_intento_lectura`(
    IN p_id_usuario INT,
    IN p_puntaje_total INT,
    IN p_respuestas_correctas INT,
    IN p_total_preguntas INT,
    IN p_tiempo decimal, 
    IN p_puntajeL INT,
    IN p_puntajeI INT,
    IN p_puntajeC INT
)
BEGIN

    IF p_puntaje_total < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El puntaje no puede ser negativo';
    END IF;

    INSERT INTO intentolectura (ID_usuario, puntaje_total, respuestas_correctas, total_preguntas, tiempo_minutos, puntaje_literal, puntaje_inferencial, puntaje_critico)
    VALUES (p_id_usuario, p_puntaje_total, p_respuestas_correctas, p_total_preguntas, p_tiempo, p_puntajeL, p_puntajeI, p_puntajeC);

    UPDATE usuario
    SET puntos_xp = puntos_xp + p_puntaje_total
    WHERE id = p_id_usuario;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `nombre_usuario` varchar(200) NOT NULL,
  `contrasenia_hash` varchar(200) NOT NULL,
  `correo_electronico` varchar(200) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivoescritura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_usuario` int(11) NOT NULL,
  `url_archivo` varchar(200) DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `nombre_archivo` varchar(200) NOT NULL,
  `puntaje_promedio` decimal(5,2) DEFAULT NULL,
  `ID_dificultad` int(11) DEFAULT NULL,
  `ID_tipoTexto` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID_usuario_idx` (`ID_usuario`),
  KEY `FK_IDNivelDificultad_idx` (`ID_dificultad`),
  KEY `FK_IDTipoTexto_idx` (`ID_tipoTexto`),
  CONSTRAINT `FK_IDNivelDificultad` FOREIGN KEY (`ID_dificultad`) REFERENCES `niveldificultad` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_IDTipoTexto` FOREIGN KEY (`ID_tipoTexto`) REFERENCES `tipotexto` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_IDUsuario` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `detallesejercicioescritura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `puntaje_coherencia` int(11) NOT NULL,
  `puntaje_cohesion` int(11) NOT NULL,
  `puntaje_gramatica` int(11) NOT NULL,
  `puntaje_argumentacion` int(11) DEFAULT NULL,
  `puntaje_estructura` int(11) NOT NULL,
  `retroalimentacion` longtext DEFAULT NULL,
  `ID_generacionIA` int(11) DEFAULT NULL,
  `ID_archivoEscritura` int(11) DEFAULT NULL,
  `fecha_registrada` datetime DEFAULT NULL,
  `ID_TipoTexto` int(11) DEFAULT NULL,
  `detallesejercicioescrituracol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_IDArchivoEscritura_idx` (`ID_archivoEscritura`),
  KEY `FK_IDGeneracionIA` (`ID_generacionIA`),
  KEY `FK_TipoTexto_idx` (`ID_TipoTexto`),
  CONSTRAINT `FK_IDArchivoEscritura` FOREIGN KEY (`ID_archivoEscritura`) REFERENCES `archivoescritura` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_IDGeneracionIA` FOREIGN KEY (`ID_generacionIA`) REFERENCES `generacionia` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_TipoTexto` FOREIGN KEY (`ID_TipoTexto`) REFERENCES `tipotexto` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `generacionia` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `prompt` varchar(500) NOT NULL,
  `modeloIA` varchar(100) NOT NULL,
  `ID_modulo` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_IDModulo_idx` (`ID_modulo`),
  CONSTRAINT `FK_IDModulo` FOREIGN KEY (`ID_modulo`) REFERENCES `modulo` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `historialhabilidadusuario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_usuario` int(11) NOT NULL,
  `puntaje_literal_promedio` int(11) DEFAULT NULL,
  `puntaje_inferencial_promedio` int(11) DEFAULT NULL,
  `puntaje_critico_promedio` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `FK_IDUsuario_idx` (`ID_usuario`),
  CONSTRAINT `FK_historialHabilidad_IDUsuario` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `intentolectura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_usuario` int(11) NOT NULL,
  `puntaje_total` int(11) DEFAULT NULL,
  `respuestas_correctas` int(11) DEFAULT NULL,
  `total_preguntas` int(11) DEFAULT NULL,
  `fecha_intento` timestamp NOT NULL DEFAULT current_timestamp(),
  `tiempo_minutos` decimal(5,2) NOT NULL,
  `puntaje_literal` int(11) DEFAULT NULL,
  `puntaje_inferencial` int(11) DEFAULT NULL,
  `puntaje_critico` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_intentoLectura_IDUsuario_idx` (`ID_usuario`),
  CONSTRAINT `FK_intentoLectura_IDUsuario` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_intento_usuario` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logro` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `recompensa_xp` int(11) NOT NULL,
  `ID_adminResponsable` int(11) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`ID`),
  KEY `FK_logro_IDAdmin_idx` (`ID_adminResponsable`),
  CONSTRAINT `FK_logro_IDAdmin` FOREIGN KEY (`ID_adminResponsable`) REFERENCES `admin` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nivelcomprensionlectora` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `niveldificultad` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nivelprogreso` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `puntos_requeridos` int(11) NOT NULL DEFAULT 0,
  `ID_adminResponsable` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`ID`),
  KEY `FK_nivelProgreso_IDAdmin_idx` (`ID_adminResponsable`),
  CONSTRAINT `FK_nivelProgreso_IDAdmin` FOREIGN KEY (`ID_adminResponsable`) REFERENCES `admin` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `opcionpregunta` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_pregunta` int(11) NOT NULL,
  `texto_opcion` varchar(200) NOT NULL,
  `es_correcta` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `FK_opcionPregunta_IDPregunta_idx` (`ID_pregunta`),
  CONSTRAINT `FK_opcionPregunta_IDPregunta` FOREIGN KEY (`ID_pregunta`) REFERENCES `preguntalectura` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `preguntalectura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_textoLectura` int(11) NOT NULL,
  `texto_pregunta` varchar(500) NOT NULL,
  `ID_nivelComprension` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`),
  KEY `fk_preguntaLectura_IDnivelComprension` (`ID_nivelComprension`),
  KEY `FK_preguntaLectura_IDTexto_idx` (`ID_textoLectura`),
  CONSTRAINT `FK_preguntaLectura_IDTexto` FOREIGN KEY (`ID_textoLectura`) REFERENCES `textolectura` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_preguntaLectura_IDnivelComprension` FOREIGN KEY (`ID_nivelComprension`) REFERENCES `nivelcomprensionlectora` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `respuestalectura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_opcionPregunta` int(11) NOT NULL,
  `ID_intentoLectura` int(11) NOT NULL,
  `ID_texto` int(11) NOT NULL,
  `ID_pregunta` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_respuestasIntento_idx` (`ID_intentoLectura`),
  KEY `FK_PromocionesIntento_idx` (`ID_opcionPregunta`),
  KEY `FK_IDtexto_idx` (`ID_texto`),
  KEY `FK_IDpregunta_RespuestasIntento_idx` (`ID_pregunta`),
  CONSTRAINT `FK_IDOpcionPregunta` FOREIGN KEY (`ID_opcionPregunta`) REFERENCES `opcionpregunta` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_IDTexto` FOREIGN KEY (`ID_texto`) REFERENCES `textolectura` (`ID`),
  CONSTRAINT `FK_IDpregunta_RespuestasIntento` FOREIGN KEY (`ID_pregunta`) REFERENCES `preguntalectura` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_respuestasIntento` FOREIGN KEY (`ID_intentoLectura`) REFERENCES `intentolectura` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `textolectura` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_dificultad` int(11) NOT NULL,
  `ID_tipoTexto` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `contenido` varchar(2000) NOT NULL,
  `fuente` varchar(200) NOT NULL,
  `ID_generacionIA` int(11) DEFAULT NULL,
  `ID_adminResponsable` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`ID`),
  KEY `FK_textoLectura_IDdificultad_idx` (`ID_dificultad`),
  KEY `FK_textoLectura_IDtipoTexto_idx` (`ID_tipoTexto`),
  KEY `FK_textoLectura_IDgeneracionIA_idx` (`ID_generacionIA`),
  KEY `FK_textoLectura_IDadmin_idx` (`ID_adminResponsable`),
  CONSTRAINT `FK_textoLectura_IDadmin` FOREIGN KEY (`ID_adminResponsable`) REFERENCES `admin` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_textoLectura_IDdificultad` FOREIGN KEY (`ID_dificultad`) REFERENCES `niveldificultad` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_textoLectura_IDgeneracionIA` FOREIGN KEY (`ID_generacionIA`) REFERENCES `generacionia` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_textoLectura_IDtipoTexto` FOREIGN KEY (`ID_tipoTexto`) REFERENCES `tipotexto` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipotexto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasenia_hash` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `puntos_xp` int(11) DEFAULT 0,
  `dias_racha` int(11) DEFAULT 0,
  `ID_nivelProgreso` int(11) NOT NULL DEFAULT 1,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `ultima_conexion` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_usuario_IDNivelProgreso_idx` (`ID_nivelProgreso`),
  CONSTRAINT `FK_usuario_IDNivelProgreso` FOREIGN KEY (`ID_nivelProgreso`) REFERENCES `nivelprogreso` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuariologro` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_usuario` int(11) NOT NULL,
  `ID_logro` int(11) NOT NULL,
  `fecha_desbloqueo` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `FK_usuariologro_IDUsuario_idx` (`ID_usuario`),
  KEY `FK_usuariologro_IDLogro_idx` (`ID_logro`),
  CONSTRAINT `FK_usuariologro_IDLogro` FOREIGN KEY (`ID_logro`) REFERENCES `logro` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_usuariologro_IDUsuario` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

