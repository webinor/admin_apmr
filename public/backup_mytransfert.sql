-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: localhost    Database: my_transfert_db
-- ------------------------------------------------------
-- Server version	8.0.35-0ubuntu0.20.04.1

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
-- Table structure for table `action_menu_admins`
--

DROP TABLE IF EXISTS `action_menu_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `action_menu_admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action_id` int unsigned NOT NULL,
  `admin_id` int unsigned NOT NULL,
  `menu_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action_menu_admins`
--

LOCK TABLES `action_menu_admins` WRITE;
/*!40000 ALTER TABLE `action_menu_admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `action_menu_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` mediumint unsigned NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ownerable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ownerable_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cover picture',
  `price` bigint unsigned DEFAULT NULL,
  `payment_method` enum('Month','Night') COLLATE utf8mb4_unicode_ci NOT NULL,
  `adable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adable_id` bigint unsigned NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ads_ownerable_type_ownerable_id_index` (`ownerable_type`,`ownerable_id`),
  KEY `ads_adable_type_adable_id_index` (`adable_type`,`adable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appartments`
--

DROP TABLE IF EXISTS `appartments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appartments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appartments`
--

LOCK TABLES `appartments` WRITE;
/*!40000 ALTER TABLE `appartments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appartments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (1,'4jfQqbxemYlq95HaQwF8','BICEC','2024-04-20 14:18:01','2024-04-22 09:47:43'),(2,'flbHk41iYpgHi0LtCdKP','ECOBANK','2024-04-21 21:16:01','2024-04-21 21:16:01'),(3,'hoKWKf0bfaUoHoQXzMOrz2Ss3RDnzu','SOCIETE GENERALE','2024-04-22 09:37:48','2024-04-22 09:37:48'),(4,'kHqL9kVwp3RmapwJtVl11jn4HALgTn','UBA','2024-04-22 11:13:03','2024-04-22 11:13:03'),(5,'agCh5GCSJrRum4bYenXBgs5fDoST8q','ACCESS BANK','2024-04-22 11:13:17','2024-04-22 11:13:17'),(6,'tCYgcThLkVfzwRm8h1UTVukLxu7sMR','BANQUE ATLANTIQUE CAMEROUN','2024-04-22 11:13:50','2024-04-22 11:13:50'),(7,'LmwthdLi2HQrYz1pHpSPLgdz2mGqxk','BANGE BANK CAMEROUN','2024-04-22 11:14:19','2024-04-22 11:14:19'),(8,'C5Zw39W6eeh4pgXX5OgA4OzyRkqOPk','AFRILAND FIRST BANK','2024-04-22 11:14:56','2024-04-22 11:14:56'),(9,'pCzPlt776MBllyUX0svy99mVrGnj4S','SCB CAMEROUN','2024-04-22 11:15:20','2024-04-22 11:15:20'),(10,'vhJ8fDX9Hyy7EBw4NNZLetYQPiwiwF','COMMERCIAL BANK','2024-04-22 11:15:34','2024-04-22 11:15:34'),(11,'pBuaIQsONvxTUNDmkPDULBsxalVyHQ','BGFI BANK','2024-04-22 11:15:52','2024-04-22 11:15:52');
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bed_rooms`
--

DROP TABLE IF EXISTS `bed_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bed_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bed_rooms`
--

LOCK TABLES `bed_rooms` WRITE;
/*!40000 ALTER TABLE `bed_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `bed_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` mediumint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commodities`
--

DROP TABLE IF EXISTS `commodities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commodities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci,
  `admin_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commodities`
--

