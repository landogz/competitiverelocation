-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dbrelocation
CREATE DATABASE IF NOT EXISTS `dbrelocation` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbrelocation`;

-- Dumping structure for table dbrelocation.agents
CREATE TABLE IF NOT EXISTS `agents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `external_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `services` json DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corporate_sales` decimal(15,2) NOT NULL DEFAULT '0.00',
  `consumer_sales` decimal(15,2) NOT NULL DEFAULT '0.00',
  `local_sales` decimal(15,2) NOT NULL DEFAULT '0.00',
  `long_distance_sales` decimal(15,2) NOT NULL DEFAULT '0.00',
  `delivery_service_sales` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_sales` decimal(15,2) NOT NULL DEFAULT '0.00',
  `truck_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `truck_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `num_trucks` int NOT NULL DEFAULT '0',
  `num_crews` int NOT NULL DEFAULT '0',
  `affiliated_company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `local_moving_service` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `delivery_service` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `labor_services` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `commercial_moving` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `carrierInterestReason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `external_created_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `randomcodes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_agent` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `general_freight` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agents_external_id_unique` (`external_id`),
  KEY `agents_user_id_foreign` (`user_id`),
  CONSTRAINT `agents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrelocation.agents: ~6 rows (approximately)
INSERT INTO `agents` (`id`, `external_id`, `company_name`, `contact_name`, `contact_title`, `address`, `city`, `state`, `zip_code`, `services`, `phone_number`, `email`, `company_website`, `corporate_sales`, `consumer_sales`, `local_sales`, `long_distance_sales`, `delivery_service_sales`, `total_sales`, `truck_size`, `truck_image`, `num_trucks`, `num_crews`, `affiliated_company`, `local_moving_service`, `delivery_service`, `labor_services`, `commercial_moving`, `carrierInterestReason`, `external_created_at`, `status`, `randomcodes`, `booking_agent`, `general_freight`, `is_active`, `created_at`, `updated_at`, `user_id`) VALUES
	(1, '25', 'Sample Company', 'John Doe', 'Manager', '123 Main Street', 'New York', 'NY', '10001', '{"labor": false, "delivery": true, "commercial": true, "local_moving": true, "booking_agent": true, "general_freight": true}', '123-456-7890', 'rolan.benavidez@gmail.com', 'www.samplecompany.com', 50000.00, 30000.00, 10000.00, 15000.00, 20000.00, 125000.00, '20ft', 'https://competitiverelocation.com/wp-content/uploads/2025/02/Sample-Company_1738454400_1.jpg, https://competitiverelocation.com/wp-content/uploads/2025/02/Sample-Company_1738454400_2.jpg', 2, 3, NULL, 'no', 'no', 'no', 'no', NULL, NULL, 'approved', NULL, 'no', 'no', 1, '2025-04-24 20:29:48', '2025-04-24 20:29:48', NULL),
	(2, '26', 'Landogz Web Solutions', 'Rolan Jr M Benavidez', 'Developer', 'Purok 4', 'New York, NY, USA', 'New York', '2202', '{"labor": true, "delivery": true, "commercial": false, "local_moving": true, "booking_agent": false, "general_freight": true}', '09465283233', 'rolan.benavidez@gmail.com', 'https://landogzwebsolutions.com/', 2000.00, 2000.00, 2000.00, 2000.00, 2000.00, 10000.00, '20ft', 'https://competitiverelocation.com/wp-content/uploads/2025/02/Landogz-Web-Solutions_1738454400_1.jpg', 1, 1, NULL, 'no', 'no', 'no', 'no', NULL, NULL, 'approved', NULL, 'no', 'no', 1, '2025-04-24 20:29:48', '2025-04-24 20:29:48', NULL),
	(3, '27', 'dave the mover', 'dave smith', 'owner', '25 n valley', 'Vineland, NJ, USA', 'New Jersey', '08332', '{"labor": false, "delivery": true, "commercial": false, "local_moving": false, "booking_agent": false, "general_freight": false}', '609-222-9282', 'crsmoving08@gmail.com', 'www.competitiverelocation.com', 0.00, 0.00, 100000.00, 0.00, 30000.00, 130000.00, '16', 'https://competitiverelocation.com/wp-content/uploads/2025/02/dave-the-mover_1738540800_1.jpg', 1, 1, NULL, 'no', 'no', 'no', 'no', NULL, NULL, 'approved', NULL, 'no', 'no', 1, '2025-04-24 20:31:50', '2025-04-24 20:31:50', NULL),
	(4, '28', 'Ladyworkx Trucks', 'Jay Randels', 'owner', '400 South Orange Avenue', 'Orlando, FL, USA', 'Florida', '32801', '{"labor": true, "delivery": true, "commercial": true, "local_moving": true, "booking_agent": false, "general_freight": true}', '848 359 8030', 'love@competitiverelocation.com', 'www.competitiverelocation.com', 1.00, 1.00, 1.00, 23.00, 12.00, 12333.00, '26', 'https://competitiverelocation.com/wp-content/uploads/2025/02/Ladyworkx-Trucks_1738627200_1.jpg', 1, 1, NULL, 'no', 'no', 'no', 'no', NULL, NULL, 'approved', NULL, 'no', 'no', 1, '2025-04-24 20:31:50', '2025-04-24 20:31:50', NULL),
	(5, '29', 'd&y moving', 'lamar reyess', 'owner', '23 main st', 'Glassboro, NJ, USA', 'New Jersey', '08028', '{"labor": false, "delivery": true, "commercial": false, "local_moving": true, "booking_agent": false, "general_freight": false}', '6092229282', 'crsmoving08@gmail.com', 'www.competitiverelocation.com', 100000.00, 100000.00, 250000.00, 0.00, 0.00, 450000.00, '16', 'https://competitiverelocation.com/wp-content/uploads/2025/02/dy-moving_1740355200_1.jpg', 1, 1, NULL, 'no', 'no', 'no', 'no', NULL, NULL, 'approved', NULL, 'no', 'no', 1, '2025-04-24 20:31:50', '2025-04-28 18:04:24', NULL),
	(6, '30', 'Move Makers Real Estate', 'Phillip Graves', 'Program Manager', '600 West Annsbury Street', 'Philadelphia, PA, USA', 'Pennsylvania', '19140', '{"labor": true, "delivery": true, "commercial": true, "local_moving": true, "booking_agent": true, "general_freight": false}', '2158689483', 'MoveMakers76@gmail.com', 'Www.MoveMakersRealty.com', 1.00, 1.00, 1.00, 1.00, 1.00, 5.00, '0', '', 1, 2, NULL, 'no', 'no', 'no', 'no', NULL, NULL, 'approved', NULL, 'no', 'no', 1, '2025-04-24 20:31:50', '2025-04-24 20:31:50', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
