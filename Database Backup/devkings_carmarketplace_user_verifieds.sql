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
-- Table structure for table `user_verifieds`
--

DROP TABLE IF EXISTS `user_verifieds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_verifieds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `verification_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_doc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_doc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_verifieds`
--

LOCK TABLES `user_verifieds` WRITE;
/*!40000 ALTER TABLE `user_verifieds` DISABLE KEYS */;
INSERT INTO `user_verifieds` VALUES (24,'T57AO49BGV','69','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740475207_photo_id.jpg','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740475207_address_doc.jpg','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740475207_business_doc.jpg','paid','accepted','2025-02-25 09:20:07','2025-02-26 10:20:31'),(25,'TBAD9JN38W','85','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740477314_photo_id.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740477315_address_doc.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740477315_business_doc.png','null','null','2025-02-25 09:55:15','2025-02-25 09:55:15'),(26,'Z083XRIPYQ','69','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740478567_photo_id.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740478567_address_doc.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740478567_business_doc.png','null','null','2025-02-25 10:16:07','2025-02-25 10:16:07'),(27,'1FZWM8G5HJ','85','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740481208_photo_id.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740481209_address_doc.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740481209_business_doc.png','null','null','2025-02-25 11:00:09','2025-02-25 11:00:09'),(28,'HMC4FSNEAG','69','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740481345_photo_id.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740481345_address_doc.png','https://apicarmarketplace.dkingsolution.org/storage/uploads/1740481345_business_doc.png','null','null','2025-02-25 11:02:25','2025-02-25 11:02:25');
/*!40000 ALTER TABLE `user_verifieds` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 17:29:36