LOCK TABLES `commodities` WRITE;
/*!40000 ALTER TABLE `commodities` DISABLE KEYS */;
/*!40000 ALTER TABLE `commodities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commodity_ads`
--

DROP TABLE IF EXISTS `commodity_ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commodity_ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` int unsigned NOT NULL,
  `commodity_id` int unsigned NOT NULL,
  `number` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commodity_ads_ad_id_index` (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commodity_ads`
--

LOCK TABLES `commodity_ads` WRITE;
/*!40000 ALTER TABLE `commodity_ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `commodity_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coworking_spaces`
--

DROP TABLE IF EXISTS `coworking_spaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coworking_spaces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coworking_spaces`
--

LOCK TABLES `coworking_spaces` WRITE;
/*!40000 ALTER TABLE `coworking_spaces` DISABLE KEYS */;
/*!40000 ALTER TABLE `coworking_spaces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_rate` decimal(8,3) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'SnyykTKcNA0SveYKAv5k','Euro','EUR',655.957,'2024-04-20 14:18:01','2024-04-22 11:02:23'),(2,'hrS6lPI7bKFiEprrDm7q38HaXeiE3B','Dollard AMERICAIN',NULL,550.000,'2024-04-22 10:43:44','2024-04-22 11:12:10'),(3,'UjRLP8PNhLIIXpWy1jxKaMDCKTrzM6','YEN',NULL,180.000,'2024-04-22 11:11:34','2024-04-22 11:11:51');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `descriptions`
--

DROP TABLE IF EXISTS `descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `descriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `descriptionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptionable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `descriptions_descriptionable_type_descriptionable_id_index` (`descriptionable_type`,`descriptionable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descriptions`
--

LOCK TABLES `descriptions` WRITE;
/*!40000 ALTER TABLE `descriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `descriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auxiliary_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` smallint unsigned NOT NULL,
  `admin_id` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_personal_email_unique` (`personal_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
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
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` mediumint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fileable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileable_id` bigint unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_fileable_type_fileable_id_index` (`fileable_type`,`fileable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (1,'App\\Models\\Transfert',1,'cx7YnQ9TOx','OmcnOl2eQy.png','OmcnOl2eQy.png','268717','image/png','2024-04-20 14:18:24','2024-04-20 14:18:24'),(2,'App\\Models\\Transfert',1,'Xct0krpKNZ','TXwOXFUpSz.png','TXwOXFUpSz.png','268717','image/png','2024-04-20 14:18:24','2024-04-20 14:18:24'),(3,'App\\Models\\Transfert',1,'Mrs7Q7ecqR','MQPEcpFCKW.png','MQPEcpFCKW.png','169829','image/png','2024-04-20 14:18:24','2024-04-20 14:18:24'),(4,'App\\Models\\Transfert',3,'aVxC1Yinrq','cjf8wl04X3.png','cjf8wl04X3.png','268717','image/png','2024-04-20 14:33:13','2024-04-20 14:33:13'),(5,'App\\Models\\Transfert',4,'ptSsimvtqa','wVxUXh7rxV.png','wVxUXh7rxV.png','268358','image/png','2024-04-20 14:33:39','2024-04-20 14:33:39'),(6,'App\\Models\\Transfert',4,'wSAU8SOry8','qQF9jTcic4.pdf','qQF9jTcic4.pdf','615533','application/pdf','2024-04-20 14:33:39','2024-04-20 14:33:39'),(7,'App\\Models\\Transfert',5,'l2Af4Ul3n4','QTGvAmcIG1.pdf','QTGvAmcIG1.pdf','429515','application/pdf','2024-04-20 14:36:21','2024-04-20 14:36:21'),(8,'App\\Models\\Transfert',5,'xS78ngArHw','FXo2GCyn9a.png','FXo2GCyn9a.png','185773','image/png','2024-04-20 14:36:21','2024-04-20 14:36:21'),(9,'App\\Models\\Transfert',6,'7tkbl9PWZn','YEITPwO0UL.png','YEITPwO0UL.png','268717','image/png','2024-04-20 14:37:38','2024-04-20 14:37:38'),(10,'App\\Models\\Transfert',6,'aXaf71ly2q','TpZpfsCAP3.pdf','TpZpfsCAP3.pdf','429515','application/pdf','2024-04-20 14:37:38','2024-04-20 14:37:38'),(11,'App\\Models\\Transfert',6,'UVPirTLxII','RTlRcsdaQd.pdf','RTlRcsdaQd.pdf','484973','application/pdf','2024-04-20 14:46:56','2024-04-20 14:46:56'),(12,'App\\Models\\Transfert',6,'IavfgznIeY','oJOh5LQ6aA.pdf','oJOh5LQ6aA.pdf','146405','application/pdf','2024-04-20 14:48:23','2024-04-20 14:48:23'),(13,'App\\Models\\Transfert',6,'RUg0vFGhzA','d6Q8TsrOGM.pdf','d6Q8TsrOGM.pdf','615533','application/pdf','2024-04-20 14:51:24','2024-04-20 14:51:24'),(14,'App\\Models\\Transfert',6,'ndsbZR97i7','QqBp07dl1S.pdf','QqBp07dl1S.pdf','146405','application/pdf','2024-04-20 14:53:53','2024-04-20 14:53:53'),(15,'App\\Models\\Transfert',4,'C1jyn3URan','FAXGpGt0PV.pdf','FAXGpGt0PV.pdf','491369','application/pdf','2024-04-21 18:33:50','2024-04-21 18:33:50'),(16,'App\\Models\\Transfert',4,'BCdhLmPlEk','JBtMVmskms.pdf','JBtMVmskms.pdf','62917','application/pdf','2024-04-21 18:40:44','2024-04-21 18:40:44'),(17,'App\\Models\\Transfert',4,'OqkP8XSmjs','Y69N7AtjvY.pdf','Y69N7AtjvY.pdf','429515','application/pdf','2024-04-21 18:41:38','2024-04-21 18:41:38'),(18,'App\\Models\\Transfert',4,'wgGXTdD0OJ','0NqVCRlaH0.pdf','0NqVCRlaH0.pdf','668058','application/pdf','2024-04-21 18:57:34','2024-04-21 18:57:34'),(19,'App\\Models\\Transfert',4,'YZ6MdrqNHR','ANiZl3YVs8.pdf','ANiZl3YVs8.pdf','429515','application/pdf','2024-04-21 19:00:18','2024-04-21 19:00:18'),(20,'App\\Models\\Transfert',4,'AlI8oBwkyc','PkmOnUgoRU.pdf','PkmOnUgoRU.pdf','429515','application/pdf','2024-04-21 19:00:49','2024-04-21 19:00:49'),(21,'App\\Models\\Transfert',4,'ot7SqpF0XJ','OmPOvAI5uD.pdf','OmPOvAI5uD.pdf','62917','application/pdf','2024-04-21 19:01:19','2024-04-21 19:01:19'),(22,'App\\Models\\Transfert',4,'s2xNEGASLn','WBM6zWcX3u.pdf','WBM6zWcX3u.pdf','491369','application/pdf','2024-04-21 19:01:19','2024-04-21 19:01:19'),(23,'App\\Models\\Transfert',4,'Nmn3dfg4bK','sequence_1_2024_04_11_11_41_17.pdf.pdf','sequence_1_2024_04_11_11_41_17.pdf.pdf','62917','application/pdf','2024-04-21 19:20:29','2024-04-21 19:20:29'),(24,'App\\Models\\Transfert',4,'TAUtEGMWDh','presentation_de_la_plateforme (1)','presentation_de_la_plateforme (1)','146405','application/pdf','2024-04-21 19:22:45','2024-04-21 19:22:45');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `heroes`
--

DROP TABLE IF EXISTS `heroes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `heroes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `services` json NOT NULL,
  `credentials` json DEFAULT NULL,
  `salary` int NOT NULL,
  `is_salary_negociable` tinyint(1) NOT NULL,
  `accpet_terms` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `heroes`
--

LOCK TABLES `heroes` WRITE;
/*!40000 ALTER TABLE `heroes` DISABLE KEYS */;
/*!40000 ALTER TABLE `heroes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `houses`
--

DROP TABLE IF EXISTS `houses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `houses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `houses`
--

LOCK TABLES `houses` WRITE;
/*!40000 ALTER TABLE `houses` DISABLE KEYS */;
/*!40000 ALTER TABLE `houses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lands`
--

DROP TABLE IF EXISTS `lands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lands`
--

LOCK TABLES `lands` WRITE;
/*!40000 ALTER TABLE `lands` DISABLE KEYS */;
/*!40000 ALTER TABLE `lands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_id` int unsigned NOT NULL,
  `city_id` int unsigned NOT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_ad_id_index` (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2022_12_31_121334_create_heroes_table',1),(6,'2022_12_31_214532_create_user_codes_table',1),(7,'2022_12_31_223821_create_jobs_table',1),(8,'2023_01_13_124401_create_admins_table',1),(9,'2023_04_24_160637_create_ads_table',1),(10,'2023_04_24_160750_create_commodities_table',1),(11,'2023_04_24_161155_create_ad_users_table',1),(12,'2023_04_24_161456_create_commodity_ads_table',1),(13,'2023_04_25_135303_create_searches_table',1),(14,'2023_04_30_092944_create_cities_table',1),(15,'2023_04_30_093850_create_actions_table',1),(16,'2023_04_30_093905_create_menus_table',1),(17,'2023_04_30_094213_create_action_menu_admins_table',1),(18,'2023_04_30_104450_create_employees_table',1),(19,'2023_04_30_104524_create_roles_table',1),(20,'2023_05_16_170617_create_files_table',1),(21,'2023_05_18_093631_create_descriptions_table',1),(22,'2023_05_18_093647_create_locations_table',1),(23,'2023_05_28_051103_create_tags_table',1),(24,'2023_09_16_130813_add_index_to_locations',1),(25,'2023_09_16_131531_add_index_to_commodity_ads',1),(26,'2023_09_23_185455_create_appartments_table',1),(27,'2023_09_23_185515_create_studios_table',1),(28,'2023_09_23_185526_create_rooms_table',1),(29,'2023_09_23_190606_create_categories_table',1),(30,'2023_09_24_004440_create_bed_rooms_table',1),(31,'2023_09_24_015601_create_room_ads_table',1),(32,'2023_09_24_211943_create_shops_table',1),(33,'2023_09_24_211959_create_warehouses_table',1),(34,'2023_09_24_212011_create_lands_table',1),(35,'2023_09_24_212022_create_stores_table',1),(36,'2023_09_24_212032_create_houses_table',1),(37,'2023_09_24_212356_create_offices_table',1),(38,'2023_10_05_091940_increase_price_range_on_ads',1),(39,'2023_11_12_103319_add_whatsapp_phone_number_to_users',1),(40,'2024_01_19_192241_create_coworking_spaces_table',1),(41,'2024_01_19_192302_create_wharehouses_table',1),(42,'2024_04_12_234700_create_transferts_table',1),(43,'2024_04_16_142716_create_banks_table',1),(44,'2024_04_17_090905_create_currencies_table',1),(45,'2024_04_19_160819_create_documents_table',1),(46,'2024_04_20_132842_create_suppliers_table',1),(49,'2024_04_22_114846_modify_change_rate',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'MyAuthApp','926212c97973359ccb627ac582f8160aa1a86c56546497720d59642dfe1aafc9','[\"*\"]','2024-04-20 14:53:53','2024-04-20 14:18:09','2024-04-20 14:53:53'),(2,'App\\Models\\User',1,'MyAuthApp','796e2e9a4bd81dd6a046ecfab059561493c8107c2f033f5cc2ef13bf3da8dc8d','[\"*\"]','2024-04-21 21:16:29','2024-04-21 18:24:33','2024-04-21 21:16:29'),(3,'App\\Models\\User',1,'MyAuthApp','1f5ec873cf87a288fcf756e2bcc16110c6041f3eee0f26a78bb893cc51f1c6f7','[\"*\"]',NULL,'2024-04-22 08:12:28','2024-04-22 08:12:28'),(4,'App\\Models\\User',1,'MyAuthApp','3217f40e63fa2d596f9288b3847778fe17df8426b21698346d18a8df031b5f3a','[\"*\"]','2024-04-22 11:15:52','2024-04-22 08:13:31','2024-04-22 11:15:52'),(5,'App\\Models\\User',1,'MyAuthApp','4026298dffd8c7473ee2387e7813eba7d80a5e681093c65058a6104a010c852e','[\"*\"]',NULL,'2024-04-22 13:01:41','2024-04-22 13:01:41'),(6,'App\\Models\\User',1,'MyAuthApp','61bba7e7a1c4ed22838ff03b42424a1334419c5d9b1a04a5af88ac516cf2b58f','[\"*\"]',NULL,'2024-04-22 13:04:14','2024-04-22 13:04:14'),(7,'App\\Models\\User',1,'MyAuthApp','4f6f4a60f31b503095a4f94ccb4fcbd1a904466b6f3ce2655f548e7c4b746b90','[\"*\"]',NULL,'2024-04-22 13:08:25','2024-04-22 13:08:25'),(8,'App\\Models\\User',1,'MyAuthApp','3da7911d80e1f6e3f302d5ea032e832ce3115985077e7e71385f15ff54240111','[\"*\"]','2024-04-22 14:09:04','2024-04-22 14:08:11','2024-04-22 14:09:04'),(9,'App\\Models\\User',1,'MyAuthApp','e033445600938c168aaac03ca21e8644b4a75ca221587dd1e7a5821e81cca054','[\"*\"]',NULL,'2024-04-24 11:47:44','2024-04-24 11:47:44'),(10,'App\\Models\\User',1,'MyAuthApp','a910b8caf8a962cc6a3aefbcf4af0829e5a0af9b14742fdf3e50ca338ab8f2d6','[\"*\"]',NULL,'2024-04-24 11:55:41','2024-04-24 11:55:41'),(11,'App\\Models\\User',1,'MyAuthApp','27a19314b4075c9be15c5dfef7b248c83cc4c8c7c6b4ec19a10ad6a803be7c44','[\"*\"]',NULL,'2024-04-24 12:34:17','2024-04-24 12:34:17'),(12,'App\\Models\\User',1,'MyAuthApp','919c917a934f4b1a7c84575f7d6bc80ea2bcf019ad6cbb0716b2211807ae0f86','[\"*\"]','2024-04-24 20:32:09','2024-04-24 20:07:20','2024-04-24 20:32:09');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_role_name_unique` (`role_name`),
  UNIQUE KEY `roles_role_slug_unique` (`role_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_ads`
--

DROP TABLE IF EXISTS `room_ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` int unsigned NOT NULL,
  `room_id` int unsigned NOT NULL,
  `number` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_ads`
--

LOCK TABLES `room_ads` WRITE;
/*!40000 ALTER TABLE `room_ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `room_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `admin_id` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `searches`
--

DROP TABLE IF EXISTS `searches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `searches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `searches`
--

LOCK TABLES `searches` WRITE;
/*!40000 ALTER TABLE `searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `searches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shops` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `studios`
--

DROP TABLE IF EXISTS `studios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `studios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studios`
--

LOCK TABLES `studios` WRITE;
/*!40000 ALTER TABLE `studios` DISABLE KEYS */;
/*!40000 ALTER TABLE `studios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` mediumint unsigned NOT NULL,
  `social_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_area_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` enum('0','1','2') COLLATE utf8mb4_unicode_ci DEFAULT '1',
  `city_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unique_identification_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auxiliary_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `begining_collaboration` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'zYfuiDi3hMOToKpI4mXh',1,'Bruen LLC',NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-20 14:18:01','2024-04-20 14:18:01'),(2,'K0bMlZWJlV5GxX2nM0pP',1,'Cummings, Quigley and Haley',NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-20 14:18:01','2024-04-20 14:18:01'),(3,'gMbLu21cV8B9HOonqO13',1,'Beer-Osinski',NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-20 14:18:01','2024-04-20 14:18:01'),(4,'ALYI3i32XY6JNoHzqnbD',1,'Pollich PLC',NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-20 14:18:01','2024-04-20 14:18:01'),(5,'sJWkHE7crDFwZ9l20PH5',1,'Ward, Ratke and Herman',NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-20 14:18:01','2024-04-20 14:18:01'),(6,'zG2INfRpLFfKhZ87e1EIpVrplmPAOk',1,'Eos non minima volu',NULL,NULL,'1',NULL,'Nulla rem nisi moles',NULL,'749','552',NULL,'diqi@mailinator.com',NULL,'2024-04-22 09:59:33','2024-04-22 10:18:21'),(7,'9375hJV3DJDai5wXtdoLuAbFgYtkYZ',1,'Est commodo digniss',NULL,NULL,'1',NULL,'Magna nihil ut liber',NULL,'40','657',NULL,'towame@mailinator.com',NULL,'2024-04-22 10:19:08','2024-04-22 10:19:08'),(8,'gdKgh0GA6QOz9JSVzxAb98jhEb4Ehv',1,'Dicta mollit velit',NULL,NULL,'1',NULL,'Doloremque consequat',NULL,'572','20',NULL,'rifuq@mailinator.com',NULL,'2024-04-24 20:32:09','2024-04-24 20:32:09');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `taggable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taggable_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_taggable_type_taggable_id_index` (`taggable_type`,`taggable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transferts`
--

DROP TABLE IF EXISTS `transferts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transferts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `deposit` date DEFAULT NULL,
  `execution` date DEFAULT NULL,
  `amount` double(32,2) unsigned NOT NULL,
  `currency_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transferts`
--

LOCK TABLES `transferts` WRITE;
/*!40000 ALTER TABLE `transferts` DISABLE KEYS */;
INSERT INTO `transferts` VALUES (1,NULL,'uaVrnTv61za9bM715LPOqnGfxVg3JzSk3UJsUchMfXTDLx1843','3','1','2','2024-04-16','2024-04-23',16000000064.00,1,'2024-04-20 14:18:15','2024-04-22 11:06:27',NULL),(2,NULL,'7eYoBgEjEqUwQK0mbTwsmfdfl0AS4YmVBLCEv82LgWHvsRQfen','1','2','0',NULL,NULL,227999999600.00,1,'2024-04-20 14:30:54','2024-04-22 09:10:58',NULL),(3,NULL,'XEhJs5hbIlBhKrlhjJ3vHWAWM2xHOjH74BqWiDi7SSA9clcv3L','9','5','2','2024-04-11','2024-05-02',1919999824.00,1,'2024-04-20 14:33:08','2024-04-22 14:09:04',NULL),(4,NULL,'DHzsrY5Xr2T7oo7pXXPUkXroSs42BNUl6dNhpur3T9R4HG4WdH','1','1','0',NULL,NULL,3200000144.00,1,'2024-04-20 14:33:26','2024-04-22 09:11:16',NULL),(5,NULL,'SYtP5c0AdUvY8rKeEAEQccJKI56xQD20uvdfjk1IiTyk1hWiwR','1','4','0',NULL,NULL,4640000000192.00,1,'2024-04-20 14:36:09','2024-04-22 09:11:33',NULL),(6,NULL,'uK6Oi8jEWzZfm7J5Wcku1nXBx0sxP7ZvSjBMMsy1E3XSrGX4Pu','1','4','2','2024-04-15','2024-04-26',350749424.00,1,'2024-04-20 14:37:26','2024-04-22 09:11:49',NULL);
/*!40000 ALTER TABLE `transferts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_codes`
--

DROP TABLE IF EXISTS `user_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_codes`
--

LOCK TABLES `user_codes` WRITE;
/*!40000 ALTER TABLE `user_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number_verified_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `information_complete_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defined_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'k4FbSXJOULF0VnkrYz5R','Aubin','EKITIKE','a.ekitike@montresorier.com','650910717',NULL,NULL,'$2y$10$VVz9ivdVV8zOrGr8k7O4GuWlD8JozMoNLi8Kk2lilEqucNxApWFMm',NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-20 14:18:01',NULL,NULL,NULL,NULL,'OfHP0lYEQk','2024-04-20 14:18:01','2024-04-20 14:18:01');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wharehouses`
--

DROP TABLE IF EXISTS `wharehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wharehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wharehouses`
--

LOCK TABLES `wharehouses` WRITE;
/*!40000 ALTER TABLE `wharehouses` DISABLE KEYS */;
/*!40000 ALTER TABLE `wharehouses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-24 22:35:11
