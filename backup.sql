-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: gyaproject
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agendamientos`
--

DROP TABLE IF EXISTS `agendamientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agendamientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `presupuesto_id` bigint unsigned NOT NULL,
  `obra_id` bigint unsigned NOT NULL,
  `mes` int NOT NULL,
  `inicio` int NOT NULL,
  `fin` int NOT NULL,
  `estado` int NOT NULL,
  `observacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agendamientos_usuario_id_foreign` (`usuario_id`),
  KEY `agendamientos_presupuesto_id_foreign` (`presupuesto_id`),
  KEY `agendamientos_obra_id_foreign` (`obra_id`),
  CONSTRAINT `agendamientos_obra_id_foreign` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`),
  CONSTRAINT `agendamientos_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuesto_aprobados` (`id`),
  CONSTRAINT `agendamientos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agendamientos`
--

LOCK TABLES `agendamientos` WRITE;
/*!40000 ALTER TABLE `agendamientos` DISABLE KEYS */;
INSERT INTO `agendamientos` VALUES (9,1,'2025-03-04',20,63,2,1,1,4,NULL,'2025-03-04 05:15:52','2025-03-04 05:15:52');
/*!40000 ALTER TABLE `agendamientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,'Administrador',1,NULL,NULL),(2,'Ingenieria',1,NULL,NULL),(3,'Deposito',1,NULL,NULL),(4,'Laboratorio',1,NULL,NULL),(5,'Administracion',1,NULL,NULL),(6,'Gestion de Obras',1,NULL,NULL),(7,'Ingeniera +',1,NULL,NULL);
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insumos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int NOT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insumos_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `insumos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
INSERT INTO `insumos` VALUES (13,'Molde para probeta',1,2,'2025-01-28 13:21:53','2025-01-28 13:21:53'),(14,'Reoplast',1,2,'2025-01-28 13:22:07','2025-01-28 13:22:07'),(15,'Clavo 2x11',1,2,'2025-01-28 13:22:15','2025-01-28 13:22:15'),(16,'Mazo',1,2,'2025-01-28 13:22:22','2025-01-28 13:22:22'),(17,'Alambre de atar',1,2,'2025-01-28 13:22:26','2025-01-28 13:22:26'),(18,'Prolongador',1,2,'2025-01-28 13:22:44','2025-01-28 13:22:44'),(19,'Lente',1,2,'2025-01-28 13:22:46','2025-01-28 13:22:46'),(20,'Guantes de hilo',1,2,'2025-01-28 13:22:52','2025-01-28 13:22:52'),(21,'Guante de cirujano',1,2,'2025-01-28 13:22:58','2025-01-28 13:22:58'),(22,'Tapaboca',1,2,'2025-01-28 13:23:03','2025-01-28 13:23:03'),(23,'Taladro',1,2,'2025-01-28 13:23:05','2025-01-28 13:23:05'),(24,'Mecha de 14',1,2,'2025-01-28 13:23:08','2025-01-28 13:23:08'),(25,'Mecha de 10',1,2,'2025-01-28 13:23:11','2025-01-28 13:23:11'),(26,'Modulo de andamio con rueda',1,2,'2025-01-28 13:24:17','2025-01-28 13:24:17'),(27,'Escalera de 3,5 metros',1,2,'2025-01-28 13:24:31','2025-01-28 13:24:31'),(28,'Extractor con pedestal y transformador',1,2,'2025-01-28 13:24:40','2025-01-28 13:24:40'),(29,'Tanque de agua para extractor',1,2,'2025-01-28 13:24:54','2025-01-28 13:24:54'),(30,'Escalera de 6 peldaños',1,2,'2025-01-28 13:25:07','2025-01-28 13:25:07'),(31,'Broca de 8 cm',1,2,'2025-01-28 13:25:12','2025-01-28 13:25:12'),(32,'Tarugo',1,2,'2025-01-28 13:25:16','2025-01-28 13:25:16'),(33,'Llave inglesa',1,2,'2025-01-28 13:25:45','2025-01-28 13:25:45'),(34,'Llave Nro 14',1,2,'2025-01-28 13:25:55','2025-01-28 13:25:55'),(35,'Llave Nro 15',1,2,'2025-01-28 13:26:02','2025-01-28 13:26:02'),(36,'Destornillador plano fino',1,2,'2025-01-28 13:26:11','2025-01-28 13:26:11'),(37,'Mortero de reparación',1,2,'2025-01-28 13:26:18','2025-01-28 13:26:18'),(38,'Balde de albañil',1,2,'2025-01-28 13:26:24','2025-01-28 13:26:24'),(39,'Balde de 20 litros',1,2,'2025-01-28 13:26:31','2025-01-28 13:26:31'),(40,'Cuchara',1,2,'2025-01-28 13:26:36','2025-01-28 13:26:36'),(41,'Hormifix',1,2,'2025-01-28 13:26:40','2025-01-28 13:26:40'),(42,'Tenaza',1,2,'2025-01-28 13:26:43','2025-01-28 13:26:43'),(43,'Trapo de piso',1,2,'2025-01-28 13:26:48','2025-01-28 13:26:48'),(44,'Escoba',1,2,'2025-01-28 13:26:52','2025-01-28 13:26:52'),(45,'Bolsa para basura',1,2,'2025-01-28 13:26:56','2025-01-28 13:26:56'),(46,'Bolsa para escombro',1,2,'2025-01-28 13:27:03','2025-01-28 13:27:03'),(47,'Bolsa para testigo',1,2,'2025-01-28 13:27:09','2025-01-28 13:27:09'),(48,'Pachometro',1,2,'2025-01-28 13:27:49','2025-01-28 13:27:49'),(49,'Metro',1,2,'2025-01-28 13:27:52','2025-01-28 13:27:52'),(50,'Modulo de andamio',1,2,'2025-01-28 13:28:17','2025-01-28 13:28:17'),(51,'Acople',1,2,'2025-01-28 13:28:23','2025-01-28 13:28:23'),(52,'Listones de madera',1,2,'2025-01-28 13:28:29','2025-01-28 13:28:29'),(53,'Placa terciada de 18 mm',1,2,'2025-01-28 13:28:36','2025-01-28 13:28:36'),(54,'Tirante de 3 metros',1,2,'2025-01-28 13:28:44','2025-01-28 13:28:44'),(55,'Tirante corto',1,2,'2025-01-28 13:28:55','2025-01-28 13:28:55'),(56,'Varilla de 10',1,2,'2025-01-28 13:29:32','2025-01-28 13:29:32'),(57,'Varilla de 8',1,2,'2025-01-28 13:29:38','2025-01-28 13:29:38'),(58,'Puntal de 4 metros',1,2,'2025-01-28 13:29:45','2025-01-28 13:29:45'),(59,'Mecha de 25',1,2,'2025-01-28 13:29:51','2025-01-28 13:29:51'),(60,'Mecha de 16',1,2,'2025-01-28 13:29:57','2025-01-28 13:29:57'),(61,'Varilla de 6',1,2,'2025-01-28 13:31:06','2025-01-28 13:31:06'),(62,'Varilla de 12',1,2,'2025-01-28 13:31:13','2025-01-28 13:31:13'),(63,'Escalera extensible de 7 metros',1,2,'2025-01-28 13:31:35','2025-01-28 13:31:35'),(64,'Tablon',1,2,'2025-01-28 13:32:25','2025-01-28 13:32:25'),(65,'Piola',1,2,'2025-01-28 13:32:27','2025-01-28 13:32:27'),(66,'Arnes',1,2,'2025-01-28 13:32:36','2025-01-28 13:32:36'),(67,'Varilla de 16',1,2,'2025-01-28 13:32:51','2025-01-28 13:32:51'),(68,'Disco de corte para acero',1,2,'2025-01-28 13:32:56','2025-01-28 13:32:56'),(69,'Disco de corte para madera',1,2,'2025-01-28 13:33:02','2025-01-28 13:33:02'),(70,'Modulo de andamio multidireccional',1,2,'2025-01-28 13:33:32','2025-01-28 13:33:32'),(71,'Guante Nro 5',1,2,'2025-01-28 13:33:40','2025-01-28 13:33:40'),(72,'Tablero eléctrico',1,2,'2025-01-28 13:33:56','2025-01-28 13:33:56'),(73,'Alargue con ficha industrial',1,2,'2025-01-28 13:34:06','2025-01-28 13:34:06'),(74,'Extractor con pedestal',1,2,'2025-01-28 13:34:59','2025-01-28 13:34:59'),(75,'Tanque de agua',1,2,'2025-01-28 13:35:04','2025-01-28 13:35:04'),(76,'Broca de 6,8',1,2,'2025-01-28 13:35:13','2025-01-28 13:35:13'),(77,'Disco importado',1,2,'2025-01-28 13:35:18','2025-01-28 13:35:18'),(78,'Amoladora',1,2,'2025-01-28 13:35:21','2025-01-28 13:35:21'),(79,'Mecha de 12',1,2,'2025-01-28 13:35:31','2025-01-28 13:35:31'),(80,'Mazo y punzon',1,2,'2025-01-28 13:35:40','2025-01-28 13:35:40'),(81,'Cuchara albañil',1,2,'2025-01-28 13:35:47','2025-01-28 13:35:47'),(82,'Balde albañil con cuchara',1,2,'2025-01-28 13:35:57','2025-01-28 13:35:57'),(83,'Esclerometro',1,2,'2025-01-28 13:36:09','2025-01-28 13:36:09'),(84,'Surfer',1,2,'2025-01-28 13:36:13','2025-01-28 13:36:13'),(85,'Calibre',1,2,'2025-01-28 13:36:15','2025-01-28 13:36:15'),(86,'Fisurómetro',1,2,'2025-01-28 13:36:23','2025-01-28 13:36:23'),(87,'Fenolftaleina',1,2,'2025-01-28 13:36:30','2025-01-28 13:36:30'),(88,'Delantal de cuero',1,2,'2025-01-28 13:36:36','2025-01-28 13:36:36'),(89,'Protector facial',1,2,'2025-01-28 13:36:44','2025-01-28 13:36:44'),(90,'Pernera de cuero',1,2,'2025-01-28 13:36:55','2025-01-28 13:36:55'),(91,'Arnés con talabarte',1,2,'2025-01-28 13:37:05','2025-01-28 13:37:05'),(92,'Graut',1,2,'2025-01-28 13:37:34','2025-01-28 13:37:34'),(93,'Pincel',1,2,'2025-01-28 13:37:39','2025-01-28 13:37:39'),(94,'Cinta métrica',1,2,'2025-01-28 13:37:54','2025-01-28 13:37:54'),(95,'Escalera',1,2,'2025-01-28 13:38:08','2025-01-28 13:38:08'),(96,'Plomada',1,2,'2025-01-28 13:38:13','2025-01-28 13:38:13'),(97,'Placa terciada de 2mm',1,2,'2025-01-28 13:38:51','2025-01-28 13:38:51'),(98,'Alfajías',1,2,'2025-01-28 13:39:02','2025-01-28 13:39:02'),(99,'Sikadur 31',1,2,'2025-01-28 13:39:08','2025-01-28 13:39:08'),(100,'Tinner',1,2,'2025-01-28 13:39:15','2025-01-28 13:39:15'),(101,'Puntal',1,2,'2025-01-28 13:39:22','2025-01-28 13:39:22'),(102,'Reflector',1,2,'2025-01-28 13:40:28','2025-01-28 13:40:28'),(103,'Casco',1,2,'2025-01-28 13:40:33','2025-01-28 13:40:33'),(104,'Fenólico de 18mm',1,2,'2025-01-28 13:41:04','2025-01-28 13:41:04'),(105,'Alambre trinca',1,2,'2025-01-28 13:41:14','2025-01-28 13:41:14'),(106,'Clavo 1\" 1/2\' sencillo',1,2,'2025-01-28 13:41:33','2025-01-28 13:41:33'),(107,'Hidro lavadora',1,2,'2025-01-28 13:41:49','2025-01-28 13:41:49'),(108,'Placa terciada de 4mm',1,2,'2025-01-28 13:42:00','2025-01-28 13:42:00'),(109,'Listones 1x3',1,2,'2025-01-28 13:42:18','2025-01-28 13:42:18'),(110,'Tirante 3x3',1,2,'2025-01-28 13:42:24','2025-01-28 13:42:24'),(111,'Punta para martillete',1,2,'2025-01-28 13:42:38','2025-01-28 13:42:38'),(112,'Tiza',1,2,'2025-01-28 13:43:47','2025-01-28 13:43:47'),(113,'Marcador (Pincel)',1,2,'2025-01-28 13:43:53','2025-01-28 13:43:53'),(114,'Cinta métrica larga',1,2,'2025-01-28 13:44:23','2025-01-28 13:44:23'),(115,'Cinta métrica 10m',1,2,'2025-01-28 13:44:30','2025-01-28 13:44:30'),(116,'Prolongador con zapatilla',1,2,'2025-01-28 13:44:37','2025-01-28 13:44:37'),(117,'Zapatilla',1,2,'2025-01-28 13:44:44','2025-01-28 13:44:44'),(118,'Prolongador largo',1,2,'2025-01-28 13:45:07','2025-01-28 13:45:07'),(119,'Prolongador de 100m',1,2,'2025-01-28 13:45:19','2025-01-28 13:45:19'),(120,'Linterna',1,2,'2025-01-28 13:45:23','2025-01-28 13:45:23'),(121,'Pala ancha',1,2,'2025-01-28 13:45:37','2025-01-28 13:45:37'),(122,'Mazo grande',1,2,'2025-01-28 13:45:41','2025-01-28 13:45:41'),(123,'Martillete de 11kg',1,2,'2025-01-28 13:45:52','2025-01-28 13:45:52'),(124,'Aislapol',1,2,'2025-01-28 13:46:09','2025-01-28 13:46:09'),(125,'Balde de agua con manguera',1,2,'2025-01-28 13:46:24','2025-01-28 13:46:24'),(126,'Escurridor',1,2,'2025-01-28 13:46:42','2025-01-28 13:46:42'),(127,'Bota',1,2,'2025-01-28 13:47:16','2025-01-28 13:47:16'),(128,'Lente protector',1,2,'2025-01-28 13:47:24','2025-01-28 13:47:24'),(129,'Distanciómetro',1,2,'2025-01-28 13:47:31','2025-01-28 13:47:31'),(130,'Bulón',1,2,'2025-01-28 13:49:53','2025-01-28 13:49:53'),(131,'Lijadora',1,2,'2025-01-28 13:50:02','2025-01-28 13:50:02'),(132,'Aspiradora',1,2,'2025-01-28 13:50:06','2025-01-28 13:50:06'),(133,'Broca de 3cm',1,2,'2025-01-28 13:50:21','2025-01-28 13:50:21'),(134,'Bidón',1,2,'2025-01-28 13:50:25','2025-01-28 13:50:25'),(135,'Extractor sin pedestal',1,2,'2025-01-28 13:50:32','2025-01-28 13:50:32'),(136,'Martillete de 9 kg',1,2,'2025-01-28 13:50:40','2025-01-28 13:50:40'),(137,'Fractag',1,2,'2025-01-28 13:52:37','2025-01-28 13:52:37'),(138,'Puntal metálico de 3 m',1,2,'2025-01-28 13:53:02','2025-01-28 13:53:02'),(139,'Disco trenzado',1,2,'2025-01-28 13:53:20','2025-01-28 13:53:20'),(140,'Sikadur 32',1,2,'2025-01-28 13:53:34','2025-01-28 13:53:34'),(141,'Curex',1,2,'2025-01-28 13:53:37','2025-01-28 13:53:37'),(142,'Escalera corta',1,2,'2025-01-28 13:53:58','2025-01-28 13:53:58'),(143,'Andamio',1,2,'2025-01-28 13:54:08','2025-01-28 13:54:08'),(144,'Tablón metálico',1,2,'2025-01-28 13:54:16','2025-01-28 13:54:16'),(145,'Acople para andamio',1,2,'2025-01-28 13:54:23','2025-01-28 13:54:23'),(146,'Cuerda de vida',1,2,'2025-01-28 13:54:32','2025-01-28 13:54:32'),(147,'Disco diamantado de 9\"',1,2,'2025-01-28 13:55:09','2025-01-28 13:55:09'),(148,'Disco para hormigón de 4,5\"',1,2,'2025-01-28 13:55:34','2025-01-28 13:55:34'),(149,'Disco para hormigón de 7\"',1,2,'2025-01-28 13:55:47','2025-01-28 13:55:47'),(150,'Texdur 32',1,2,'2025-01-28 13:55:56','2025-01-28 13:55:56'),(151,'Estopa',1,2,'2025-01-28 13:55:59','2025-01-28 13:55:59'),(152,'Vibrador grande',1,2,'2025-01-28 13:56:33','2025-01-28 13:56:33'),(153,'Manguera',1,2,'2025-01-28 13:56:47','2025-01-28 13:56:47'),(154,'Disco para madera 7\"',1,2,'2025-01-28 13:58:11','2025-01-28 13:58:11'),(155,'Disco diamantado de 7\"',1,2,'2025-01-28 13:58:24','2025-01-28 13:58:24'),(156,'Disco para metal 7\"',1,2,'2025-01-28 13:58:36','2025-01-28 13:58:36'),(157,'Broca de 15',1,2,'2025-01-28 13:59:05','2025-01-28 13:59:05'),(158,'Chaleco',1,2,'2025-01-28 13:59:17','2025-01-28 13:59:17'),(159,'Varilla de 20',1,2,'2025-01-28 13:59:39','2025-01-28 13:59:39'),(160,'Escalera de 2 metros',1,2,'2025-01-28 14:00:03','2025-01-28 14:00:03'),(161,'Disco para cortar piso',1,2,'2025-01-28 14:00:13','2025-01-28 14:00:13'),(162,'Martillete para retiro de contrapiso',1,2,'2025-01-28 14:01:22','2025-01-28 14:01:22'),(163,'Cinta métrica 20 m',1,2,'2025-01-28 14:01:40','2025-01-28 14:01:40'),(164,'Aspiradora chica',1,2,'2025-01-28 14:01:53','2025-01-28 14:01:53'),(165,'Cuter',1,2,'2025-01-28 14:02:02','2025-01-28 14:02:02'),(166,'Tijera',1,2,'2025-01-28 14:02:08','2025-01-28 14:02:08'),(167,'Cinta de embalaje',1,2,'2025-01-28 14:02:14','2025-01-28 14:02:14'),(168,'Pala de punta',1,2,'2025-01-28 14:02:23','2025-01-28 14:02:23'),(169,'Balde de 3 kg',1,2,'2025-01-28 14:02:44','2025-01-28 14:02:44'),(170,'Georradar',1,2,'2025-01-28 14:03:52','2025-01-28 14:03:52'),(171,'Escalera extensible',1,2,'2025-01-28 14:04:06','2025-01-28 14:04:06'),(172,'Escalera tipo A',1,2,'2025-01-28 14:04:10','2025-01-28 14:04:10'),(173,'Tablero trifásico',1,2,'2025-01-28 14:04:51','2025-01-28 14:04:51'),(174,'Disco diamantado 4,5',1,2,'2025-01-28 14:05:59','2025-01-28 14:05:59'),(175,'Cinta de peligro',1,2,'2025-01-28 14:06:12','2025-01-28 14:06:12'),(176,'Carretilla',1,2,'2025-01-28 14:06:37','2025-01-28 14:06:37'),(177,'Vallado metálico',1,2,'2025-01-28 14:06:45','2025-01-28 14:06:45'),(178,'Caseta metálica',1,2,'2025-01-28 14:06:55','2025-01-28 14:06:55'),(179,'Morte de 30mpa',1,2,'2025-01-28 14:07:03','2025-01-28 14:07:03'),(180,'Mezcladora',1,2,'2025-01-28 14:07:17','2025-01-28 14:07:17'),(181,'Fibra de carbono',1,2,'2025-01-28 14:07:40','2025-01-28 14:07:40'),(182,'Mapa Wrap 31',1,2,'2025-01-28 14:07:47','2025-01-28 14:07:47'),(183,'Balanza',1,2,'2025-01-28 14:07:55','2025-01-28 14:07:55'),(184,'Rodillo',1,2,'2025-01-28 14:08:01','2025-01-28 14:08:01'),(185,'Cono de seguridad',1,2,'2025-01-28 14:08:39','2025-01-28 14:08:39'),(186,'Protector auditivo',1,2,'2025-01-28 14:08:52','2025-01-28 14:08:52'),(187,'Martillete de 16kg',1,2,'2025-01-30 13:16:33','2025-01-30 13:16:33'),(188,'Martillo',1,2,'2025-01-30 13:17:12','2025-01-30 13:17:12'),(191,'Balde con arena',1,2,'2025-01-31 22:48:50','2025-01-31 22:48:50'),(192,'Caño de PVC de 100 mm',1,2,'2025-02-03 20:04:41','2025-02-03 20:04:41'),(193,'Plancha de isopor de 1 cm',1,2,'2025-02-03 20:04:49','2025-02-03 20:04:49'),(194,'Pata de cabra',1,16,'2025-02-10 15:29:35','2025-02-10 15:29:35'),(195,'Pisón',1,16,'2025-02-10 15:30:17','2025-02-10 15:30:17'),(196,'Mecha de 12 larga',1,16,'2025-02-10 15:31:48','2025-02-10 15:31:48'),(197,'Tablitas de madera para cerrar testigos',1,16,'2025-02-10 15:36:34','2025-02-10 15:36:34'),(199,'Disco de corte para acero 3mm',1,10,'2025-02-10 20:36:20','2025-02-10 20:36:20'),(200,'Soplador',1,16,'2025-02-10 22:45:24','2025-02-10 22:45:24'),(201,'Pistola de calor',1,16,'2025-02-10 22:45:32','2025-02-10 22:45:32'),(202,'Martillo de integridad',1,16,'2025-02-10 22:46:35','2025-02-10 22:46:35'),(203,'Equipo de integridad',1,16,'2025-02-10 22:46:46','2025-02-10 22:46:46'),(204,'Sombrilla',1,16,'2025-02-10 22:46:54','2025-02-10 22:46:54'),(205,'Brocha',1,16,'2025-02-10 22:47:57','2025-02-10 22:47:57'),(206,'Adibon',1,20,'2025-02-12 19:59:54','2025-02-12 19:59:54'),(207,'asdfasdf',1,2,'2025-02-17 04:32:27','2025-02-17 04:32:27');
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_01_22_011711_create_areas_table',1),(6,'2025_01_22_011912_create_usuarios_table',1),(7,'2025_01_22_112824_create_insumos_table',1),(8,'2025_01_22_173242_create_obras_table',1),(9,'2025_01_23_113601_create_pedido_para_obras_table',1),(10,'2025_01_23_113612_create_pedido_para_obra_detalles_table',1),(11,'2025_02_07_100655_create_modulos_table',1),(12,'2025_02_07_100706_create_permisos_table',1),(27,'2025_02_14_142102_create_presupuesto_aprobados_table',2),(28,'2025_02_21_103851_add_field_razon_social_in_obras_table',3),(29,'2025_02_21_141327_add_field_clave_in_presupuesto_aprobados_table',4),(32,'2025_03_02_210023_create_agendamientos_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos`
--

