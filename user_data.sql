/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.3-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: user_data
-- ------------------------------------------------------
-- Server version	11.4.3-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `balance_transfers`
--

DROP TABLE IF EXISTS `balance_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balance_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transfer_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `balance_transfers_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `balance_transfers_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `balance_transfers`
--

LOCK TABLES `balance_transfers` WRITE;
/*!40000 ALTER TABLE `balance_transfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `balance_transfers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_username` varchar(50) NOT NULL,
  `receiver_username` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES
(1,'rohmat','iwan','p','2024-12-04 00:03:59'),
(2,'iwan','rohmat','tes1','2024-12-04 00:05:05'),
(3,'iwan','iwan','p','2024-12-04 00:05:10'),
(4,'iwan','iwan','p','2024-12-04 00:05:15'),
(5,'iwan','iwan','p','2024-12-04 00:05:20'),
(6,'iwan','rohmat','kaunih apee','2024-12-04 00:08:51'),
(7,'iwan','kipli','halo','2024-12-04 00:09:46'),
(8,'iwan','iwan','halo','2024-12-04 00:10:02'),
(9,'lingga','kipli','pantek','2024-12-09 18:03:22');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topup_requests`
--

DROP TABLE IF EXISTS `topup_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topup_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `topup_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topup_requests`
--

LOCK TABLES `topup_requests` WRITE;
/*!40000 ALTER TABLE `topup_requests` DISABLE KEYS */;
INSERT INTO `topup_requests` VALUES
(1,4,1.00,'approved','2024-12-11 12:24:58','2024-12-11 12:25:21',NULL),
(2,4,1.00,'approved','2024-12-11 12:25:05','2024-12-11 12:25:22',NULL),
(3,4,1.00,'rejected','2024-12-11 12:33:27','2024-12-11 12:35:19',NULL),
(4,6,1.00,'rejected','2024-12-11 12:34:17','2024-12-11 12:35:20',NULL),
(5,5,1.00,'approved','2024-12-11 12:48:16','2024-12-11 12:48:51',NULL),
(6,4,1.00,'approved','2024-12-13 01:33:03','2024-12-13 01:33:18',NULL),
(7,4,1.00,'approved','2024-12-13 01:36:47','2024-12-13 01:37:02',NULL),
(8,4,1.00,'approved','2024-12-13 01:57:58','2024-12-13 01:58:12',NULL),
(9,5,5.00,'approved','2024-12-13 04:09:09','2024-12-13 04:09:27',NULL),
(10,3,10.00,'approved','2024-12-13 04:13:43','2024-12-13 04:14:52',NULL),
(11,3,1.00,'approved','2024-12-18 02:19:52','2024-12-18 02:25:54','uploads/Screenshot From 2024-10-13 18-53-11.png'),
(12,4,1.00,'rejected','2024-12-20 19:33:46','2024-12-20 19:35:25','uploads/Screenshot From 2024-11-24 20-38-13.png'),
(13,3,1.00,'pending','2024-12-23 14:34:56',NULL,'uploads/Screenshot From 2024-10-13 18-56-24.png');
/*!40000 ALTER TABLE `topup_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_balance`
--

DROP TABLE IF EXISTS `user_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 1.00,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_balance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_balance`
--

LOCK TABLES `user_balance` WRITE;
/*!40000 ALTER TABLE `user_balance` DISABLE KEYS */;
INSERT INTO `user_balance` VALUES
(1,2,1.00),
(2,3,1.00),
(3,4,9.00),
(4,5,10.00),
(5,6,1.00);
/*!40000 ALTER TABLE `user_balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'admin','admin','admin@gmail.com','admin','jl.labattoa','admin'),
(2,'user','user1','user@gmail.com','user','jl.bacukiki','user'),
(3,'iwan','iwan','iwan@gmail.com','iwan123','pathuk, gunung kidul','user'),
(4,'kipli','kipli','kipli@gmail.com','kipli','jl.plantsvszombie','user'),
(5,'rohmat','rohmat','rohmat@gmail.com','rohmat','jl.plantsvszombie','user'),
(6,'lingga','lingga','lingga@gmail.com','lingga','jl. kaliuang KM.10','user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2024-12-24  8:41:39
