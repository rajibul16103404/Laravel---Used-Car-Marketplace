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
-- Table structure for table `scraping_logs`
--

DROP TABLE IF EXISTS `scraping_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scraping_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `scrape_date` datetime DEFAULT NULL,
  `base_url` varchar(500) DEFAULT NULL,
  `page` int DEFAULT NULL,
  `records_fetched` int DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scraping_logs`
--

LOCK TABLES `scraping_logs` WRITE;
/*!40000 ALTER TABLE `scraping_logs` DISABLE KEYS */;
INSERT INTO `scraping_logs` VALUES (1,'2025-02-21 00:20:02','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',1,0,'Failed'),(2,'2025-02-21 00:20:58','https://qatarsale.com/en/products/cars_for_sale?page=',1,0,'Failed'),(3,'2025-02-21 00:21:45','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',2,0,'Failed'),(4,'2025-02-21 00:22:36','https://qatarsale.com/en/products/cars_for_sale?page=',2,0,'Failed'),(5,'2025-02-21 00:23:19','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',1,0,'Success'),(6,'2025-02-21 00:24:11','https://qatarsale.com/en/products/cars_for_sale?page=',1,0,'Failed'),(7,'2025-02-21 00:24:42','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',1,0,'Success'),(8,'2025-02-21 00:25:33','https://qatarsale.com/en/products/cars_for_sale?page=',1,0,'Failed'),(9,'2025-02-21 00:27:50','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',1,0,'Success'),(10,'2025-02-21 00:28:44','https://qatarsale.com/en/products/cars_for_sale?page=',1,0,'Success'),(11,'2025-02-21 00:32:39','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',2,0,'Success'),(12,'2025-02-21 00:33:31','https://qatarsale.com/en/products/cars_for_sale?page=',2,0,'Success'),(13,'2025-02-21 00:37:17','https://qlv.qatarliving.com/en/vehicles/cars?curpage=',3,0,'Success'),(14,'2025-02-21 00:38:10','https://qatarsale.com/en/products/cars_for_sale?page=',3,0,'Success');
/*!40000 ALTER TABLE `scraping_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 17:27:38
