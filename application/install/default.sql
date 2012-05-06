-- MySQL dump 10.13  Distrib 5.5.14, for Linux (x86_64)
--
-- Host: localhost    Database: produccio2_vile
-- ------------------------------------------------------
-- Server version	5.5.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping default data for `userType`

LOCK TABLES `produccio2_vile`.`userType` WRITE;
/*!40000 ALTER TABLE `produccio2_vile`.`userType` DISABLE KEYS */;
INSERT INTO `produccio2_vile`.`userType` VALUES (1,'SuperAdmin','2012-01-31','2012-01-31',NULL),(2,'Admin','2012-01-31','2012-01-31',NULL);
/*!40000 ALTER TABLE `produccio2_vile`.`userType` ENABLE KEYS */;
UNLOCK TABLES;

-- Dumping default data for `produccio2_vile`.`user`

LOCK TABLES `produccio2_vile`.`user` WRITE;
/*!40000 ALTER TABLE `produccio2_vile`.`user` DISABLE KEYS */;
INSERT INTO `produccio2_vile`.`user` VALUES (1,'SuperAdmin','superadmin@produccio2_vile.com','9b0712c6f0df78a693ef3610829a7b29',1,2,'2012-01-31','2012-01-31',NULL);
/*!40000 ALTER TABLE `produccio2_vile`.`user` ENABLE KEYS */;
UNLOCK TABLES;

-- Dumping default data for `produccio2_vile`.`archivo`

LOCK TABLES `produccio2_vile`.`archivo` WRITE;
/*!40000 ALTER TABLE `produccio2_vile`.`archivo` DISABLE KEYS */;
INSERT INTO `produccio2_vile`.`archivo` VALUES (1,'home',NULL,'',1,NULL,480,1,'2012-01-31','2012-01-31',NULL),
(2,'superadmin',1,'',1,NULL,480,1,'2012-01-31','2012-01-31',NULL);
/*!40000 ALTER TABLE `produccio2_vile`.`archivo` ENABLE KEYS */;
UNLOCK TABLES;

