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
-- Table structure for table `shippings`
--

DROP TABLE IF EXISTS `shippings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shippings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shippings`
--

LOCK TABLES `shippings` WRITE;
/*!40000 ALTER TABLE `shippings` DISABLE KEYS */;
INSERT INTO `shippings` VALUES (1,'Bangladesh','88','Chittagong','88001',150,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(2,'Bangladesh','88','Mongla','88002',160,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(3,'Bangladesh','88','Payra','88003',170,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(4,'Bangladesh','88','Matarbari','88004',180,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(5,'Qatar','974','Hamad International Airport','97400',50,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(6,'Qatar','974','Ras Laffan','97401',60,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(7,'Qatar','974','Umm Said','97402',70,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(8,'Qatar','974','Doha','97403',65,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(9,'Qatar','974','Mesaieed','97404',55,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(10,'Qatar','974','Al Rayyan Marine Terminal','97405',80,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(11,'Qatar','974','Al Ruwais','97406',85,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(12,'Qatar','974','Port Of Halul Island','97407',90,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(13,'Qatar','974','Rabban Gabbro Storage Area (Mesaieed Port)','97408',95,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(14,'Qatar','974','Qatalum','97409',105,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(15,'China','86','Port of Shanghai','86000',190,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(16,'China','86','Port of Guangzhou','86001',195,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(17,'China','86','Port of Xiamen China','86002',210,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(18,'China','86','Lianyungang Gangguanhe Port Area','86003',215,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(19,'China','86','Port of Ningbo-Zhoushan','86004',220,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(20,'China','86','Port Of Jinzhou','86005',225,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(21,'China','86','Port of Dalian','86006',230,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(22,'China','86','Port of Zhuhai','86007',235,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(23,'China','86','Port of Shenzhen','86008',250,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(24,'China','86','Port of Hong Kong','86009',255,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(25,'China','86','Port of Yingkou','86010',270,'1','2025-01-20 10:20:51','2025-01-20 10:20:51'),(26,'China','86','Port of Zhanjiang','86011',275,'1','2025-01-20 10:20:51','2025-01-20 10:20:51');
/*!40000 ALTER TABLE `shippings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 17:23:19
