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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2019_08_19_000000_create_failed_jobs_table',1),(3,'2019_12_14_000001_create_personal_access_tokens_table',1),(4,'2024_12_12_065014_add_role_to_users_table',1),(5,'2024_12_13_053855_create_password_resets_table',1),(6,'2024_12_16_053207_create_body_type_table',1),(7,'2024_12_17_085051_create_categories_table',1),(8,'2024_12_17_101949_create_colors_table',1),(9,'2024_12_17_102111_create_conditions_table',1),(10,'2024_12_17_102131_create_cylinders_table',1),(11,'2024_12_17_102210_create_doors_table',1),(12,'2024_12_17_102227_create_drive_types_table',1),(13,'2024_12_17_102248_create_fuel_types_table',1),(14,'2024_12_17_102309_create_makes_table',1),(15,'2024_12_17_102419_create_transmissions_table',1),(16,'2024_12_18_064342_create_carmodels_table',1),(17,'2024_12_18_110103_create_carlists_table',1),(18,'2024_12_23_065502_create_validate_phones_table',1),(19,'2024_12_26_051526_create_v_i_n_s_table',1),(20,'2024_12_26_122442_create_exterior_colors_table',2),(21,'2024_12_26_122619_create_interior_colors_table',2),(22,'2024_12_27_052915_create_trims_table',3),(23,'2024_12_27_053145_create_versions_table',3),(24,'2024_12_27_053220_create_body_sub_types_table',3),(25,'2024_12_27_053302_create_vehicle_types_table',3),(26,'2024_12_27_053324_create_drive_trains_table',3),(27,'2024_12_27_053352_create_engines_table',3),(28,'2024_12_27_053413_create_engine_sizes_table',3),(29,'2024_12_27_053428_create_engine_blocks_table',3),(30,'2024_12_27_053444_create_made_ins_table',3),(31,'2024_12_27_053502_create_overall_heights_table',3),(32,'2024_12_27_053527_create_overall_lengths_table',3),(33,'2024_12_27_053545_create_overall_widths_table',3),(34,'2024_12_27_053607_create_highway_mpgs_table',3),(35,'2024_12_27_054539_create_city_mpgs_table',3),(36,'2024_12_27_054557_create_powertrain_types_table',3),(37,'2024_12_27_110045_create_std_seatings_table',4),(38,'2025_01_01_112110_add_view_count_to_products_table',5),(39,'2025_01_02_151346_create_carlists_table',6),(40,'2025_01_02_184856_create_privet_cars_table',7),(41,'2025_01_06_060953_create_inventory_types_table',8),(42,'2025_01_06_061007_create_seller_types_table',8),(43,'2025_01_08_093829_create_carts_table',9),(44,'2025_01_09_021411_create_subscriptions_table',9),(45,'2025_01_09_092725_create_checkouts_table',10),(46,'2025_01_13_185757_create_order_items_table',11),(47,'2025_01_15_064047_create_transactions_table',12),(48,'2025_01_21_055631_create_shippings_table',13),(49,'2025_01_27_194804_create_purchases_table',14),(50,'2025_01_29_113602_add_email_phone_name_otp_to_carlist_table',15),(51,'2025_02_05_055647_create_user_verifieds_table',16),(52,'0001_01_01_000000_create_users_table',17),(53,'0001_01_01_000001_create_cache_table',17);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-27 17:27:49