DROP TABLE IF EXISTS `modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
INSERT INTO `modulos` VALUES (1,'are_ing','Area de Ingeniería',1,NULL,NULL),(2,'are_dep','Area de Deposito',1,NULL,NULL),(3,'ped_obr_ing','Pedido para obra -Ingenieria',1,NULL,NULL),(4,'ped_obr_dep','Pedido para obra -Deposito',1,NULL,NULL),(5,'man','Mantenimiento',1,NULL,NULL),(6,'ins','Insumos',1,NULL,NULL),(7,'obr','Obras',1,NULL,NULL),(8,'usu','Usuarios',1,NULL,NULL),(9,'per','Permisos',1,NULL,NULL),(10,'pre_apr_ing','Presupuestos Aprobados - Ingenieria',1,NULL,NULL),(11,'are_adm','Area de Adminitracion',1,NULL,NULL),(12,'pre_apr_adm','Presupuestos Aprobados - Administracion',1,NULL,NULL),(13,'val_pre_apr','Validacion de Presupuestos Aprobados',1,NULL,NULL),(14,'age_tra','Agendamiento de trabajos',1,NULL,NULL);
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obras`
--

DROP TABLE IF EXISTS `obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peticionario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` int NOT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `fecha_carga` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ruc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_fac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_fac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_pet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_obr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_obr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_obr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_adm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_adm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_adm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `obras_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `obras_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obras`
--

LOCK TABLES `obras` WRITE;
/*!40000 ALTER TABLE `obras` DISABLE KEYS */;
INSERT INTO `obras` VALUES (63,'Multiplaza',NULL,NULL,NULL,NULL,NULL,1,1,'2025-02-21','2025-02-21 20:46:57','2025-02-21 20:46:57',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `obras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_para_obra_detalles`
--

DROP TABLE IF EXISTS `pedido_para_obra_detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_para_obra_detalles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_para_obra_id` bigint unsigned NOT NULL,
  `insumo_id` bigint unsigned NOT NULL,
  `cantidad` float NOT NULL,
  `medida` int NOT NULL,
  `confirmado` int NOT NULL,
  `usuario_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_para_obra_detalles_pedido_para_obra_id_foreign` (`pedido_para_obra_id`),
  KEY `pedido_para_obra_detalles_insumo_id_foreign` (`insumo_id`),
  KEY `pedido_para_obra_detalles_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `pedido_para_obra_detalles_insumo_id_foreign` FOREIGN KEY (`insumo_id`) REFERENCES `insumos` (`id`),
  CONSTRAINT `pedido_para_obra_detalles_pedido_para_obra_id_foreign` FOREIGN KEY (`pedido_para_obra_id`) REFERENCES `pedido_para_obras` (`id`),
  CONSTRAINT `pedido_para_obra_detalles_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=772 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_para_obra_detalles`
