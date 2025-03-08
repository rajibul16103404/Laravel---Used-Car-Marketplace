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
-- Table structure for table `fuel_types`
--

DROP TABLE IF EXISTS `fuel_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fuel_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fuel_types`
--

LOCK TABLES `fuel_types` WRITE;
/*!40000 ALTER TABLE `fuel_types` DISABLE KEYS */;
INSERT INTO `fuel_types` VALUES (1,'Unleaded',1,'2025-01-02 11:42:50','2025-02-26 10:51:37'),(2,'E85 / Unleaded',1,'2025-01-02 11:42:51','2025-01-02 11:42:51'),(3,'Diesel',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(4,'Electric',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(5,'Premium Unleaded',1,'2025-01-05 22:16:29','2025-01-05 22:16:29'),(6,'Electric / Premium Unleaded',1,'2025-01-05 22:18:10','2025-01-05 22:18:10'),(7,'E85',1,'2025-01-06 10:34:03','2025-01-06 10:34:03'),(8,'Compressed Natural Gas',1,'2025-01-06 10:34:07','2025-01-06 10:34:07'),(9,'Electric / Unleaded',1,'2025-01-06 10:34:14','2025-01-06 10:34:14'),(10,'E85 / Premium Unleaded',1,'2025-01-20 08:16:03','2025-01-20 08:16:03'),(11,'row.fuel_type',1,'2025-01-30 05:37:05','2025-01-30 05:37:05'),(12,'Petrol',1,'2025-01-30 06:10:49','2025-01-30 06:10:49'),(13,'test',1,'2025-02-07 04:03:28','2025-02-07 04:03:28'),(15,'test00',1,'2025-02-07 09:02:55','2025-02-07 09:02:55'),(17,'Gasoline',1,'2025-02-07 09:32:49','2025-02-07 09:32:49'),(18,'Hybrid',1,'2025-02-23 08:23:26','2025-02-23 08:23:26'),(19,'Hybrid Plugin',1,'2025-02-23 08:42:57','2025-02-23 08:42:57'),(20,'Hybrid Plugin',1,'2025-02-23 08:42:57','2025-02-23 08:42:57'),(21,'Hybrid Plugin',1,'2025-02-23 08:42:57','2025-02-23 08:42:57'),(22,'Hybrid Plugin',1,'2025-02-23 08:42:57','2025-02-23 08:42:57'),(23,'test 100',1,'2025-02-25 07:43:39','2025-02-25 07:43:39'),(24,'FinalTest01',1,'2025-02-25 10:02:53','2025-02-25 10:02:53');
/*!40000 ALTER TABLE `fuel_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 17:24:09
