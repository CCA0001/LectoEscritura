/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

LOCK TABLES `niveldificultad` WRITE;
/*!40000 ALTER TABLE `niveldificultad` DISABLE KEYS */;
INSERT INTO `niveldificultad` (`ID`, `nombre`) VALUES (1,'B├ísico'),
(2,'Intermedio'),
(3,'Avanzado');
/*!40000 ALTER TABLE `niveldificultad` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `tipotexto` WRITE;
/*!40000 ALTER TABLE `tipotexto` DISABLE KEYS */;
INSERT INTO `tipotexto` (`ID`, `nombre`, `descripcion`) VALUES (1,'Narrativo','Textos que relatan sucesos reales o ficticios con estructura de historia'),
(2,'Expositivo','Textos que explican o informan sobre un tema de forma objetiva'),
(3,'Argumentativo','Textos que defienden una postura mediante argumentos y evidencias'),
(4,'Descriptivo','Textos que detallan caracter├¡sticas de personas, lugares u objetos');
/*!40000 ALTER TABLE `tipotexto` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `nivelprogreso` WRITE;
/*!40000 ALTER TABLE `nivelprogreso` DISABLE KEYS */;
INSERT INTO `nivelprogreso` (`ID`, `nombre`, `puntos_requeridos`, `ID_adminResponsable`, `descripcion`, `estado`) VALUES (1,'Principiante',0,NULL,'Nivel inicial para nuevos usuarios','Activo'),
(2,'Explorador',500,NULL,'Usuario con actividad regular en la plataforma','Inactivo'),
(3,'Lector H├íbil',1500,1,'Domina textos de dificultad intermedia','Activo'),
(4,'Escritor Principiante',310,7,'Produce textos con buena coherencia y cohesi├│n','Activo'),
(5,'Experto',6000,2,'Desempe├▒o sobresaliente en lectura y escritura','Activo'),
(6,'Experto II ',7000,7,'Desempe├▒o sobresaliente en lectura y escritura','Activo'),
(7,'Experto III',8000,7,'Desempe├▒o sobresaliente en lectura y escritura III','Activo'),
(8,'Experto IV',100000,7,'.','Activo'),
(9,'Experto v',11000,7,'adfsdasd','Activo'),
(10,'Experto VI',120000,7,'ASDCASD','Activo');
/*!40000 ALTER TABLE `nivelprogreso` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `logro` WRITE;
/*!40000 ALTER TABLE `logro` DISABLE KEYS */;
INSERT INTO `logro` (`ID`, `nombre`, `descripcion`, `recompensa_xp`, `ID_adminResponsable`, `estado`) VALUES (1,'Primera Lectura','Completa tu primer texto de lectura',51,7,'Inactivo'),
(2,'Racha de 7 d├¡as','Mant├®n una racha activa durante 7 d├¡as consecutivos',105,7,'Activo'),
(3,'Lector Veloz','Completa un texto en menos de 3 minutos con 80% de acierto',150,NULL,'Activo'),
(4,'Escritor Novato','Sube tu primer ejercicio de escritura',75,NULL,'Activo'),
(5,'Perfeccionista','Obt├®n puntaje perfecto en un intento de lectura',200,NULL,'Activo'),
(6,'Explorador de G├®neros','Lee textos de 4 tipos distintos',120,1,'Activo'),
(7,'Maestro Argumentativo','Obt├®n m├ís de 85 puntos en un texto argumentativo',180,2,'Activo'),
(8,'Racha de 30 d├¡as','Mant├®n una racha de 30 d├¡as consecutivos',500,1,'Activo'),
(9,'Colaborador','Completa todos los m├│dulos disponibles al menos una vez',250,2,'Activo'),
(10,'├ëlite','Alcanza el nivel Experto',1000,1,'Activo'),
(11,'Elite II','Alcanza el nivel Experto II',1200,7,'Inactivo');
/*!40000 ALTER TABLE `logro` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