--

LOCK TABLES `pedido_para_obra_detalles` WRITE;
/*!40000 ALTER TABLE `pedido_para_obra_detalles` DISABLE KEYS */;
INSERT INTO `pedido_para_obra_detalles` VALUES (770,56,18,1,13,1,NULL,'2025-03-02 00:49:17','2025-03-02 00:49:17'),(771,56,147,1,5,1,NULL,'2025-03-02 00:49:17','2025-03-02 00:49:17');
/*!40000 ALTER TABLE `pedido_para_obra_detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_para_obras`
--

DROP TABLE IF EXISTS `pedido_para_obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_para_obras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `obra_id` bigint unsigned NOT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `observacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_insumo` int NOT NULL,
  `insumo_confirmado` int NOT NULL,
  `insumo_faltante` int NOT NULL,
  `estado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_para_obras_obra_id_foreign` (`obra_id`),
  KEY `pedido_para_obras_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `pedido_para_obras_obra_id_foreign` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`),
  CONSTRAINT `pedido_para_obras_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_para_obras`
--

LOCK TABLES `pedido_para_obras` WRITE;
/*!40000 ALTER TABLE `pedido_para_obras` DISABLE KEYS */;
INSERT INTO `pedido_para_obras` VALUES (56,63,'2025-03-01','2025-03-02',1,NULL,2,0,2,1,'2025-03-02 00:12:57','2025-03-02 00:49:17');
/*!40000 ALTER TABLE `pedido_para_obras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `area_id` bigint unsigned NOT NULL,
  `modulo_id` bigint unsigned NOT NULL,
  `ver` int NOT NULL,
  `agregar` int NOT NULL,
  `editar` int NOT NULL,
  `eliminar` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permisos_area_id_foreign` (`area_id`),
  KEY `permisos_modulo_id_foreign` (`modulo_id`),
  CONSTRAINT `permisos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `permisos_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,1,9,1,1,1,1,NULL,'2025-02-12 05:36:30'),(2,1,1,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(3,1,2,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(4,1,3,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(5,1,4,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(6,1,5,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(7,1,6,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(8,1,7,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(9,1,8,1,1,1,1,'2025-02-12 05:02:57','2025-02-14 13:35:25'),(10,2,1,1,1,1,2,'2025-02-12 05:09:43','2025-02-17 04:31:48'),(11,2,3,1,1,1,2,'2025-02-12 05:09:43','2025-02-17 04:31:48'),(12,2,2,2,2,2,2,'2025-02-12 05:09:43','2025-02-12 05:09:43'),(13,2,4,2,2,2,2,'2025-02-12 05:09:43','2025-02-12 05:09:43'),(14,2,5,1,1,2,2,'2025-02-12 05:09:43','2025-02-17 04:31:48'),(15,2,6,1,1,2,2,'2025-02-12 05:09:43','2025-02-17 04:31:48'),(16,2,7,2,2,2,2,'2025-02-12 05:09:43','2025-02-12 05:09:43'),(17,2,8,2,2,2,2,'2025-02-12 05:09:43','2025-02-12 05:09:43'),(18,2,9,2,2,2,2,'2025-02-12 05:09:43','2025-02-12 05:09:43'),(19,3,2,1,1,1,1,'2025-02-12 05:16:33','2025-02-17 04:33:17'),(20,3,4,1,1,1,1,'2025-02-12 05:16:33','2025-02-17 04:33:17'),(21,3,1,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(22,3,3,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(23,3,5,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(24,3,6,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(25,3,7,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(26,3,8,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(27,3,9,2,2,2,2,'2025-02-12 05:16:33','2025-02-12 05:16:33'),(28,4,1,1,1,1,1,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(29,4,2,1,1,1,1,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(30,4,3,1,1,1,1,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(31,4,4,1,1,1,1,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(32,4,5,1,1,1,1,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(33,4,6,1,1,1,1,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(34,4,7,2,2,2,2,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(35,4,8,2,2,2,2,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(36,4,9,2,2,2,2,'2025-02-12 05:17:20','2025-02-12 05:17:20'),(37,6,5,2,2,2,2,'2025-02-12 05:18:52','2025-02-17 04:36:06'),(38,6,7,1,1,1,1,'2025-02-12 05:18:52','2025-02-17 04:38:10'),(39,6,1,1,1,1,1,'2025-02-12 05:18:52','2025-02-17 04:36:20'),(40,6,2,2,2,2,2,'2025-02-12 05:18:52','2025-02-12 05:18:52'),(41,6,3,2,2,2,2,'2025-02-12 05:18:52','2025-02-12 05:18:52'),(42,6,4,2,2,2,2,'2025-02-12 05:18:52','2025-02-12 05:18:52'),(43,6,6,2,2,2,2,'2025-02-12 05:18:52','2025-02-12 05:18:52'),(44,6,8,2,2,2,2,'2025-02-12 05:18:52','2025-02-12 05:18:52'),(45,6,9,2,2,2,2,'2025-02-12 05:18:52','2025-02-12 05:18:52'),(46,1,10,1,1,1,1,'2025-02-14 15:52:49','2025-02-14 21:58:22'),(47,1,11,1,1,1,1,'2025-02-14 15:57:51','2025-02-14 15:57:51'),(48,1,12,1,1,1,1,'2025-02-14 15:57:51','2025-02-14 21:56:52'),(49,1,13,1,1,1,1,'2025-02-14 22:43:13','2025-02-14 22:43:13'),(50,2,10,2,2,2,2,'2025-02-17 04:31:48','2025-02-17 04:31:48'),(51,2,11,2,2,2,2,'2025-02-17 04:31:48','2025-02-17 04:31:48'),(52,2,12,2,2,2,2,'2025-02-17 04:31:48','2025-02-17 04:31:48'),(53,2,13,2,2,2,2,'2025-02-17 04:31:48','2025-02-17 04:31:48'),(54,3,10,2,2,2,2,'2025-02-17 04:33:11','2025-02-17 04:33:11'),(55,3,11,2,2,2,2,'2025-02-17 04:33:11','2025-02-17 04:33:11'),(56,3,12,2,2,2,2,'2025-02-17 04:33:11','2025-02-17 04:33:11'),(57,3,13,2,2,2,2,'2025-02-17 04:33:11','2025-02-17 04:33:11'),(58,5,12,1,1,1,1,'2025-02-17 04:34:04','2025-02-17 04:35:34'),(59,5,1,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(60,5,2,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(61,5,3,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(62,5,4,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(63,5,5,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(64,5,6,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(65,5,7,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(66,5,8,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(67,5,9,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(68,5,10,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(69,5,11,1,1,1,1,'2025-02-17 04:34:04','2025-02-17 04:35:34'),(70,5,13,2,2,2,2,'2025-02-17 04:34:04','2025-02-17 04:34:04'),(71,6,10,2,2,2,2,'2025-02-17 04:36:06','2025-02-17 04:36:06'),(72,6,11,2,2,2,2,'2025-02-17 04:36:06','2025-02-17 04:36:06'),(73,6,12,2,2,2,2,'2025-02-17 04:36:06','2025-02-17 04:36:06'),(74,6,13,1,1,1,1,'2025-02-17 04:36:06','2025-02-17 04:38:23'),(75,7,1,1,1,1,1,'2025-02-17 04:40:05','2025-02-17 04:40:42'),(76,7,5,1,1,1,1,'2025-02-17 04:40:05','2025-02-17 04:40:42'),(77,7,2,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(78,7,3,1,1,1,1,'2025-02-17 04:40:05','2025-02-17 04:40:42'),(79,7,4,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(80,7,6,1,1,1,1,'2025-02-17 04:40:05','2025-02-17 04:40:42'),(81,7,7,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(82,7,8,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(83,7,9,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(84,7,10,1,1,1,1,'2025-02-17 04:40:05','2025-02-17 04:40:42'),(85,7,11,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(86,7,12,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(87,7,13,2,2,2,2,'2025-02-17 04:40:05','2025-02-17 04:40:21'),(88,1,14,1,1,1,1,'2025-02-28 20:26:54','2025-02-28 20:26:54');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_aprobados`
--

DROP TABLE IF EXISTS `presupuesto_aprobados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presupuesto_aprobados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha_carga` date NOT NULL,
  `fecha_aprobacion` date DEFAULT NULL,
  `fecha_gestion` date DEFAULT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `validado_por` bigint unsigned DEFAULT NULL,
  `gestionado_por` bigint unsigned DEFAULT NULL,
  `obra_id` bigint unsigned DEFAULT NULL,
  `presupuesto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monto_total` int NOT NULL,
  `estado` int NOT NULL,
  `tipo_trabajo` int NOT NULL,
  `anticipo` int DEFAULT NULL,
  `orden_trabajo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clave` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presupuesto_aprobados_usuario_id_foreign` (`usuario_id`),
  KEY `presupuesto_aprobados_validado_por_foreign` (`validado_por`),
  KEY `presupuesto_aprobados_gestionado_por_foreign` (`gestionado_por`),
  KEY `presupuesto_aprobados_obra_id_foreign` (`obra_id`),
  CONSTRAINT `presupuesto_aprobados_gestionado_por_foreign` FOREIGN KEY (`gestionado_por`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `presupuesto_aprobados_obra_id_foreign` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`),
  CONSTRAINT `presupuesto_aprobados_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `presupuesto_aprobados_validado_por_foreign` FOREIGN KEY (`validado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_aprobados`
--

LOCK TABLES `presupuesto_aprobados` WRITE;
/*!40000 ALTER TABLE `presupuesto_aprobados` DISABLE KEYS */;
INSERT INTO `presupuesto_aprobados` VALUES (19,'2025-02-28','2025-02-28','2025-03-02',1,1,1,63,'public/presupuestos/67c1ea1385c87.pdf','Pre 24-25',NULL,50000000,3,1,1,'1234','2025-02-28 19:53:40','2025-03-04 05:58:05','Multiplaza - Subsuelo'),(20,'2025-03-03','2025-03-03','2025-03-03',1,1,1,63,'public/presupuestos/67c64f6e39f99.pdf','Pre 24-25',NULL,5000000,4,1,1,'5656','2025-03-04 03:55:10','2025-03-04 05:15:52','Multiplaza - Tiendas');
/*!40000 ALTER TABLE `presupuesto_aprobados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` int NOT NULL,
  `area_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuarios_area_id_foreign` (`area_id`),
  CONSTRAINT `usuarios_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Admin','$2y$12$o3afn.KUdffZJdN/DBqsou0EypGK7b8SN.T2uhy6Fubi2A0wN91ge',1,1,NULL,'2025-02-07 17:31:32'),(2,'Ezequiel','$2y$12$zcmNJiNlCUqKuvcIzRCtSuf8X/SSMCQiRdYVWIrLDaW30dUx/hnG6',1,7,NULL,'2025-02-07 17:31:32'),(3,'Julio','$2y$12$5DinGWyIi0k74XKxkDar.Ozlu/mLbDUhyjRBSqncneV6QxuChcvOW',1,6,NULL,'2025-01-28 13:17:20'),(4,'Paola','$2y$12$BtE6QLMSex1QoDO46GA7EekDl2UkL0XSoS87RUzUpGp4ppOyxlwdC',1,3,'2025-02-04 16:21:59','2025-02-04 16:21:59'),(5,'Nelson','$2y$12$i4hBdSdot65wfGjsOG3wTOKylcWtvzsAnScOAa8x9xQcdA5yRJ132',1,3,'2025-02-04 16:22:40','2025-02-04 16:51:51'),(6,'Debora','$2y$12$bD299K5SuWO5nWQvnh58mOe5afEiMaFnn0vwbW0xqZKTen4vo/AzG',1,4,'2025-02-04 16:42:15','2025-02-04 16:42:15'),(7,'Santiago','$2y$12$ovwGcV2xPbbMeINyOb/fUOKyKHztYCiyF3fLJ/xgtSgar7LQwjr3K',1,4,'2025-02-04 16:42:26','2025-02-04 16:42:26'),(8,'Moises','$2y$12$QRrgZw7bcH5dgfPYYlp6z.eFqjk4Ta/GtK7j42ILih4CximbzJoZu',1,2,'2025-02-04 16:42:35','2025-02-04 16:42:35'),(9,'Pamela','$2y$12$leEEO4D6cDIo9cPrPFkCCO/ILl/KsIpXuJRCemFiLThZ8rKQ3.bq6',1,7,'2025-02-04 16:42:46','2025-02-04 16:42:46'),(10,'Fabio','$2y$12$wKNTrg79UILtH40WiySrvOBGbDvv3FUiQJF1W8Q3ePWJeQAbZ0EzO',1,2,'2025-02-04 16:43:04','2025-02-04 16:43:04'),(11,'Rita','$2y$12$4wxKuAb/m.1sKSL6wOgxde.Sc.9X82UwsOCs8OLiU.eP/JQaPAXTa',1,2,'2025-02-04 16:43:13','2025-02-04 16:43:13'),(12,'Marizelle','$2y$12$b8Zz7ClW28cW1V9htbH2oOLw2BLrQvY0uHeu0rMJC8PO8UyGwxBI6',1,2,'2025-02-04 16:43:28','2025-02-10 15:45:31'),(13,'Fanny','$2y$12$7E9rJcbVvcYiFLQMHO1DEu0Le1GggMjTeFTgwAOQP6hrBDCCNMVq2',1,2,'2025-02-04 16:43:46','2025-02-04 16:43:46'),(14,'Junior','$2y$12$2Ev2qa1NArsoeX/ZM0eir.qE0q2U5SkF2z/a96fbt5hUGC/4kiEVa',1,2,'2025-02-04 16:46:09','2025-02-04 16:46:09'),(15,'Hugo','$2y$12$YXZZC5gyZ20B8Gok5AGsiuxemq8KWBZnsd9JwWbMetFxg9Cae6v3C',1,2,'2025-02-04 16:46:25','2025-02-04 16:46:25'),(16,'Jorge','$2y$12$nlXHGXCX.D.nWTrVe6mLH.w0q31nPCtmLwpothJA43lGktIJvJZXi',1,2,'2025-02-04 16:46:44','2025-02-04 16:46:44'),(17,'Sergio','$2y$12$JsufBzdW0b.3Q2VhWPa75.MTFU.OQzfYq6nbOt.aF0SeKhI8nuxou',1,2,'2025-02-04 16:46:57','2025-02-04 16:46:57'),(18,'Nahir','$2y$12$LoqiVoSgV/SFzvFM8xUxVeitnAblUV0r1hxoaXHdR/TzWiIf8YUv.',1,5,'2025-02-04 16:47:14','2025-02-04 16:47:14'),(19,'Veronica','$2y$12$DKoYZBuFcBeXUvKdpbaGl.m/b0fr8hOzec4PFC6bkZqEdfSgDwJ7a',1,5,'2025-02-04 16:47:27','2025-02-04 16:47:27'),(20,'Jorge R','$2y$12$tnZZpvyZRj45jblRqUXw3OlrImc3cbt2.aqvc767WOjzc8InH4UVO',1,2,'2025-02-11 19:35:12','2025-02-11 19:35:12');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'gyaproject'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-03 23:58:56
