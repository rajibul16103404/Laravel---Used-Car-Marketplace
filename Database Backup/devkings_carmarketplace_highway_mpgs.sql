-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 170.10.160.198    Database: devkings_carmarketplace
-- ------------------------------------------------------
-- Server version	8.0.41-cll-lve

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `highway_mpgs`
--

DROP TABLE IF EXISTS `highway_mpgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `highway_mpgs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `highway_mpgs`
--

LOCK TABLES `highway_mpgs` WRITE;
/*!40000 ALTER TABLE `highway_mpgs` DISABLE KEYS */;
INSERT INTO `highway_mpgs` VALUES (1,'25',1,'2025-01-02 11:42:50','2025-01-02 11:42:50'),(2,'24',1,'2025-01-02 11:42:50','2025-01-02 11:42:50'),(3,'26',1,'2025-01-02 11:42:51','2025-01-02 11:42:51'),(4,'33',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(5,'18',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(6,'37',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(7,'63',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(8,'31',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(9,'29',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(10,'32',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(11,'28',1,'2025-01-05 22:16:30','2025-01-05 22:16:30'),(12,'23',1,'2025-01-05 22:17:13','2025-01-05 22:17:13'),(13,'61',1,'2025-01-05 22:17:13','2025-01-05 22:17:13'),(14,'19',1,'2025-01-05 22:17:13','2025-01-05 22:17:13'),(15,'35',1,'2025-01-05 22:17:13','2025-01-05 22:17:13'),(16,'20',1,'2025-01-05 22:17:40','2025-01-05 22:17:40'),(17,'27',1,'2025-01-05 22:17:40','2025-01-05 22:17:40'),(18,'87',1,'2025-01-05 22:17:41','2025-01-05 22:17:41'),(19,'34',1,'2025-01-05 22:18:09','2025-01-05 22:18:09'),(20,'36',1,'2025-01-05 22:18:10','2025-01-05 22:18:10'),(21,'88',1,'2025-01-05 22:18:10','2025-01-05 22:18:10'),(22,'54',1,'2025-01-05 22:18:10','2025-01-05 22:18:10'),(23,'21',1,'2025-01-05 22:18:40','2025-01-05 22:18:40'),(24,'39',1,'2025-01-05 22:18:40','2025-01-05 22:18:40'),(25,'30',1,'2025-01-06 10:34:03','2025-01-06 10:34:03'),(26,'22',1,'2025-01-06 10:34:05','2025-01-06 10:34:05'),(27,'38',1,'2025-01-06 10:34:05','2025-01-06 10:34:05'),(28,'41',1,'2025-01-06 10:34:06','2025-01-06 10:34:06'),(29,'44',1,'2025-01-06 10:34:07','2025-01-06 10:34:07'),(30,'40',1,'2025-01-06 10:34:10','2025-01-06 10:34:10'),(31,'48',1,'2025-01-06 10:34:14','2025-01-06 10:34:14'),(32,'47',1,'2025-01-06 10:34:14','2025-01-06 10:34:14'),(33,'43',1,'2025-01-06 10:34:16','2025-01-06 10:34:16'),(34,'15',1,'2025-01-20 08:16:00','2025-01-20 08:16:00'),(35,'50',1,'2025-01-20 08:16:03','2025-01-20 08:16:03'),(36,'99',1,'2025-01-20 08:16:03','2025-01-20 08:16:03'),(37,'46',1,'2025-01-20 08:16:03','2025-01-20 08:16:03'),(38,'49',1,'2025-01-20 08:16:06','2025-01-20 08:16:06'),(39,'42',1,'2025-01-20 08:16:06','2025-01-20 08:16:06'),(40,'14',1,'2025-01-20 08:16:08','2025-01-20 08:16:08'),(41,'117',1,'2025-01-20 08:16:09','2025-01-20 08:16:09'),(42,'106',1,'2025-01-20 08:16:13','2025-01-20 08:16:13'),(43,'17',1,'2025-01-20 08:16:13','2025-01-20 08:16:13'),(44,'79',1,'2025-01-20 08:16:14','2025-01-20 08:16:14'),(45,'126',1,'2025-01-20 08:16:15','2025-01-20 08:16:15'),(46,'89',1,'2025-01-20 08:16:16','2025-01-20 08:16:16'),(47,'90',1,'2025-01-20 08:16:16','2025-01-20 08:16:16'),(48,'95',1,'2025-01-20 08:16:18','2025-01-20 08:16:18'),(49,'84',1,'2025-02-03 07:46:34','2025-02-03 07:46:34'),(50,'91',1,'2025-02-03 07:46:38','2025-02-03 07:46:38'),(51,'100',1,'2025-02-03 07:46:46','2025-02-03 07:46:46'),(52,'111',1,'2025-02-03 07:46:48','2025-02-03 07:46:48'),(53,'60',1,'2025-02-03 07:46:51','2025-02-03 07:46:51'),(54,'92',1,'2025-02-03 07:59:45','2025-02-03 07:59:45'),(55,'107',1,'2025-02-03 07:59:50','2025-02-03 07:59:50'),(56,'93',1,'2025-02-03 07:59:53','2025-02-03 07:59:53'),(57,'94',1,'2025-02-03 07:59:58','2025-02-03 07:59:58'),(58,'120',1,'2025-02-03 07:59:59','2025-02-03 07:59:59'),(59,'test',1,'2025-02-07 04:04:28','2025-02-07 04:04:28'),(61,'test00',1,'2025-02-07 09:03:43','2025-02-07 09:03:43'),(63,'test 100',1,'2025-02-25 07:44:26','2025-02-25 07:44:26'),(64,'FinalTest01',1,'2025-02-25 10:04:47','2025-02-25 10:04:47');
/*!40000 ALTER TABLE `highway_mpgs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 17:26:54
