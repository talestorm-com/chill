use chill;
-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: chill
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.16.04.2

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

--
-- Table structure for table `catalog__group`
--

DROP TABLE IF EXISTS `catalog__group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__group` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(19) unsigned DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `name` varchar(1024) NOT NULL,
  `visible` int(1) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(256) NOT NULL,
  `guid` varchar(80) DEFAULT NULL,
  `html_mode` int(1) unsigned NOT NULL DEFAULT '1',
  `default_image` varchar(64) DEFAULT NULL,
  `import_processor` varchar(1024) DEFAULT '',
  `terminal` int(1) unsigned NOT NULL DEFAULT '0',
  `meta_title` varchar(1024) DEFAULT '',
  `og_title` varchar(1024) DEFAULT '',
  `meta_keywords` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `og_description` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  UNIQUE KEY `guid` (`guid`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `catalog__group_2_parent_catalog__group` FOREIGN KEY (`parent_id`) REFERENCES `catalog__group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__group`
--

LOCK TABLES `catalog__group` WRITE;
/*!40000 ALTER TABLE `catalog__group` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__group__properties`
--

DROP TABLE IF EXISTS `catalog__group__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__group__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `catalog__group_properties_2_catalog_group` FOREIGN KEY (`id`) REFERENCES `catalog__group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__group__properties`
--

LOCK TABLES `catalog__group__properties` WRITE;
/*!40000 ALTER TABLE `catalog__group__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__group__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product`
--

DROP TABLE IF EXISTS `catalog__product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(80) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'активен',
  `sort` int(11) NOT NULL DEFAULT '0',
  `orderable` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'возможен заказ (нет размера)',
  `article` varchar(255) NOT NULL COMMENT 'артикул - уник',
  `html_mode_d` int(1) unsigned NOT NULL DEFAULT '1',
  `html_mode_c` int(1) unsigned NOT NULL DEFAULT '1',
  `default_image` varchar(64) DEFAULT NULL,
  `source_article` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_by_article` (`article`,`id`) USING BTREE,
  UNIQUE KEY `products_by_guid` (`guid`,`id`) USING BTREE,
  UNIQUE KEY `products_by_alias` (`alias`,`id`) USING BTREE,
  UNIQUE KEY `products_unique_uid` (`guid`),
  UNIQUE KEY `product_unique_alias` (`alias`),
  KEY `products_by_enabled` (`enabled`,`id`),
  KEY `sort` (`sort`),
  KEY `source_article` (`source_article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product`
--

LOCK TABLES `catalog__product` WRITE;
/*!40000 ALTER TABLE `catalog__product` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__color`
--

DROP TABLE IF EXISTS `catalog__product__color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__color` (
  `guid` varchar(80) NOT NULL COMMENT 'uuid цвета',
  `product_id` bigint(19) unsigned NOT NULL COMMENT 'товар, для которого цвет',
  `exchange_uid` varchar(250) DEFAULT NULL COMMENT 'код товара+имя цвета - для импорта',
  `sort` int(11) NOT NULL DEFAULT '0',
  `html_color` varchar(100) NOT NULL DEFAULT 'rgba(0,0,0,0)' COMMENT 'html цвета',
  PRIMARY KEY (`guid`,`product_id`),
  UNIQUE KEY `exchange_ud` (`exchange_uid`),
  KEY `catalog_product_color_2_catalog_product` (`product_id`),
  KEY `catalog_product_color_by_sort` (`guid`,`product_id`,`sort`),
  CONSTRAINT `catalog_product_color_2_catalog_product` FOREIGN KEY (`product_id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__color`
--

LOCK TABLES `catalog__product__color` WRITE;
/*!40000 ALTER TABLE `catalog__product__color` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__color__strings`
--

DROP TABLE IF EXISTS `catalog__product__color__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__color__strings` (
  `guid` varchar(80) NOT NULL,
  `name` varchar(1024) NOT NULL,
  PRIMARY KEY (`guid`),
  CONSTRAINT `catalog_product_color_strings_catalog_product_color` FOREIGN KEY (`guid`) REFERENCES `catalog__product__color` (`guid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__color__strings`
--

LOCK TABLES `catalog__product__color__strings` WRITE;
/*!40000 ALTER TABLE `catalog__product__color__strings` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__color__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__group`
--

DROP TABLE IF EXISTS `catalog__product__group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__group` (
  `product_id` bigint(19) unsigned NOT NULL,
  `group_id` bigint(19) unsigned NOT NULL,
  `sort_in_group` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`,`group_id`),
  KEY `products_in_group_by_sort` (`sort_in_group`,`product_id`,`group_id`),
  KEY `products_in_group_by_sort_2` (`sort_in_group`,`group_id`,`product_id`),
  KEY `product_grpup_2_group` (`group_id`),
  CONSTRAINT `product_grpup_2_group` FOREIGN KEY (`group_id`) REFERENCES `catalog__group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_grpup_2_product` FOREIGN KEY (`product_id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__group`
--

LOCK TABLES `catalog__product__group` WRITE;
/*!40000 ALTER TABLE `catalog__product__group` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__hash`
--

DROP TABLE IF EXISTS `catalog__product__hash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__hash` (
  `product_id` bigint(19) unsigned NOT NULL,
  `color_id` varchar(80) NOT NULL DEFAULT '',
  `size_id` bigint(19) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(80) NOT NULL,
  PRIMARY KEY (`product_id`,`color_id`,`size_id`),
  CONSTRAINT `catalog__product__hash_2_product` FOREIGN KEY (`product_id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Табло связей наш товар - товар 1с';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__hash`
--

LOCK TABLES `catalog__product__hash` WRITE;
/*!40000 ALTER TABLE `catalog__product__hash` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__hash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__meta`
--

DROP TABLE IF EXISTS `catalog__product__meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__meta` (
  `id` bigint(19) unsigned NOT NULL,
  `title` varchar(1024) NOT NULL,
  `og_title` varchar(2048) DEFAULT NULL,
  `keywords` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `og_description` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `product_meta_2_product` FOREIGN KEY (`id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__meta`
--

LOCK TABLES `catalog__product__meta` WRITE;
/*!40000 ALTER TABLE `catalog__product__meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__price`
--

DROP TABLE IF EXISTS `catalog__product__price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__price` (
  `id` bigint(19) unsigned NOT NULL,
  `retail` double DEFAULT NULL,
  `gross` double DEFAULT NULL,
  `retail_old` double DEFAULT NULL,
  `gross_old` double DEFAULT NULL,
  `discount_retail` double DEFAULT NULL,
  `discount_gross` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `catalog__product__price__2_catalog_product` FOREIGN KEY (`id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__price`
--

LOCK TABLES `catalog__product__price` WRITE;
/*!40000 ALTER TABLE `catalog__product__price` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__product__link`
--

DROP TABLE IF EXISTS `catalog__product__product__link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__product__link` (
  `product_1` bigint(19) unsigned NOT NULL,
  `product_2` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`product_1`,`product_2`),
  UNIQUE KEY `catalog_product_product_rpm` (`product_2`,`product_1`),
  CONSTRAINT `catalog_product_product_2_product` FOREIGN KEY (`product_1`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `catalog_product_product_2_product_2` FOREIGN KEY (`product_2`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__product__link`
--

LOCK TABLES `catalog__product__product__link` WRITE;
/*!40000 ALTER TABLE `catalog__product__product__link` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__product__link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__property`
--

DROP TABLE IF EXISTS `catalog__product__property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__property` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `catalog__product__property__2__catalog_product` FOREIGN KEY (`id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__property`
--

LOCK TABLES `catalog__product__property` WRITE;
/*!40000 ALTER TABLE `catalog__product__property` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__size`
--

DROP TABLE IF EXISTS `catalog__product__size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__size` (
  `product_id` bigint(19) unsigned NOT NULL,
  `size_id` bigint(19) unsigned NOT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`product_id`,`size_id`),
  UNIQUE KEY `product_id` (`size_id`,`product_id`) USING BTREE,
  KEY `enabled` (`enabled`),
  CONSTRAINT `catalog__product_size_2_catalog_product` FOREIGN KEY (`product_id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `catalog_product_size_2_size` FOREIGN KEY (`size_id`) REFERENCES `catalog__size__def` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__size`
--

LOCK TABLES `catalog__product__size` WRITE;
/*!40000 ALTER TABLE `catalog__product__size` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product__strings`
--

DROP TABLE IF EXISTS `catalog__product__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `description` mediumtext NOT NULL,
  `consists` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `product__strings_2_product` FOREIGN KEY (`id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product__strings`
--

LOCK TABLES `catalog__product__strings` WRITE;
/*!40000 ALTER TABLE `catalog__product__strings` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__product_guid_link`
--

DROP TABLE IF EXISTS `catalog__product_guid_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__product_guid_link` (
  `guid_1c` varchar(100) NOT NULL,
  `color_guid` varchar(100) DEFAULT NULL,
  `size_guid` varchar(100) DEFAULT NULL,
  `product_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`guid_1c`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `catalog__product__guid__link_2_caalog_product` FOREIGN KEY (`product_id`) REFERENCES `catalog__product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__product_guid_link`
--

LOCK TABLES `catalog__product_guid_link` WRITE;
/*!40000 ALTER TABLE `catalog__product_guid_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__product_guid_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__size__alter`
--

DROP TABLE IF EXISTS `catalog__size__alter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__size__alter` (
  `size_id` bigint(19) unsigned NOT NULL,
  `alter_id` bigint(19) unsigned NOT NULL,
  `alter_size` varchar(100) NOT NULL,
  PRIMARY KEY (`size_id`,`alter_id`),
  UNIQUE KEY `catalog_size_alter_rpm` (`alter_id`,`size_id`),
  CONSTRAINT `catalog__size__alter_2_catalog_size_def` FOREIGN KEY (`size_id`) REFERENCES `catalog__size__def` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `catalog_size_alter_2_catalog_size_alter_def` FOREIGN KEY (`alter_id`) REFERENCES `catalog__size__alter__def` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='соответсвие альтернативных размеров';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__size__alter`
--

LOCK TABLES `catalog__size__alter` WRITE;
/*!40000 ALTER TABLE `catalog__size__alter` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__size__alter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__size__alter__def`
--

DROP TABLE IF EXISTS `catalog__size__alter__def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__size__alter__def` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id альтератора',
  `name` varchar(255) NOT NULL COMMENT 'наименование альтернативной системы размеров',
  `short_name` varchar(100) NOT NULL COMMENT 'краткое наименование системы размеров',
  `visible` int(1) unsigned NOT NULL DEFAULT '1',
  `html_mode` int(1) unsigned NOT NULL DEFAULT '1',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_size_alter_def_by_visibility` (`visible`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='таблица альтернативных систем размеров';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__size__alter__def`
--

LOCK TABLES `catalog__size__alter__def` WRITE;
/*!40000 ALTER TABLE `catalog__size__alter__def` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__size__alter__def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__size__def`
--

DROP TABLE IF EXISTS `catalog__size__def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__size__def` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(80) NOT NULL,
  `size` varchar(255) NOT NULL COMMENT 'наименование размера в номенклатуре магазина',
  PRIMARY KEY (`id`,`guid`) USING BTREE,
  UNIQUE KEY `catalog_size_def_index_rpm` (`guid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='словарь размеров';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__size__def`
--

LOCK TABLES `catalog__size__def` WRITE;
/*!40000 ALTER TABLE `catalog__size__def` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__size__def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__tile`
--

DROP TABLE IF EXISTS `catalog__tile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__tile` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `title` varchar(512) NOT NULL,
  `info` varchar(512) NOT NULL DEFAULT '',
  `visible` int(1) unsigned NOT NULL DEFAULT '1',
  `loader` varchar(255) NOT NULL DEFAULT 'default',
  `template` varchar(255) NOT NULL DEFAULT 'default',
  `crop` int(1) unsigned NOT NULL DEFAULT '1',
  `crop_fill` int(1) unsigned NOT NULL DEFAULT '0',
  `background` varchar(50) DEFAULT NULL,
  `show_header` int(1) unsigned NOT NULL DEFAULT '1',
  `css_class` varchar(100) DEFAULT NULL,
  `ignore_catalog_visibility` int(1) unsigned NOT NULL DEFAULT '0',
  `ignore_product_visibility` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__tile`
--

LOCK TABLES `catalog__tile` WRITE;
/*!40000 ALTER TABLE `catalog__tile` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__tile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__tile__catalog`
--

DROP TABLE IF EXISTS `catalog__tile__catalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__tile__catalog` (
  `t_id` bigint(19) unsigned NOT NULL,
  `c_id` bigint(19) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `override` varchar(255) DEFAULT NULL,
  `image_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`t_id`,`c_id`),
  KEY `catalog__tile__catalog__2__catalog` (`c_id`),
  CONSTRAINT `catalog__tile__catalog__2__catalog` FOREIGN KEY (`c_id`) REFERENCES `catalog__group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `catalog__tile__catalog__2__catalog__tile` FOREIGN KEY (`t_id`) REFERENCES `catalog__tile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__tile__catalog`
--

LOCK TABLES `catalog__tile__catalog` WRITE;
/*!40000 ALTER TABLE `catalog__tile__catalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__tile__catalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog__tile__properties`
--

DROP TABLE IF EXISTS `catalog__tile__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog__tile__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `catalog__tile_props_2_catalog_tile` FOREIGN KEY (`id`) REFERENCES `catalog__tile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog__tile__properties`
--

LOCK TABLES `catalog__tile__properties` WRITE;
/*!40000 ALTER TABLE `catalog__tile__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog__tile__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chill__orders`
--

DROP TABLE IF EXISTS `chill__orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chill__orders` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `payport_id` varchar(100) DEFAULT NULL,
  `amount` double unsigned NOT NULL DEFAULT '100',
  `status` varchar(100) NOT NULL DEFAULT 'dummy',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`id`),
  UNIQUE KEY `payport_id` (`payport_id`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chill__orders`
--

LOCK TABLES `chill__orders` WRITE;
/*!40000 ALTER TABLE `chill__orders` DISABLE KEYS */;
INSERT INTO `chill__orders` VALUES (1,1,NULL,100,'created','2020-04-20 00:09:47','2020-04-20 00:09:47'),(2,22,NULL,100,'created','2020-04-20 00:10:20','2020-04-20 00:10:20'),(3,1,NULL,100,'created','2020-04-20 00:10:45','2020-04-20 00:10:45'),(4,1,NULL,100,'created','2020-04-20 00:10:47','2020-04-20 00:10:47'),(5,1,NULL,100,'created','2020-04-20 00:12:26','2020-04-20 00:12:26'),(6,1,NULL,100,'created','2020-04-20 00:12:28','2020-04-20 00:12:28'),(7,1,NULL,100,'created','2020-04-20 00:12:29','2020-04-20 00:12:29'),(8,1,NULL,100,'created','2020-04-20 00:12:29','2020-04-20 00:12:29'),(9,1,NULL,100,'created','2020-04-20 00:12:30','2020-04-20 00:12:30'),(10,1,NULL,100,'created','2020-04-20 00:12:31','2020-04-20 00:12:31'),(11,1,NULL,100,'created','2020-04-20 00:12:31','2020-04-20 00:12:31'),(12,1,NULL,100,'created','2020-04-20 00:12:32','2020-04-20 00:12:32'),(13,1,NULL,100,'created','2020-04-20 00:12:32','2020-04-20 00:12:32'),(14,1,NULL,100,'created','2020-04-20 00:12:33','2020-04-20 00:12:33'),(15,1,NULL,100,'created','2020-04-20 00:12:33','2020-04-20 00:12:33'),(16,1,NULL,100,'created','2020-04-20 00:12:34','2020-04-20 00:12:34'),(17,22,NULL,100,'created','2020-04-20 00:14:47','2020-04-20 00:14:47'),(18,22,NULL,100,'created','2020-04-20 00:15:19','2020-04-20 00:15:19'),(19,1,NULL,100,'created','2020-04-20 00:18:18','2020-04-20 00:18:18'),(20,1,NULL,100,'created','2020-04-20 00:18:52','2020-04-20 00:18:52'),(21,1,NULL,100,'created','2020-04-20 00:19:01','2020-04-20 00:19:01'),(22,22,NULL,100,'created','2020-04-20 00:20:01','2020-04-20 00:20:01'),(23,22,NULL,100,'created','2020-04-20 00:26:21','2020-04-20 00:26:21'),(24,1,'1001176',100,'FILLED','2020-04-20 00:31:25','2020-04-20 00:31:25'),(25,22,'1001177',100,'FILLED','2020-04-20 00:31:42','2020-04-20 00:31:42'),(26,1,'1001178',100,'FILLED','2020-04-20 00:36:28','2020-04-20 00:36:28'),(27,22,'1001179',100,'FILLED','2020-04-20 00:52:49','2020-04-20 00:52:50'),(28,22,'1001180',100,'FILLED','2020-04-20 00:53:06','2020-04-20 00:53:07'),(29,1,'1001181',100,'FILLED','2020-04-20 00:57:00','2020-04-20 00:57:00'),(30,1,'1001182',100,'FILLED','2020-04-20 00:59:20','2020-04-20 00:59:21'),(31,1,'1001183',100,'FILLED','2020-04-20 01:01:48','2020-04-20 01:01:48'),(32,22,'1001184',100,'FILLED','2020-04-20 01:05:54','2020-04-20 01:05:55'),(33,22,'1001185',100,'FILLED','2020-04-20 01:07:08','2020-04-20 01:07:09'),(34,22,'1001186',100,'FILLED','2020-04-20 01:08:47','2020-04-20 01:08:48'),(35,22,'1001187',100,'FILLED','2020-04-20 01:12:39','2020-04-20 01:12:40'),(36,22,'1001188',100,'FILLED','2020-04-20 01:17:52','2020-04-20 01:17:53'),(37,1,'1001189',100,'FILLED','2020-04-20 01:18:12','2020-04-20 01:18:12'),(38,1,'1001190',100,'FILLED','2020-04-20 01:20:08','2020-04-20 01:20:08'),(39,22,'1001191',100,'FILLED','2020-04-20 01:31:09','2020-04-20 01:31:10'),(40,1,'1001192',100,'EXECUTED','2020-04-20 01:53:46','2020-04-20 01:53:47'),(41,1,NULL,3.5,'created','2020-04-20 01:55:13','2020-04-20 01:55:13'),(42,1,NULL,3.5,'created','2020-04-20 01:55:47','2020-04-20 01:55:47'),(43,1,'1001193',300.5,'FILLED','2020-04-20 01:55:59','2020-04-20 01:55:59'),(44,1,NULL,3.5,'created','2020-04-20 01:56:35','2020-04-20 01:56:35'),(45,1,NULL,3.5,'created','2020-04-20 01:56:37','2020-04-20 01:56:37'),(46,1,NULL,20,'created','2020-04-20 01:57:13','2020-04-20 01:57:13'),(47,1,NULL,30,'created','2020-04-20 01:57:23','2020-04-20 01:57:23'),(48,1,NULL,40,'created','2020-04-20 01:57:28','2020-04-20 01:57:28'),(49,1,NULL,50,'created','2020-04-20 01:57:32','2020-04-20 01:57:32'),(50,1,NULL,60,'created','2020-04-20 01:57:37','2020-04-20 01:57:37'),(51,1,NULL,70,'created','2020-04-20 01:57:41','2020-04-20 01:57:41'),(52,1,NULL,80,'created','2020-04-20 01:57:46','2020-04-20 01:57:46'),(53,1,NULL,90,'created','2020-04-20 01:57:49','2020-04-20 01:57:49'),(54,1,'1001194',100,'FILLED','2020-04-20 01:57:55','2020-04-20 01:57:55'),(55,1,NULL,90,'created','2020-04-20 01:58:07','2020-04-20 01:58:07'),(56,1,'1001195',101.3,'EXECUTED','2020-04-20 01:58:21','2020-04-20 01:58:21'),(57,22,'1001196',100,'EXECUTED','2020-04-20 01:59:41','2020-04-20 01:59:42'),(58,1,'1001197',100,'REJECTED','2020-04-20 01:59:49','2020-04-20 01:59:49'),(59,22,'1001198',100,'REJECTED','2020-04-20 02:04:54','2020-04-20 02:04:55'),(60,22,'1001199',223,'EXECUTED','2020-04-20 10:13:47','2020-04-20 10:13:48'),(61,22,'1001200',100,'FILLED','2020-04-20 10:31:54','2020-04-20 10:31:55'),(62,22,'1001201',100,'EXECUTED','2020-04-20 10:41:02','2020-04-20 10:41:03'),(63,1,'1001202',100,'FILLED','2020-04-20 13:59:41','2020-04-20 13:59:42'),(64,22,'1001203',100,'FILLED','2020-04-20 17:34:19','2020-04-20 17:34:20'),(65,22,'1001204',100,'FILLED','2020-04-20 17:34:26','2020-04-20 17:34:27'),(66,1,'1001205',100,'EXECUTED','2020-04-21 23:15:23','2020-04-21 23:15:24'),(67,32,'1001211',100,'FILLED','2020-04-24 18:19:59','2020-04-24 18:20:00'),(68,35,'1001212',100,'EXECUTED','2020-05-31 15:51:39','2020-05-31 15:51:41');
/*!40000 ALTER TABLE `chill__orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chill__promo`
--

DROP TABLE IF EXISTS `chill__promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chill__promo` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chill__promo`
--

LOCK TABLES `chill__promo` WRITE;
/*!40000 ALTER TABLE `chill__promo` DISABLE KEYS */;
INSERT INTO `chill__promo` VALUES (1,'CHILLKONCHILL','Чилл Кончилл',60),(3,'inchillwetrust','inchillwetrust',60);
/*!40000 ALTER TABLE `chill__promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chill__promo__user`
--

DROP TABLE IF EXISTS `chill__promo__user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chill__promo__user` (
  `promo_id` bigint(19) unsigned NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  `activated` datetime NOT NULL,
  PRIMARY KEY (`promo_id`,`user_id`),
  UNIQUE KEY `user_id` (`user_id`,`promo_id`),
  CONSTRAINT `chill__user__promo__2__promo` FOREIGN KEY (`promo_id`) REFERENCES `chill__promo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chill__user__promo__2__user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chill__promo__user`
--

LOCK TABLES `chill__promo__user` WRITE;
/*!40000 ALTER TABLE `chill__promo__user` DISABLE KEYS */;
INSERT INTO `chill__promo__user` VALUES (1,1,'2020-04-25 23:07:00'),(3,1,'2020-04-25 23:10:42'),(3,33,'2020-04-26 02:10:47');
/*!40000 ALTER TABLE `chill__promo__user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chill__user_social`
--

DROP TABLE IF EXISTS `chill__user_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chill__user_social` (
  `domain` varchar(100) NOT NULL,
  `social_id` varchar(200) NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`domain`,`social_id`),
  UNIQUE KEY `domain` (`domain`,`social_id`,`user_id`),
  KEY `chill__social__2__user` (`user_id`),
  CONSTRAINT `chill__social__2__user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chill__user_social`
--

LOCK TABLES `chill__user_social` WRITE;
/*!40000 ALTER TABLE `chill__user_social` DISABLE KEYS */;
INSERT INTO `chill__user_social` VALUES ('fb','120958282504484',33);
/*!40000 ALTER TABLE `chill__user_social` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientorder`
--

DROP TABLE IF EXISTS `clientorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientorder` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `reserve` int(1) unsigned NOT NULL DEFAULT '0',
  `shop_id` bigint(19) unsigned DEFAULT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `dealer` int(1) unsigned NOT NULL DEFAULT '0',
  `delivery` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `created` (`created`),
  KEY `reserve` (`reserve`),
  KEY `shop_id` (`shop_id`),
  CONSTRAINT `clientorder_2_client` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `clientorder_2_offline` FOREIGN KEY (`shop_id`) REFERENCES `storage__offline__shop` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientorder`
--

LOCK TABLES `clientorder` WRITE;
/*!40000 ALTER TABLE `clientorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientorder__comment`
--

DROP TABLE IF EXISTS `clientorder__comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientorder__comment` (
  `id` bigint(19) unsigned NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `clientorer_comment_2_clientorder` FOREIGN KEY (`id`) REFERENCES `clientorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientorder__comment`
--

LOCK TABLES `clientorder__comment` WRITE;
/*!40000 ALTER TABLE `clientorder__comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientorder__comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientorder__items`
--

DROP TABLE IF EXISTS `clientorder__items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientorder__items` (
  `id` bigint(19) unsigned NOT NULL,
  `item_guid` varchar(100) NOT NULL,
  `item_product_id` bigint(19) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `color_name` varchar(255) DEFAULT NULL,
  `product_article` varchar(255) NOT NULL,
  `sizes` varchar(255) DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `qty` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`item_guid`) USING BTREE,
  KEY `item_product_id` (`item_product_id`),
  CONSTRAINT `clientorder_item_2_clientorder` FOREIGN KEY (`id`) REFERENCES `clientorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `clientorder_item_product_2_product` FOREIGN KEY (`item_product_id`) REFERENCES `catalog__product` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientorder__items`
--

LOCK TABLES `clientorder__items` WRITE;
/*!40000 ALTER TABLE `clientorder__items` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientorder__items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientorder__status`
--

DROP TABLE IF EXISTS `clientorder__status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientorder__status` (
  `id` bigint(19) unsigned NOT NULL,
  `status` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `clientorder__status_2_clientorder` FOREIGN KEY (`id`) REFERENCES `clientorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientorder__status`
--

LOCK TABLES `clientorder__status` WRITE;
/*!40000 ALTER TABLE `clientorder__status` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientorder__status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientorder__total`
--

DROP TABLE IF EXISTS `clientorder__total`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientorder__total` (
  `id` bigint(19) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `clientorder__total_2_clientorder` FOREIGN KEY (`id`) REFERENCES `clientorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientorder__total`
--

LOCK TABLES `clientorder__total` WRITE;
/*!40000 ALTER TABLE `clientorder__total` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientorder__total` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_block`
--

DROP TABLE IF EXISTS `content_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_block` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `editor` int(1) unsigned NOT NULL DEFAULT '1',
  `comment` varchar(1025) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_block`
--

LOCK TABLES `content_block` WRITE;
/*!40000 ALTER TABLE `content_block` DISABLE KEYS */;
INSERT INTO `content_block` VALUES (6,'login',0,'','<div class=\"login_background\">\n  <div class=\"login_background_inner\">\n    <div id=\"login_form\">\n      <h4>Авторизация</h4>\n      <form id=\"login_form_form\" action=\"/Auth/API\" method=\"POST\" onsubmit=\"javascript:return false;\">\n        <div class=\"in_form_input\">\n          <input type=\"text\" placeholder=\"email или телефон\" name=\"login\">\n        </div>\n        <div class=\"in_form_input\">\n          <input type=\"password\" placeholder=\"Пароль\" name=\"password\">\n        </div>\n        <div class=\"in_form_btn\">\n          <button type=\"submit\" id=\"login_button\">Авторизоваться</button>\n        </div>\n        <p>\n          <a href=\"/Register\">Зарегистрироваться</a>\n        </p>\n      </form>\n    </div>\n  </div>\n</div>\n{literal}\n<script>\n  (function(){\n    window.Eve=window.Eve||{};\n    window.Eve.EFO=window.Eve.EFO||{};\n    window.Eve.EFO.Ready=window.Eve.EFO.Ready||[];\n    window.Eve.EFO.Ready.push(function(){\n      var U = window.Eve.EFO.U;\n      var handle = jQuery(\'#login_form\');\n      handle.show();\n      var button = jQuery(\'#login_button\');\n      button.on(\'click\',function(e){\n        e.stopPropagation();\n        e.preventDefault?e.preventDefault:e.returnValue=false;\n        if(button.hasClass(\'disabled\')){\n          return;\n        }\n        try{\n          var email = U.NEString(handle.find(\'input[name=login]\').val(),null);\n          email?0:U.Error(\"Укажите email или номер телефона\");\n          var phone = window.Eve.EFO.Checks.formatPhone(email);\n          (email||phone)?0:U.Error(\"Укажите корректный email или телефон\");\n          var password = U.NEString(handle.find(\'input[name=password]\').val(),null);\n          password?0:U.Error(\"Укажите пароль\");\n          button.addClass(\'disabled\');\n          jQuery.post(handle.find(\'form\').attr(\'action\'),{login:email?email:phone,password:password,action:\"auth\"})\n            .done(function(d){\n            if (U.isObject(d)){\n              if(d.status===\'ok\'){\n                window.location.reload(true);\n                return;\n              }\n              if(d.status===\'error\'){\n                alert(d.error_info.message);\n                return;\n              }\n            }\n            alert(\"Некорректный ответ сервера\");\n          })\n            .fail(function(){\n            alert(\"Ошибка связи с сервером\");\n          })\n            .always(function(){\n            button.removeClass(\'disabled\');\n          })\n        }catch(e){\n          alert(e.message);\n          return;\n        }\n      });\n    });\n  })();\n</script>\n{/literal}'),(7,'fos',0,'fos','<div id=\"zakaz_katalog\" class=\"order\">\n    <form  class=\"row\">\n        <div class=\"col s12\">\n            <h3>Контактная информация</h3>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_name\" data-id=\"form_3_name\" placeholder=\"Имя\" data-field=\"contact\">\n            </div>\n        </div>\n\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_email\" data-id=\"form_3_email\" placeholder=\"Email\" data-field=\"email\" >\n            </div>\n        </div>\n        <div class=\"col s12\">\n            <h3>Информация о сериале</h3>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_or_name\" data-id=\"form_3_film_or_name\" placeholder=\"Название (на языке оригинала)\" data-field=\"common_name\">\n            </div>\n        </div>\n\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_or_name\" data-id=\"form_3_film_name\" placeholder=\"Название (на английском)\" data-field=\"name\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_year\" data-id=\"form_3_film_year\" placeholder=\"Год выпуска\" data-field=\"year\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_url\" data-id=\"form_3_film_url\" placeholder=\"Ссылка на скачивание проекта\" data-field=\"link\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_count\" data-id=\"form_3_film_count\" placeholder=\"Количество сезонов и серий\" data-field=\"ss_qty\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_chro\" data-id=\"form_3_film_chro\" placeholder=\"Продолжительность серии\" data-field=\"series_length\">\n            </div>\n        </div>\n        <div class=\"col s12\">\n            <div class=\"one_input_desc\">\n                <textarea name=\"form_3_film_desc\" data-id=\"form_3_film_desc\" placeholder=\"Краткая аннотация\" data-field=\"annotation\"></textarea>\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_rej\" data-id=\"form_3_film_rej\" placeholder=\"Режиссер\" data-field=\"director\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_prod\" data-id=\"form_3_film_prod\" placeholder=\"Продюсер\" data-field=\"producer\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_act\" data-id=\"form_3_film_act\" placeholder=\"Актеры\" data-field=\"actor\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <input name=\"form_3_film_tr\" data-id=\"form_3_film_tr\" placeholder=\"Ссылка на скачивание трейлера\" data-field=\"trailer\">\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <div class=\"file-field input-field\">\n                    <div class=\"btn\">\n                        <span><i class=\"mdi mdi-link-variant\"></i></span>\n                        <input type=\"file\" multiple name=\"form_3_film_pos\" data-id=\"form_3_film_pos\" id=\"posters\">\n                    </div>\n                    <div class=\"file-path-wrapper\">\n                        <input class=\"file-path validate\" type=\"text\" placeholder=\"Постер\" name=\"form_3_film_pos\" data-id=\"form_3_film_pos\">\n                    </div>\n\n                </div>\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input\">\n                <div class=\"file-field input-field\">\n                    <div class=\"btn\">\n                        <span><i class=\"mdi mdi-link-variant\"></i></span>\n                        <input type=\"file\" multiple name=\"form_3_film_kad\" data-id=\"form_3_film_kad\" id=\"frames\">\n                    </div>\n                    <div class=\"file-path-wrapper\">\n                        <input class=\"file-path validate\" type=\"text\" placeholder=\"Кадры из проекта\" name=\"form_3_film_kad\" data-id=\"form_3_film_kad\" >\n                    </div>\n\n                </div>\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input one_input_icon\">\n                <input name=\"form_3_film_other_fb\" data-id=\"form_3_film_other_fb\" placeholder=\"https://facebook.com\" data-field=\"facebook\">\n                <span>\n                    <i class=\"mdi mdi-facebook\"></i>\n                </span>\n\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input one_input_icon\">\n                <input name=\"form_3_film_other_vk\" data-id=\"form_3_film_other_vk\" placeholder=\"https://vk.com\" data-field=\"vk\">\n                <span>\n                    <i class=\"mdi mdi-vk\"></i>\n                </span>\n\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input one_input_icon\">\n                <input name=\"form_3_film_other_in\" data-id=\"form_3_film_other_in\" placeholder=\"https://instagram.com\" data-field=\"instagramm\">\n                <span>\n                    <i class=\"mdi mdi-instagram\"></i>\n                </span>\n\n            </div>\n        </div>\n        <div class=\"col s12 l6\">\n            <div class=\"one_input one_input_icon\">\n                <input name=\"form_3_film_other_yt\" data-id=\"form_3_film_other_yt\" placeholder=\"https://youtube.com\" data-field=\"youtube\">\n                <span>\n                    <i class=\"mdi mdi-youtube\"></i>\n                </span>\n\n            </div>\n        </div>\n        <div class=\"col s12\">\n            <div class=\"one_input_desc\">\n                <textarea name=\"form_3_film_fest\" data-id=\"form_3_film_fest\" placeholder=\"Информация об участии в фестивалях\" data-field=\"festival\"></textarea>\n            </div>\n        </div>\n        <div class=\"col s12\">\n            <div class=\"one_checkbox\">\n                <input type=\"checkbox\" id=\"form_3_checkgvgvgvg\" checked=\"checked\" class=\"filled-in\" data-filed=\"commit\">\n                <label for=\"form_3_checkgvgvgvg\">Я принимаю условия пользования платформой</label>\n            </div>\n        </div>\n        <div class=\"col s12 l6 offset-l3\">\n            <div class=\"one_btn\">\n                <button class=\"button_get-catalog\" id=\"hahahahabutton\">Отправить</button>\n            </div>\n        </div>\n    </form>\n</div>\n<script>\n    {literal}\n    (function () {\n        window.Eve = window.Eve || {};\n        window.Eve.EFO = window.Eve.EFO || {};\n        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];\n        window.Eve.EFO.Ready.push(function () {\n            var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;\n            var form = jQuery(\'#zakaz_katalog form\'), button = jQuery(\'#hahahahabutton\');\n            form.on(\'submit\', function (e) {\n                e.preventDefault ? e.preventDefault() : e.returnValue = false;\n            });\n\n            var filters = {\n                \'contact\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'email\': [\'Strip\', \'Trim\', \'NEString\', \'EmailMatch\'],\n                \'common_name\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'name\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'year\': [\'IntMore0\'],\n                \'link\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'ss_qty\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'series_length\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'director\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'producer\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'actor\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'trailer\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'facebook\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'vk\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'instagramm\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'youtube\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'annotation\': [\'Strip\', \'Trim\', \'NEString\'],\n                \'festival\': [\'Strip\', \'Trim\', \'NEString\', \'DefaultEmptyString\'],\n                \'commit\': [\'Boolean\', \'DefaultFalse\']\n            };\n            var error_voc = {\n                \'.a\': \'.a\',\n                \"Filter fails on contact: ValueIsInvalid:NEString\":\"Укажите Ваше имя\",\n                \"Filter fails on email: ValueIsInvalid:NEString\": \"Укажите Ваш email\",\n                \"Filter fails on email: ValueIsInvalid:InvalidEmail\": \"Некорректный email\",\n                \"Filter fails on common_name: ValueIsInvalid:NEString\": \"Укажите наименование произвединия на языке оригинвала\",\n                \"Filter fails on name: ValueIsInvalid:NEString\": \"Укажите название произведения на английском языке\",\n                \"Filter fails on year: ValueIsInvalid:IntMore0\": \"Укажите год выхода\",\n                \"Filter fails on link: ValueIsInvalid:NEString\": \"Укажите ссылку на Ваше произведение\",\n                \"Filter fails on ss_qty: ValueIsInvalid:NEString\": \"Укажите количество серий и сезонов\",\n                \"Filter fails on series_length: ValueIsInvalid:NEString\": \"Укажиет продолжительность серии\",\n                \"Filter fails on annotation: ValueIsInvalid:NEString\": \"Напишите несколько строк о Вашем произведении в поле \\\"аннотация\\\"\",\n                \'.dummy\': \'.dummy\'\n            };\n            function get_data() {\n                var d = {};\n                form.find(\'[data-field]\').each(function () {\n                    var t = jQuery(this);\n                    var N = U.NEString(t.data(\'field\'), null);\n                    if (N) {\n                        if (t.is(\'input[type=checkbox]\')) {\n                            d[N] = t.prop(\'checked\');\n                        } else if (t.is(\'input\') || t.is(\'textarea\')) {\n                            d[N] = t.val();\n                        }\n                    }\n                });\n                return d;\n            }\n\n            function data_filter() {\n                var d = get_data();\n                var cd = EFO.Filter.Filter().applyFiltersToHash(d, filters);\n                EFO.Filter.Filter().throwValuesErrorFirst(cd, true);\n                return cd;\n            }\n\n            function show_error(x) {\n                alert(U.NEString(error_voc[x], x));\n                console.log(x);\n            }\n\n            button.on(\'click\', function (e) {\n                e.stopPropagation();\n                e.preventDefault ? e.preventDefault() : e.returnValue = false;\n                if (button.hasClass(\"loading_now\")) {\n                    return;\n                }\n                debugger;\n                try {\n                    var data = data_filter();\n                } catch (e) {\n                    show_error(e.message);\n                    return;\n                }\n                var form_data = new FormData();\n                var posters = jQuery(\'#posters\').get(0);\n                if (posters.files.length) {\n                    for (var i = 0; i < posters.files.length; i++) {\n                        form_data.append(\'posters[]\', posters.files[i]);\n                    }\n                }\n                var frames = jQuery(\'#frames\').get(0);\n                if (frames.files.length) {\n                    for (var i = 0; i < frames.files.length; i++) {\n                        form_data.append(\'frames[]\', frames.files[i]);\n                    }\n                }\n                form_data.append(\'action\', \'fos\');\n                form_data.append(\'data\', JSON.stringify(data));\n                button.addClass(\"loading_now\");\n                jQuery.ajax({url: \"/Info/API\", processData: false,\n                    contentType: false, data: form_data, dataType: \'json\', method: \'POST\'})\n                        .done(function (d) {\n                            if (d.status === \"ok\") {\n                                alert(\"Ваша заявка зарегистрирована.\\nМы ответим в самое ближайшее время.\");\n                                window.location.href = \"/\";\n                                return;\n                            }\n                            if (d.status === \"error\") {\n                                show_error(d.error_info.message);\n                                return;\n                            }\n                            alert(\"Некорректный отвт сервера\");\n                        })\n                        .fail(function () {\n                            alert(\"Ошибка связи с сервером\");\n                        })\n                        .always(function () {\n                            button.removeClass(\'loading_now\');\n                        });\n            });\n\n\n\n        });\n    })();\n    {/literal}\n</script>'),(8,'slide_on_main',0,'Слайдшоу на главную','<div id=\"slideshow_main_out\">\n  <div class=\"container\">\n    <div class=\"row\">\n      <div class=\"col l10 offset-l1 s12\">\n        <div id=\"slideshow_main\" class=\"owl-carousel\">\n          <div class=\"one_slide_top\">\n            <a href=\"/page/about\">\n              <p>Слайдер с информацией от команды, баннера и т.п.</p>\n            </a>\n          </div>\n          <div class=\"one_slide_top\">\n            <a href=\"/page/help\">\n              <p>Слайдер о помощи</p>\n            </a>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n\n<script>\n  $(\'#slideshow_main\').owlCarousel({\n    loop:true,\n    margin:40,\n    nav:true,\n    dots:false,\n    items:1\n  })\n</script>'),(9,'login_new',0,'Логин chill','<div id=\"bg_bg\"></div>\n<div id=\"login_signup\">\n  <div id=\"close_login_signup\">\n    <i class=\"mdi mdi-close\"></i>\n  </div>\n  <div id=\"login_block\">\n    <div class=\"in_ls_block\">\n      <div class=\"row\">\n        <div class=\"col s12\">\n          <div class=\"in_ls_logo\">\n            <img src=\"/assets/chill/images/logo_black.png\">\n          </div>\n        </div>\n        <div class=\"col s12 l8 offset-l2\">\n          <h3>Вход</h3>\n          <form id=\"login\" onsubmit=\"return false;\">\n            <div class=\"ls_input\">\n              <label for=\"login_email\">Телефон или email</label>\n              <input type=\"text\" placeholder=\"example@chill.com\" id=\"login_username\" name=\"login\">\n            </div>\n            <div class=\"ls_input\">\n              <label for=\"login_pass\">Пароль</label>\n              <input type=\"password\" placeholder=\"*******\" id=\"login_password\" name=\"password\">\n            </div>\n            <div class=\"ls_btn\">\n              <button type=\"submit\" id=\"login_page_login_button\">Войти</button>\n            </div>\n          </form>\n          <!--<div class=\"right-align\" id=\"recover_password\">\n            <a href=\"/\">Забыли пароль?</a>\n          </div>\n          <div id=\"no_acc\">\n            Нет аккаунта? <a>Зарегистрируйтесь</a>\n          </div>\n-->\n        </div>\n      </div>\n      <div id=\"policy\">\n        <div class=\"row\">\n          <div class=\"col s12 l8 offset-l2\">\n            <a href=\"/policy\">\n              Пользовательское соглашение и правила использования\n            </a>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n<script>\n  {literal}\n  jQuery(function(){\n    var form = jQuery(\"#login\");\n    var button = jQuery(\"#login_page_login_button\");\n    var login = form.find(\"#login_username\");\n    var password = form.find(\'#login_password\');\n    button.on(\'click\',function(e){\n      e.preventDefault?e.preventDefault:e.returnValue = false;\n      e.stopPropagation();\n\n      if(button.hasClass(\'loading_now\')){\n        return;\n      } \n      try{\n        var data = {\n          action:\"login\",\n          login:login.val(),\n          password:password.val()\n        } ;\n        if(! /^[^@\\,\\;\\s]{1,}@[^@\\,\\;\\s]{1,}\\.[^@\\.\\,\\;\\s]{1,}$/i.test(data.login)){\n          throw new Error(\"Укажите корректный email\");\n        }\n        if(typeof(data.password) !== \"string\" || data.password.length < 6){\n          throw new Error(\"Указан неверный пароль\");\n        } \n        button.addClass(\'loading_now\');\n        jQuery.post(\'/Auth/API\',data)\n          .done(function(d){\n          if(d.status===\"ok\"){\n            window.location.href=\"/\";\n            return;\n          }\n          if(d.status===\'error\'){\n            alert (d.error_info.message);\n            return;\n          }\n          alert(\"Некорректный ответ сервера\");\n        })\n          .fail(function(){\n          alert(\"Ошибка связи с сервером!\");\n        })\n          .always(function(){\n          button.removeClass(\'loading_now\');\n        })\n      }catch(e){\n        alert(e.message);\n        button.removeClass(\'loading_now\');\n\n      }\n\n    });\n  });\n  {/literal}\n    </script>'),(10,'slide_new',0,'slide_new','<div id=\"slideshow_main_out\">\n  <div class=\"container\">\n    <div class=\"row\">\n      <div class=\"col l10 offset-l1 s12\">\n        <div id=\"slideshow_main\" class=\"owl-carousel\">\n\n          <a href=\"/catalog\" class=\"one_slide_top_a\">\n            <img alt=\"Каталог\" class=\"slide_a_1\">\n          </a>\n          <a href=\"/page/for_authors\" class=\"one_slide_top_a\">\n            <img alt=\"Стать автором\" class=\"slide_a_2\">\n          </a>\n          <a href=\"/newslist\" class=\"one_slide_top_a\">\n            <img alt=\"Новости\" class=\"slide_a_3\" src=\"/assets/chill/images/block_top/3_1.jpg\">\n          </a>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n\n<script>\n  $(\'#slideshow_main\').owlCarousel({\n    loop:true,\n    margin:40,\n    nav:true,\n    dots:false,\n    items:1\n  })\n  $(document).ready(function(){\n    var a=Math.round(Math.random()*3);\n    var b=Math.round(Math.random()*2);\n    image = new Array();\n    image[0]=\"/assets/chill/images/block_top/1_1.jpg\";\n    image[1]=\"/assets/chill/images/block_top/1_2.jpg\";\n    image[2]=\"/assets/chill/images/block_top/1_3.jpg\";\n    image[3]=\"/assets/chill/images/block_top/1_4.jpg\";\n    imag = new Array();\n    imag[0]=\"/assets/chill/images/block_top/2_1.jpg\";\n    imag[1]=\"/assets/chill/images/block_top/2_2.jpg\";\n    imag[2]=\"/assets/chill/images/block_top/2_3.jpg\";\n    $(\".slide_a_1\").attr(\"src\",image[a]);\n    $(\".slide_a_2\").attr(\"src\",imag[b]);\n    console.log(a);\n    console.log(b);\n  });\n</script>'),(11,'video',0,'video','<div id=\"video_first\">\n  <video id=\"video_video_first_h\" muted autoplay playsinline>\n    <source src=\"/assets/chill/images/g_01_h.mp4\" type=\"video/mp4\">\n  </video>\n  <video id=\"video_video_first_v\" muted autoplay playsinline>\n    <source src=\"/assets/chill/images/g_01_v.mp4\" type=\"video/mp4\">\n  </video>\n</div>\n\n<script>\n  $(document).ready(function(){\n    var ww = $(window).width();\n    var wh = $(window).height();\n\n    $(\"body\").css(\"overflow\",\"hidden\");\n    if (ww > wh){\n      $(\"#video_video_first_h\").fadeIn(0).get(0).play();;\n      $(\"#video_video_first_v\").fadeOut(0);\n    }else{\n      $(\"#video_video_first_v\").fadeIn(0).get(0).play();;\n      $(\"#video_video_first_h\").fadeOut(0);\n    }\n    $(\"#video_video_first_v\").on(\'ended\',function(){\n      $(\"#video_first\").fadeOut(500);\n      $(\"body\").css(\"overflow-y\",\"scroll\");\n    });\n    $(\"#video_video_first_h\").on(\'ended\',function(){\n      $(\"#video_first\").fadeOut(500);\n      $(\"body\").css(\"overflow-y\",\"scroll\");\n    });\n  });\n</script>\n<style>\n  div#video_first {\n    position: fixed;\n    z-index: 10;\n    top: 0;\n    left: 0;\n    height: 100%;\n    background-color: #000;\n    width: 100%;\n    display: flex;\n    align-items: center;\n    justify-content: center;\n  }\n\n  #video_first video {\n    width: 100%;\n    height: 100%;\n  }\n</style>'),(12,'restart_vid',0,'restart_vid','<div id=\"test\"></div>');
/*!40000 ALTER TABLE `content_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filterpreset`
--

DROP TABLE IF EXISTS `filterpreset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filterpreset` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(100) NOT NULL DEFAULT '''''',
  `alias` varchar(255) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `active` int(1) unsigned NOT NULL DEFAULT '0',
  `html_mode` int(1) unsigned NOT NULL DEFAULT '1',
  `published` datetime DEFAULT NULL,
  `cost` double NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `default_image` varchar(100) DEFAULT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  UNIQUE KEY `alias` (`alias`),
  KEY `active` (`active`),
  KEY `name` (`name`),
  KEY `published` (`published`),
  KEY `created` (`created`),
  KEY `updated` (`updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filterpreset`
--

LOCK TABLES `filterpreset` WRITE;
/*!40000 ALTER TABLE `filterpreset` DISABLE KEYS */;
/*!40000 ALTER TABLE `filterpreset` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `filterpreset_bi` BEFORE INSERT ON `filterpreset`
 FOR EACH ROW BEGIN
  SET NEW.guid=UUID();
  SET NEW.created = NOW();
  SET NEW.updated = NOW();
  IF(NEW.active=1) THEN
    SET NEW.published=NOW();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `filterpreset_bu` BEFORE UPDATE ON `filterpreset`
 FOR EACH ROW BEGIN
   SET NEW.updated=NOW();
   IF(NEW.active=1 AND OLD.active=0) THEN
    SET NEW.published=NOW();
  END IF;
     IF(NEW.active=0 AND OLD.active=1) THEN
    SET NEW.published=NULL;
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `filterpreset__item`
--

DROP TABLE IF EXISTS `filterpreset__item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filterpreset__item` (
  `id` bigint(19) unsigned NOT NULL,
  `uid` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(1024) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `html_mode` int(1) unsigned NOT NULL DEFAULT '1',
  `info` mediumtext NOT NULL,
  `preset` blob NOT NULL,
  PRIMARY KEY (`id`,`uid`),
  KEY `sort` (`sort`),
  CONSTRAINT `filterpreset__item__2__filterpreset` FOREIGN KEY (`id`) REFERENCES `filterpreset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filterpreset__item`
--

LOCK TABLES `filterpreset__item` WRITE;
/*!40000 ALTER TABLE `filterpreset__item` DISABLE KEYS */;
/*!40000 ALTER TABLE `filterpreset__item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filterpreset__properties`
--

DROP TABLE IF EXISTS `filterpreset__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filterpreset__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `filterpreset__properties_2_filterpreset` FOREIGN KEY (`id`) REFERENCES `filterpreset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filterpreset__properties`
--

LOCK TABLES `filterpreset__properties` WRITE;
/*!40000 ALTER TABLE `filterpreset__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `filterpreset__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filterpreset__qty`
--

DROP TABLE IF EXISTS `filterpreset__qty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filterpreset__qty` (
  `id` bigint(19) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `qty` (`qty`),
  CONSTRAINT `filterpreset__qty_2_filterpreset` FOREIGN KEY (`id`) REFERENCES `filterpreset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filterpreset__qty`
--

LOCK TABLES `filterpreset__qty` WRITE;
/*!40000 ALTER TABLE `filterpreset__qty` DISABLE KEYS */;
/*!40000 ALTER TABLE `filterpreset__qty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__attributes`
--

DROP TABLE IF EXISTS `fitness__attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__attributes` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `measure` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__attributes`
--

LOCK TABLES `fitness__attributes` WRITE;
/*!40000 ALTER TABLE `fitness__attributes` DISABLE KEYS */;
INSERT INTO `fitness__attributes` VALUES (1,'Вес','кг'),(2,'Рост','см'),(3,'Плечи','см'),(4,'Бицепс','см'),(5,'Талия','см');
/*!40000 ALTER TABLE `fitness__attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__package`
--

DROP TABLE IF EXISTS `fitness__package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__package` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` double NOT NULL DEFAULT '1',
  `days` int(11) NOT NULL,
  `usages` int(11) NOT NULL,
  `active` int(1) unsigned NOT NULL DEFAULT '1',
  `default_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `active` (`active`,`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__package`
--

LOCK TABLES `fitness__package` WRITE;
/*!40000 ALTER TABLE `fitness__package` DISABLE KEYS */;
INSERT INTO `fitness__package` VALUES (2,'Арнольд',20000,64,5,1,'8b72422691201b745f10ba9b4f9da478'),(4,'KIM GYM',15000,100,120,1,'877bb0f90edc3a96ab8c845a98cd7d3f'),(5,'Жиротопка',10,500,2,1,'b5672a9e934626558f60762d7d8a0030');
/*!40000 ALTER TABLE `fitness__package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__package__properties`
--

DROP TABLE IF EXISTS `fitness__package__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__package__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `fitness__package__props__2_package` FOREIGN KEY (`id`) REFERENCES `fitness__package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__package__properties`
--

LOCK TABLES `fitness__package__properties` WRITE;
/*!40000 ALTER TABLE `fitness__package__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `fitness__package__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__place__owner`
--

DROP TABLE IF EXISTS `fitness__place__owner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__place__owner` (
  `place_id` bigint(19) unsigned NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`place_id`,`user_id`),
  UNIQUE KEY `place_id` (`place_id`,`user_id`),
  KEY `place_owner_2_user` (`user_id`),
  CONSTRAINT `place_owner_2_place` FOREIGN KEY (`place_id`) REFERENCES `fitness__places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `place_owner_2_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__place__owner`
--

LOCK TABLES `fitness__place__owner` WRITE;
/*!40000 ALTER TABLE `fitness__place__owner` DISABLE KEYS */;
/*!40000 ALTER TABLE `fitness__place__owner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__places`
--

DROP TABLE IF EXISTS `fitness__places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__places` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `address` varchar(1024) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `default_image` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `features` mediumblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lat` (`lat`,`id`),
  UNIQUE KEY `id` (`name`,`id`) USING BTREE,
  KEY `address` (`address`),
  KEY `lon` (`lon`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__places`
--

LOCK TABLES `fitness__places` WRITE;
/*!40000 ALTER TABLE `fitness__places` DISABLE KEYS */;
INSERT INTO `fitness__places` VALUES (3,'Lotte Fitnes','г. Москва, Новинский б-р, 10',37.583469489681,55.750412727765,'a279965ce94447b6e2ddb36eb3a020dc','+7 (900) 900 12 34',_binary '[]'),(4,'Фитнес Прага','г Москва, ул Арбат, д 2/1',37.599558,55.752475,'7066f41f72271b941408f567cad82108','+7 (900) 222 33 11',_binary '[]'),(6,'Yoga Studio','г Москва, ул Зоологическая, д 8',37.582749,55.765771,'5418fcf58d0b1d97c3e1524e0d718b08','+7 (912) 999 99 99',_binary '[]');
/*!40000 ALTER TABLE `fitness__places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__places__properties`
--

DROP TABLE IF EXISTS `fitness__places__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__places__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `fitness_places__properties_2_fitness__places` FOREIGN KEY (`id`) REFERENCES `fitness__places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__places__properties`
--

LOCK TABLES `fitness__places__properties` WRITE;
/*!40000 ALTER TABLE `fitness__places__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `fitness__places__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__trainer__buisy`
--

DROP TABLE IF EXISTS `fitness__trainer__buisy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__trainer__buisy` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `trainer_id` bigint(19) unsigned NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  `place_id` bigint(19) unsigned NOT NULL,
  `place_name` varchar(255) NOT NULL,
  `trainer_name` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `datum` date NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned NOT NULL,
  `state` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'wait/ok/discard',
  PRIMARY KEY (`id`),
  UNIQUE KEY `trainer_id` (`trainer_id`,`id`),
  UNIQUE KEY `user_id` (`user_id`,`id`),
  UNIQUE KEY `trainer_id_2` (`trainer_id`,`datum`,`start`,`state`) USING BTREE,
  KEY `start` (`start`,`end`,`id`) USING BTREE,
  KEY `confirmed` (`state`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__trainer__buisy`
--

LOCK TABLES `fitness__trainer__buisy` WRITE;
/*!40000 ALTER TABLE `fitness__trainer__buisy` DISABLE KEYS */;
INSERT INTO `fitness__trainer__buisy` VALUES (1,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-18',46800,52200,0),(2,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-18',0,3600,0),(4,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-20',180000,183600,0),(5,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-25',46800,52200,0),(6,5,1,4,'Стадион динамо','Заяшников Сергей Иванович','Колбасян Васян','2019-11-27',180000,183600,0),(7,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-28',270000,273600,0),(8,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-25',0,3600,0),(9,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-19',90000,93600,0),(10,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-19',136800,142200,1),(11,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-26',136800,142200,1),(12,5,1,4,'Стадион динамо','Заяшников Сергей Иванович','Колбасян Васян','2019-11-26',90000,93600,1),(13,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-20',226800,232200,1),(14,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-11-27',226800,232200,2),(15,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-12-23',0,3600,0),(16,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-12-23',46800,52200,0),(17,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-12-24',136800,142200,1),(18,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-12-24',90000,93600,0),(19,5,1,3,'Качалка в подвале','Заяшников Сергей Иванович','Колбасян Васян','2019-12-26',316800,322200,0),(20,5,11,4,'Стадион динамо','Иванов Иван Иванович','Покачев Алексей','2019-12-25',226800,232200,0),(21,5,1,4,'Стадион динамо','Иванов Иван Иванович','Колбасян Васян Сервеладович','2019-12-26',270000,273600,0),(22,5,13,4,'Стадион динамо','Иванов Иван Иванович','gdgdgd dgdgdgd','2019-12-13',405000,414000,1),(23,5,1,4,'Фитнес Прага','Иванов Иван Иванович','Петров Александр Петрович','2019-12-28',513000,516600,0),(24,5,15,4,'Фитнес Прага','Иванов Иван Иванович','Покачев Алексей Петрович','2019-12-27',360000,363600,0),(25,5,15,4,'Фитнес Прага','Иванов Иван Иванович','Покачев Алексей Петрович','2019-12-31',90000,93600,0),(26,17,15,6,'Yoga Studio','Покачев Алексей','Покачев Алексей Петрович','2019-12-23',72000,75600,1),(27,17,15,3,'Lotte Fitnes','Покачев Алексей','Покачев Алексей Петрович','2019-12-25',246600,253800,1),(28,5,15,3,'Lotte Fitnes','Иванов Иван Иванович','Покачев Алексей Петрович','2019-12-27',405000,414000,0),(29,17,15,3,'Lotte Fitnes','Покачев Алексей','Покачев Алексей Петрович','2019-12-26',315000,318600,1),(30,5,15,3,'Lotte Fitnes','Иванов Иван Иванович','Покачев Алексей Петрович','2019-12-25',180000,183600,1);
/*!40000 ALTER TABLE `fitness__trainer__buisy` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `fitness_trainer_buisy_bi` BEFORE INSERT ON `fitness__trainer__buisy`
 FOR EACH ROW BEGIN
  SET NEW.state=0;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `fitness__trainer__buisy__before_update` BEFORE UPDATE ON `fitness__trainer__buisy`
 FOR EACH ROW BEGIN
   IF (OLD.state!=0)THEN
     SET NEW.state=OLD.state;
   END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `fitness_trainer_buisy_after_update` AFTER UPDATE ON `fitness__trainer__buisy`
 FOR EACH ROW BEGIN
  IF(OLD.state=0 AND NEW.state=2) THEN
	INSERT INTO user__usages(user_id,usage_count,expires)
    VALUES(NEW.user_id,1,DATE_ADD(NOW(), INTERVAL 3 DAY))
    ON DUPLICATE KEY UPDATE  usage_count=usage_count+VALUES(usage_count);
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `fitness__trainer__hall`
--

DROP TABLE IF EXISTS `fitness__trainer__hall`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__trainer__hall` (
  `trainer_id` bigint(19) unsigned NOT NULL,
  `hall_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`trainer_id`,`hall_id`),
  UNIQUE KEY `hall_id` (`hall_id`,`trainer_id`),
  UNIQUE KEY `trainer_id` (`trainer_id`),
  CONSTRAINT `trainer__hall_2__hall` FOREIGN KEY (`hall_id`) REFERENCES `fitness__places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trainer__hall_2__trainer` FOREIGN KEY (`trainer_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__trainer__hall`
--

LOCK TABLES `fitness__trainer__hall` WRITE;
/*!40000 ALTER TABLE `fitness__trainer__hall` DISABLE KEYS */;
INSERT INTO `fitness__trainer__hall` VALUES (1,4);
/*!40000 ALTER TABLE `fitness__trainer__hall` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__trainer__info`
--

DROP TABLE IF EXISTS `fitness__trainer__info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__trainer__info` (
  `id` bigint(19) unsigned NOT NULL,
  `info` mediumblob NOT NULL,
  `features` mediumblob NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fitness_trainer_info_2_trainer` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__trainer__info`
--

LOCK TABLES `fitness__trainer__info` WRITE;
/*!40000 ALTER TABLE `fitness__trainer__info` DISABLE KEYS */;
/*!40000 ALTER TABLE `fitness__trainer__info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__trainer__interval`
--

DROP TABLE IF EXISTS `fitness__trainer__interval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__trainer__interval` (
  `trainer_id` bigint(19) unsigned NOT NULL,
  `time_id` int(11) unsigned NOT NULL COMMENT 'week__zero__based',
  `length` int(11) unsigned NOT NULL COMMENT 'length in minutes',
  `active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'is interval_active',
  PRIMARY KEY (`trainer_id`,`time_id`) USING BTREE,
  CONSTRAINT `fitness__interval__2__trainer` FOREIGN KEY (`trainer_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__trainer__interval`
--

LOCK TABLES `fitness__trainer__interval` WRITE;
/*!40000 ALTER TABLE `fitness__trainer__interval` DISABLE KEYS */;
/*!40000 ALTER TABLE `fitness__trainer__interval` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__user__attributes`
--

DROP TABLE IF EXISTS `fitness__user__attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__user__attributes` (
  `user_id` bigint(19) unsigned NOT NULL,
  `attribute_id` bigint(19) unsigned NOT NULL,
  `value` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`attribute_id`),
  KEY `fua_2_a` (`attribute_id`),
  CONSTRAINT `fua_2_a` FOREIGN KEY (`attribute_id`) REFERENCES `fitness__attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fua_2_u` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__user__attributes`
--

LOCK TABLES `fitness__user__attributes` WRITE;
/*!40000 ALTER TABLE `fitness__user__attributes` DISABLE KEYS */;
INSERT INTO `fitness__user__attributes` VALUES (1,1,99),(1,2,43),(1,3,99),(1,4,100500);
/*!40000 ALTER TABLE `fitness__user__attributes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `fitness_user_attributes_au` AFTER UPDATE ON `fitness__user__attributes`
 FOR EACH ROW BEGIN
  INSERT INTO fitness__user__attributes_history(user_id,attribute_id,`value`,datum)
  VALUES(OLD.user_id,OLD.attribute_id,OLD.`value`,NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `fitness__user__attributes_history`
--

DROP TABLE IF EXISTS `fitness__user__attributes_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__user__attributes_history` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `attribute_id` bigint(19) unsigned NOT NULL,
  `value` double NOT NULL DEFAULT '0',
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`,`user_id`,`attribute_id`),
  UNIQUE KEY `user_id` (`user_id`,`id`,`attribute_id`),
  UNIQUE KEY `uah_2_a` (`attribute_id`,`user_id`,`id`) USING BTREE,
  CONSTRAINT `auh_2_u` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `uah_2_a` FOREIGN KEY (`attribute_id`) REFERENCES `fitness__attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__user__attributes_history`
--

LOCK TABLES `fitness__user__attributes_history` WRITE;
/*!40000 ALTER TABLE `fitness__user__attributes_history` DISABLE KEYS */;
INSERT INTO `fitness__user__attributes_history` VALUES (1,1,1,1000,'2019-12-22 01:26:18'),(2,1,1,99,'2019-12-22 01:26:22'),(3,1,1,99,'2019-12-22 01:26:25'),(4,1,2,44,'2019-12-22 02:47:29'),(5,1,2,43,'2019-12-22 02:47:33');
/*!40000 ALTER TABLE `fitness__user__attributes_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fitness__user__order`
--

DROP TABLE IF EXISTS `fitness__user__order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fitness__user__order` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `package_id` bigint(19) unsigned DEFAULT NULL,
  `package_name` varchar(255) NOT NULL,
  `cost` double NOT NULL,
  `usages` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`id`),
  KEY `package_id` (`package_id`,`id`),
  CONSTRAINT `fitness_user__order_2_package` FOREIGN KEY (`package_id`) REFERENCES `fitness__package` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fitness_user__order_2_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fitness__user__order`
--

LOCK TABLES `fitness__user__order` WRITE;
/*!40000 ALTER TABLE `fitness__user__order` DISABLE KEYS */;
INSERT INTO `fitness__user__order` VALUES (1,1,NULL,'basic',500,30,'2019-11-13 00:00:00','2019-11-27 00:00:00'),(3,1,NULL,'Basic',1500,12,'2019-11-13 16:31:04','2019-12-14 16:31:04'),(4,1,NULL,'Basic',1500,12,'2019-11-13 16:34:49','2019-12-14 16:34:49'),(5,1,4,'Супермен',15000,120,'2019-11-13 16:35:03','2020-02-21 16:35:03'),(6,1,5,'Дрищ',10,2,'2019-11-13 16:35:09','2021-03-27 16:35:09'),(9,1,NULL,'Basic',1500,12,'2019-12-22 17:24:34','2020-01-22 17:24:34'),(12,1,2,'Арнольд',20000,5,'2019-12-22 17:28:39','2020-02-24 17:28:39'),(13,1,2,'Арнольд',20000,5,'2019-12-22 17:33:23','2020-02-24 17:33:23'),(14,1,4,'KIM GYM',15000,120,'2019-12-22 17:36:10','2020-03-31 17:36:10'),(15,1,4,'KIM GYM',15000,120,'2019-12-22 17:36:37','2020-03-31 17:36:37'),(16,1,2,'Арнольд',20000,5,'2019-12-22 17:36:48','2020-02-24 17:36:48'),(17,1,2,'Арнольд',20000,5,'2019-12-22 17:41:45','2020-02-24 17:41:45'),(18,1,2,'Арнольд',20000,5,'2019-12-22 17:42:06','2020-02-24 17:42:06'),(19,1,2,'Арнольд',20000,5,'2019-12-22 17:42:11','2020-02-24 17:42:11'),(20,1,2,'Арнольд',20000,5,'2019-12-22 17:42:11','2020-02-24 17:42:11');
/*!40000 ALTER TABLE `fitness__user__order` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `fitness__ordr__ai` AFTER INSERT ON `fitness__user__order`
 FOR EACH ROW BEGIN
  INSERT INTO user__usages(user_id,usage_count,expires)
  VALUES(NEW.user_id,NEW.usages,NEW.expires)
  ON DUPLICATE KEY UPDATE usage_count=usage_count+VALUES(usage_count),
  user__usages.expires=NEW.expires;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `guid` varchar(80) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(1) unsigned NOT NULL DEFAULT '1',
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  UNIQUE KEY `guid` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagefly__aspect_preset`
--

DROP TABLE IF EXISTS `imagefly__aspect_preset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagefly__aspect_preset` (
  `context` varchar(100) NOT NULL,
  `owner_id` varchar(100) NOT NULL,
  `image` varchar(32) NOT NULL,
  `preset` varchar(25) NOT NULL,
  `csx` double DEFAULT NULL,
  `csy` double DEFAULT NULL,
  `cex` double DEFAULT NULL,
  `cey` double DEFAULT NULL,
  PRIMARY KEY (`context`,`owner_id`,`image`,`preset`),
  CONSTRAINT `imagefly__aspect__preset_2__imagefly_image` FOREIGN KEY (`context`, `owner_id`, `image`) REFERENCES `imagefly__images` (`context`, `owner_id`, `image`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagefly__aspect_preset`
--

LOCK TABLES `imagefly__aspect_preset` WRITE;
/*!40000 ALTER TABLE `imagefly__aspect_preset` DISABLE KEYS */;
INSERT INTO `imagefly__aspect_preset` VALUES ('fallback','1','media_content_poster','hposter',-0.22088913690476,23.123604910714,99.77794828869,75.68359375),('fallback','1','media_content_poster','sq',0,0,100,100),('fallback','1','media_content_poster','vposter',19.496372767857,6.7545572916667,81.624348958333,95.023018973214),('fallback','1','media_lent','hposter',-29.230769230769,-9.3269230769231,136.15384615385,115.09615384615),('fallback','1','media_lent','sq',0,-0.096153846153846,102.30769230769,102.21153846154),('fallback','1','media_lent','vposter',-10,-27.019230769231,111.53846153846,134.51923076923),('media_content_poster','109','3fa05f536fdf4060eae09e4c5db07dbf','hposter',-0.71194556451613,5.079048463357,99.288054435484,82.160534869976),('media_content_poster','109','3fa05f536fdf4060eae09e4c5db07dbf','sq',14.610635080645,0.94193262411348,78.723538306452,94.913563829787),('media_content_poster','109','3fa05f536fdf4060eae09e4c5db07dbf','vposter',0,0,100,100),('media_content_poster','110','f19f22de72396fb48478b37bec27cd28','hposter',0.077097039473684,21.710526315789,100.07709703947,74.229029605263),('media_content_poster','110','f19f22de72396fb48478b37bec27cd28','sq',2.3797286184211,3.6184210526316,98.432360197368,99.671052631579),('media_content_poster','111','ecedc8d82374911e57c02713b15a38fb','hposter',-0.19140625,8.4,99.80859375,92.30625),('media_content_poster','111','ecedc8d82374911e57c02713b15a38fb','sq',0.05859375,0,61.55859375,98.4),('media_content_poster','112','7e787bac33c37a8e9b0ce36d2442a79b','hposter',-0.12234340044743,-0.31446540880503,96.633808724832,57.085298742138),('media_content_poster','112','7e787bac33c37a8e9b0ce36d2442a79b','sq',12.341390520134,0,99.589712667785,98.11320754717),('media_content_poster','113','0211003e05f76aee2b67a6c3c818e2e3','hposter',0.065104166666667,0,83.676215277778,98.80859375),('media_content_poster','113','0211003e05f76aee2b67a6c3c818e2e3','sq',15.620659722222,0.625,58.120659722222,96.25),('media_content_poster','114','846e75c194dfc089183c2550c337bda7','hposter',-0.03498460677302,1.5300445103858,97.362160649314,91.849035608309),('media_content_poster','114','846e75c194dfc089183c2550c337bda7','sq',17.597257206829,0.54092482690406,72.593059054016,97.721933728981),('media_content_poster','115','edbea8a0bcc204ac25ce561d30687e1a','hposter',3.30859375,6.8888888888889,99.05859375,96.173611111111),('media_content_poster','115','edbea8a0bcc204ac25ce561d30687e1a','sq',24.55859375,2.8888888888889,79.55859375,100.66666666667),('media_content_poster','116','b144e0ffb6b9d9c5daa01796b68aa086','hposter',0.0390625,13.241591647912,100.0390625,91.808108277069),('media_content_poster','116','b144e0ffb6b9d9c5daa01796b68aa086','sq',12.5390625,0.73846586646662,77.2890625,97.887753188297),('media_content_poster','117','e80b66f9dc13fec3d6a198ecbf68b4e2','hposter',0.040690104166667,0.086805555555556,97.957356770833,91.25675154321),('media_content_poster','117','e80b66f9dc13fec3d6a198ecbf68b4e2','sq',22.71728515625,4.1666666666667,75.321451822917,97.685185185185),('media_content_poster','118','d4847e2a1f045d5b0bfa4d5db07bf689','hposter',0.88763575605681,4.6218487394958,97.378863826232,72.715336134454),('media_content_poster','118','d4847e2a1f045d5b0bfa4d5db07bf689','sq',13.75182748538,3.5014005602241,84.553832497911,98.459383753501),('media_content_poster','118','d4847e2a1f045d5b0bfa4d5db07bf689','vposter',0,0,100,100),('media_content_poster','119','129fed56e9b24d5f47b4a09bd118993e','hposter',0,0,100,100),('media_content_poster','119','129fed56e9b24d5f47b4a09bd118993e','sq',26.166373239437,2.0042539267016,75.931631455399,94.499890924956),('media_content_poster','120','fe034a8a413dabeac8865160fad72f40','hposter',1.2534981343284,1.3205713319811,84.742304104478,96.739773495605),('media_content_poster','120','fe034a8a413dabeac8865160fad72f40','sq',18.977378731343,2.6728363759297,60.488572761194,92.9365280595),('media_content_poster','128','fe9b101022f8da84fc896d5eb7d1e3ea','hposter',0,14.741443452381,100.22321428571,84.788876488095),('media_content_poster','128','fe9b101022f8da84fc896d5eb7d1e3ea','sq',13.839285714286,-0.13950892857143,88.392857142857,99.26525297619),('media_content_poster','128','fe9b101022f8da84fc896d5eb7d1e3ea','vposter',0,0,100,100),('media_content_poster','131','fb99e288e5fbde6e84e249847e7d1ff7','hposter',0.34040178571429,24.326046137339,98.5546875,63.090967006438),('media_content_poster','131','fb99e288e5fbde6e84e249847e7d1ff7','sq',-0.016741071428571,14.401153433476,99.983258928571,89.508449570815),('media_content_poster','131','fb99e288e5fbde6e84e249847e7d1ff7','vposter',6.4118303571429,0.45265557939914,99.626116071429,100.0494568133),('media_content_poster','132','05e3c5ece969e2b560e2717af1144179','hposter',0.69754464285714,26.740209227468,99.626116071429,65.785944206009),('media_content_poster','132','05e3c5ece969e2b560e2717af1144179','sq',-0.016741071428571,3.1350590128755,99.983258928571,78.242355150215),('media_content_poster','132','05e3c5ece969e2b560e2717af1144179','vposter',5.6975446428571,-0.083825107296137,99.268973214286,99.894380364807),('media_content_poster','133','0280ad9ee46e9243e3df3d47d226a82b','sq',0,0,100,100),('media_content_poster','133','0280ad9ee46e9243e3df3d47d226a82b','vposter',6.0546875,-0.083825107296137,99.268973214286,99.512976126609),('media_content_poster','134','7316f1a0aa229b742e14cd8ab2186ce9','vposter',4.6261160714286,-0.083825107296137,98.197544642857,99.894380364807),('media_content_poster','135','aca473978521a6ff6a6e8cf2f3862f68','vposter',6.0546875,0.1844152360515,98.911830357143,99.395620976395),('media_content_poster','136','08b827689287d3da9ace410a6013da77','vposter',5.3404017857143,-0.083825107296137,98.911830357143,99.894380364807),('media_content_poster','137','cc6413d7db42805f22c0745f111a2f53','vposter',6.4118303571429,0.1844152360515,99.268973214286,99.395620976395),('media_content_poster','138','85a79b4fddfa5c7e3a59fad548a2a0ac','vposter',5.6975446428571,0.1844152360515,98.911830357143,99.781216469957),('media_content_poster','139','891e7f283b2b7452a72d74c790771452','vposter',5.3404017857143,0.1844152360515,98.5546875,99.781216469957),('media_content_poster','140','6013f77076031cf54774b430a8d7a77c','vposter',6.0546875,-0.083825107296137,99.626116071429,99.894380364807),('media_content_poster','141','fafbdc12bc537e9f07950565098729b2','vposter',6.4118303571429,-0.083825107296137,99.626116071429,99.512976126609),('media_content_poster','142','dbfca9d6c11032834b1c93c417951297','vposter',4.6261160714286,-0.083825107296137,98.197544642857,99.894380364807),('media_content_poster','143','408c3dd89b2b2459b4c9c7c26c974425','hposter',-0.016741071428571,5.25,99.983258928571,42.03125),('media_content_poster','143','408c3dd89b2b2459b4c9c7c26c974425','sq',-0.016741071428571,0,91.411830357143,64),('media_content_poster','143','408c3dd89b2b2459b4c9c7c26c974425','vposter',-0.016741071428571,0,97.483258928571,97.1015625),('media_content_poster','144','ede2e226b172d4861a6cd433c5f828fc','hposter',0.34040178571429,5.5,99.268973214286,41.890625),('media_content_poster','144','ede2e226b172d4861a6cd433c5f828fc','sq',-0.016741071428571,0,91.411830357143,64),('media_content_poster','145','aa2058950d58e5dbfa7413a11a5f2198','hposter',-0.016741071428571,6,99.983258928571,42.78125),('media_content_poster','145','aa2058950d58e5dbfa7413a11a5f2198','sq',-0.016741071428571,0,90.340401785714,63.25),('media_content_poster','145','aa2058950d58e5dbfa7413a11a5f2198','vposter',-0.016741071428571,0,98.5546875,98.16796875),('media_content_poster','146','d70c3d220b07a2a88f18239a9c9f6d3f','hposter',0.69754464285714,6,98.911830357143,42.12890625),('media_content_poster','146','d70c3d220b07a2a88f18239a9c9f6d3f','sq',-0.016741071428571,0,90.697544642857,63.5),('media_content_poster','146','d70c3d220b07a2a88f18239a9c9f6d3f','vposter',-0.016741071428571,0,98.911830357143,98.52734375),('media_content_poster','147','5bb37d7b8c914601ac879354bf29ae25','hposter',-0.016741071428571,6.25,99.983258928571,43.03125),('media_content_poster','147','5bb37d7b8c914601ac879354bf29ae25','sq',-0.016741071428571,0,90.340401785714,63.25),('media_content_poster','147','5bb37d7b8c914601ac879354bf29ae25','vposter',-0.016741071428571,0,96.0546875,95.67578125),('media_content_poster','148','2e5b860264315cf497c044c66acf0e47','hposter',-0.016741071428571,6.5,99.983258928571,43.28125),('media_content_poster','148','2e5b860264315cf497c044c66acf0e47','sq',-0.016741071428571,0,90.697544642857,63.5),('media_content_poster','148','2e5b860264315cf497c044c66acf0e47','vposter',-0.016741071428571,0,97.840401785714,97.45703125),('media_content_poster','149','1cb53827d1a6cb5d8811645bbc8c9b9a','hposter',-0.016741071428571,6.25,99.983258928571,43.03125),('media_content_poster','149','1cb53827d1a6cb5d8811645bbc8c9b9a','sq',-0.016741071428571,0,90.697544642857,63.5),('media_content_poster','149','1cb53827d1a6cb5d8811645bbc8c9b9a','vposter',-0.016741071428571,0,98.197544642857,97.8125),('media_content_poster','150','5a2306a91ccaa0376a7dd358600d8483','hposter',-0.016741071428571,7.25,99.983258928571,44.03125),('media_content_poster','150','5a2306a91ccaa0376a7dd358600d8483','sq',-0.016741071428571,0,90.340401785714,63.25),('media_content_poster','150','5a2306a91ccaa0376a7dd358600d8483','vposter',-0.016741071428571,0,97.840401785714,97.45703125),('media_content_poster','151','eb5ba49a9f3f16d33236a8bd1ff14854','hposter',8.7270066889632,-0.3647113022113,92.339046822742,96.994011056511),('media_content_poster','151','eb5ba49a9f3f16d33236a8bd1ff14854','none',10.730211817168,2.7149884648054,91.44370122631,92.149877899695),('media_content_poster','151','eb5ba49a9f3f16d33236a8bd1ff14854','sq',29.351309921962,0.86378992628993,73.386984392419,97.915386977887),('media_content_poster','151','eb5ba49a9f3f16d33236a8bd1ff14854','vposter',36.040273132664,-0.3647113022113,67.812848383501,98.433660933661),('media_content_poster','158','7535a76d0858436913ffbf362994e473','hposter',0.18197791164659,17.546824919872,100.18197791165,70.005759214744),('media_content_poster','158','7535a76d0858436913ffbf362994e473','sq',0.18197791164659,1.5211838942308,97.370732931727,98.476312099359),('media_content_poster','175','c0c133008fc8cf94b3b3ab845b53cb0c','hposter',0,0,100,100),('media_content_poster','188','6ba67b93f717ed359f863cfcdab75e9d','hposter',2.5654635527247,33.5703125,97.045293701345,68.65625),('media_content_poster','188','6ba67b93f717ed359f863cfcdab75e9d','sq',0.088464260438783,0.0703125,95.629865534324,67.5703125),('media_content_poster','188','6ba67b93f717ed359f863cfcdab75e9d','vposter',0.088464260438783,0.0703125,98.460721868365,98.953125),('media_content_poster','190','e26187487e3dc401e209a182e9b03e3b','vposter',0.088464260438783,0.0703125,98.460721868365,98.953125),('media_content_poster','191','67857f89076906d2569ae10be156079d','vposter',0.088464260438783,0.0703125,98.81457891012,99.30859375),('media_content_poster','192','c663fc07ed841f360eecc143f4095600','vposter',0.088464260438783,0.0703125,97.753007784855,98.23828125),('media_content_poster','193','80ccbf3c424f801992896d92dfb80223','vposter',0.088464260438783,0.0703125,98.460721868365,98.953125),('media_content_poster','195','ad0c9d6e4e401bee6f3cf75322f50ca5','hposter',0,0,100,100),('media_content_poster','195','ad0c9d6e4e401bee6f3cf75322f50ca5','none',0,0,100,100),('media_content_poster','210','1c25144a08ad57b6f1189d1e26ddb093','sq',0,-0.0546875,99.25,99.1953125),('media_content_poster','214','c08e6d951fac8e0d3ed01daae9b1ec14','none',0,0,100,100),('media_content_poster','214','c08e6d951fac8e0d3ed01daae9b1ec14','vposter',0,0,100,100),('media_content_poster','216','345e5d244f021aa8e1a71afd4d6d6d36','hposter',0,27.196662808642,59.895833333333,82.933063271605),('media_content_poster','217','902571664a80d9d83de7e842145f963e','none',0,0,100,100),('media_content_poster','217','902571664a80d9d83de7e842145f963e','vposter',0,0,100,100),('media_content_poster','219','3597ccec9c4c48ba3faa4364ed007bc1','sq',0,0,100,100),('media_content_poster','221','eb89d71f5c69390b0c374be5494e7ca2','sq',0.12987531969309,6.1216651678657,97.955962276215,97.848283872902),('media_content_poster','51','886f21d35a02f3e61dfcf0911c841ff6','hposter',2.7416191155492,31.166666666667,97.130587256301,65.8671875),('media_content_poster','51','886f21d35a02f3e61dfcf0911c841ff6','sq',1.220354850214,5,96.441467546362,71.75),('media_content_poster','51','886f21d35a02f3e61dfcf0911c841ff6','vposter',2.5521576319544,3,98.486537089872,98.671875),('media_content_poster','52','a1fdbbe31fb497e59fe462148989db93','hposter',-0.20617867332382,30.5,99.79047788873,67.33203125),('media_content_poster','52','a1fdbbe31fb497e59fe462148989db93','sq',0.50708808844508,9.5,98.937901212553,78.5),('media_content_poster','56','7363f887624f8c154e147dd789d5a087','hposter',0,0,100,100),('media_content_poster','56','7363f887624f8c154e147dd789d5a087','vposter',0.12630765572991,0.83333333333333,98.794876367095,99.3125),('media_content_poster','57','45ae95f9bf015afc3098157b79a98f6e','vposter',-0.11144793152639,0.33333333333333,99.270387541607,99.5234375),('media_content_poster','58','94efda82a21081d421d4d0ba6064e9b3','vposter',-0.11144793152639,0.33333333333333,99.270387541607,99.5234375),('media_content_poster','59','41343aa72d7ad98eeeabffa45c4338a6','vposter',-0.11144793152639,0.5,99.508143128864,99.9296875),('media_content_poster','60','c573ea87ff52bace455a38b21ba2341d','vposter',-0.11144793152639,0.33333333333333,99.508143128864,99.763020833333),('media_content_poster','66','6dfcaa8d824f3aa25ffbaa04be06a9c1','hposter',0.043432939541348,-0.058667083854819,99.070535093815,93.642443679599),('media_content_poster','66','6dfcaa8d824f3aa25ffbaa04be06a9c1','sq',38.959346768589,-0.058667083854819,91.773801250869,95.060231539424),('media_content_poster','66','6dfcaa8d824f3aa25ffbaa04be06a9c1','vposter',58.887827194811,-0.065185648727576,97.803741023859,99.512411347518),('media_content_poster','68','e26d0dba6d60d6d99d6ed46d68efa571','hposter',-0.05074228717235,1.2019230769231,99.947807933194,94.667597187758),('media_content_poster','68','e26d0dba6d60d6d99d6ed46d68efa571','sq',25.465379262352,0.37479321753515,80.904952447228,99.216811414392),('media_content_poster','68','e26d0dba6d60d6d99d6ed46d68efa571','vposter',25.929308745071,-0.038771712158809,64.899385293435,98.674007444169),('media_content_poster','69','e371b4456c9507dfedf3d11bcee94c5d','sq',25.929308745071,0.37479321753515,81.136917188587,98.803246484698),('media_content_poster','69','e371b4456c9507dfedf3d11bcee94c5d','vposter',26.857167710508,0.37479321753515,65.595279517513,98.499534739454),('media_content_poster','70','9007d53757b1adf79df8ce8bd33704d5','hposter',-0.013020833333333,8.7288951421801,99.361979166667,45.7981585703),('media_content_poster','70','9007d53757b1adf79df8ce8bd33704d5','sq',0.048828125,9.478672985782,100.048828125,80.568720379147),('media_content_poster','70','9007d53757b1adf79df8ce8bd33704d5','vposter',4.2708333333333,3.5946139415482,96.770833333333,97.187253159558),('media_content_poster','72','f5ea7b387e2a8794c5675a252dddabc0','hposter',0,0,100,100),('media_content_poster','72','f5ea7b387e2a8794c5675a252dddabc0','vposter',0.065104166666667,0.095650671406003,97.842881944444,99.038556477093),('media_content_poster','74','ebc1a0711d9e1bdf0f0c8053b26f4714','vposter',0.065104166666667,0.095650671406003,98.3984375,99.600118483412),('media_content_poster','75','b184d78f3131b14153a99f6e07a067f8','vposter',0.065104166666667,0.095650671406003,98.676215277778,99.880899486572),('media_content_poster','76','ba439f594342c6c75befcb110737e489','vposter',0.065104166666667,0.095650671406003,98.676215277778,99.880899486572),('media_content_poster','77','b3de8744dfc70e3fa97e750aa04c592c','vposter',0.065104166666667,0.095650671406003,98.953993055556,100.16476599526),('media_content_poster','78','625c36a514e0879082ec59e9ed76b5bb','hposter',0.51175958188153,2.5619017894299,99.814895470383,95.830732417811),('media_content_poster','78','625c36a514e0879082ec59e9ed76b5bb','sq',36.144381533101,3.3551810237203,89.454486062718,98.860799001248),('media_content_poster','78','625c36a514e0879082ec59e9ed76b5bb','vposter',49.390243902439,0.89731585518102,88.182346109175,99.634571369122),('media_content_poster','80','cb722eb03f187d026b1f29d99d861b48','vposter',14.608739837398,1.3777297074578,75.953898743533,98.546302018953),('media_content_poster','81','a6a34fb0084b71547b6bac34cbbf1b63','vposter',14.239190687361,0.96569839307787,76.692997043607,99.898279769262),('media_content_poster','82','5df9aeeec57e40e138a99ee66495c916','vposter',14.239190687361,0.55366707869798,76.692997043607,99.486248454883),('media_content_poster','83','c76c623b857db56dec0944178ca70da2','vposter',15.717387287509,0.14163576431809,78.910291943829,100.25236918006),('media_content_poster','84','0b716077ab95a5f7e32784fa19441944','vposter',27.553061934586,0.37479321753515,66.75510322431,99.675610008271),('media_content_poster','85','5fa28ac37b4dd35f1cb70672c7f53386','vposter',27.553061934586,0.37479321753515,66.75510322431,99.675610008271),('media_content_poster','86','14afa0bdeda5f3e2bd39d3773a8be4af','vposter',27.321097193227,1.2019230769231,66.291173741591,99.914702233251),('media_content_poster','87','f3c5b54e1e3a6467dec912a7f9743a80','vposter',27.089132451867,-0.038771712158809,66.291173741591,99.262045078577),('media_content_poster','88','ba5dbaece7d323afb8333d0de4c95f48','vposter',27.785026675945,1.2019230769231,66.75510322431,99.914702233251),('media_content_poster','89','19cac5890fd6516e83ec8ca535a5f5b1','vposter',27.785026675945,0.37479321753515,66.987067965669,99.675610008271),('media_content_poster','90','0838d4461998a543736195556ece51ab','vposter',27.785026675945,-0.45233664185277,66.987067965669,98.848480148883),('media_content_poster','91','455ff4f7945ecd18483561826a66f8e7','vposter',27.089132451867,0.37479321753515,66.523138482951,100.26364764268),('media_content_trailer','130','885d6e206c895a6ed0d6feb1453c7b36','hposter',-0.016276041666667,-0.13761856368564,96.511501736111,98.831300813008),('media_content_trailer','130','885d6e206c895a6ed0d6feb1453c7b36','sq',22.900390625,1.2173949864499,71.858723958333,96.745850271003),('media_content_trailer','130','885d6e206c895a6ed0d6feb1453c7b36','vposter',29.844835069444,1.8949017615176,63.872612847222,95.983655149051),('media_content_trailer','203','bb6811eaade8820141fd35817d7173c5','sq',26.25,3.984375,74.166666666667,99.817708333333),('media_content_trailer','73','0ab0cf5ab62a3daece1514bfe5b4f033','sq',8.98828125,1.7172261815454,71.48828125,95.490669542386),('media_studio','10','d48c620d9cbf11e2a0870abe16aeb22b','none',0,0,100,100),('media_studio','2','d7e0e610921221ab5881abc790d82744','hposter',47.916666666667,-13.676427738928,112.08333333333,53.92263986014),('media_studio','2','d7e0e610921221ab5881abc790d82744','none',62.916666666667,-0.27316433566434,100.83333333333,45.764131701632),('media_studio','2','d7e0e610921221ab5881abc790d82744','sq',58.75,-4.935168997669,102.91666666667,56.836392773893),('media_studio','2','d7e0e610921221ab5881abc790d82744','vposter',55.416666666667,-17.755681818182,100,64.994900932401);
/*!40000 ALTER TABLE `imagefly__aspect_preset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagefly__colors`
--

DROP TABLE IF EXISTS `imagefly__colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagefly__colors` (
  `image` varchar(48) NOT NULL,
  `crop_start_x` double DEFAULT NULL,
  `crop_start_y` double DEFAULT NULL,
  `crop_end_x` double DEFAULT NULL,
  `crop_end_y` double DEFAULT NULL,
  `doc` int(19) NOT NULL,
  PRIMARY KEY (`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagefly__colors`
--

LOCK TABLES `imagefly__colors` WRITE;
/*!40000 ALTER TABLE `imagefly__colors` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagefly__colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagefly__images`
--

DROP TABLE IF EXISTS `imagefly__images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagefly__images` (
  `context` varchar(100) NOT NULL,
  `owner_id` varchar(100) NOT NULL,
  `image` varchar(64) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `crop_start_x` double DEFAULT NULL,
  `crop_start_y` double DEFAULT NULL,
  `crop_end_x` double DEFAULT NULL,
  `crop_end_y` double DEFAULT NULL,
  `title` varchar(1024) DEFAULT NULL,
  `doc` int(19) NOT NULL,
  PRIMARY KEY (`context`,`owner_id`,`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagefly__images`
--

LOCK TABLES `imagefly__images` WRITE;
/*!40000 ALTER TABLE `imagefly__images` DISABLE KEYS */;
INSERT INTO `imagefly__images` VALUES ('age_restriction','1','38f22861713b138fd84ddfb18187ed54',0,NULL,NULL,NULL,NULL,NULL,1585432050),('avatar','1','aaca0f5eb4d2d98a6ce6dffa99f8254b',0,NULL,NULL,NULL,NULL,NULL,1587372343),('avatar','31','aaca0f5eb4d2d98a6ce6dffa99f8254b',0,NULL,NULL,NULL,NULL,NULL,1587381590),('avatar','32','aaca0f5eb4d2d98a6ce6dffa99f8254b',0,NULL,NULL,NULL,NULL,NULL,1587742146),('avatar','33','aaca0f5eb4d2d98a6ce6dffa99f8254b',0,NULL,NULL,NULL,NULL,NULL,1587856136),('fallback','1','age_restriction',0,NULL,NULL,NULL,NULL,NULL,1586976303),('fallback','1','avatar',0,NULL,NULL,NULL,NULL,NULL,1586976300),('fallback','1','fallback',0,NULL,NULL,NULL,NULL,NULL,1586976301),('fallback','1','media_content_frame',0,NULL,NULL,NULL,NULL,NULL,1586976296),('fallback','1','media_content_poster',0,NULL,NULL,NULL,NULL,NULL,1586976294),('fallback','1','media_content_trailer',0,NULL,NULL,NULL,NULL,NULL,1586976292),('fallback','1','media_lent',0,NULL,NULL,NULL,NULL,NULL,1586976276),('fallback','1','media_person',0,NULL,NULL,NULL,NULL,NULL,1586976279),('fallback','1','media_studio',0,NULL,NULL,NULL,NULL,NULL,1586976282),('fallback','1','social_fallback',0,NULL,NULL,NULL,NULL,NULL,1586976285),('media_content_poster','109','3fa05f536fdf4060eae09e4c5db07dbf',0,NULL,NULL,NULL,NULL,NULL,1587114604),('media_content_poster','110','f19f22de72396fb48478b37bec27cd28',0,NULL,NULL,NULL,NULL,NULL,1587114710),('media_content_poster','111','ecedc8d82374911e57c02713b15a38fb',0,NULL,NULL,NULL,NULL,NULL,1587114836),('media_content_poster','112','7e787bac33c37a8e9b0ce36d2442a79b',0,NULL,NULL,NULL,NULL,NULL,1587114910),('media_content_poster','113','0211003e05f76aee2b67a6c3c818e2e3',0,NULL,NULL,NULL,NULL,NULL,1587115007),('media_content_poster','114','846e75c194dfc089183c2550c337bda7',0,NULL,NULL,NULL,NULL,NULL,1587115118),('media_content_poster','115','edbea8a0bcc204ac25ce561d30687e1a',0,NULL,NULL,NULL,NULL,NULL,1587116274),('media_content_poster','116','b144e0ffb6b9d9c5daa01796b68aa086',0,NULL,NULL,NULL,NULL,NULL,1587116433),('media_content_poster','117','e80b66f9dc13fec3d6a198ecbf68b4e2',0,NULL,NULL,NULL,NULL,NULL,1587116503),('media_content_poster','118','d4847e2a1f045d5b0bfa4d5db07bf689',0,NULL,NULL,NULL,NULL,NULL,1587116756),('media_content_poster','119','129fed56e9b24d5f47b4a09bd118993e',0,NULL,NULL,NULL,NULL,NULL,1587117020),('media_content_poster','120','fe034a8a413dabeac8865160fad72f40',0,NULL,NULL,NULL,NULL,NULL,1587117105),('media_content_poster','128','fe9b101022f8da84fc896d5eb7d1e3ea',0,NULL,NULL,NULL,NULL,NULL,1587369671),('media_content_poster','131','fb99e288e5fbde6e84e249847e7d1ff7',0,NULL,NULL,NULL,NULL,NULL,1587492270),('media_content_poster','132','05e3c5ece969e2b560e2717af1144179',0,NULL,NULL,NULL,NULL,NULL,1587492539),('media_content_poster','133','0280ad9ee46e9243e3df3d47d226a82b',0,NULL,NULL,NULL,NULL,NULL,1587492597),('media_content_poster','134','7316f1a0aa229b742e14cd8ab2186ce9',0,NULL,NULL,NULL,NULL,NULL,1587492965),('media_content_poster','135','aca473978521a6ff6a6e8cf2f3862f68',0,NULL,NULL,NULL,NULL,NULL,1587493345),('media_content_poster','136','08b827689287d3da9ace410a6013da77',0,NULL,NULL,NULL,NULL,NULL,1587493376),('media_content_poster','137','cc6413d7db42805f22c0745f111a2f53',0,NULL,NULL,NULL,NULL,NULL,1587493401),('media_content_poster','138','85a79b4fddfa5c7e3a59fad548a2a0ac',0,NULL,NULL,NULL,NULL,NULL,1587493431),('media_content_poster','139','891e7f283b2b7452a72d74c790771452',0,NULL,NULL,NULL,NULL,NULL,1587493453),('media_content_poster','140','6013f77076031cf54774b430a8d7a77c',0,NULL,NULL,NULL,NULL,NULL,1587493478),('media_content_poster','141','fafbdc12bc537e9f07950565098729b2',0,NULL,NULL,NULL,NULL,NULL,1587493508),('media_content_poster','142','dbfca9d6c11032834b1c93c417951297',0,NULL,NULL,NULL,NULL,NULL,1587493532),('media_content_poster','143','408c3dd89b2b2459b4c9c7c26c974425',0,NULL,NULL,NULL,NULL,NULL,1587507221),('media_content_poster','144','ede2e226b172d4861a6cd433c5f828fc',0,NULL,NULL,NULL,NULL,NULL,1587507271),('media_content_poster','145','aa2058950d58e5dbfa7413a11a5f2198',0,NULL,NULL,NULL,NULL,NULL,1587507321),('media_content_poster','146','d70c3d220b07a2a88f18239a9c9f6d3f',0,NULL,NULL,NULL,NULL,NULL,1587507365),('media_content_poster','147','5bb37d7b8c914601ac879354bf29ae25',0,NULL,NULL,NULL,NULL,NULL,1587507401),('media_content_poster','148','2e5b860264315cf497c044c66acf0e47',0,NULL,NULL,NULL,NULL,NULL,1587507438),('media_content_poster','149','1cb53827d1a6cb5d8811645bbc8c9b9a',0,NULL,NULL,NULL,NULL,NULL,1587507480),('media_content_poster','150','5a2306a91ccaa0376a7dd358600d8483',0,NULL,NULL,NULL,NULL,NULL,1587507541),('media_content_poster','151','eb5ba49a9f3f16d33236a8bd1ff14854',0,NULL,NULL,NULL,NULL,NULL,1587539340),('media_content_poster','155','b0ae88b539d287f89135a519fd0de517',0,NULL,NULL,NULL,NULL,NULL,1587635995),('media_content_poster','157','5bb488dba498b9c6b24805ac16051895',0,NULL,NULL,NULL,NULL,NULL,1587636110),('media_content_poster','158','7535a76d0858436913ffbf362994e473',0,NULL,NULL,NULL,NULL,NULL,1587636454),('media_content_poster','159','c85a4cf2e67eca546c92a1f93b6cc872',0,NULL,NULL,NULL,NULL,NULL,1587636425),('media_content_poster','160','e15ed9c099e681e23497cffc840d75ad',0,NULL,NULL,NULL,NULL,NULL,1587636513),('media_content_poster','161','92f8e69fd1f4f1ba299b36f3fe6dee94',0,NULL,NULL,NULL,NULL,NULL,1587636537),('media_content_poster','167','aaccd462d7063d0b1be70ddbbf18ed02',0,NULL,NULL,NULL,NULL,NULL,1587835173),('media_content_poster','175','c0c133008fc8cf94b3b3ab845b53cb0c',0,NULL,NULL,NULL,NULL,NULL,1587841536),('media_content_poster','181','849e7a56aff73a1f8afe0b61f4de3359',0,NULL,NULL,NULL,NULL,NULL,1588088007),('media_content_poster','182','dd3c75ce5555cdb921a55b38f49f9f1c',0,NULL,NULL,NULL,NULL,NULL,1588088060),('media_content_poster','183','69adcc2136f5e0ea124e3daf0d64b5c1',0,NULL,NULL,NULL,NULL,NULL,1588088100),('media_content_poster','188','6ba67b93f717ed359f863cfcdab75e9d',0,NULL,NULL,NULL,NULL,NULL,1588101907),('media_content_poster','190','e26187487e3dc401e209a182e9b03e3b',0,NULL,NULL,NULL,NULL,NULL,1588102204),('media_content_poster','191','67857f89076906d2569ae10be156079d',0,NULL,NULL,NULL,NULL,NULL,1588102638),('media_content_poster','192','c663fc07ed841f360eecc143f4095600',0,NULL,NULL,NULL,NULL,NULL,1588103096),('media_content_poster','193','80ccbf3c424f801992896d92dfb80223',0,NULL,NULL,NULL,NULL,NULL,1588103557),('media_content_poster','195','ad0c9d6e4e401bee6f3cf75322f50ca5',0,NULL,NULL,NULL,NULL,NULL,1588857261),('media_content_poster','196','7638f42dd2ee8f805661bc649ae870ae',0,NULL,NULL,NULL,NULL,NULL,1588858709),('media_content_poster','198','f9caf801231b51927c26b89720baf880',0,NULL,NULL,NULL,NULL,NULL,1589200040),('media_content_poster','199','074a4375e896b3044643390fa0e7bd50',0,NULL,NULL,NULL,NULL,NULL,1589200054),('media_content_poster','200','8334fe963498d670a7fa8824539b6f42',0,NULL,NULL,NULL,NULL,NULL,1589200062),('media_content_poster','201','518df5e7e6889f849122fc5c0c2b8d84',0,NULL,NULL,NULL,NULL,NULL,1589200070),('media_content_poster','202','1e7b1d0af8d8c9ef0c11a429a02b75c7',0,NULL,NULL,NULL,NULL,NULL,1589200088),('media_content_poster','206','ab97bf9697208b80b6e95435d3d84418',0,NULL,NULL,NULL,NULL,NULL,1588931597),('media_content_poster','210','1c25144a08ad57b6f1189d1e26ddb093',0,NULL,NULL,NULL,NULL,NULL,1588955565),('media_content_poster','212','f05a91befaf9273beecabd67b29bf548',0,NULL,NULL,NULL,NULL,NULL,1589043587),('media_content_poster','213','c9cba77ce7a5b060553c4dc2d74492ed',0,NULL,NULL,NULL,NULL,NULL,1590739798),('media_content_poster','214','c08e6d951fac8e0d3ed01daae9b1ec14',0,NULL,NULL,NULL,NULL,NULL,1589104501),('media_content_poster','216','345e5d244f021aa8e1a71afd4d6d6d36',1,NULL,NULL,NULL,NULL,NULL,1589116576),('media_content_poster','216','7a2140f0af9530f1471dbd7709a0cdeb',0,NULL,NULL,NULL,NULL,NULL,1589116877),('media_content_poster','217','902571664a80d9d83de7e842145f963e',0,NULL,NULL,NULL,NULL,NULL,1589188754),('media_content_poster','219','3597ccec9c4c48ba3faa4364ed007bc1',0,NULL,NULL,NULL,NULL,NULL,1589204740),('media_content_poster','221','eb89d71f5c69390b0c374be5494e7ca2',0,NULL,NULL,NULL,NULL,NULL,1589388608),('media_content_poster','223','45d924b63edb99c7d558441083cefa08',0,NULL,NULL,NULL,NULL,NULL,1589388735),('media_content_poster','51','886f21d35a02f3e61dfcf0911c841ff6',0,NULL,NULL,NULL,NULL,NULL,1586794394),('media_content_poster','51','8b916a435a26ae67451788588f821417',0,NULL,NULL,NULL,NULL,NULL,1586794394),('media_content_poster','52','a1fdbbe31fb497e59fe462148989db93',0,NULL,NULL,NULL,NULL,NULL,1586794470),('media_content_poster','54','304411f8aa6edd936db4e64e8e2c7fa9',0,NULL,NULL,NULL,NULL,NULL,1586794933),('media_content_poster','55','1714c9f3ec7dcd5a96b4f6a8376c7955',0,NULL,NULL,NULL,NULL,NULL,1586794981),('media_content_poster','56','7363f887624f8c154e147dd789d5a087',0,NULL,NULL,NULL,NULL,NULL,1587312649),('media_content_poster','57','45ae95f9bf015afc3098157b79a98f6e',0,NULL,NULL,NULL,NULL,NULL,1587312678),('media_content_poster','58','94efda82a21081d421d4d0ba6064e9b3',0,NULL,NULL,NULL,NULL,NULL,1587312696),('media_content_poster','59','41343aa72d7ad98eeeabffa45c4338a6',0,NULL,NULL,NULL,NULL,NULL,1587312717),('media_content_poster','60','c573ea87ff52bace455a38b21ba2341d',0,NULL,NULL,NULL,NULL,NULL,1587312734),('media_content_poster','66','6dfcaa8d824f3aa25ffbaa04be06a9c1',0,NULL,NULL,NULL,NULL,NULL,1587117496),('media_content_poster','68','e26d0dba6d60d6d99d6ed46d68efa571',0,NULL,NULL,NULL,NULL,NULL,1587313667),('media_content_poster','69','e371b4456c9507dfedf3d11bcee94c5d',0,NULL,NULL,NULL,NULL,NULL,1587313708),('media_content_poster','70','158d470c9b288e2bc0fc8e770624d1c1',0,NULL,NULL,NULL,NULL,NULL,1586960893),('media_content_poster','70','164a1efa7276a03e47fb4cf8d25ee399',0,NULL,NULL,NULL,NULL,NULL,1586960901),('media_content_poster','70','3032a417740aba3480a28dbf08efa021',0,NULL,NULL,NULL,NULL,NULL,1586960896),('media_content_poster','70','3641b15578002b84ca0fa53b171fe510',0,NULL,NULL,NULL,NULL,NULL,1586960900),('media_content_poster','70','76393ba60a22164406a09a187b8bfbe3',0,NULL,NULL,NULL,NULL,NULL,1586960901),('media_content_poster','70','81344183d512623be365d8953dafee5f',0,NULL,NULL,NULL,NULL,NULL,1586960900),('media_content_poster','70','81b769fc146eb580bb3f7f36976bfd95',0,NULL,NULL,NULL,NULL,NULL,1586960894),('media_content_poster','70','88052225694b19834bbbab9528765cae',0,NULL,NULL,NULL,NULL,NULL,1586960899),('media_content_poster','70','9007d53757b1adf79df8ce8bd33704d5',0,NULL,NULL,NULL,NULL,NULL,1586960832),('media_content_poster','70','c924f7836e2f613d06eea54f8b7955e6',0,NULL,NULL,NULL,NULL,NULL,1586960896),('media_content_poster','70','e0927ec1334476dbaf9f40301bd1d0ce',0,NULL,NULL,NULL,NULL,NULL,1586960897),('media_content_poster','70','ef9745b85007e413e65dd75a48c51339',0,NULL,NULL,NULL,NULL,NULL,1586960900),('media_content_poster','72','f5ea7b387e2a8794c5675a252dddabc0',0,NULL,NULL,NULL,NULL,NULL,1587313062),('media_content_poster','74','ebc1a0711d9e1bdf0f0c8053b26f4714',0,NULL,NULL,NULL,NULL,NULL,1587313082),('media_content_poster','75','b184d78f3131b14153a99f6e07a067f8',0,NULL,NULL,NULL,NULL,NULL,1587313096),('media_content_poster','76','ba439f594342c6c75befcb110737e489',0,NULL,NULL,NULL,NULL,NULL,1587313110),('media_content_poster','77','b3de8744dfc70e3fa97e750aa04c592c',0,NULL,NULL,NULL,NULL,NULL,1587313126),('media_content_poster','78','625c36a514e0879082ec59e9ed76b5bb',0,NULL,NULL,NULL,NULL,NULL,1587117750),('media_content_poster','80','cb722eb03f187d026b1f29d99d861b48',0,NULL,NULL,NULL,NULL,NULL,1587313452),('media_content_poster','81','a6a34fb0084b71547b6bac34cbbf1b63',0,NULL,NULL,NULL,NULL,NULL,1587313477),('media_content_poster','82','5df9aeeec57e40e138a99ee66495c916',0,NULL,NULL,NULL,NULL,NULL,1587313497),('media_content_poster','83','c76c623b857db56dec0944178ca70da2',0,NULL,NULL,NULL,NULL,NULL,1587313525),('media_content_poster','84','0b716077ab95a5f7e32784fa19441944',0,NULL,NULL,NULL,NULL,NULL,1587313744),('media_content_poster','85','5fa28ac37b4dd35f1cb70672c7f53386',0,NULL,NULL,NULL,NULL,NULL,1587313765),('media_content_poster','86','14afa0bdeda5f3e2bd39d3773a8be4af',0,NULL,NULL,NULL,NULL,NULL,1587313788),('media_content_poster','87','f3c5b54e1e3a6467dec912a7f9743a80',0,NULL,NULL,NULL,NULL,NULL,1587313810),('media_content_poster','88','ba5dbaece7d323afb8333d0de4c95f48',0,NULL,NULL,NULL,NULL,NULL,1587313829),('media_content_poster','89','19cac5890fd6516e83ec8ca535a5f5b1',0,NULL,NULL,NULL,NULL,NULL,1587313852),('media_content_poster','90','0838d4461998a543736195556ece51ab',0,NULL,NULL,NULL,NULL,NULL,1587313871),('media_content_poster','91','455ff4f7945ecd18483561826a66f8e7',0,NULL,NULL,NULL,NULL,NULL,1587313892),('media_content_trailer','130','885d6e206c895a6ed0d6feb1453c7b36',0,NULL,NULL,NULL,NULL,NULL,1588259361),('media_content_trailer','203','bb6811eaade8820141fd35817d7173c5',0,NULL,NULL,NULL,NULL,NULL,1588964244),('media_content_trailer','220','7e8720bc8b044e7c4a236c60f87db591',0,NULL,NULL,NULL,NULL,NULL,1589356122),('media_content_trailer','224','f8d11a09dbb6a6760b32f1a627ec43d7',0,NULL,NULL,NULL,NULL,NULL,1589388693),('media_content_trailer','73','0ab0cf5ab62a3daece1514bfe5b4f033',0,NULL,NULL,NULL,NULL,NULL,1588595928),('media_studio','10','d48c620d9cbf11e2a0870abe16aeb22b',0,NULL,NULL,NULL,NULL,NULL,1586450793),('media_studio','2','d7e0e610921221ab5881abc790d82744',0,NULL,NULL,NULL,NULL,NULL,1585426566),('REQUEST_FRAME','2','4e8d1770d61a25592851b8ac1603e839',0,NULL,NULL,NULL,NULL,NULL,1590798875),('REQUEST_POSTER','2','4e8d1770d61a25592851b8ac1603e839',0,NULL,NULL,NULL,NULL,NULL,1590798875),('SMILE','12','smile',0,NULL,NULL,NULL,NULL,NULL,1590923204),('SMILE','13','smile',0,NULL,NULL,NULL,NULL,NULL,1590923199),('SMILE','14','smile',0,NULL,NULL,NULL,NULL,NULL,1590923194),('SMILE','15','smile',0,NULL,NULL,NULL,NULL,NULL,1590923187),('SMILE','16','smile',0,NULL,NULL,NULL,NULL,NULL,1590923181),('SMILE','17','smile',0,NULL,NULL,NULL,NULL,NULL,1590923176),('SMILE','3','smile',0,NULL,NULL,NULL,NULL,NULL,1590841760),('SMILE','4','smile',0,NULL,NULL,NULL,NULL,NULL,1590834357),('SMILE','5','smile',0,NULL,NULL,NULL,NULL,NULL,1590841938),('SMILE','6','smile',0,NULL,NULL,NULL,NULL,NULL,1590834330),('SMILE','7','smile',0,NULL,NULL,NULL,NULL,NULL,1590834317),('SMILE','8','smile',0,NULL,NULL,NULL,NULL,NULL,1590834268),('SMILE','9','smile',0,NULL,NULL,NULL,NULL,NULL,1590831773);
/*!40000 ALTER TABLE `imagefly__images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagefly__images_props`
--

DROP TABLE IF EXISTS `imagefly__images_props`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagefly__images_props` (
  `context` varchar(100) NOT NULL,
  `owner_id` varchar(100) NOT NULL,
  `image` varchar(32) NOT NULL,
  `property_name` varchar(100) NOT NULL,
  `property_value` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`context`,`owner_id`,`image`,`property_name`),
  CONSTRAINT `imagefly__images_props_ibfk_1` FOREIGN KEY (`context`, `owner_id`, `image`) REFERENCES `imagefly__images` (`context`, `owner_id`, `image`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagefly__images_props`
--

LOCK TABLES `imagefly__images_props` WRITE;
/*!40000 ALTER TABLE `imagefly__images_props` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagefly__images_props` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagefly__media_context`
--

DROP TABLE IF EXISTS `imagefly__media_context`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagefly__media_context` (
  `context` varchar(100) NOT NULL,
  `max_width` double DEFAULT NULL,
  `max_height` double DEFAULT NULL,
  `min_width` double DEFAULT NULL,
  `min_height` double DEFAULT NULL,
  `allow_caching` int(1) NOT NULL DEFAULT '1',
  `background` varchar(100) DEFAULT NULL,
  `allow_mimes` blob,
  PRIMARY KEY (`context`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagefly__media_context`
--

LOCK TABLES `imagefly__media_context` WRITE;
/*!40000 ALTER TABLE `imagefly__media_context` DISABLE KEYS */;
INSERT INTO `imagefly__media_context` VALUES ('age_restriction',1600,1600,20,20,1,NULL,_binary 'a:0:{}'),('avatar',1200,1200,100,100,1,NULL,_binary 'a:0:{}'),('emojirenderer',1000,1000,5,5,1,NULL,_binary 'a:0:{}'),('fallback',3600,3600,100,100,1,NULL,_binary 'a:0:{}'),('media_content_frame',2000,2000,100,100,1,NULL,_binary 'a:0:{}'),('media_content_poster',2000,2000,100,100,1,NULL,_binary 'a:0:{}'),('media_content_trailer',2000,2000,100,100,1,NULL,_binary 'a:0:{}'),('media_lent',2000,2000,50,50,1,NULL,_binary 'a:0:{}'),('media_person',1600,1600,100,100,1,NULL,_binary 'a:0:{}'),('media_studio',1600,1600,50,50,1,NULL,_binary 'a:0:{}'),('REQUEST_FRAME',1200,1200,300,300,1,NULL,_binary 'a:0:{}'),('REQUEST_POSTER',1200,1200,300,300,1,NULL,_binary 'a:0:{}'),('SMILE',1200,1200,32,32,1,NULL,_binary 'a:0:{}'),('social_fallback',1600,1600,400,400,1,NULL,_binary 'a:0:{}');
/*!40000 ALTER TABLE `imagefly__media_context` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infopage`
--

DROP TABLE IF EXISTS `infopage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infopage` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(100) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `published` int(1) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `dop` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `html_mode_c` int(1) unsigned NOT NULL DEFAULT '1',
  `html_mode_i` int(1) unsigned NOT NULL DEFAULT '1',
  `meta_title` varchar(255) NOT NULL DEFAULT '',
  `intro` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `meta_keywords` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  UNIQUE KEY `alias` (`alias`),
  KEY `infopage_by_published` (`published`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infopage`
--

LOCK TABLES `infopage` WRITE;
/*!40000 ALTER TABLE `infopage` DISABLE KEYS */;
INSERT INTO `infopage` VALUES (2,'5dd6a18f-0333-11ea-82f6-001e5826d92c','home',1,'home','2019-11-09 20:56:14',0,1,'','','{display_lent_v2}','',''),(4,'3ad08814-49d0-11ea-82f6-001e5826d92c','login',1,'Логин','2020-02-07 17:35:27',0,1,'','','<!DOCTYPE html>\n<html lang=\"ru-RU\">\n\n  <head>\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>Capital Solutions</title>\n    <base href=\".\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <link type=\"text/css\" rel=\"stylesheet\" href=\"/assets/front_capital_main/css/materialize.min.css\" media=\"screen,projection\">\n    <link href=\"/assets/front_capital_main/css/materialdesignicons.min.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/front_capital_main/css/main.css\" media=\"screen\">\n    <script type=\"text/javascript\" src=\"/assets/front_capital_main/js/jquery-2.2.1.min.js\"></script>\n    <script type=\"text/javascript\" src=\"/assets/front_capital_main/js/materialize.min.js\"></script>\n    <script type=\"text/javascript\" src=\"/assets/front_capital_main/js/main.js\"></script>\n  </head>\n\n  <body class=\"login\">\n    <div id=\"login_form\">\n      <div class=\"form_in\">\n        <h2>Вход</h2>\n        <form id=\"login_page_form\" onsubmit=\"return false;\">\n          <div class=\"input_block\">\n            <input type=\"text\" placeholder=\"email\" id=\"login_username\">\n          </div>\n          <div class=\"input_block\">\n            <input type=\"password\" placeholder=\"password\" id=\"login_password\">\n          </div>\n          <div class=\"button_block\">\n            <button id=\"login_page_login_button\">Войти</button>\n          </div>\n        </form>\n        <div id=\"no-acc\">\n          <a href=\"/signup\">Нет аккаунта. Регистрация</a>\n        </div>\n      </div>\n    </div>\n    <script>\n      {literal}\n      jQuery(function(){\n        var form = jQuery(\"#login_page_form\");\n        var button = jQuery(\"#login_page_login_button\");\n        var login = form.find(\"#login_username\");\n        var password = form.find(\'#login_password\');\n        button.on(\'click\',function(e){\n          e.preventDefault?e.preventDefault:e.returnValue = false;\n          e.stopPropagation();\n\n          if(button.hasClass(\'loading_now\')){\n            return;\n          } \n          try{\n            var data = {\n              action:\"login\",\n              login:login.val(),\n              password:password.val()\n            } ;\n            if(! /^[^@\\,\\;\\s]{1,}@[^@\\,\\;\\s]{1,}\\.[^@\\.\\,\\;\\s]{1,}$/i.test(data.login)){\n              throw new Error(\"Укажите корректный email\");\n            }\n            if(typeof(data.password) !== \"string\" || data.password.length < 6){\n              throw new Error(\"Указан неверный пароль\");\n            } \n            button.addClass(\'loading_now\');\n            jQuery.post(\'/Auth/API\',data)\n              .done(function(d){\n              if(d.status===\"ok\"){\n                  window.location.href=\"/Cabinet\";\n                return;\n              }\n              if(d.status===\'error\'){\n                 alert (d.error_info.message);\n                return;\n              }\n              alert(\"Некорректный ответ сервера\");\n            })\n              .fail(function(){\n              alert(\"Ошибка связи с сервером!\");\n            })\n              .always(function(){\n              button.removeClass(\'loading_now\');\n            })\n          }catch(e){\n            alert(e.message);\n            button.removeClass(\'loading_now\');\n\n          }\n\n        });\n      });\n      {/literal}\n    </script>\n\n  </body>\n\n</html>','',''),(5,'21a93c07-4a17-11ea-82f6-001e5826d92c','signup',1,'signup','2020-02-08 02:02:59',0,1,'','','<!DOCTYPE html>\n<html lang=\"ru-RU\">\n  <head>\n    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n    <title>Capital Solutions</title>\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <link type=\"text/css\" rel=\"stylesheet\" href=\"/assets/front_capital_main/css/materialize.min.css\" media=\"screen,projection\">\n    <link href=\"/assets/front_capital_main/css/materialdesignicons.min.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\">\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"/assets/front_capital_main/css/main.css\" media=\"screen\">\n    <script type=\"text/javascript\" src=\"/assets/front_capital_main/js/jquery-2.2.1.min.js\"></script>\n    <script type=\"text/javascript\" src=\"/assets/front_capital_main/js/materialize.min.js\"></script>\n    <script type=\"text/javascript\" src=\"/assets/front_capital_main/js/main.js\"></script>\n  </head>\n\n  <body class=\"login\">\n    <div id=\"signup_form\" style=\"display:block\">\n      <div class=\"form_in\">\n        <h2>Регистрация</h2>\n        <form onsubmit=\"return false;\">\n          <div class=\"input_block\">\n            <input type=\"text\" placeholder=\"имя\" id=\"signup_username\">\n          </div>\n          <div class=\"input_block\">\n            <input type=\"text\" placeholder=\"email\" id=\"signup_email\">\n          </div>\n          <div class=\"input_block\">\n            <input type=\"text\" placeholder=\"phone\" id=\"signup_phone\">\n          </div>\n          <div class=\"input_block\">\n            <input type=\"text\" placeholder=\"birthday\" id=\"birthday\">\n          </div>\n          <div class=\"input_block\">\n            <input type=\"password\" placeholder=\"password\" id=\"signup_password\">\n          </div>\n          <div class=\"button_block\">\n            <button>Войти</button>\n          </div>\n        </form>\n        <div id=\"yes-acc\">\n          <a href=\"/login\">Есть аккаунт? Войти</a>\n        </div>\n      </div>\n    </div>\n    <script>\n      {literal}\n      jQuery(function () {\n        var form = jQuery(\"#signup_form form\");\n        var button = form.find(\'button\');\n\n        function get_data() {\n          return {                            \n            \"name\": jQuery.trim(jQuery(\"#signup_username\").val()),\n            \"login\": jQuery.trim(jQuery(\"#signup_email\").val()),\n            \"phone\": jQuery.trim(jQuery(\"#signup_phone\").val()),\n            \"birth_date\": jQuery.trim(jQuery(\"#birthday\").val()),\n            \"password\": jQuery(\"#signup_password\").val()\n          };\n        }\n\n\n        function check_data(r) {\n          if (!/^[^@\\,\\;\\s]{1,}@[^@\\,\\;\\s]{1,}\\.[^@\\.\\,\\;\\s]{1,}$/i.test(r.login)) {\n            throw new Error(\"Укажите корректный email\");\n          }\n          if (typeof (r.password) !== \"string\" || r.password.length < 6) {\n            throw new Error(\"Минимальный пароль - 6 символов\");\n          }\n          if (typeof (r.name) !== \"string\" || !r.name.length) {\n            throw new Error(\"Укажите имя\");\n          }\n        }\n\n        button.on(\'click\', function (e) {\n          if (button.hasClass(\'loading_now\')) {\n            return;\n          }\n          e.preventDefault ? e.preventDefault() : e.returnValue = false;\n          e.stopPropagation();\n          var data = get_data();\n          try {\n            check_data(data);\n          } catch (e) {\n            alert(e.message);\n            return;\n          }\n          button.addClass(\'loading_now\');\n          jQuery.post(\'Auth/API\', {\"action\": \"register\",data:JSON.stringify(data)})\n            .done(function (d) {\n            if(d.status===\"ok\"){\n              window.location.href=\"/Cabinet\";\n              return;\n            }\n            if(d.status===\"error\"){\n              alert(d.error_info.message);\n              return;\n            }\n            alert(\"Некорректный ответ сервера\");\n          })\n            .fail(function () {\n            alert(\"Ошибка связи с сервером\");\n          })\n            .always(function () {\n            button.removeClass(\"loading_now\");\n          });\n        });\n      });\n      {/literal}\n    </script>\n  </body>\n\n</html>','',''),(6,'8ce00efb-8000-11ea-82f6-001e5826d92c','soap_page',1,'soap_page','2020-04-16 16:37:24',0,1,'','','{render_selected_soap}','',''),(7,'f275e048-8093-11ea-82f6-001e5826d92c','about',1,'О сервисе','2020-04-17 10:12:30',1,1,'','','<h1>О сервисе</h1>\n<p>О сервисе</p>','',''),(8,'33098743-8094-11ea-82f6-001e5826d92c','policy',1,'Политика конфиденциальности','2020-04-17 10:14:18',1,1,'','','<h1>Политика конфиденциальности</h1>\n<p><strong>Политика в отношении обработки персональных данных</strong></p>\n<ol>\n<li>Общие положения</li>\n</ol>\n<p>Настоящая политика обработки персональных данных составлена в соответствии с требованиями Федерального закона от 27.07.2006г. №152-ФЗ &laquo;О персональных данных&raquo; и определяет порядок обработки персональных данных и меры по обеспечению безопасности персональных данных&nbsp;ООО \"ЧИЛЛ В\"&nbsp;(ИНН 7724479119, ОГРН 1197746371602), зарегистрированное по адресу: 115612, г. Москва, ул. Братеевская, д. 10, корп. 4, эт. 1, ком. 2, оф. 2, юридическое лицо по законодательству РФ (далее &ndash; Провайдер).</p>\n<p>Пожалуйста, ознакомьтесь с настоящей Политикой перед использованием Сервиса или передачей вашей информации на Сервис. В случае несогласия с условиями настоящего Политики, вы обязаны воздержаться от использования Сервиса.</p>\n<ul>\n<li>Провайдер ставит своей важнейшей целью и условием осуществления своей деятельности соблюдение прав и свобод человека и гражданина при обработке его персональных данных, в том числе защиты прав на неприкосновенность частной жизни, личную и семейную тайну.</li>\n<li>Настоящая политика Провайдера в отношении обработки персональных данных (далее &ndash; Политика) применяется ко всей информации, которую Провайдер может получить о посетителях веб-сайта&nbsp;<a href=\"http://____________________\">http://____________________</a>.</li>\n<li>Факт использования Пользователем Сервиса означает его полное и безоговорочное согласие с условиями настоящей Политики. В случае несогласия с условиями настоящей Политики, Пользователь обязан воздержаться от использования Сервиса.</li>\n</ul>\n<ol start=\"2\">\n<li>Основные понятия, используемые в Политике:</li>\n</ol>\n<ul>\n<li>Автоматизированная обработка персональных данных &ndash; обработка персональных данных с помощью средств вычислительной техники;</li>\n<li>Блокирование персональных данных &ndash; временное прекращение обработки персональных данных (за исключением случаев, если обработка необходима для уточнения персональных данных);</li>\n<li>Веб-сайт &ndash; совокупность графических и информационных материалов, а также программ для ЭВМ и баз данных, обеспечивающих их доступность в сети интернет по сетевому адресу&nbsp;<a href=\"http://_________________\">http://_________________</a>;</li>\n<li>Сервис - комплекс услуг, предоставляемых Пользователю с использованием Сайта;</li>\n<li>Информационная система персональных данных &mdash; совокупность содержащихся в базах данных персональных данных, и обеспечивающих их обработку информационных технологий и технических средств;</li>\n<li>Обезличивание персональных данных &mdash; действия, в результате которых невозможно определить без использования дополнительной информации принадлежность персональных данных конкретному Пользователю или иному субъекту персональных данных;</li>\n<li>Обработка персональных данных &ndash; любое действие (операция) или совокупность действий (операций), совершаемых с использованием средств автоматизации или без использования таких средств с персональными данными, включая сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, передачу (распространение, предоставление, доступ), обезличивание, блокирование, удаление, уничтожение персональных данных;</li>\n<li>Персональные данные &ndash; любая информация, относящаяся прямо или косвенно к определенному или определяемому Пользователю веб-сайта&nbsp;http://______________;</li>\n<li>Пользователь &ndash; любой посетитель веб-сайта&nbsp;http://___________________;</li>\n<li>Предоставление персональных данных &ndash; действия, направленные на раскрытие персональных данных определенному лицу или определенному кругу лиц;</li>\n<li>Распространение персональных данных &ndash; любые действия, направленные на раскрытие персональных данных неопределенному кругу лиц (передача персональных данных) или на ознакомление с персональными данными неограниченного круга лиц, в том числе обнародование персональных данных в средствах массовой информации, размещение в информационно-телекоммуникационных сетях или предоставление доступа к персональным данным каким-либо иным способом;</li>\n<li>Уничтожение персональных данных &ndash; любые действия, в результате которых персональные данные уничтожаются безвозвратно с невозможностью дальнейшего восстановления содержания персональных данных в информационной системе персональных данных и (или) результате которых уничтожаются материальные носители персональных данных.</li>\n</ul>\n<ol start=\"3\">\n<li>Информация Пользователей, получаемая и обрабатываемая Провайдером.</li>\n</ol>\n<p>&nbsp;</p>\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; При регистрации Пользователя на Сервисе, а также при оплате и пользовании продуктами и услугами Сервиса, при проведении опросов на Сервисе, Провайдер может запросить у Пользователя следующую информацию:</p>\n<ul>\n<li>Адрес электронной почты;</li>\n<li>Фамилия, имя, отчество;</li>\n<li>Пол, возраст;</li>\n<li>изображение (фотография и видеозапись), которое позволяет установить личность Пользователя и с этой целью используется Провайдером;</li>\n<li>место жительства;</li>\n<li>номера телефонов;</li>\n<li>информация о логине и пароле для доступа к отдельным функциям Сервиса;</li>\n</ul>\n<p>Также на Сайте происходит сбор и обработка обезличенных данных о посетителях (в т.ч. файлов &laquo;cookie&raquo; IP-адрес, информация о стране и (или) городе нахождения Пользователя, информация об Интернет-браузере Пользователя, время доступа, адрес запрашиваемой Страницы, об устройстве Пользователя, с помощью которого осуществляется доступ к Сервису), в том числе с помощью сервисов интернет-статистики (Яндекс Метрика, Гугл Аналитика и других).</p>\n<p>Обезличенные данные Пользователей, собираемые с помощью сервисов интернет-статистики, служат для сбора информации о действиях Пользователей на сайте, улучшения качества сайта и его содержания.</p>\n<p>Вышеперечисленные данные далее по тексту Политики объединены общим понятием Персональные данные.</p>\n<p>Провайдер исходит из того, что Пользователь предоставляет актуальную и достоверную информацию и не проверяет достоверность информации, предоставляемой Пользователем. Последствия предоставления недостоверной информации определены в Пользовательском соглашении, а также в законодательстве Российской Федерации.</p>\n<ol start=\"4\">\n<li>Цели обработки персональных данных.</li>\n</ol>\n<p>Предоставляя свои персональные данные Провайдеру, Пользователь соглашается на их обработку Провайдером в следующих целях:</p>\n<ul>\n<li>идентификация Пользователя в целях исполнения Соглашения, Условий;</li>\n<li>обработка запросов Пользователей службой поддержки Сервиса, осуществляемой Провайдером;</li>\n<li>анализ и исследования предпочтений Пользователя в целях улучшения Сервиса;</li>\n<li>заключение, исполнение и прекращение гражданско-правовых договоров;</li>\n<li>рассылка новостей и рекламной информации о Сервисе;</li>\n<li>направление информационных сообщений (например, для восстановления пароля доступа к учетной записи Пользователя);</li>\n<li>предотвращение и выявление мошенничества и незаконного использования Сервиса;</li>\n<li>проведение Провайдером или уполномоченных им третьими лицами статистических и маркетинговых исследований на основе деперсонализированных данных;</li>\n<li>улучшение качества и удобства использования, повышения эффективности Сервиса, разработки новых сервисов Провайдера;</li>\n</ul>\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Провайдер имеет право направлять Пользователю уведомления о новых продуктах и услугах, специальных предложениях и различных событиях. Пользователь всегда может отказаться от получения информационных сообщений, направив Провайдеру письмо на адрес электронной почты&nbsp;info@chillvision.ru&nbsp;с пометкой &laquo;Отказ от уведомлений о новых продуктах и услугах и специальных предложениях&raquo;.</p>\n<p>&nbsp;</p>\n<ol start=\"5\">\n<li>Правовые основания обработки персональных данных.\n<ul>\n<li>Провадер обрабатывает персональные данные Пользователя только в случае их заполнения и/или отправки Пользователем самостоятельно через специальные формы, расположенные на сайте&nbsp;http://______________. Заполняя соответствующие формы и/или отправляя свои персональные данные Провайдеру, Пользователь выражает свое согласие с данной Политикой.</li>\n<li>Провайдер обрабатывает обезличенные данные о Пользователе в случае, если это разрешено в настройках браузера Пользователя (включено сохранение файлов &laquo;cookie&raquo; и использование технологии JavaScript).</li>\n</ul>\n</li>\n</ol>\n<ol start=\"6\">\n<li>Порядок сбора, хранения, передачи и других видов обработки персональных данных</li>\n</ol>\n<p>&nbsp;</p>\n<p>Безопасность персональных данных, которые обрабатываются Провайдером, обеспечивается путем реализации правовых, организационных и технических мер, необходимых для выполнения в полном объеме требований действующего законодательства в области защиты персональных данных.</p>\n<ul>\n<li>Провайдер обеспечивает сохранность персональных данных и принимает все возможные меры, исключающие доступ к персональным данным неуполномоченных лиц.</li>\n<li>Персональные данные Пользователя никогда, ни при каких условиях не будут переданы третьим лицам, за исключением случаев, связанных с исполнением действующего законодательства.</li>\n<li>В случае выявления неточностей в персональных данных, Пользователь может актуализировать их самостоятельно, путем направления Провайдеру уведомление на адрес электронной почты Провайдера&nbsp;info@chillvision.ruс пометкой &laquo;Актуализация персональных данных&raquo;.</li>\n<li>Срок обработки персональных данных является неограниченным. Пользователь может в любой момент отозвать свое согласие на обработку персональных данных, направив Провайдеру уведомление посредством электронной почты на электронный адрес Провайдера info@chillvision.ruс пометкой &laquo;Отзыв согласия на обработку персональных данных&raquo;.</li>\n<li>Пользователь уведомлен, что Провайдер не получает финансовую информацию о Пользователях. Вся финансовая информация, предоставляемая Пользователем для осуществления платежей при использовании Сервиса, собирается и обрабатывается указанными на Сервисе платежными посредниками.</li>\n</ul>\n<ol start=\"7\">\n<li>Заключительные положения</li>\n</ol>\n<ul>\n<li>Пользователь может получить любые разъяснения по интересующим вопросам, касающимся обработки его персональных данных, обратившись к Провайдеру с помощью электронной почты&nbsp;info@chillvision.ru.</li>\n<li>В данном документе будут отражены любые изменения политики обработки персональных данных Провайдером. Политика действует бессрочно до замены ее новой версией.</li>\n<li>Провайдер гарантирует неразглашение персональной информации Пользователей. Не считается нарушением настоящего положения раскрытие Провайдером информации о Пользователе по требованию уполномоченного государственного органа согласно действующему законодательству РФ.</li>\n<li>Провайдер может направлять Пользователю информацию посредством коротких сообщений или электронных писем о Сервисе, в том числе информацию рекламного характера, на электронную почту и мобильный телефон Пользователя с его согласия, выраженного посредством совершения им действий, позволяющих достоверно установить его волеизъявление на получение подобных сообщений. Указание Пользователем в настройках Учетной записи его номера телефона или адреса электронной почты является надлежащим подтверждением согласия Пользователя на получение вышеуказанных сообщений.</li>\n<li>Актуальная версия Политики в свободном доступе расположена в сети Интернет по адресу&nbsp;http://__________________.</li>\n</ul>\n<p><strong>Дата последнего изменения Политики конфиденциальности ___.___.__________г</strong></p>','',''),(9,'4f126eb7-8094-11ea-82f6-001e5826d92c','pay_rules',1,'Правила оплаты и возврата','2020-04-17 10:15:05',1,1,'','','<h1>Правила оплаты и возврата</h1>\n<p>rules</p>','',''),(10,'6a26eea4-8094-11ea-82f6-001e5826d92c','for_authors',1,'Стать автором контента','2020-04-17 10:15:51',0,1,'','','<h1><span class=\"ult\">CHILL -  первое независимое медиа для монетизации твоего контента</span></h1>\n<p>Заполните форму, чтобы Ваш сериал был добавлен на нашу платформу.</p>\n{content_block alias=\"fos\"}','',''),(11,'7278e2a0-8094-11ea-82f6-001e5826d92c','help',1,'Помощь','2020-04-17 10:16:05',1,1,'','','<h1>Помощь</h1>\n<p>help</p>\n<p>&nbsp;</p>','',''),(12,'8298b134-8094-11ea-82f6-001e5826d92c','use_rules',1,'Правила использования','2020-04-17 10:16:32',1,1,'','','<h1>Правила использования</h1>\n<p>use_rules</p>','',''),(14,'7aae75b7-8294-11ea-82f6-001e5826d92c','news_page',1,'news_page','2020-04-19 23:21:21',0,1,'','','{render_selected_news_object}','',''),(15,'036aaa31-8325-11ea-82f6-001e5826d92c','search_by_tag',1,'search_by_tag','2020-04-20 16:35:58',0,1,'','','{display_tagged_lent}','',''),(16,'7030a784-8341-11ea-82f6-001e5826d92c','search_by_genre',1,'search_by_genre','2020-04-20 19:59:26',0,1,'','','{display_genred_lent}','',''),(17,'372199ac-83b9-11ea-82f6-001e5826d92c','search_by_emoji',1,'search_by_emoji','2020-04-21 10:16:50',0,1,'','','{display_emojed_lent}','',''),(18,'9c4e04ce-83bc-11ea-82f6-001e5826d92c','search_by_origin',1,'search_by_origin','2020-04-21 10:41:08',0,1,'','','{display_origined_lent}','',''),(19,'5e3318a7-8d65-11ea-82f6-001e5826d92c','lent_v_2',1,'lent_v_2','2020-05-03 17:41:49',0,1,'','','{display_lent_v2}','',''),(20,'b50f613f-9170-11ea-82f6-001e5826d92c','search_by_track',1,'search_by_track','2020-05-08 21:13:04',0,1,'','','{display_tracklanged_lent}','','');
/*!40000 ALTER TABLE `infopage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infopage__properties`
--

DROP TABLE IF EXISTS `infopage__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infopage__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `ip_props_2_ip_tab` FOREIGN KEY (`id`) REFERENCES `infopage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infopage__properties`
--

LOCK TABLES `infopage__properties` WRITE;
/*!40000 ALTER TABLE `infopage__properties` DISABLE KEYS */;
INSERT INTO `infopage__properties` VALUES (2,'default_layout','front/layout',0),(2,'default_template','page_empty',0),(7,'default_layout','front/layout_a',0),(8,'default_layout','front/layout_a',0),(9,'default_layout','front/layout_a',0),(10,'default_layout','front/layout_a',0),(11,'default_layout','front/layout_a',0),(12,'default_layout','front/layout_a',0);
/*!40000 ALTER TABLE `infopage__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infopage__specs`
--

DROP TABLE IF EXISTS `infopage__specs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infopage__specs` (
  `spec_key` varchar(100) NOT NULL,
  `page_id` bigint(19) unsigned DEFAULT NULL,
  PRIMARY KEY (`spec_key`),
  KEY `infopage__specs_by_page_id` (`page_id`,`spec_key`),
  CONSTRAINT `infopage_spaec_2_infopage_ref` FOREIGN KEY (`page_id`) REFERENCES `infopage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ремап страниц на алиасы инфошек';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infopage__specs`
--

LOCK TABLES `infopage__specs` WRITE;
/*!40000 ALTER TABLE `infopage__specs` DISABLE KEYS */;
/*!40000 ALTER TABLE `infopage__specs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lang__tokens`
--

DROP TABLE IF EXISTS `lang__tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang__tokens` (
  `section` varchar(50) NOT NULL,
  `token` varchar(950) NOT NULL,
  `translation` varchar(1024) NOT NULL,
  PRIMARY KEY (`section`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang__tokens`
--

LOCK TABLES `lang__tokens` WRITE;
/*!40000 ALTER TABLE `lang__tokens` DISABLE KEYS */;
INSERT INTO `lang__tokens` VALUES ('admin','$(...).currentTime is not a function','$(...).currentTime is not a function'),('admin','$(...).find(...).setAttribute is not a function','$(...).find(...).setAttribute is not a function'),('admin','$(...).get(...).currentTime is not a function','$(...).get(...).currentTime is not a function'),('admin','$(...).style is not a function','$(...).style is not a function'),('admin','0','0'),('admin','aaa is not defined','aaa is not defined'),('admin','access denied','access denied'),('admin','Argument 1 passed to Content\\MediaContent\\Readers\\ctSEASON\\MediaContentObject::F() must be of the type integer, null given, called in /STORAGE/WEB/chill/app/controllers/admin/MediaContentController.php on line 46','Argument 1 passed to Content\\MediaContent\\Readers\\ctSEASON\\MediaContentObject::F() must be of the type integer, null given, called in /STORAGE/WEB/chill/app/controllers/admin/MediaContentController.php on line 46'),('admin','Argument 1 passed to Content\\MediaContent\\Writers\\AWriter::F() must implement interface DataMap\\IDataMap, null given, called in /STORAGE/WEB/chill/app/controllers/admin/MediaContentController.php on line 81','Argument 1 passed to Content\\MediaContent\\Writers\\AWriter::F() must implement interface DataMap\\IDataMap, null given, called in /STORAGE/WEB/chill/app/controllers/admin/MediaContentController.php on line 81'),('admin','Call to undefined function MediaVendor\\strintf()','Call to undefined function MediaVendor\\strintf()'),('admin','Call to undefined method AgeRestriction\\AgeRestriction::load_from_db()','Call to undefined method AgeRestriction\\AgeRestriction::load_from_db()'),('admin','Call to undefined method Content\\MediaContent\\Readers\\ctBANNER\\MediaContentObject::load_strings()','Call to undefined method Content\\MediaContent\\Readers\\ctBANNER\\MediaContentObject::load_strings()'),('admin','Call to undefined method Content\\MediaContent\\TagList\\CountryTagList::get_strings_table_key()','Call to undefined method Content\\MediaContent\\TagList\\CountryTagList::get_strings_table_key()'),('admin','Call to undefined method controllers\\admin\\MediaContentController::API_get_trailert()','Call to undefined method controllers\\admin\\MediaContentController::API_get_trailert()'),('admin','Call to undefined method Promo\\Writer::get_filters()','Call to undefined method Promo\\Writer::get_filters()'),('admin','Cannot add or update a child row: a foreign key constraint fails (`chill`.`media__content__trailer__strings`, CONSTRAINT `media__content__trailer__strings__2__media__content__trailer` FOREIGN KEY (`id`) REFERENCES `media__content__trailer` (`id`) ON DELETE C)','Cannot add or update a child row: a foreign key constraint fails (`chill`.`media__content__trailer__strings`, CONSTRAINT `media__content__trailer__strings__2__media__content__trailer` FOREIGN KEY (`id`) REFERENCES `media__content__trailer` (`id`) ON DELETE C)'),('admin','Cannot add or update a child row: a foreign key constraint fails (`chill`.`media__content__trailer__strings`, CONSTRAINT `media__content__trailer__strings__2__media__content__trailer` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON)','Cannot add or update a child row: a foreign key constraint fails (`chill`.`media__content__trailer__strings`, CONSTRAINT `media__content__trailer__strings__2__media__content__trailer` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON)'),('admin','Cannot read property \'0\' of undefined','Cannot read property \'0\' of undefined'),('admin','Cannot read property \'autoHeight\' of null','Cannot read property \'autoHeight\' of null'),('admin','Cannot read property \'autoplayHoverPause\' of null','Cannot read property \'autoplayHoverPause\' of null'),('admin','Cannot read property \'bindToObject\' of undefined','Cannot read property \'bindToObject\' of undefined'),('admin','Cannot read property \'empty\' of undefined','Cannot read property \'empty\' of undefined'),('admin','Cannot read property \'files\' of undefined','Cannot read property \'files\' of undefined'),('admin','Cannot read property \'get_path\' of undefined','Cannot read property \'get_path\' of undefined'),('admin','Cannot read property \'id\' of null','Cannot read property \'id\' of null'),('admin','Cannot read property \'id\' of undefined','Cannot read property \'id\' of undefined'),('admin','Cannot read property \'init_editor\' of undefined','Cannot read property \'init_editor\' of undefined'),('admin','Cannot read property \'innerHTML\' of null','Cannot read property \'innerHTML\' of null'),('admin','Cannot read property \'language_list\' of undefined','Cannot read property \'language_list\' of undefined'),('admin','Cannot read property \'length\' of null','Cannot read property \'length\' of null'),('admin','Cannot read property \'length\' of undefined','Cannot read property \'length\' of undefined'),('admin','Cannot read property \'message\' of undefined','Cannot read property \'message\' of undefined'),('admin','Cannot read property \'open\' of undefined','Cannot read property \'open\' of undefined'),('admin','Cannot read property \'pause\' of null','Cannot read property \'pause\' of null'),('admin','Cannot read property \'poster\' of undefined','Cannot read property \'poster\' of undefined'),('admin','Cannot read property \'refresh\' of undefined','Cannot read property \'refresh\' of undefined'),('admin','Cannot read property \'replace\' of undefined','Cannot read property \'replace\' of undefined'),('admin','Cannot read property \'serie_id\' of null','Cannot read property \'serie_id\' of null'),('admin','Cannot read property \'setContainer\' of undefined','Cannot read property \'setContainer\' of undefined'),('admin','Cannot read property \'set_data\' of undefined','Cannot read property \'set_data\' of undefined'),('admin','Cannot read property \'set_owner_id\' of undefined','Cannot read property \'set_owner_id\' of undefined'),('admin','cant access property `id` in class `Language\\CountryWriter` for read. no getter method','cant access property `id` in class `Language\\CountryWriter` for read. no getter method'),('admin','cant access property `id` in class `MediaVendor\\MediaVendorWriter` for read. no getter method','cant access property `id` in class `MediaVendor\\MediaVendorWriter` for read. no getter method'),('admin','cant access property `index` in class `Language\\LanguageList` for read. no getter method','cant access property `index` in class `Language\\LanguageList` for read. no getter method'),('admin','cant access property `valid` in class `Promo\\Promo` for read. no getter method','cant access property `valid` in class `Promo\\Promo` for read. no getter method'),('admin','Cant find factory for filter Date','Cant find factory for filter Date'),('admin','CantRegisterStyleObject:noidorcssFields','CantRegisterStyleObject:noidorcssFields'),('admin','carousel is not defined','carousel is not defined'),('admin','Chart is not defined','Chart is not defined'),('admin','Class \'Content\\MediaContent\\Readers\\ctTEXT\\MediaContentObject\' not found','Class \'Content\\MediaContent\\Readers\\ctTEXT\\MediaContentObject\' not found'),('admin','Class \'Content\\MediaContent\\Writers\\ctCOLLECTION\\Writer\' not found','Class \'Content\\MediaContent\\Writers\\ctCOLLECTION\\Writer\' not found'),('admin','Class \'Content\\MediaContent\\Writers\\ctTEXT\\CommonWriter\' not found','Class \'Content\\MediaContent\\Writers\\ctTEXT\\CommonWriter\' not found'),('admin','Class \'Content\\MediaPerson\\writer\\MediaPerson\' not found','Class \'Content\\MediaPerson\\writer\\MediaPerson\' not found'),('admin','Class \'Content\\RequestRequest\\Writer\\CommonWriter\' not found','Class \'Content\\RequestRequest\\Writer\\CommonWriter\' not found'),('admin','Class \'Emoji\\Emoji\' not found','Class \'Emoji\\Emoji\' not found'),('admin','Class \'Emoji\\EmojiWriter\' not found','Class \'Emoji\\EmojiWriter\' not found'),('admin','Class \'Language\\TrackLangWriter\' not found','Class \'Language\\TrackLangWriter\' not found'),('admin','Class \'Promo\\Writer\' not found','Class \'Promo\\Writer\' not found'),('admin','Class \'Review\\Review\' not found','Class \'Review\\Review\' not found'),('admin','Class \'Review\\Writer\' not found','Class \'Review\\Writer\' not found'),('admin','class not exists `\\Filters\\classes\\BoolenFilter` in `Helpers\\Helpers::class_implements`','class not exists `\\Filters\\classes\\BoolenFilter` in `Helpers\\Helpers::class_implements`'),('admin','class not exists `\\Filters\\classes\\NumericIntFilter` in `Helpers\\Helpers::class_implements`','class not exists `\\Filters\\classes\\NumericIntFilter` in `Helpers\\Helpers::class_implements`'),('admin','Column \'html_mode\' cannot be null','Column \'html_mode\' cannot be null'),('admin','Column \'id\' cannot be null','Column \'id\' cannot be null'),('admin','Column \'info\' cannot be null','Column \'info\' cannot be null'),('admin','Column \'intro\' cannot be null','Column \'intro\' cannot be null'),('admin','Column \'name\' cannot be null','Column \'name\' cannot be null'),('admin','Column count doesn\'t match value count at row 1','Column count doesn\'t match value count at row 1'),('admin','component load error','Ошибка при загрузке компонента'),('admin','Data_editorEmoji:Filter fails on image: ValueIsInvalid:NEString','Data_editorEmoji:Filter fails on image: ValueIsInvalid:NEString'),('admin','Data_editorEmoji:Filter fails on tag: ValueIsInvalid:NEString','Data_editorEmoji:Filter fails on tag: ValueIsInvalid:NEString'),('admin','Data_editorMedia_vendor:Common:Cant find factory for filter DEfault0','Data_editorMedia_vendor:Common:Cant find factory for filter DEfault0'),('admin','Data_editorPackage_item:Filter fails on price: ValueIsInvalid:Float','Ценник указан некорректно'),('admin','Data_editorReview:Filter fails on rate: ValueIsInvalid:IntMore0','Data_editorReview:Filter fails on rate: ValueIsInvalid:IntMore0'),('admin','Data_editorTraining_hall:Filter fails on address: ValueIsInvalid:NEString','Адрес - обязательное поле'),('admin','Data_editorTraining_hall:Filter fails on lat: ValueIsInvalid:Float','Координаты - требуется указать'),('admin','Data_editorTraining_hall:Filter fails on name: ValueIsInvalid:NEString','Наименование - обязательное поле'),('admin','Duplicate entry \'Рыдание\' for key \'tag\'','Duplicate entry \'Рыдание\' for key \'tag\''),('admin','e.stopPropagetion is not a function','e.stopPropagetion is not a function'),('admin','EFO.Checks.formatPriceNDS is not a function','EFO.Checks.formatPriceNDS is not a function'),('admin','empty string','empty string'),('admin','error on uploading `%s`:image too small to upload in this context','error on uploading `%s`:image too small to upload in this context'),('admin','error on uploading `%s`:mime not supported for this context','error on uploading `%s`:mime not supported for this context'),('admin','errors while uploading','errors while uploading'),('admin','EveFlash is not defined','EveFlash is not defined'),('admin','field `common_name`: value_is_empty','field `common_name`: value_is_empty'),('admin','field `content_type`: value_is_empty','field `content_type`: value_is_empty'),('admin','field `image`: empty string','field `image`: empty string'),('admin','field `name_en`: empty string','field `name_en`: empty string'),('admin','field `name`: value_is_empty','field `name`: value_is_empty'),('admin','field `rate`: value_is_empty','field `rate`: value_is_empty'),('admin','field `season_id`: value_is_empty','field `season_id`: value_is_empty'),('admin','Filter fails on age_restriction: ValueIsInvalid:IntMore0','Filter fails on age_restriction: ValueIsInvalid:IntMore0'),('admin','Filter fails on birth_date: ValueIsInvalid:NEString','Filter fails on birth_date: ValueIsInvalid:NEString'),('admin','Filter fails on common_name: ValueIsEmpty','Filter fails on common_name: ValueIsEmpty'),('admin','Filter fails on common_name: ValueIsInvalid:NEString','Filter fails on common_name: ValueIsInvalid:NEString'),('admin','Filter fails on emoji: ValueIsInvalid:IntMore0','Filter fails on emoji: ValueIsInvalid:IntMore0'),('admin','Filter fails on family: ValueIsInvalid:NEString','Filter fails on family: ValueIsInvalid:NEString'),('admin','Filter fails on login: ValueIsInvalid:NEString','Filter fails on login: ValueIsInvalid:NEString'),('admin','Filter fails on name: ValueIsInvalid:NEString','Filter fails on name: ValueIsInvalid:NEString'),('admin','Filter fails on num: ValueIsInvalid:IntMore0','Filter fails on num: ValueIsInvalid:IntMore0'),('admin','Filter fails on phone: ValueIsInvalid:NEString','Filter fails on phone: ValueIsInvalid:NEString'),('admin','Filter fails on seasonseason_id: ValueIsInvalid:IntMore0','Filter fails on seasonseason_id: ValueIsInvalid:IntMore0'),('admin','FilterDef error: no filters','FilterDef error: no filters'),('admin','FloatMoreOr is not defined','FloatMoreOr is not defined'),('admin','FrontRegister_form:phone:NEString','FrontRegister_form:phone:NEString'),('admin','handle is not defined','handle is not defined'),('admin','Incorrect integer value: \'акака\' for column \'vertical\' at row 1','Incorrect integer value: \'акака\' for column \'vertical\' at row 1'),('admin','input.match is not a function','input.match is not a function'),('admin','Invalid or unexpected token','Invalid or unexpected token'),('admin','Invalid regular expression flags','Invalid regular expression flags'),('admin','invalid request','invalid request'),('admin','invalid server responce','invalid server responce'),('admin','invalid server response','Некорректный ответ сервера'),('admin','Invalid Unicode escape sequence','Invalid Unicode escape sequence'),('admin','jQuery(...).datepicker is not a function','jQuery(...).datepicker is not a function'),('admin','jQuery.is is not a function','jQuery.is is not a function'),('admin','Malformed arrow function parameter list','Malformed arrow function parameter list'),('admin','marker.setLngLat(...).addToMap is not a function','marker.setLngLat(...).addToMap is not a function'),('admin','mc is not defined','mc is not defined'),('admin','MediaColor_editor:empty','MediaColor_editor:empty'),('admin','MediaColor_editor:empty_link_text','MediaColor_editor:empty_link_text'),('admin','missing ) after argument list','missing ) after argument list'),('admin','model is not defined','model is not defined'),('admin','network error','network error'),('admin','no API action `API_create` found in `controllers\\FrontEnd\\InfoController`','no API action `API_create` found in `controllers\\FrontEnd\\InfoController`'),('admin','no API action `API_language_list` found in `controllers\\admin\\MediaContentController`','no API action `API_language_list` found in `controllers\\admin\\MediaContentController`'),('admin','no API action `API_post_image_crop_v2` found in `controllers\\MediaAPI\\ImageFlyController`','no API action `API_post_image_crop_v2` found in `controllers\\MediaAPI\\ImageFlyController`'),('admin','no API action `API_post` found in `controllers\\admin\\StatusController`','no API action `API_post` found in `controllers\\admin\\StatusController`'),('admin','no API action `API_put` found in `controllers\\admin\\MediaContentController`','no API action `API_put` found in `controllers\\admin\\MediaContentController`'),('admin','no API action `API_submit_profile_client` found in `controllers\\FrontEnd\\CabinetController`','no API action `API_submit_profile_client` found in `controllers\\FrontEnd\\CabinetController`'),('admin','no API action `API_submit_profile_fd_hole` found in `controllers\\FrontEnd\\CabinetController`','no API action `API_submit_profile_fd_hole` found in `controllers\\FrontEnd\\CabinetController`'),('admin','no appropriate writer method for content-type `ctBANNER` in controllers\\admin\\MediaContentController::API_put','no appropriate writer method for content-type `ctBANNER` in controllers\\admin\\MediaContentController::API_put'),('admin','no appropriate writer method for content-type `ctGIF` in controllers\\admin\\MediaContentController::API_put','no appropriate writer method for content-type `ctGIF` in controllers\\admin\\MediaContentController::API_put'),('admin','no appropriate writer method for content-type `ctSEASONSEASON` in controllers\\admin\\MediaContentController::API_put','no appropriate writer method for content-type `ctSEASONSEASON` in controllers\\admin\\MediaContentController::API_put'),('admin','no appropriate writer method for content-type `ctSEASONSERIES` in controllers\\admin\\MediaContentController::API_put','no appropriate writer method for content-type `ctSEASONSERIES` in controllers\\admin\\MediaContentController::API_put'),('admin','no appropriate writer method for content-type `ctSEASON` in controllers\\admin\\MediaContentController::API_put','no appropriate writer method for content-type `ctSEASON` in controllers\\admin\\MediaContentController::API_put'),('admin','no appropriate writer method for content-type `ctTEXT` in controllers\\admin\\MediaContentController::API_put','no appropriate writer method for content-type `ctTEXT` in controllers\\admin\\MediaContentController::API_put'),('admin','No template: Data_editorMediacontentType_editorGif_editor.TAB_content','No template: Data_editorMediacontentType_editorGif_editor.TAB_content'),('admin','No template: Data_editorMediacontentType_editorGif_editor.TAB_files','No template: Data_editorMediacontentType_editorGif_editor.TAB_files'),('admin','No template: Data_editorMediacontentType_editorGif_editor.TAB_intro','No template: Data_editorMediacontentType_editorGif_editor.TAB_intro'),('admin','No template: Data_editorMediacontentType_editorGif_editor.TAB_pers','No template: Data_editorMediacontentType_editorGif_editor.TAB_pers'),('admin','No template: Data_editorMediacontentType_editorGif_editor.TAB_posters','No template: Data_editorMediacontentType_editorGif_editor.TAB_posters'),('admin','No template: Data_editorMediacontentType_editorGif_editor.TAB_trailers','No template: Data_editorMediacontentType_editorGif_editor.TAB_trailers'),('admin','No template: error','No template: error'),('admin','No template: files','No template: files'),('admin','No template: popup','No template: popup'),('admin','no uploader method for context ctVIDEO in controllers\\admin\\CDNAPIController::API_get_uploader','no uploader method for context ctVIDEO in controllers\\admin\\CDNAPIController::API_get_uploader'),('admin','not found','Не найдено'),('admin','nothing selected','Ничего не выбрано'),('admin','NRY','NRY'),('admin','null is not an object (evaluating \'t.serie_id\')','null is not an object (evaluating \'t.serie_id\')'),('admin','Object.entries is not a function. (In \'Object.entries(t)\', \'Object.entries\' is undefined)','Object.entries is not a function. (In \'Object.entries(t)\', \'Object.entries\' is undefined)'),('admin','password required for new users','password required for new users'),('admin','passwords require 6 chars at least','passwords require 6 chars at least'),('admin','Plyr is not defined','Plyr is not defined'),('admin','popup.close is not a function','popup.close is not a function'),('admin','reader.readAsDataUrl is not a function','reader.readAsDataUrl is not a function'),('admin','remove_confirm_title','remove_confirm_title'),('admin','remove_image_confirm','remove_image_confirm'),('admin','render_point_data is not defined','render_point_data is not defined'),('admin','RequestIsEmpty!','RequestIsEmpty!'),('admin','ResizeObserver loop limit exceeded','ResizeObserver loop limit exceeded'),('admin','response is not defined','response is not defined'),('admin','run_authorization_sequence is not defined','run_authorization_sequence is not defined'),('admin','run_registration_sequence is not defined','run_registration_sequence is not defined'),('admin','save owner object to activate image manipulation','Сохраните запись чтобы активировать редактор картинок'),('admin','Script error.','Script error.'),('admin','search_serie is not defined','search_serie is not defined'),('admin','selected date does not matches selected interval','selected date does not matches selected interval'),('admin','selected time is buisy','selected time is buisy'),('admin','selectemoji is not defined','selectemoji is not defined'),('admin','SelectorsMediaAge_selector:nothing selected','SelectorsMediaAge_selector:nothing selected'),('admin','SelectorsMediaCountry_selector:nothing selected','SelectorsMediaCountry_selector:nothing selected'),('admin','SelectorsMediaTracklang_selector:nothing selected','SelectorsMediaTracklang_selector:nothing selected'),('admin','SelectorsMedia_person_selector:nothing selected','SelectorsMedia_person_selector:nothing selected'),('admin','serie is not defined','serie is not defined'),('admin','source is not defined','source is not defined'),('admin','SQLSTATE[22003]: Numeric value out of range: 1264 Out of range value for column \'sort\' at row 1','SQLSTATE[22003]: Numeric value out of range: 1264 Out of range value for column \'sort\' at row 1'),('admin','SQLSTATE[22007]: Invalid datetime format: 1292 Incorrect datetime value: \'0\' for column \'birth_date\' at row 1','SQLSTATE[22007]: Invalid datetime format: 1292 Incorrect datetime value: \'0\' for column \'birth_date\' at row 1'),('admin','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'333333\' for key \'name\'','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'333333\' for key \'name\''),('admin','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'70000000000\' for key \'user_by_phonestrip\'','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'70000000000\' for key \'user_by_phonestrip\''),('admin','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'pokaccio@gmail.com\' for key \'login\'','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'pokaccio@gmail.com\' for key \'login\''),('admin','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'Канада\' for key \'common_name\'','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'Канада\' for key \'common_name\''),('admin','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'Страх\' for key \'tag\'','SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry \'Страх\' for key \'tag\''),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'%s S1 ON(S1.id=A.id)\n            LEFT JOIN media__content__collection_strings_la\' at line 6','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'%s S1 ON(S1.id=A.id)\n            LEFT JOIN media__content__collection_strings_la\' at line 6'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'(LV1.name,LV2.name)name,\n            COALESCE(LV1.html_mode,LV2.html_mode)html_m\' at line 5','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'(LV1.name,LV2.name)name,\n            COALESCE(LV1.html_mode,LV2.html_mode)html_m\' at line 5'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'(P.price,0)price,\n            COALESCE(LV1.name,LV2.name)name,\n            COALE\' at line 3','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'(P.price,0)price,\n            COALESCE(LV1.name,LV2.name)name,\n            COALE\' at line 3'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \')\n                VALUES(\'sxsxs\',\'#000000\',\'0\');\n                \nSET @af8c92bbd\' at line 1','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \')\n                VALUES(\'sxsxs\',\'#000000\',\'0\');\n                \nSET @af8c92bbd\' at line 1'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'.common_name,\n            COALESCE(LV1.name,LV2.name)name,\n            COALESCE(\' at line 3','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'.common_name,\n            COALESCE(LV1.name,LV2.name)name,\n            COALESCE(\' at line 3'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'COALESCE(B.info,C.info) info\n            FROM media__studio A\n            LEFT J\' at line 4','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'COALESCE(B.info,C.info) info\n            FROM media__studio A\n            LEFT J\' at line 4'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'ISNERT INTO media__content__actor(name,html_mode_i,html_mode_c,image,intro,info)\' at line 1','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'ISNERT INTO media__content__actor(name,html_mode_i,html_mode_c,image,intro,info)\' at line 1'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'media__content__actor__strings_lang_en\' at line 1','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'media__content__actor__strings_lang_en\' at line 1'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'WHERE id=\'3\'\' at line 1','SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'WHERE id=\'3\'\' at line 1'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1065 Query was empty','SQLSTATE[42000]: Syntax error or access violation: 1065 Query was empty'),('admin','SQLSTATE[42000]: Syntax error or access violation: 1305 FUNCTION chill.COLAESCE does not exist','SQLSTATE[42000]: Syntax error or access violation: 1305 FUNCTION chill.COLAESCE does not exist'),('admin','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__origin\' doesn\'t exist','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__origin\' doesn\'t exist'),('admin','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__season_series\' doesn\'t exist','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__season_series\' doesn\'t exist'),('admin','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__tag_list\' doesn\'t exist','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__tag_list\' doesn\'t exist'),('admin','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__text__text__lang_ru\' doesn\'t exist','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__content__text__text__lang_ru\' doesn\'t exist'),('admin','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__genre\' doesn\'t exist','SQLSTATE[42S02]: Base table or view not found: 1146 Table \'chill.media__genre\' doesn\'t exist'),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'A.id\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'A.id\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'A.user_id\' in \'on clause\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'A.user_id\' in \'on clause\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'EMJ.tag\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'EMJ.tag\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'family\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'family\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'text\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'text\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'U.is\' in \'on clause\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'U.is\' in \'on clause\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'U.name\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'U.name\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'UF.famiy\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'UF.famiy\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'usafe_count\' in \'field list\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'usafe_count\' in \'field list\''),('admin','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'X.d\' in \'on clause\'','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'X.d\' in \'on clause\''),('admin','SQLSTATE[HY000]: General error: 1364 Field \'id\' doesn\'t have a default value','SQLSTATE[HY000]: General error: 1364 Field \'id\' doesn\'t have a default value'),('admin','SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens','SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens'),('admin','SQLSTATE[HY093]: Invalid parameter number: parameter was not defined','SQLSTATE[HY093]: Invalid parameter number: parameter was not defined'),('admin','Syntax error, unrecognized expression: [data-season-id=a{$season->id}]','Syntax error, unrecognized expression: [data-season-id=a{$season->id}]'),('admin','t is not defined','t is not defined'),('admin','Table \'chill.media__content__actor__strings__lang_ru\' doesn\'t exist','Table \'chill.media__content__actor__strings__lang_ru\' doesn\'t exist'),('admin','Table \'chill.media__content__tag_list\' doesn\'t exist','Table \'chill.media__content__tag_list\' doesn\'t exist'),('admin','Table \'fitness.fitness_places\' doesn\'t exist','Table \'fitness.fitness_places\' doesn\'t exist'),('admin','The operation is insecure.','The operation is insecure.'),('admin','this.each is not a function','this.each is not a function'),('admin','this.get_poster_url is not a function','this.get_poster_url is not a function'),('admin','this.get_table_item is not a function','this.get_table_item is not a function'),('admin','this.image_list.get_images_params is not a function','this.image_list.get_images_params is not a function'),('admin','this.init_table is not a function','this.init_table is not a function'),('admin','this.items.push is not a function','this.items.push is not a function'),('admin','this.on_network_fail is not a function','this.on_network_fail is not a function'),('admin','this.on_serie_access_fail is not a function','this.on_serie_access_fail is not a function'),('admin','this.reload is not a function','this.reload is not a function'),('admin','this.showlOader is not a function','this.showlOader is not a function'),('admin','this.source.setSource is not a function','this.source.setSource is not a function'),('admin','this._is_selected.bindToObjectWparam is not a function','this._is_selected.bindToObjectWparam is not a function'),('admin','this._select_season is not a function','this._select_season is not a function'),('admin','this._select_season_trailer is not a function','this._select_season_trailer is not a function'),('admin','this._select_series is not a function','this._select_series is not a function'),('admin','tt[i].get_type is not a function','tt[i].get_type is not a function'),('admin','U.AnyBool is not a function','U.AnyBool is not a function'),('admin','U.NESting is not a function','U.NESting is not a function'),('admin','Unclosed section \"has_default_image\" at 506','Unclosed section \"has_default_image\" at 506'),('admin','Unclosed section \"selected_role_exists\" at 1016','Unclosed section \"selected_role_exists\" at 1016'),('admin','Unclosed tag at 785','Unclosed tag at 785'),('admin','Unexpected end of input','Unexpected end of input'),('admin','Unexpected identifier','Unexpected identifier'),('admin','Unexpected token \'&&\'','Unexpected token \'&&\''),('admin','Unexpected token \')\'','Unexpected token \')\''),('admin','Unexpected token \'.\'','Unexpected token \'.\''),('admin','Unexpected token \'/\'','Unexpected token \'/\''),('admin','Unexpected token \';\'','Unexpected token \';\''),('admin','Unexpected token \'<\'','Unexpected token \'<\''),('admin','Unexpected token \'else\'','Unexpected token \'else\''),('admin','Unexpected token \'{\'','Unexpected token \'{\''),('admin','Unknown column \'genre_id\' in \'field list\'','Unknown column \'genre_id\' in \'field list\''),('admin','Unknown column \'og_decription\' in \'field list\'','Unknown column \'og_decription\' in \'field list\''),('admin','Unknown column \'target_url\' in \'field list\'','Unknown column \'target_url\' in \'field list\''),('admin','unknown error','unknown error'),('admin','unknown media context `infopage_gallery`','unknown media context `infopage_gallery`'),('admin','unknown media context `media_content_frame`','unknown media context `media_content_frame`'),('admin','unknown media context `media_content_poster`','unknown media context `media_content_poster`'),('admin','unknown media context `media_content_trailer`','unknown media context `media_content_trailer`'),('admin','unknown media context `media_person`','unknown media context `media_person`'),('admin','unknown media context `media_vendor`','unknown media context `media_vendor`'),('admin','value_is_empty','value_is_empty'),('admin','warning_title','warning_title'),('admin','window.Eve.chill_player is not a function','window.Eve.chill_player is not a function'),('admin','window.location.href is not a function','window.location.href is not a function'),('admin','window.location.pathname is not a function','window.location.pathname is not a function'),('admin','wundow is not defined','wundow is not defined'),('admin','x is not defined','x is not defined'),('admin','x.setDelegate is not a function','x.setDelegate is not a function'),('admin','x.set_allow_multi is not a function','x.set_allow_multi is not a function'),('admin','x.set_elegate is not a function','x.set_elegate is not a function'),('admin','x.set_uploader_params is not a function','x.set_uploader_params is not a function'),('admin','x.show(...).load(...).set_allow_multi(...).addCallback is not a function','x.show(...).load(...).set_allow_multi(...).addCallback is not a function'),('admin','x.show(...).se_allow_multi is not a function','x.show(...).se_allow_multi is not a function'),('admin','xx.get is not a function','xx.get is not a function'),('admin','Y.Load is not a function','Y.Load is not a function'),('admin','Y.report_fail is not a function','Y.report_fail is not a function'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \')\' at line 6','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \')\' at line 6'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \');    \n            \nDELETE FROM `media__content__properties` WHERE `id` = @a717e\' at line 6','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \');    \n            \nDELETE FROM `media__content__properties` WHERE `id` = @a717e\' at line 6'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \');    \n            \nINSERT INTO media__content__price(id,price)\n            VALU\' at line 13','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \');    \n            \nINSERT INTO media__content__price(id,price)\n            VALU\' at line 13'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'* FROM media__content__gif__strings WHERE id=@a1bd43810e7b4267ea93611f8ed150332;\' at line 1','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'* FROM media__content__gif__strings WHERE id=@a1bd43810e7b4267ea93611f8ed150332;\' at line 1'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'* FROM media__content__gif__strings WHERE id=@a3d36d75256bbc954fda0403aa684083f;\' at line 1','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'* FROM media__content__gif__strings WHERE id=@a3d36d75256bbc954fda0403aa684083f;\' at line 1'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'DELETE FROM media__content__origin WHERE id=@a1f4df33210d7f1598b9ca55c056670fd;\n\' at line 2','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'DELETE FROM media__content__origin WHERE id=@a1f4df33210d7f1598b9ca55c056670fd;\n\' at line 2'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'DELETE FROM media__content__origin WHERE id=@a3b5570bc2c3c7066bde36baa3907b3d4;\n\' at line 2','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'DELETE FROM media__content__origin WHERE id=@a3b5570bc2c3c7066bde36baa3907b3d4;\n\' at line 2'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'free=\'1\'   \n                WHERE id=@aa6775d5d98940ae65ed0326b70894ef9;\n       \' at line 6','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'free=\'1\'   \n                WHERE id=@aa6775d5d98940ae65ed0326b70894ef9;\n       \' at line 6'),('admin','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'UPADTE name=VALUES(name),meta_title=VALUES(meta_title),og_title=VALUES(og_title)\' at line 8','You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'UPADTE name=VALUES(name),meta_title=VALUES(meta_title),og_title=VALUES(og_title)\' at line 8'),('admin','Выберите время','Выберите время'),('admin','Ничего не выбрано','Ничего не выбрано'),('admin','Ничего не выбрано!','Ничего не выбрано!'),('admin','Сначала выберите зал','Сначала выберите зал'),('admin','Сначала необходимо сохранить сезон!','Сначала необходимо сохранить сезон!'),('admin','Укажите корректный email адрес!','Укажите корректный email адрес!'),('admin','Это время уже зарезервировано, выберите другое','Это время уже зарезервировано, выберите другое');
/*!40000 ALTER TABLE `lang__tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language__language`
--

DROP TABLE IF EXISTS `language__language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language__language` (
  `id` varchar(10) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`,`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language__language`
--

LOCK TABLES `language__language` WRITE;
/*!40000 ALTER TABLE `language__language` DISABLE KEYS */;
INSERT INTO `language__language` VALUES ('en','English','English',1,0),('ru','Russian','Русский',1,-100);
/*!40000 ALTER TABLE `language__language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__age__restriction`
--

DROP TABLE IF EXISTS `media__age__restriction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__age__restriction` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `international_name` varchar(255) NOT NULL,
  `default_image` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `international_name` (`international_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__age__restriction`
--

LOCK TABLES `media__age__restriction` WRITE;
/*!40000 ALTER TABLE `media__age__restriction` DISABLE KEYS */;
INSERT INTO `media__age__restriction` VALUES (1,'0+','38f22861713b138fd84ddfb18187ed54'),(3,'18+',NULL),(4,'16+',NULL),(5,'12+',NULL);
/*!40000 ALTER TABLE `media__age__restriction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__age__restriction__strings`
--

DROP TABLE IF EXISTS `media__age__restriction__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__age__restriction__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  CONSTRAINT `media__age__restriction__strings__2__language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__age__restriction__strings__2__media__age__restriction` FOREIGN KEY (`id`) REFERENCES `media__age__restriction` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__age__restriction__strings`
--

LOCK TABLES `media__age__restriction__strings` WRITE;
/*!40000 ALTER TABLE `media__age__restriction__strings` DISABLE KEYS */;
INSERT INTO `media__age__restriction__strings` VALUES (1,'en','for kidds'),(1,'ru','Для детишек'),(4,'en','16+'),(4,'ru','16+'),(5,'en','12+'),(5,'ru','12+');
/*!40000 ALTER TABLE `media__age__restriction__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content`
--

DROP TABLE IF EXISTS `media__content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) DEFAULT NULL,
  `ctype` varchar(100) NOT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '1',
  `age_restriction` bigint(19) unsigned DEFAULT NULL,
  `emoji` bigint(19) unsigned DEFAULT NULL,
  `track_language` bigint(19) unsigned DEFAULT NULL,
  `series_count` int(11) unsigned DEFAULT NULL,
  `seasons_count` int(11) unsigned DEFAULT NULL,
  `free` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `age_restriction` (`age_restriction`),
  KEY `emoji` (`emoji`),
  KEY `track_language` (`track_language`),
  CONSTRAINT `media__content__2__age__restriction` FOREIGN KEY (`age_restriction`) REFERENCES `media__age__restriction` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `media__content__2__emoji` FOREIGN KEY (`emoji`) REFERENCES `media__emoji` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `media__content__2__track__language` FOREIGN KEY (`track_language`) REFERENCES `media__content__tracklang` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content`
--

LOCK TABLES `media__content` WRITE;
/*!40000 ALTER TABLE `media__content` DISABLE KEYS */;
INSERT INTO `media__content` VALUES (51,'53bfbcf0-7da0-11ea-82f6-001e5826d92c','ctSEASON',1,3,NULL,1,7,1,0),(52,'c4b5c50b-7da1-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(54,'6258f0c5-7da2-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(55,'039b3dc9-7da3-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(56,'94ed9730-7da5-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(57,'0d88a5e9-7da6-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(58,'6b1c8704-7da6-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(59,'ce948727-7da6-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(60,'3d9acc05-7db0-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(66,'8334ff70-7f23-11ea-82f6-001e5826d92c','ctSEASON',1,3,NULL,1,9,1,0),(68,'3fa0a319-7f24-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(69,'4b079556-7f24-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(70,'f3a1df82-7f24-11ea-82f6-001e5826d92c','ctSEASON',1,4,NULL,1,5,1,0),(71,'68ae17b4-7f25-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(72,'6ee45617-7f25-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(73,'cf71f109-7f25-11ea-82f6-001e5826d92c','ctTRAILER',1,NULL,NULL,1,5,1,0),(74,'50d2d5ce-7f26-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(75,'108780d5-7f27-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(76,'5958bb43-7f27-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(77,'9f1a6350-7f27-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(78,'c92dcc8c-7f28-11ea-82f6-001e5826d92c','ctSEASON',1,4,NULL,1,4,1,0),(79,'458c53d1-7f29-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(80,'515bcdea-7f29-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(81,'71e34930-7f47-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(82,'837d78eb-7f47-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(83,'d14543aa-7f48-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(84,'a7b0cf74-7f4a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(85,'c7f49369-7f4a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(86,'d19a23ba-7f4a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(87,'dab092cb-7f4a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(88,'e4af0358-7f4a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(89,'2f7858e3-7f4b-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(90,'35cad9f8-7f4b-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(91,'3baa83b9-7f4b-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(109,'30d2ecfe-808b-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(110,'7501f279-808b-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(111,'c143eb20-808b-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(112,'ebcea9aa-808b-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(113,'2654887a-808c-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(114,'6814642e-808c-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(115,'060cb55f-808f-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(116,'69287b23-808f-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(117,'a0367035-808f-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(118,'3a18d8db-8090-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(119,'bd37735e-8090-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(120,'08567426-8091-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(128,'1096aea4-82dd-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(130,'f6870d2b-83e4-11ea-82f6-001e5826d92c','ctTRAILER',1,NULL,NULL,1,7,1,0),(131,'83c2e3d5-83fa-11ea-82f6-001e5826d92c','ctSEASON',1,5,NULL,1,10,1,0),(132,'2a0d5b81-83fb-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(133,'4a51c8b0-83fb-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(134,'250419c0-83fc-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(135,'0a799309-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(136,'1d5e9f4d-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(137,'2c0c6831-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(138,'3d93bdde-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(139,'4b382e82-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(140,'59d27395-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(141,'6bdab12c-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(142,'7a2cb101-83fd-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(143,'51e5a9a0-8416-11ea-82f6-001e5826d92c','ctSEASON',1,5,NULL,1,6,1,0),(144,'1de94893-8417-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(145,'28ed692d-8417-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(146,'dec294c0-8417-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(147,'82ad65d4-8418-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(148,'1af14f8d-8419-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(149,'3850f2aa-841a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(150,'14bab0b9-841e-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(151,'01ea0b4e-8468-11ea-82f6-001e5826d92c','ctTEXT',1,NULL,NULL,NULL,NULL,NULL,0),(155,'2e71dafe-8549-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,NULL,NULL,NULL,0),(157,'57c9027d-8549-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,NULL,NULL,NULL,0),(158,'906ae096-8549-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,NULL,NULL,NULL,0),(159,'2e7efdc5-854a-11ea-82f6-001e5826d92c','ctGIF',1,NULL,NULL,1,4,1,0),(160,'611d9448-854a-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,NULL,NULL,NULL,0),(161,'70d42b68-854a-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,1,8,9,0),(167,'9d1aa04b-8643-11ea-82f6-001e5826d92c','ctSEASON',0,3,NULL,NULL,NULL,NULL,0),(175,'ecb779c2-8726-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(176,'a73e1f74-872a-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(177,'16638ffc-872d-11ea-82f6-001e5826d92c','ctTRAILER',0,NULL,NULL,NULL,NULL,NULL,0),(181,'9782bf55-8965-11ea-82f6-001e5826d92c','ctGIF',1,NULL,NULL,1,5,1,0),(182,'b7714def-8965-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,NULL,NULL,NULL,0),(183,'ce915243-8965-11ea-82f6-001e5826d92c','ctGIF',0,NULL,NULL,1,NULL,NULL,0),(188,'e89c2b17-8985-11ea-82f6-001e5826d92c','ctSEASON',1,5,NULL,1,4,1,0),(189,'95465f59-8986-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(190,'a20ffc9e-8986-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(191,'a769be8e-8987-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(192,'b9fcd8ca-8988-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(193,'cace4b89-8989-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(195,'f45148d5-9063-11ea-82f6-001e5826d92c','ctSEASON',1,5,NULL,1,5,1,0),(196,'3aafad03-9065-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(198,'5655d51f-9068-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(199,'f5d64663-9077-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(200,'471403a9-907e-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(201,'f4e418e7-907e-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(202,'f7ce5685-907f-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(203,'be0932c6-9080-11ea-82f6-001e5826d92c','ctTRAILER',1,NULL,NULL,1,5,1,0),(206,'972e5015-9111-11ea-82f6-001e5826d92c','ctSEASON',0,5,NULL,1,NULL,NULL,0),(207,'98845e9b-9147-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(208,'e44339c3-9148-11ea-82f6-001e5826d92c','ctSEASONSERIES',0,NULL,NULL,NULL,NULL,NULL,0),(209,'7a4015db-9149-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(210,'86b17dda-9149-11ea-82f6-001e5826d92c','ctCOLLECTION',1,NULL,NULL,NULL,NULL,NULL,0),(212,'700b9726-9216-11ea-82f6-001e5826d92c','ctCOLLECTION',1,NULL,NULL,NULL,NULL,NULL,0),(213,'877fed15-9216-11ea-82f6-001e5826d92c','ctBANNER',1,NULL,NULL,NULL,NULL,NULL,0),(214,'c2987403-92a3-11ea-82f6-001e5826d92c','ctSEASON',1,4,NULL,1,10,1,0),(215,'af699ec6-92bc-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(216,'d4aa1f37-92bc-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(217,'1e9dd9c6-9368-11ea-82f6-001e5826d92c','ctSEASON',0,5,14,1,8,1,1),(218,'ba61fb29-9368-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(219,'b101e285-938d-11ea-82f6-001e5826d92c','ctSEASON',1,4,15,4,10,1,1),(220,'99e8a845-94ed-11ea-82f6-001e5826d92c','ctTRAILER',1,NULL,NULL,1,5,1,0),(221,'d277b920-9511-11ea-82f6-001e5826d92c','ctSEASON',1,4,NULL,1,3,1,1),(222,'dc1c9f5e-9511-11ea-82f6-001e5826d92c','ctSEASONSEASON',1,NULL,NULL,NULL,NULL,NULL,0),(223,'e579ad35-9511-11ea-82f6-001e5826d92c','ctSEASONSERIES',1,NULL,NULL,NULL,NULL,NULL,0),(224,'e678723b-9539-11ea-82f6-001e5826d92c','ctTRAILER',1,NULL,NULL,1,6,1,0);
/*!40000 ALTER TABLE `media__content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__actor`
--

DROP TABLE IF EXISTS `media__content__actor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__actor` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `common_name` varchar(512) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__actor`
--

LOCK TABLES `media__content__actor` WRITE;
/*!40000 ALTER TABLE `media__content__actor` DISABLE KEYS */;
INSERT INTO `media__content__actor` VALUES (5,'Riccardo Cannella',NULL),(6,'Nicolo Cappello',NULL),(7,'Tonino Basile',NULL),(8,'Ivan Olivieri',NULL),(9,'Antonietta Bello',NULL),(10,'Joachim Jung',NULL),(11,'Hannes Hellmann',NULL),(12,'Patrycia Ziolkowska',NULL),(13,'Peter Lohmeyer',NULL),(14,'Bernd Grawert',NULL),(15,'Angela Roy',NULL),(16,'Tomas Diaz',NULL),(17,'Nico Martinez Bergen',NULL),(18,'Dj Caso',NULL),(19,'Sebastian Rubilar',NULL),(20,'Anita Lira',NULL),(21,'Victoria Amenabar',NULL),(22,'Claudia Tapia',NULL),(23,'Gonzalo Dalgalarrando',NULL),(24,'Luke Eve',NULL),(25,'Glen Dolman',NULL),(26,'Tania Lambert',NULL),(27,'Sarah Blasko',NULL),(28,'Bethany Ryan',NULL),(29,'Gina Carter',NULL),(30,'Stephen Fry',NULL),(31,'Odessa Young',NULL),(32,'Benson Jack Anthony',NULL),(33,'Alyssa Rallo Bennet',NULL),(34,'Gary O. Bennet',NULL),(35,'Wylie Rush',NULL),(36,'Ben Sound',NULL),(37,'Keshko',NULL),(38,'Zoe Curzi',NULL),(39,'Jennifer McCabe',NULL),(40,'Ben Rosenthal',NULL),(41,'Azi Coppin',NULL),(42,'Sam Stagg',NULL),(43,'Paulina Gerzon',NULL),(44,'Claire Lilley',NULL),(45,'Maia Van De Mark',NULL),(46,'Owen Fitzpatrick',NULL),(47,'Jacob Nichols',NULL),(48,'Cara Hurley',NULL),(49,'Siena Richardson',NULL),(50,'Mercedes Flores',NULL),(51,'Ruby Frankel',NULL),(52,'Michael J. Bevan',NULL),(53,'Buchanan Highhouse',NULL),(54,'Katherine Trevas Schneider',NULL),(55,'ко',NULL),(56,'пшщ',NULL),(57,'Marta Alioto',NULL),(58,'Giulio Forges Davanzati',NULL),(59,'Chris Modoono',NULL),(60,'Gil Zabarsky',NULL),(61,'Gabriel Frye-Behar',NULL),(62,'Ethan Gustavson',NULL),(63,'Jean Roy',NULL),(64,'Alexandre Vigneault',NULL),(65,'David Marescot',NULL),(66,'Jerom Landglos',NULL),(67,'Marisol Alvarez',NULL);
/*!40000 ALTER TABLE `media__content__actor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__actor__properties`
--

DROP TABLE IF EXISTS `media__content__actor__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__actor__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `media__content__actor_props_2_actor` FOREIGN KEY (`id`) REFERENCES `media__content__actor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__actor__properties`
--

LOCK TABLES `media__content__actor__properties` WRITE;
/*!40000 ALTER TABLE `media__content__actor__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__actor__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__actor__strings_lang_en`
--

DROP TABLE IF EXISTS `media__content__actor__strings_lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__actor__strings_lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `html_mode` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'combined (0,1,2,3)',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__actor__lang_en__2_media__content__actor` FOREIGN KEY (`id`) REFERENCES `media__content__actor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__actor__strings_lang_en`
--

LOCK TABLES `media__content__actor__strings_lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__actor__strings_lang_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__actor__strings_lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__actor__strings_lang_ru`
--

DROP TABLE IF EXISTS `media__content__actor__strings_lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__actor__strings_lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `html_mode` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'combined (0,1,2,3)',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__actor__lang_ru__2_media__content__actor` FOREIGN KEY (`id`) REFERENCES `media__content__actor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__actor__strings_lang_ru`
--

LOCK TABLES `media__content__actor__strings_lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__actor__strings_lang_ru` DISABLE KEYS */;
INSERT INTO `media__content__actor__strings_lang_ru` VALUES (5,'Riccardo Cannella',3,'',''),(6,'Nicolo Cappello',3,'',''),(7,'Tonino Basile',3,'',''),(8,'Иван Оливьери',3,'',''),(9,'Антониетта Белло',3,'',''),(10,'Joachim Jung',3,'',''),(11,'Hannes Hellmann',3,'',''),(12,'Patrycia Ziolkowska',3,'',''),(13,'Peter Lohmeyer',3,'',''),(14,'Bernd Grawert',3,'',''),(15,'Angela Roy',3,'',''),(16,'Tomas Diaz',3,'',''),(17,'Nico Martinez Bergen',3,'',''),(18,'Dj Caso',3,'',''),(19,'Sebastian Rubilar',3,'',''),(20,'Anita Lira',3,'',''),(21,'Victoria Amenabar',3,'',''),(22,'Claudia Tapia',3,'',''),(23,'Gonzalo Dalgalarrando',3,'',''),(24,'Luke Eve',3,'',''),(25,'Glen Dolman',3,'',''),(26,'Tania Lambert',3,'',''),(27,'Sarah Blasko',3,'',''),(28,'Bethany Ryan',3,'',''),(29,'Gina Carter',3,'',''),(30,'Stephen Fry',3,'',''),(31,'Odessa Young',3,'',''),(32,'Benson Jack Anthony',3,'',''),(33,'Alyssa Rallo Bennet',3,'',''),(34,'Gary O. Bennet',3,'',''),(35,'Wylie Rush',3,'',''),(36,'Ben Sound',3,'',''),(37,'Keshko',3,'',''),(38,'Zoe Curzi',3,'',''),(39,'Jennifer McCabe',3,'',''),(40,'Ben Rosenthal',3,'',''),(41,'Azi Coppin',3,'',''),(42,'Sam Stagg',3,'',''),(43,'Paulina Gerzon',3,'',''),(44,'Claire Lilley',3,'',''),(45,'Maia Van De Mark',3,'',''),(46,'Owen Fitzpatrick',3,'',''),(47,'Jacob Nichols',3,'',''),(48,'Cara Hurley',3,'',''),(49,'Siena Richardson',3,'',''),(50,'Mercedes Flores',3,'',''),(51,'Ruby Frankel',3,'',''),(52,'Michael J. Bevan',3,'',''),(53,'Buchanan Highhouse',3,'',''),(54,'Katherine Trevas Schneider',3,'',''),(55,'ко',3,'',''),(56,'нкн',3,'',''),(57,'Марта Алиото',3,'',''),(58,'Джулио Форджес Даванзати',3,'',''),(59,'Крис Модуно',3,'',''),(60,'Гил Забарский',3,'',''),(61,'Гэбриэл Фрай-Бехар',3,'',''),(62,'Итан Густавсон',3,'',''),(63,'Жан Рой',3,'',''),(64,'Александр Виньо',3,'',''),(65,'Дэвид Мареско',3,'',''),(66,'Жером Лэндгло',3,'',''),(67,'Марисоль Альварез',3,'','');
/*!40000 ALTER TABLE `media__content__actor__strings_lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__banner`
--

DROP TABLE IF EXISTS `media__content__banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__banner` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `background` varchar(25) DEFAULT NULL,
  `text_color` varchar(25) DEFAULT NULL,
  `default_poster` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__banner_2_media__contet` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__banner`
--

LOCK TABLES `media__content__banner` WRITE;
/*!40000 ALTER TABLE `media__content__banner` DISABLE KEYS */;
INSERT INTO `media__content__banner` VALUES (213,'DR new','#f5b500','#000000','c9cba77ce7a5b060553c4dc2d74492ed');
/*!40000 ALTER TABLE `media__content__banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__banner__strings`
--

DROP TABLE IF EXISTS `media__content__banner__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__banner__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `bannertext` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  CONSTRAINT `media__content__banner__strings_banner` FOREIGN KEY (`id`) REFERENCES `media__content__banner` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__banner__strings_language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__banner__strings`
--

LOCK TABLES `media__content__banner__strings` WRITE;
/*!40000 ALTER TABLE `media__content__banner__strings` DISABLE KEYS */;
INSERT INTO `media__content__banner__strings` VALUES (213,'en','https://digitalreporter.ru/',NULL),(213,'ru','https://digitalreporter.ru/',NULL);
/*!40000 ALTER TABLE `media__content__banner__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__cdn__file`
--

DROP TABLE IF EXISTS `media__content__cdn__file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__cdn__file` (
  `id` bigint(19) unsigned NOT NULL,
  `cdn_id` varchar(200) NOT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '0',
  `content_type` varchar(120) NOT NULL,
  `size` varchar(100) NOT NULL,
  `info` mediumblob NOT NULL,
  PRIMARY KEY (`id`,`cdn_id`),
  CONSTRAINT `media__content__video__file__2__media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__cdn__file`
--

LOCK TABLES `media__content__cdn__file` WRITE;
/*!40000 ALTER TABLE `media__content__cdn__file` DISABLE KEYS */;
INSERT INTO `media__content__cdn__file` VALUES (54,'5eb6dd390e47cf684413d6c2',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd390e47cf684413d6c2\",\"name\":\"ANACHRONISME_EP.01_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_54/anachronisme_ep.01_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":139856040,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:41:29\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd390e47cf684413d6c2\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_54/anachronisme_ep.01_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":126999,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":573.461333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1951000,\"duration\":573.474,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1817090,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":573.473473,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd390e47cf684413d6c2\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd3c0e47cf684413d6c7.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd3c0e47cf684413d6c5.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd3c0e47cf684413d6c3.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(55,'5eb6dc98ef3db53f42a30817',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc98ef3db53f42a30817\",\"name\":\"ANACHRONISME_EP.02_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_55/anachronisme_ep.02_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":123837301,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:48\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc98ef3db53f42a30817\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_55/anachronisme_ep.02_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":122071,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":506.78,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1954784,\"duration\":506.807,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1825796,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":506.806974,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc98ef3db53f42a30817\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc9aef3db53f42a3081c.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc9aef3db53f42a3081a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc9aef3db53f42a30818.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(56,'5eb6dc280e47cf684413d644',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc280e47cf684413d644\",\"name\":\"ANACHRONISME_EP.03_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_56/anachronisme_ep.03_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":113239880,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:56\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc280e47cf684413d644\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_56/anachronisme_ep.03_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":109274,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":473.664,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1912536,\"duration\":473.674,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1796345,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":473.673674,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc280e47cf684413d644\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc2a0e47cf684413d649.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc2a0e47cf684413d647.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc2a0e47cf684413d645.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(57,'5eb6dca4ef3db53f42a3081f',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dca4ef3db53f42a3081f\",\"name\":\"ANACHRONISME_EP.04_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_57/anachronisme_ep.04_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":127611234,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:39:00\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dca4ef3db53f42a3081f\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_57/anachronisme_ep.04_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":124030,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":523.697,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1949289,\"duration\":523.724,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1818344,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":523.723974,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dca4ef3db53f42a3081f\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dca7ef3db53f42a30824.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dca7ef3db53f42a30822.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dca7ef3db53f42a30820.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(58,'5eb6dd10ef3db53f42a3087c',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd10ef3db53f42a3087c\",\"name\":\"ANACHRONISME_EP.05_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_58/anachronisme_ep.05_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":130853464,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:48\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd10ef3db53f42a3087c\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_58/anachronisme_ep.05_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":125328,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":536.088,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1952646,\"duration\":536.107,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1820415,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":536.10302,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd10ef3db53f42a3087c\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd14ef3db53f42a30884.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd14ef3db53f42a30882.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd14ef3db53f42a30880.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(59,'5eb6dc48ef3db53f42a307ff',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc48ef3db53f42a307ff\",\"name\":\"ANACHRONISME_EP.06_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_59/anachronisme_ep.06_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":107919590,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:37:28\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc48ef3db53f42a307ff\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_59/anachronisme_ep.06_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":107491,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":445.848,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1936298,\"duration\":445.88,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1821891,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":445.879963,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc48ef3db53f42a307ff\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc4def3db53f42a30804.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc4def3db53f42a30802.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc4def3db53f42a30800.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(60,'5eb6dc3a0e47cf684413d64c',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc3a0e47cf684413d64c\",\"name\":\"ANACHRONISME_EP.07_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/season_52/series_60/anachronisme_ep.07_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":88144116,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:37:14\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc3a0e47cf684413d64c\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/season_52/series_60/anachronisme_ep.07_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127123,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":382.037333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1845713,\"duration\":382.049,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1711669,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":382.048715,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc3a0e47cf684413d64c\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc3b0e47cf684413d651.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc3b0e47cf684413d64f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc3b0e47cf684413d64d.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(69,'5eb6dc420e47cf684413d654',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc420e47cf684413d654\",\"name\":\"WIGU 1 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_69/wigu 1 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":47796668,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:37:22\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc420e47cf684413d654\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_69/wigu%201%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":125873,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":183.344989,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2085471,\"duration\":183.351,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1951437,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":183.350017,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc420e47cf684413d654\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc430e47cf684413d659.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc430e47cf684413d657.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc430e47cf684413d655.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(72,'5eb6dce20e47cf684413d692',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dce20e47cf684413d692\",\"name\":\"Ep.1_Jenny_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/70/season_71/series_72/ep.1_jenny_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":73425594,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:02\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dce20e47cf684413d692\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/70/season_71/series_72/ep.1_jenny_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127893,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":275.392,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2132976,\"duration\":275.392,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1998266,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":275.375375,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dce20e47cf684413d692\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dce40e47cf684413d697.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dce40e47cf684413d695.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dce40e47cf684413d693.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(73,'5eb6dced0e47cf684413d69a',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dced0e47cf684413d69a\",\"name\":\"Trailer_Jan2019 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/70/trailers/73/trailer_jan2019 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":10825146,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:13\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dced0e47cf684413d69a\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/70/trailers/73/trailer_jan2019%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":108951,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":43.630295,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":1984854,\"duration\":43.631,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1869762,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":43.610277,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dced0e47cf684413d69a\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dced0e47cf684413d69f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dced0e47cf684413d69d.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dced0e47cf684413d69b.jpg\"],\"description\":\"\",\"private\":false,\"status\":\"ok\",\"perms\":null}'),(74,'5eb6dccdef3db53f42a3082f',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dccdef3db53f42a3082f\",\"name\":\"Ep.2_Peter_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/70/season_71/series_74/ep.2_peter_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":69551465,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:39:41\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dccdef3db53f42a3082f\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/70/season_71/series_74/ep.2_peter_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128437,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":269.165,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2067031,\"duration\":269.184,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1931762,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":269.170003,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dccdef3db53f42a3082f\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dccfef3db53f42a30834.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dccfef3db53f42a30832.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dccfef3db53f42a30830.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(75,'5eb6dcf90e47cf684413d6a2',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dcf90e47cf684413d6a2\",\"name\":\"Ep.3_Lisa1_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/70/season_71/series_75/ep.3_lisa1_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":33434266,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:25\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dcf90e47cf684413d6a2\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/70/season_71/series_75/ep.3_lisa1_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":124430,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":133.354667,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2005549,\"duration\":133.367,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1874154,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":133.3667,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dcf90e47cf684413d6a2\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcfa0e47cf684413d6a7.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcfa0e47cf684413d6a5.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcfa0e47cf684413d6a3.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(76,'5eb6dc8aef3db53f42a3080f',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc8aef3db53f42a3080f\",\"name\":\"Ep.4_ConcreteHead_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/70/season_71/series_76/ep.4_concretehead_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":61371723,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:34\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc8aef3db53f42a3080f\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/70/season_71/series_76/ep.4_concretehead_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":126961,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":234.733,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2091457,\"duration\":234.752,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1957695,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":234.734985,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc8aef3db53f42a3080f\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc8cef3db53f42a30814.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc8cef3db53f42a30812.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc8cef3db53f42a30810.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(77,'5eb6dcd2ef3db53f42a30837',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dcd2ef3db53f42a30837\",\"name\":\"Ep.5_Lisa_is_back_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/70/season_71/series_77/ep.5_lisa_is_back_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":59048552,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:39:46\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dcd2ef3db53f42a30837\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/70/season_71/series_77/ep.5_lisa_is_back_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127715,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":223.939,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2109272,\"duration\":223.958,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1974617,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":223.958041,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dcd2ef3db53f42a30837\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcd3ef3db53f42a3083c.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcd3ef3db53f42a3083a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcd3ef3db53f42a30838.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(80,'5eb6dcf0ef3db53f42a30857',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dcf0ef3db53f42a30857\",\"name\":\"RUMIS-- CAP 01 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/78/season_79/series_80/rumis-- cap 01 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":100150730,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:16\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dcf0ef3db53f42a30857\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/78/season_79/series_80/rumis--%20cap%2001%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128888,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":407.153,\"index\":1,\"language\":\"und\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1967723,\"duration\":407.174,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1831912,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":407.174007,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dcf0ef3db53f42a30857\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcf3ef3db53f42a3085c.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcf3ef3db53f42a3085a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcf3ef3db53f42a30858.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(81,'5eb6dd210e47cf684413d6ba',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd210e47cf684413d6ba\",\"name\":\"RUMIS-- CAP 02 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/78/season_79/series_81/rumis-- cap 02 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":108011431,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:41:05\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd210e47cf684413d6ba\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/78/season_79/series_81/rumis--%20cap%2002%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128611,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":416.579333,\"index\":1,\"language\":\"und\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2074231,\"duration\":416.584,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1938701,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":416.58325,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd210e47cf684413d6ba\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd230e47cf684413d6bf.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd230e47cf684413d6bd.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd230e47cf684413d6bb.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(82,'5eb6dcd60e47cf684413d68a',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dcd60e47cf684413d68a\",\"name\":\"RUMIS-- CAP 03_encoded (1280x720) (1280xauto).mp4\",\"path\":\"/soap/78/season_79/series_82/rumis-- cap 03_encoded (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":76979540,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:39:50\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dcd60e47cf684413d68a\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/78/season_79/series_82/rumis--%20cap%2003_encoded%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128666,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":301.44,\"index\":1,\"language\":\"rus\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2042981,\"duration\":301.44,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1907415,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":301.434768,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dcd60e47cf684413d68a\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcd80e47cf684413d68f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcd80e47cf684413d68d.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcd80e47cf684413d68b.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(83,'5eb6dd100e47cf684413d6b2',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd100e47cf684413d6b2\",\"name\":\"RUMIS-- CAP 04 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/78/season_79/series_83/rumis-- cap 04 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":108264615,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:48\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd100e47cf684413d6b2\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/78/season_79/series_83/rumis--%20cap%2004%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128578,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":422.979333,\"index\":1,\"language\":\"und\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2047654,\"duration\":422.98,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1912260,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":422.95629,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd100e47cf684413d6b2\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd120e47cf684413d6b7.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd120e47cf684413d6b5.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd120e47cf684413d6b3.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(84,'5eb6dcb0ef3db53f42a30827',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dcb0ef3db53f42a30827\",\"name\":\"WIGU 2 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_84/wigu 2 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":106526517,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:39:12\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dcb0ef3db53f42a30827\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_84/wigu%202%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128704,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":398.867007,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2136228,\"duration\":398.933,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1999496,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":398.933016,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dcb0ef3db53f42a30827\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcb3ef3db53f42a3082c.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcb3ef3db53f42a3082a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcb3ef3db53f42a30828.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(85,'5eb6dc910e47cf684413d67a',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc910e47cf684413d67a\",\"name\":\"WIGU 3 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_85/wigu 3 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":79688211,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:41\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc910e47cf684413d67a\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_85/wigu%203%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":129176,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":304.998231,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2090141,\"duration\":305.006,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1952758,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":305.005005,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc910e47cf684413d67a\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc930e47cf684413d67f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc930e47cf684413d67d.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc930e47cf684413d67b.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(86,'5eb6dc960e47cf684413d682',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc960e47cf684413d682\",\"name\":\"WIGU 4 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_86/wigu 4 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":88808368,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:46\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc960e47cf684413d682\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_86/wigu%204%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127571,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":336.877438,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2108974,\"duration\":336.878,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1973501,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":336.836837,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc960e47cf684413d682\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc9d0e47cf684413d687.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc9d0e47cf684413d685.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc9d0e47cf684413d683.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(87,'5eb6dc860e47cf684413d672',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc860e47cf684413d672\",\"name\":\"WIGU 5 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_87/wigu 5 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":102036669,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:30\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc860e47cf684413d672\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_87/wigu%205%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128870,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":384.594218,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2122475,\"duration\":384.595,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1985497,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":384.584585,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc860e47cf684413d672\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc8a0e47cf684413d677.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc8a0e47cf684413d675.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc8a0e47cf684413d673.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(88,'5eb6dd0def3db53f42a30877',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd0def3db53f42a30877\",\"name\":\"WIGU 6 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_88/wigu 6 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":108607440,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:45\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd0def3db53f42a30877\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_88/wigu%206%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127984,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":413.689002,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2100145,\"duration\":413.714,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1964035,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":413.713964,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd0def3db53f42a30877\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd10ef3db53f42a3087d.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd10ef3db53f42a3087a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd10ef3db53f42a30878.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(89,'5eb6dc77ef3db53f42a30807',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc77ef3db53f42a30807\",\"name\":\"WIGU 7 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_89/wigu 7 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":60116384,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:15\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc77ef3db53f42a30807\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_89/wigu%207%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128970,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":229.578005,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2094656,\"duration\":229.599,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1957503,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":229.597014,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc77ef3db53f42a30807\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc79ef3db53f42a3080c.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc79ef3db53f42a3080a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc79ef3db53f42a30808.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(90,'5eb6dd070e47cf684413d6aa',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd070e47cf684413d6aa\",\"name\":\"WIGU 8 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_90/wigu 8 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":93895721,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:39\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd070e47cf684413d6aa\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_90/wigu%208%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128889,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":353.64322,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2124073,\"duration\":353.644,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1987167,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":353.620287,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd070e47cf684413d6aa\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd090e47cf684413d6af.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd090e47cf684413d6ad.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd090e47cf684413d6ab.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(91,'5eb6dcf3ef3db53f42a3085f',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dcf3ef3db53f42a3085f\",\"name\":\"WIGU 9 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/66/season_68/series_91/wigu 9 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":88930107,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:40:19\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dcf3ef3db53f42a3085f\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/66/season_68/series_91/wigu%209%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":125735,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":340.825011,\"index\":1,\"language\":\"und\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2087279,\"duration\":340.846,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1953404,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":340.841008,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dcf3ef3db53f42a3085f\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcf9ef3db53f42a30874.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcf9ef3db53f42a30872.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dcf9ef3db53f42a30870.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(130,'5eb6dc13ef3db53f42a307e7',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc13ef3db53f42a307e7\",\"name\":\"Anachronisme - Trailer (1280x720) (1280xauto).mp4\",\"path\":\"/soap/51/trailers/130/anachronisme - trailer (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":19796980,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:35\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc13ef3db53f42a307e7\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/51/trailers/130/anachronisme%20-%20trailer%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":125037,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":79.159,\"index\":0,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1999366,\"duration\":79.213,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1867361,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":79.212963,\"fps\":29.97,\"height\":720,\"index\":1,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc13ef3db53f42a307e7\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc14ef3db53f42a307ec.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc14ef3db53f42a307ea.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc14ef3db53f42a307e8.jpg\"],\"description\":\"\",\"private\":false,\"status\":\"ok\",\"perms\":null}'),(133,'5eb6da86ef3db53f42a30753',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6da86ef3db53f42a30753\",\"name\":\"Dean\'s Office_EP 1 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_133/dean\'s office_ep 1 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":97841532,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:29:58\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6da86ef3db53f42a30753\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_133/dean%27s%20office_ep%201%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128751,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":387.316,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2020710,\"duration\":387.355,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1885044,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":387.355022,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6da86ef3db53f42a30753\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6da88ef3db53f42a30758.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6da88ef3db53f42a30756.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6da88ef3db53f42a30754.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(134,'5eb6daa7ef3db53f42a3075c',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6daa7ef3db53f42a3075c\",\"name\":\"Dean\'s Office_EP 2 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_134/dean\'s office_ep 2 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":116587652,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:30:31\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6daa7ef3db53f42a3075c\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_134/dean%27s%20office_ep%202%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128736,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":457.347,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2039177,\"duration\":457.391,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1903527,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":457.390974,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6daa7ef3db53f42a3075c\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daacef3db53f42a30768.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daacef3db53f42a30766.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daacef3db53f42a30764.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(135,'5eb6daa6ef3db53f42a3075b',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6daa6ef3db53f42a3075b\",\"name\":\"Dean\'s Office_EP 3 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_135/dean\'s office_ep 3 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":100359663,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:30:30\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6daa6ef3db53f42a3075b\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_135/dean%27s%20office_ep%203%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":129172,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":412.184,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1947724,\"duration\":412.213,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1811635,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":412.212963,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6daa6ef3db53f42a3075b\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daabef3db53f42a30761.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daabef3db53f42a3075f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daabef3db53f42a3075d.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(136,'5eb6dadf0e47cf684413d614',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dadf0e47cf684413d614\",\"name\":\"Dean\'s Office_EP 4 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_136/dean\'s office_ep 4 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":108461224,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:31:27\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dadf0e47cf684413d614\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_136/dean%27s%20office_ep%204%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128297,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":442.933333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1958919,\"duration\":442.943,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1823703,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":442.942943,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dadf0e47cf684413d614\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dae10e47cf684413d619.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dae10e47cf684413d617.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dae10e47cf684413d615.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(137,'5eb6dacdef3db53f42a3076b',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dacdef3db53f42a3076b\",\"name\":\"Dean\'s Office_EP 5 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_137/dean\'s office_ep 5 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":96308908,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:31:09\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dacdef3db53f42a3076b\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_137/dean%27s%20office_ep%205%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128299,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":390.329,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1973755,\"duration\":390.358,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1838539,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":390.358025,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dacdef3db53f42a3076b\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dad1ef3db53f42a30770.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dad1ef3db53f42a3076e.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dad1ef3db53f42a3076c.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(138,'5eb6dae6ef3db53f42a30773',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dae6ef3db53f42a30773\",\"name\":\"Dean\'s Office_EP 6 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_138/dean\'s office_ep 6 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":88826696,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:31:34\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dae6ef3db53f42a30773\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_138/dean%27s%20office_ep%206%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128661,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":371.228,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1913997,\"duration\":371.272,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1778421,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":371.272022,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dae6ef3db53f42a30773\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dae8ef3db53f42a30778.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dae8ef3db53f42a30776.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dae8ef3db53f42a30774.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(139,'5eb6daeb0e47cf684413d61c',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6daeb0e47cf684413d61c\",\"name\":\"Dean\'s Office_EP 7 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_139/dean\'s office_ep 7 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":128109222,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:31:39\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6daeb0e47cf684413d61c\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_139/dean%27s%20office_ep%207%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128618,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":513.621333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1995385,\"duration\":513.622,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1859879,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":513.613614,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6daeb0e47cf684413d61c\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daee0e47cf684413d621.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daee0e47cf684413d61f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daee0e47cf684413d61d.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(140,'5eb6daf30e47cf684413d624',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6daf30e47cf684413d624\",\"name\":\"Dean\'s Office_EP 8 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_140/dean\'s office_ep 8 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":77409211,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:31:47\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6daf30e47cf684413d624\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_140/dean%27s%20office_ep%208%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128639,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":328.052333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1887672,\"duration\":328.062,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1752109,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":328.061395,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6daf30e47cf684413d624\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daf50e47cf684413d629.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daf50e47cf684413d627.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daf50e47cf684413d625.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(141,'5eb6daf50e47cf684413d62c',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6daf50e47cf684413d62c\",\"name\":\"Dean\'s Office_EP 9 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_141/dean\'s office_ep 9 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":111969268,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:31:49\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6daf50e47cf684413d62c\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_141/dean%27s%20office_ep%209%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128138,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":463.253333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1933571,\"duration\":463.264,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1798518,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":463.263263,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6daf50e47cf684413d62c\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daf90e47cf684413d631.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daf90e47cf684413d62f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6daf90e47cf684413d62d.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(142,'5eb6db15ef3db53f42a3077b',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6db15ef3db53f42a3077b\",\"name\":\"Dean\'s Office_EP 10 - Full Sound (1280x720) (1280xauto).mp4\",\"path\":\"/soap/131/season_132/series_142/dean\'s office_ep 10 - full sound (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":91460433,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:32:21\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6db15ef3db53f42a3077b\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/131/season_132/series_142/dean%27s%20office_ep%2010%20-%20full%20sound%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128761,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":397.956009,\"index\":0,\"language\":\"eng\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":1838409,\"duration\":397.998,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1702849,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":397.998,\"fps\":29.97002997002997,\"height\":720,\"index\":1,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6db15ef3db53f42a3077b\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db19ef3db53f42a30780.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db19ef3db53f42a3077e.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db19ef3db53f42a3077c.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(145,'5eb6db2cef3db53f42a30783',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6db2cef3db53f42a30783\",\"name\":\"HIGH-LIFE_101_TheAscent_H264_10mbps_1080p25_2ch_EN-LtRt_Web-Video (1280x720) (1280xauto).mp4\",\"path\":\"/soap/143/season_144/series_145/high-life_101_theascent_h264_10mbps_1080p25_2ch_en-ltrt_web-video (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":141427793,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:32:44\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6db2cef3db53f42a30783\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/143/season_144/series_145/high-life_101_theascent_h264_10mbps_1080p25_2ch_en-ltrt_web-video%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128235,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":539.873,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2095587,\"duration\":539.907,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1960439,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":539.90699,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6db2cef3db53f42a30783\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db30ef3db53f42a30788.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db30ef3db53f42a30786.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db30ef3db53f42a30784.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(146,'5eb6db72ef3db53f42a3079f',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6db72ef3db53f42a3079f\",\"name\":\"HIGH-LIFE_102_TheRush_H264_10mbps_1080p25_2ch_EN-LtRt_Web-Video (1280x720) (1280xauto).mp4\",\"path\":\"/soap/143/season_144/series_146/high-life_102_therush_h264_10mbps_1080p25_2ch_en-ltrt_web-video (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":164918479,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:33:54\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6db72ef3db53f42a3079f\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/143/season_144/series_146/high-life_102_therush_h264_10mbps_1080p25_2ch_en-ltrt_web-video%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128410,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":629.735,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2094987,\"duration\":629.764,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1959667,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":629.764014,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6db72ef3db53f42a3079f\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db75ef3db53f42a307a4.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db75ef3db53f42a307a2.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db75ef3db53f42a307a0.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(147,'5eb6db55ef3db53f42a30797',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6db55ef3db53f42a30797\",\"name\":\"HIGH-LIFE_103_TheTwist_H264_10mbps_1080p25_2ch_EN-LtRt_Web-Video (1280x720) (1280xauto).mp4\",\"path\":\"/soap/143/season_144/series_147/high-life_103_thetwist_h264_10mbps_1080p25_2ch_en-ltrt_web-video (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":151446986,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:33:25\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6db55ef3db53f42a30797\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/143/season_144/series_147/high-life_103_thetwist_h264_10mbps_1080p25_2ch_en-ltrt_web-video%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128470,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":583.389,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2076689,\"duration\":583.417,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1941306,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":583.417,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6db55ef3db53f42a30797\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db58ef3db53f42a3079c.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db58ef3db53f42a3079a.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db58ef3db53f42a30798.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(148,'5eb6dba30e47cf684413d634',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dba30e47cf684413d634\",\"name\":\"HIGH-LIFE_104_ThePinnacle_H264_10mbps_1080p25_2ch_EN-LtRt_Web-Video (1280x720) (1280xauto).mp4\",\"path\":\"/soap/143/season_144/series_148/high-life_104_thepinnacle_h264_10mbps_1080p25_2ch_en-ltrt_web-video (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":171564506,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:34:43\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dba30e47cf684413d634\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/143/season_144/series_148/high-life_104_thepinnacle_h264_10mbps_1080p25_2ch_en-ltrt_web-video%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128408,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":658.858667,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2083171,\"duration\":658.859,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1947850,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":658.858859,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dba30e47cf684413d634\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dba70e47cf684413d639.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dba70e47cf684413d637.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dba70e47cf684413d635.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(149,'5eb6db99ef3db53f42a307a7',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6db99ef3db53f42a307a7\",\"name\":\"HIGH-LIFE_105_TheFall_H264_10mbps_1080p25_2ch_EN-LtRt_Web-Video (1280x720) (1280xauto).mp4\",\"path\":\"/soap/143/season_144/series_149/high-life_105_thefall_h264_10mbps_1080p25_2ch_en-ltrt_web-video (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":147994629,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:34:33\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6db99ef3db53f42a307a7\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/143/season_144/series_149/high-life_105_thefall_h264_10mbps_1080p25_2ch_en-ltrt_web-video%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128588,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":567.383991,\"index\":1,\"language\":\"eng\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2086617,\"duration\":567.405,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1951243,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":567.400984,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6db99ef3db53f42a307a7\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db9cef3db53f42a307ac.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db9cef3db53f42a307aa.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6db9cef3db53f42a307a8.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(150,'5eb6dbc2ef3db53f42a307af',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dbc2ef3db53f42a307af\",\"name\":\"HIGH-LIFE_106_TheReturn_H264_10mbps_1080p25_2ch_EN-LtRt_Web-Video (1280x720) (1280xauto).mp4\",\"path\":\"/soap/143/season_144/series_150/high-life_106_thereturn_h264_10mbps_1080p25_2ch_en-ltrt_web-video (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":164153321,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:35:14\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dbc2ef3db53f42a307af\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/143/season_144/series_150/high-life_106_thereturn_h264_10mbps_1080p25_2ch_en-ltrt_web-video%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128619,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":629.731995,\"index\":1,\"language\":\"eng\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":2085267,\"duration\":629.764,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1949855,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":629.764014,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dbc2ef3db53f42a307af\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbc9ef3db53f42a307b4.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbc9ef3db53f42a307b2.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbc9ef3db53f42a307b0.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(176,'5ea49231ef3db514e77657b7',1,'video/mp4','1280x720',_binary '{\"id\":\"5ea49231ef3db514e77657b7\",\"name\":\"munipov_zakharova.mp4\",\"path\":\"/soap/167/season_175/series_176/munipov_zakharova.mp4\",\"is_dir\":false,\"size\":265994784,\"content_type\":\"video/mp4\",\"create_date\":\"25.04.2020T22:40:37\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5ea49231ef3db514e77657b7\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/167/season_175/series_176/munipov_zakharova.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":53373,\"channel_layout\":\"mono\",\"channels\":1,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":4085.6,\"index\":0,\"language\":\"und\",\"sample_rate\":32000}],\"format\":{\"bit_rate\":520823,\"duration\":4085.76,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":465322,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"0:1\",\"duration\":4085.76,\"fps\":25,\"height\":720,\"index\":1,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5ea49231ef3db514e77657b7\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ea49236ef3db514e77657bf.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ea49236ef3db514e77657bd.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ea49235ef3db514e77657bb.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(190,'5eb6dc06ef3db53f42a307d8',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc06ef3db53f42a307d8\",\"name\":\"TQMAP - WAWTKTP EP. 01 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/188/season_189/series_190/tqmap - wawtktp ep. 01 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":188874503,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:22\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc06ef3db53f42a307d8\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/188/season_189/series_190/tqmap%20-%20wawtktp%20ep.%2001%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127680,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":738.401,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2046202,\"duration\":738.439,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1911615,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":738.439022,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc06ef3db53f42a307d8\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc0aef3db53f42a307e4.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc0aef3db53f42a307e2.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc0aef3db53f42a307e0.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(191,'5eb6dc1eef3db53f42a307f0',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc1eef3db53f42a307f0\",\"name\":\"TQMAP - WAWTKTP EP. 02 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/188/season_189/series_191/tqmap - wawtktp ep. 02 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":206135318,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:46\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc1eef3db53f42a307f0\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/188/season_189/series_191/tqmap%20-%20wawtktp%20ep.%2002%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127897,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":798.044,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2066351,\"duration\":798.065,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1931543,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":798.064982,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc1eef3db53f42a307f0\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc24ef3db53f42a307f5.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc24ef3db53f42a307f3.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc24ef3db53f42a307f1.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(192,'5eb6dbffef3db53f42a307d0',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dbffef3db53f42a307d0\",\"name\":\"TQMAP - WAWTKTP EP. 03 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/188/season_189/series_192/tqmap - wawtktp ep. 03 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":179928624,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:15\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dbffef3db53f42a307d0\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/188/season_189/series_192/tqmap%20-%20wawtktp%20ep.%2003%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128695,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":695.064,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2070834,\"duration\":695.096,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1935231,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":695.096013,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dbffef3db53f42a307d0\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc07ef3db53f42a307dd.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc07ef3db53f42a307db.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc07ef3db53f42a307d9.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(193,'5eb6dbf4ef3db53f42a307c7',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dbf4ef3db53f42a307c7\",\"name\":\"TQMAP - WAWTKTP EP. 04 (1280x720) (1280xauto).mp4\",\"path\":\"/soap/188/season_189/series_193/tqmap - wawtktp ep. 04 (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":152324400,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:04\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dbf4ef3db53f42a307c7\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/188/season_189/series_193/tqmap%20-%20wawtktp%20ep.%2004%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":128442,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":595.534,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2046123,\"duration\":595.563,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1910770,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":595.56298,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dbf4ef3db53f42a307c7\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbf7ef3db53f42a307cc.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbf7ef3db53f42a307ca.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbf7ef3db53f42a307c8.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(198,'5eb6dbfcef3db53f42a307cf',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dbfcef3db53f42a307cf\",\"name\":\"A-LIVE_EP.01_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/195/season_196/series_198/a-live_ep.01_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":152675572,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:12\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dbfcef3db53f42a307cf\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/season_196/series_198/a-live_ep.01_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":126016,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":607.921,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2009080,\"duration\":607.942,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1876151,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":607.942025,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dbfcef3db53f42a307cf\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc02ef3db53f42a307d5.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc02ef3db53f42a307d3.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc02ef3db53f42a307d1.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(199,'5eb6dbdfef3db53f42a307b7',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dbdfef3db53f42a307b7\",\"name\":\"A-LIVE_EP.02_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/195/season_196/series_199/a-live_ep.02_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":115919316,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:35:43\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dbdfef3db53f42a307b7\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/season_196/series_199/a-live_ep.02_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":120077,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":476.355,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1946681,\"duration\":476.377,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1819688,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":476.37696,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dbdfef3db53f42a307b7\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbe1ef3db53f42a307bc.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbe1ef3db53f42a307ba.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbe1ef3db53f42a307b8.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(200,'5eb6dc780e47cf684413d66a',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc780e47cf684413d66a\",\"name\":\"A-LIVE_EP.03_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/195/season_196/series_200/a-live_ep.03_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":116274105,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:38:16\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc780e47cf684413d66a\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/season_196/series_200/a-live_ep.03_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":105008,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":476.803333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1950862,\"duration\":476.811,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1838939,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":476.810143,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc780e47cf684413d66a\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc7c0e47cf684413d66f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc7c0e47cf684413d66d.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc7c0e47cf684413d66b.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(201,'5eb6dbfb0e47cf684413d63c',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dbfb0e47cf684413d63c\",\"name\":\"A-LIVE_EP.04_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/195/season_196/series_201/a-live_ep.04_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":109284070,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:11\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dbfb0e47cf684413d63c\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/season_196/series_201/a-live_ep.04_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":123222,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":468.629333,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1865568,\"duration\":468.636,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1735430,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":468.635302,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dbfb0e47cf684413d63c\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbfe0e47cf684413d641.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbfe0e47cf684413d63f.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dbfe0e47cf684413d63d.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(202,'5eb6dc18ef3db53f42a307ef',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dc18ef3db53f42a307ef\",\"name\":\"A-LIVE_EP.05_RUS (1280x720) (1280xauto).mp4\",\"path\":\"/soap/195/season_196/series_202/a-live_ep.05_rus (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":139670208,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:36:40\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dc18ef3db53f42a307ef\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/season_196/series_202/a-live_ep.05_rus%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":123017,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":569.155,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":1963128,\"duration\":569.174,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1833210,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":569.170003,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dc18ef3db53f42a307ef\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc25ef3db53f42a307fc.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc25ef3db53f42a307fa.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dc25ef3db53f42a307f8.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(203,'5eb6dd63ef3db53f42a3088f',1,'video/mp4','1280x720',_binary '{\"id\":\"5eb6dd63ef3db53f42a3088f\",\"name\":\"A-Live - Official Trailer (1280x720) (1280xauto).mp4\",\"path\":\"/soap/195/trailers/203/a-live - official trailer (1280x720) (1280xauto).mp4\",\"is_dir\":false,\"size\":23472141,\"content_type\":\"video/mp4\",\"create_date\":\"09.05.2020T19:42:11\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb6dd63ef3db53f42a3088f\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/trailers/203/a-live%20-%20official%20trailer%20%281280x720%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":127990,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":91.053,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2061853,\"duration\":91.072,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1927145,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":91.057975,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5eb6dd63ef3db53f42a3088f\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd64ef3db53f42a30894.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd64ef3db53f42a30892.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb6dd64ef3db53f42a30890.jpg\"],\"description\":\"\",\"private\":false,\"status\":\"ok\",\"perms\":null}'),(216,'5eb7fba0ef3db5619013a753',1,'video/mp4','1920x1080',_binary '{\"id\":\"5eb7fba0ef3db5619013a753\",\"name\":\"01_DESIR_VF.mp4\",\"path\":\"/soap/214/season_215/series_216/01_desir_vf.mp4\",\"is_dir\":false,\"size\":775956613,\"content_type\":\"video/mp4\",\"create_date\":\"10.05.2020T16:03:45\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5eb7fba0ef3db5619013a753\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/214/season_215/series_216/01_desir_vf.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":317375,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":432.265167,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":14360752,\"duration\":432.265167,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":14038744,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"0:1\",\"duration\":432.265167,\"fps\":29.97002997002997,\"height\":1080,\"index\":0,\"width\":1920}]},\"video\":\"video.platformcraft.ru/5eb7fba0ef3db5619013a753\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb7fbb4ef3db5619013a760.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb7fbb3ef3db5619013a75e.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5eb7fbb2ef3db5619013a75c.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(220,'5ebba614ef3db53f42a333c5',1,'video/mp4','1280x720',_binary '{\"id\":\"5ebba614ef3db53f42a333c5\",\"name\":\"A-LIVE_EP.01_RUS (1280x720) (1280xauto) (1280xauto).mp4\",\"path\":\"/soap/195/trailers/220/a-live_ep.01_rus (1280x720) (1280xauto) (1280xauto).mp4\",\"is_dir\":false,\"size\":152609533,\"content_type\":\"video/mp4\",\"create_date\":\"13.05.2020T10:47:32\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5ebba614ef3db53f42a333c5\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/195/trailers/220/a-live_ep.01_rus%20%281280x720%29%20%281280xauto%29%20%281280xauto%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":126000,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":607.939,\"index\":1,\"language\":\"eng\",\"sample_rate\":48000}],\"format\":{\"bit_rate\":2008102,\"duration\":607.975,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":1875191,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"16:9\",\"duration\":607.974975,\"fps\":29.97,\"height\":720,\"index\":0,\"width\":1280}]},\"video\":\"video.platformcraft.ru/5ebba614ef3db53f42a333c5\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebba619ef3db53f42a333ca.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebba619ef3db53f42a333c8.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebba619ef3db53f42a333c6.jpg\"],\"description\":\"\",\"private\":false,\"status\":\"ok\",\"perms\":null}'),(223,'5ebd3d75ef3db561901466f4',1,'video/mp4','608x1080',_binary '{\"id\":\"5ebd3d75ef3db561901466f4\",\"name\":\"vertikal_HT_RU_v2 Phone_S01 (autox1080).mp4\",\"path\":\"/soap/221/season_222/series_223/vertikal_ht_ru_v2 phone_s01 (autox1080).mp4\",\"is_dir\":false,\"size\":802806546,\"content_type\":\"video/mp4\",\"create_date\":\"14.05.2020T15:46:02\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5ebd3d75ef3db561901466f4\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/221/season_222/series_223/vertikal_ht_ru_v2%20phone_s01%20%28autox1080%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":159290,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":1161.949002,\"index\":1,\"language\":\"eng\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":5527195,\"duration\":1161.973,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":5361151,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"482372:857979\",\"duration\":1161.966992,\"fps\":30,\"height\":1080,\"index\":0,\"width\":608}]},\"video\":\"video.platformcraft.ru/5ebd3d75ef3db561901466f4\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebd3d8bef3db56190146701.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebd3d8aef3db561901466ff.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebd3d8aef3db561901466fd.jpg\"],\"description\":\"\",\"private\":true,\"status\":\"ok\",\"perms\":null}'),(224,'5ebc26cf0e47cf684413e8c6',1,'video/mp4','608x1080',_binary '{\"id\":\"5ebc26cf0e47cf684413e8c6\",\"name\":\"vertikal_HT_RU_v2 Phone_S01 (autox1080).mp4\",\"path\":\"/soap/221/trailers/224/vertikal_ht_ru_v2 phone_s01 (autox1080).mp4\",\"is_dir\":false,\"size\":802806546,\"content_type\":\"video/mp4\",\"create_date\":\"13.05.2020T19:56:47\",\"latest_update\":\"\",\"resource_url\":\"api.platformcraft.ru/1/objects/5ebc26cf0e47cf684413e8c6\",\"cdn_url\":\"w87gm8aee9.a.trbcdn.net/kinoteatr/soap/221/trailers/224/vertikal_ht_ru_v2%20phone_s01%20%28autox1080%29.mp4\",\"vod_hls\":\"\",\"advanced\":{\"audio_streams\":[{\"bit_rate\":159290,\"channel_layout\":\"stereo\",\"channels\":2,\"codec_long_name\":\"AAC (Advanced Audio Coding)\",\"codec_name\":\"aac\",\"codec_type\":\"audio\",\"duration\":1161.972971,\"index\":1,\"language\":\"eng\",\"sample_rate\":44100}],\"format\":{\"bit_rate\":5527195,\"duration\":1161.973,\"format_long_name\":\"QuickTime / MOV\",\"format_name\":\"mov,mp4,m4a,3gp,3g2,mj2\",\"nb_streams\":2},\"video_streams\":[{\"bit_rate\":5361151,\"codec_name\":\"h264\",\"codec_type\":\"video\",\"codeclongname\":\"H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10\",\"display_aspect_ratio\":\"482372:857979\",\"duration\":1161.966667,\"fps\":30,\"height\":1080,\"index\":0,\"width\":608}]},\"video\":\"video.platformcraft.ru/5ebc26cf0e47cf684413e8c6\",\"previews\":[\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebc26de0e47cf684413e8cb.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebc26de0e47cf684413e8c9.jpg\",\"w87gm8aee9.a.trbcdn.net/kinoteatr/.previews/preview-5ebc26de0e47cf684413e8c7.jpg\"],\"description\":\"\",\"private\":false,\"status\":\"ok\",\"perms\":null}');
/*!40000 ALTER TABLE `media__content__cdn__file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__collection`
--

DROP TABLE IF EXISTS `media__content__collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__collection` (
  `id` bigint(19) unsigned NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `default_poster` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`common_name`),
  CONSTRAINT `media__content__collection__2__media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__collection`
--

LOCK TABLES `media__content__collection` WRITE;
/*!40000 ALTER TABLE `media__content__collection` DISABLE KEYS */;
INSERT INTO `media__content__collection` VALUES (210,'Сериалы о сексе','1c25144a08ad57b6f1189d1e26ddb093'),(212,'Подборка о сексе new','f05a91befaf9273beecabd67b29bf548');
/*!40000 ALTER TABLE `media__content__collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__collection__items`
--

DROP TABLE IF EXISTS `media__content__collection__items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__collection__items` (
  `collection_id` bigint(19) unsigned NOT NULL,
  `content_id` bigint(19) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`collection_id`,`content_id`),
  UNIQUE KEY `content_id` (`content_id`,`collection_id`),
  KEY `sort` (`sort`),
  CONSTRAINT `media__content__collection__item__2__media_collectoin` FOREIGN KEY (`collection_id`) REFERENCES `media__content__collection` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__collection__item__2__media_content` FOREIGN KEY (`content_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__collection__items`
--

LOCK TABLES `media__content__collection__items` WRITE;
/*!40000 ALTER TABLE `media__content__collection__items` DISABLE KEYS */;
INSERT INTO `media__content__collection__items` VALUES (210,195,1),(212,188,1),(210,78,3),(212,51,3),(210,66,5),(212,66,5),(210,51,7),(212,131,7),(212,78,9),(212,70,11);
/*!40000 ALTER TABLE `media__content__collection__items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__collection_strings_lang_en`
--

DROP TABLE IF EXISTS `media__content__collection_strings_lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__collection_strings_lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `html_mode` int(11) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__collection_strings_lang_en_2_mecoco` FOREIGN KEY (`id`) REFERENCES `media__content__collection` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__collection_strings_lang_en`
--

LOCK TABLES `media__content__collection_strings_lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__collection_strings_lang_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__collection_strings_lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__collection_strings_lang_ru`
--

DROP TABLE IF EXISTS `media__content__collection_strings_lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__collection_strings_lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `html_mode` int(11) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__collection_strings_lang_ru_2_mecoco` FOREIGN KEY (`id`) REFERENCES `media__content__collection` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__collection_strings_lang_ru`
--

LOCK TABLES `media__content__collection_strings_lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__collection_strings_lang_ru` DISABLE KEYS */;
INSERT INTO `media__content__collection_strings_lang_ru` VALUES (210,'Сериалы о сексе',2,''),(212,'Подборка о сексе new',2,'');
/*!40000 ALTER TABLE `media__content__collection_strings_lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__emoji`
--

DROP TABLE IF EXISTS `media__content__emoji`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__emoji` (
  `media_id` bigint(19) unsigned NOT NULL,
  `emoji_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`media_id`,`emoji_id`),
  UNIQUE KEY `media_id` (`media_id`,`emoji_id`),
  KEY `media__emoji__2__emoji` (`emoji_id`),
  CONSTRAINT `media__emoji__2__emoji` FOREIGN KEY (`emoji_id`) REFERENCES `media__emoji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__emoji__2__media` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__emoji`
--

LOCK TABLES `media__content__emoji` WRITE;
/*!40000 ALTER TABLE `media__content__emoji` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__emoji` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__genre`
--

DROP TABLE IF EXISTS `media__content__genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__genre` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__genre`
--

LOCK TABLES `media__content__genre` WRITE;
/*!40000 ALTER TABLE `media__content__genre` DISABLE KEYS */;
INSERT INTO `media__content__genre` VALUES (2,0),(6,0),(7,0),(8,0),(9,0),(10,0),(11,0),(13,0),(14,0),(15,0),(16,0);
/*!40000 ALTER TABLE `media__content__genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__genre__strings`
--

DROP TABLE IF EXISTS `media__content__genre__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__genre__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  KEY `name` (`name`),
  CONSTRAINT `media__content__genre__strings_2_genre` FOREIGN KEY (`id`) REFERENCES `media__content__genre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__genre__strings_2_language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__genre__strings`
--

LOCK TABLES `media__content__genre__strings` WRITE;
/*!40000 ALTER TABLE `media__content__genre__strings` DISABLE KEYS */;
INSERT INTO `media__content__genre__strings` VALUES (16,'en','Action'),(6,'en','Comedy'),(14,'en','Анимация'),(14,'ru','Анимация'),(2,'ru','Детектив'),(10,'ru','Документальный'),(7,'ru','Драма'),(15,'en','Драмеди'),(15,'ru','Драмеди'),(11,'ru','Другое'),(6,'ru','Комедия'),(9,'ru','Мистика'),(8,'ru','Триллер'),(13,'en','Хоррор'),(13,'ru','Хоррор'),(16,'ru','Экшен');
/*!40000 ALTER TABLE `media__content__genre__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__genre_list`
--

DROP TABLE IF EXISTS `media__content__genre_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__genre_list` (
  `media_id` bigint(19) unsigned NOT NULL,
  `genre_id` bigint(19) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`,`genre_id`),
  UNIQUE KEY `genre_id` (`genre_id`,`media_id`),
  CONSTRAINT `media__content__genre__list_2__genre` FOREIGN KEY (`genre_id`) REFERENCES `media__content__genre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__genre__list_2__media__content` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Жанры фильма';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__genre_list`
--

LOCK TABLES `media__content__genre_list` WRITE;
/*!40000 ALTER TABLE `media__content__genre_list` DISABLE KEYS */;
INSERT INTO `media__content__genre_list` VALUES (51,7,0),(66,6,0),(70,6,0),(73,6,0),(78,6,0),(130,7,0),(131,7,0),(143,7,0),(159,6,0),(161,11,0),(161,13,1),(167,15,0),(181,6,0),(188,15,0),(195,16,0),(203,16,0),(206,6,0),(214,10,0),(217,6,1),(217,7,0),(219,10,0),(220,16,0),(221,13,0),(224,11,0);
/*!40000 ALTER TABLE `media__content__genre_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__gif`
--

DROP TABLE IF EXISTS `media__content__gif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__gif` (
  `id` bigint(19) unsigned NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `default_poster` varchar(100) DEFAULT NULL,
  `cdn_url` varchar(512) DEFAULT NULL,
  `cdn_id` varchar(100) DEFAULT NULL,
  `target` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__gif__2__meco` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__gif`
--

LOCK TABLES `media__content__gif` WRITE;
/*!40000 ALTER TABLE `media__content__gif` DISABLE KEYS */;
INSERT INTO `media__content__gif` VALUES (155,'rumis','b0ae88b539d287f89135a519fd0de517','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/155/gif.gif','5ea1671aef3db519027991d2','/Soap/78'),(157,'rumis_3','5bb488dba498b9c6b24805ac16051895','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/157/gif.gif','5ea16788ef3db519027991f2','/Soap/78'),(158,'rumis_4','7535a76d0858436913ffbf362994e473','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/158/gif.gif','5ea167bfef3db519027991fd','/Soap/78'),(159,'rumis_2','c85a4cf2e67eca546c92a1f93b6cc872','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/159/gif.gif','5ea168c8ef3db51902799232','/Soap/78'),(160,'rumis_5','e15ed9c099e681e23497cffc840d75ad','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/160/gif.gif','5ea1691def3db51902799234','/Soap/78'),(161,'rumis_6','92f8e69fd1f4f1ba299b36f3fe6dee94','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/161/gif.gif','5ea16938ef3db51902799239','/Soap/78'),(181,'sins_1','849e7a56aff73a1f8afe0b61f4de3359','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/181/gif.gif','5ea84cc2ef3db52e73f448d8','/Soap/70'),(182,'sins_2','dd3c75ce5555cdb921a55b38f49f9f1c','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/182/gif.gif','5ea84cf7ef3db52e73f448dc','/Soap/70'),(183,'sins_3','69adcc2136f5e0ea124e3daf0d64b5c1','w87gm8aee9.a.trbcdn.net/kinoteatr/gif/183/gif.gif','5ea84d1e0e47cf332401c9bb','/Soap/70');
/*!40000 ALTER TABLE `media__content__gif` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__gif__strings`
--

DROP TABLE IF EXISTS `media__content__gif__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__gif__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  KEY `name` (`name`),
  CONSTRAINT `media__content__gif__strings_2_gif` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__gif__strings_2_language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__gif__strings`
--

LOCK TABLES `media__content__gif__strings` WRITE;
/*!40000 ALTER TABLE `media__content__gif__strings` DISABLE KEYS */;
INSERT INTO `media__content__gif__strings` VALUES (181,'en','Искупитель грехов'),(181,'ru','Искупитель грехов'),(182,'en','Искупитель грехов'),(182,'ru','Искупитель грехов'),(183,'en','Искупитель грехов'),(183,'ru','Искупитель грехов'),(155,'en','Соседи'),(155,'ru','Соседи'),(157,'en','Соседи'),(157,'ru','Соседи'),(158,'en','Соседи'),(158,'ru','Соседи'),(159,'en','Соседи'),(159,'ru','Соседи'),(160,'en','Соседи'),(160,'ru','Соседи'),(161,'en','Соседи'),(161,'ru','Соседи');
/*!40000 ALTER TABLE `media__content__gif__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__meta_lang_en`
--

DROP TABLE IF EXISTS `media__content__meta_lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__meta_lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `title` varchar(1024) DEFAULT NULL,
  `og_title` varchar(1024) DEFAULT NULL,
  `description` mediumtext NOT NULL,
  `og_description` mediumtext NOT NULL,
  `keywords` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__meta__lang_en__2_media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__meta_lang_en`
--

LOCK TABLES `media__content__meta_lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__meta_lang_en` DISABLE KEYS */;
INSERT INTO `media__content__meta_lang_en` VALUES (70,NULL,NULL,'','','');
/*!40000 ALTER TABLE `media__content__meta_lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__meta_lang_ru`
--

DROP TABLE IF EXISTS `media__content__meta_lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__meta_lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `title` varchar(1024) DEFAULT NULL,
  `og_title` varchar(1024) DEFAULT NULL,
  `description` mediumtext NOT NULL,
  `og_description` mediumtext NOT NULL,
  `keywords` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__meta__lang_ru__2_media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__meta_lang_ru`
--

LOCK TABLES `media__content__meta_lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__meta_lang_ru` DISABLE KEYS */;
INSERT INTO `media__content__meta_lang_ru` VALUES (51,NULL,NULL,'','',''),(66,NULL,NULL,'','',''),(70,NULL,NULL,'','',''),(78,NULL,NULL,'','',''),(109,NULL,NULL,'','',''),(110,NULL,NULL,'','',''),(111,NULL,NULL,'','',''),(112,NULL,NULL,'','',''),(113,NULL,NULL,'','',''),(114,NULL,NULL,'','',''),(115,NULL,NULL,'','',''),(116,NULL,NULL,'','',''),(117,NULL,NULL,'','',''),(118,NULL,NULL,'','',''),(119,NULL,NULL,'','',''),(120,NULL,NULL,'','',''),(128,NULL,NULL,'','',''),(131,NULL,NULL,'','',''),(143,NULL,NULL,'','',''),(151,NULL,NULL,'','',''),(155,NULL,NULL,'','',''),(157,NULL,NULL,'','',''),(158,NULL,NULL,'','',''),(159,NULL,NULL,'','',''),(160,NULL,NULL,'','',''),(161,NULL,NULL,'','',''),(167,NULL,NULL,'','',''),(181,NULL,NULL,'','',''),(182,NULL,NULL,'','',''),(183,NULL,NULL,'','',''),(188,NULL,NULL,'','',''),(195,NULL,NULL,'','',''),(206,NULL,NULL,'','',''),(214,NULL,NULL,'','',''),(217,NULL,NULL,'','',''),(219,NULL,NULL,'','',''),(221,NULL,NULL,'','','');
/*!40000 ALTER TABLE `media__content__meta_lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__origin`
--

DROP TABLE IF EXISTS `media__content__origin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__origin` (
  `id` bigint(19) unsigned NOT NULL,
  `country_id` bigint(19) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`country_id`),
  UNIQUE KEY `media__content__origin_rpm` (`country_id`,`id`),
  KEY `sort` (`sort`),
  CONSTRAINT `media__content__origin_2_media_content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__origin_2_origin_country` FOREIGN KEY (`country_id`) REFERENCES `media__content__origin_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Страна происхождения контента';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__origin`
--

LOCK TABLES `media__content__origin` WRITE;
/*!40000 ALTER TABLE `media__content__origin` DISABLE KEYS */;
INSERT INTO `media__content__origin` VALUES (51,7,0),(66,8,0),(70,8,0),(73,8,0),(78,9,0),(130,7,0),(131,6,0),(143,10,0),(159,9,0),(161,10,0),(167,7,0),(181,8,0),(188,6,0),(195,7,0),(203,7,0),(206,6,0),(214,12,0),(217,12,0),(219,12,0),(220,7,0),(221,5,0),(224,5,0),(161,2,1);
/*!40000 ALTER TABLE `media__content__origin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__origin__country__strings`
--

DROP TABLE IF EXISTS `media__content__origin__country__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__origin__country__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(512) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `media_content_origin_country_rpm` (`language_id`,`id`),
  CONSTRAINT `media__content__origin__country_strings_2_country` FOREIGN KEY (`id`) REFERENCES `media__content__origin_country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__origin__country_strings_2_language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__origin__country__strings`
--

LOCK TABLES `media__content__origin__country__strings` WRITE;
/*!40000 ALTER TABLE `media__content__origin__country__strings` DISABLE KEYS */;
INSERT INTO `media__content__origin__country__strings` VALUES (2,'ru','РФ'),(3,'ru','Зимбабве'),(4,'ru','Литва'),(5,'ru','Латвия'),(6,'en','USA'),(6,'ru','США'),(7,'en','Italy'),(7,'ru','Италия'),(8,'en','Germany'),(8,'ru','Германия'),(9,'en','Chili'),(9,'ru','Чили'),(10,'en','Australia'),(10,'ru','Австралия'),(12,'en','Канада'),(12,'ru','Канада'),(19,'en','Kazakhstan'),(19,'ru','Казахстан');
/*!40000 ALTER TABLE `media__content__origin__country__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__origin_country`
--

DROP TABLE IF EXISTS `media__content__origin_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__origin_country` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `common_name` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `common_name` (`common_name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__origin_country`
--

LOCK TABLES `media__content__origin_country` WRITE;
/*!40000 ALTER TABLE `media__content__origin_country` DISABLE KEYS */;
INSERT INTO `media__content__origin_country` VALUES (3,'Zimbabve'),(10,'Австралия'),(8,'Германия'),(7,'Италия'),(19,'Казахстан'),(12,'Канада'),(11,'Канала'),(5,'Латвия'),(4,'Литва'),(2,'Россия'),(6,'США'),(9,'Чили');
/*!40000 ALTER TABLE `media__content__origin_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__personal`
--

DROP TABLE IF EXISTS `media__content__personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__personal` (
  `content_id` bigint(19) unsigned NOT NULL,
  `person_id` bigint(19) unsigned NOT NULL,
  `value` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`,`person_id`,`value`),
  UNIQUE KEY `person_id` (`person_id`,`content_id`,`value`),
  KEY `sort` (`sort`),
  CONSTRAINT `media__content__personal__2_media__content` FOREIGN KEY (`content_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__personal__2_personal` FOREIGN KEY (`person_id`) REFERENCES `media__content__actor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Актеры, режиссеры и прочая шелупонь';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__personal`
--

LOCK TABLES `media__content__personal` WRITE;
/*!40000 ALTER TABLE `media__content__personal` DISABLE KEYS */;
INSERT INTO `media__content__personal` VALUES (51,5,'ROLE_DIRECTOR',0),(51,5,'ROLE_SCENARIST',0),(51,6,'ROLE_OPERATOR',0),(51,7,'ROLE_PRODUCER',0),(51,8,'ROLE_ACTOR',0),(51,9,'ROLE_ACTOR',0),(70,10,'ROLE_DIRECTOR',0),(70,10,'ROLE_PRODUCER',0),(70,10,'ROLE_SCENARIST',0),(70,11,'ROLE_ACTOR',0),(70,12,'ROLE_ACTOR',0),(70,13,'ROLE_ACTOR',0),(70,14,'ROLE_ACTOR',0),(70,15,'ROLE_ACTOR',0),(78,16,'ROLE_DIRECTOR',0),(78,16,'ROLE_SCENARIST',0),(78,17,'ROLE_OPERATOR',0),(78,18,'ROLE_COMPOSITOR',0),(78,19,'ROLE_PAINTER',0),(78,20,'ROLE_PRODUCER',0),(78,21,'ROLE_ACTOR',0),(78,22,'ROLE_ACTOR',0),(78,23,'ROLE_ACTOR',0),(131,33,'ROLE_DIRECTOR',0),(131,34,'ROLE_SCENARIST',0),(131,35,'ROLE_OPERATOR',0),(131,36,'ROLE_COMPOSITOR',0),(131,37,'ROLE_COMPOSITOR',0),(131,38,'ROLE_PAINTER',0),(131,39,'ROLE_ACTOR',0),(131,40,'ROLE_ACTOR',0),(131,41,'ROLE_ACTOR',0),(131,42,'ROLE_ACTOR',0),(131,43,'ROLE_ACTOR',0),(131,44,'ROLE_ACTOR',0),(131,45,'ROLE_ACTOR',0),(131,46,'ROLE_ACTOR',0),(131,47,'ROLE_ACTOR',0),(131,48,'ROLE_ACTOR',0),(131,49,'ROLE_ACTOR',0),(131,50,'ROLE_ACTOR',0),(131,51,'ROLE_ACTOR',0),(131,52,'ROLE_ACTOR',0),(131,53,'ROLE_ACTOR',0),(131,54,'ROLE_ACTOR',0),(143,24,'ROLE_DIRECTOR',0),(143,25,'ROLE_SCENARIST',0),(143,26,'ROLE_OPERATOR',0),(143,27,'ROLE_COMPOSITOR',0),(143,28,'ROLE_PAINTER',0),(143,29,'ROLE_PRODUCER',0),(143,30,'ROLE_PRODUCER',0),(143,31,'ROLE_ACTOR',0),(143,32,'ROLE_ACTOR',0),(167,24,'ROLE_ACTOR',0),(167,55,'ROLE_DIRECTOR',0),(167,56,'ROLE_ACTOR',0),(195,57,'ROLE_ACTOR',0),(195,58,'ROLE_ACTOR',0),(206,59,'ROLE_DIRECTOR',0),(206,59,'ROLE_SCENARIST',0),(206,60,'ROLE_SCENARIST',0),(206,61,'ROLE_OPERATOR',0),(206,62,'ROLE_COMPOSITOR',0),(214,63,'ROLE_DIRECTOR',0),(214,63,'ROLE_SCENARIST',0),(214,64,'ROLE_SCENARIST',0),(214,65,'ROLE_OPERATOR',0),(214,66,'ROLE_COMPOSITOR',0),(214,67,'ROLE_PRODUCER',0);
/*!40000 ALTER TABLE `media__content__personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__price`
--

DROP TABLE IF EXISTS `media__content__price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__price` (
  `id` bigint(19) unsigned NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__price__2__media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__price`
--

LOCK TABLES `media__content__price` WRITE;
/*!40000 ALTER TABLE `media__content__price` DISABLE KEYS */;
INSERT INTO `media__content__price` VALUES (54,6),(55,6),(56,6),(57,6),(58,6),(59,6),(60,6),(69,6),(72,6),(74,6),(75,6),(76,6),(77,6),(80,6),(81,6),(82,6),(83,6),(84,6),(85,6),(86,6),(87,6),(88,6),(89,6),(90,6),(91,6),(133,6),(134,6),(135,6),(136,6),(137,6),(138,6),(139,6),(140,6),(141,6),(142,6),(145,6),(146,6),(147,6),(148,6),(149,6),(150,6),(176,0),(190,6),(191,6),(192,6),(193,6),(198,6),(199,0),(200,6),(201,6),(202,6),(208,6),(209,6),(216,6),(223,0);
/*!40000 ALTER TABLE `media__content__price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__properties`
--

DROP TABLE IF EXISTS `media__content__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `mediacontentprops2mediacontent` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Свойства медиаконтента';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__properties`
--

LOCK TABLES `media__content__properties` WRITE;
/*!40000 ALTER TABLE `media__content__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__review`
--

DROP TABLE IF EXISTS `media__content__review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__review` (
  `media_id` bigint(19) unsigned NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  `rate` int(11) unsigned NOT NULL DEFAULT '5',
  `post` datetime NOT NULL,
  `approved` int(1) unsigned NOT NULL DEFAULT '0',
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`media_id`,`user_id`),
  UNIQUE KEY `approved` (`approved`,`post`,`media_id`),
  UNIQUE KEY `media__rieview_2_user` (`user_id`,`media_id`) USING BTREE,
  CONSTRAINT `media__rieview_2_media` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__rieview_2_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__review`
--

LOCK TABLES `media__content__review` WRITE;
/*!40000 ALTER TABLE `media__content__review` DISABLE KEYS */;
INSERT INTO `media__content__review` VALUES (51,1,4,'2020-04-22 13:18:20',1,'Test'),(66,1,3,'2020-04-22 13:47:18',1,'Test'),(119,22,4,'2020-04-27 20:01:01',1,'Тест'),(120,1,5,'2020-04-22 13:32:56',1,'Test'),(143,1,5,'2020-04-22 01:30:53',1,'Тест'),(151,22,5,'2020-04-27 20:02:05',1,'ТЕст');
/*!40000 ALTER TABLE `media__content__review` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `media__content__review__AI` AFTER INSERT ON `media__content__review` FOR EACH ROW BEGIN
INSERT INTO media__content__review__accumulator(media_id,qty,average)
VALUES(NEW.media_id,1,NEW.rate)
ON DUPLICATE KEY UPDATE qty=qty+VALUES(qty),average=average+VALUES(average);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `media__content__review_AU` AFTER UPDATE ON `media__content__review` FOR EACH ROW BEGIN
UPDATE media__content__review__accumulator SET qty = CASE WHEN qty<1 THEN 0 ELSE qty-1 END,
average = CASE WHEN average<OLD.rate THEN 0 ELSE average-OLD.rate END WHERE media_id=OLD.media_id;

INSERT INTO media__content__review__accumulator(media_id,qty,average)
VALUES(NEW.media_id,1,NEW.rate)
ON DUPLICATE KEY UPDATE qty=qty+VALUES(qty),average=average+VALUES(average);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `media__content__review__AD` AFTER DELETE ON `media__content__review` FOR EACH ROW BEGIN
UPDATE media__content__review__accumulator SET qty = CASE WHEN qty<1 THEN 0 ELSE qty-1 END,
average = CASE WHEN average<OLD.rate THEN 0 ELSE average-OLD.rate END WHERE media_id=OLD.media_id;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `media__content__review__accumulator`
--

DROP TABLE IF EXISTS `media__content__review__accumulator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__review__accumulator` (
  `media_id` bigint(19) unsigned NOT NULL,
  `qty` bigint(19) unsigned NOT NULL,
  `average` double unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`),
  CONSTRAINT `media__content__review__accumulator_2_media` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__review__accumulator`
--

LOCK TABLES `media__content__review__accumulator` WRITE;
/*!40000 ALTER TABLE `media__content__review__accumulator` DISABLE KEYS */;
INSERT INTO `media__content__review__accumulator` VALUES (51,1,4),(66,1,3),(70,0,0),(78,0,0),(119,1,4),(120,1,5),(143,1,5),(151,1,5),(188,0,0);
/*!40000 ALTER TABLE `media__content__review__accumulator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__season`
--

DROP TABLE IF EXISTS `media__content__season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__season` (
  `id` bigint(19) unsigned NOT NULL,
  `common_name` varchar(512) NOT NULL,
  `default_poster` varchar(100) DEFAULT NULL,
  `eng_name` varchar(255) NOT NULL DEFAULT '',
  `origin_language` varchar(255) DEFAULT NULL,
  `released` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `common_name` (`common_name`),
  CONSTRAINT `media__content__season_2__media_content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__season`
--

LOCK TABLES `media__content__season` WRITE;
/*!40000 ALTER TABLE `media__content__season` DISABLE KEYS */;
INSERT INTO `media__content__season` VALUES (51,'Anachronisme','886f21d35a02f3e61dfcf0911c841ff6','Anachronisme',NULL,NULL),(66,'When I grow up','6dfcaa8d824f3aa25ffbaa04be06a9c1','When I grow up','Немецкий',NULL),(70,'Der Mann für die Sünde','9007d53757b1adf79df8ce8bd33704d5','The man for sins',NULL,NULL),(78,'Rumis','625c36a514e0879082ec59e9ed76b5bb','Neighbors',NULL,NULL),(131,'Dean’s Office','fb99e288e5fbde6e84e249847e7d1ff7','Dean’s Office',NULL,NULL),(143,'High Life','408c3dd89b2b2459b4c9c7c26c974425','High Life',NULL,NULL),(167,'kostya','aaccd462d7063d0b1be70ddbbf18ed02','koka',NULL,NULL),(188,'Мы все хотим убить президента','6ba67b93f717ed359f863cfcdab75e9d','We all wanted to kill the president','Английский','2020-04-16 19:00:00'),(195,'A-Live','ad0c9d6e4e401bee6f3cf75322f50ca5','A-Live','Итальянский',NULL),(206,'Showception','ab97bf9697208b80b6e95435d3d84418','Showception','английский',NULL),(214,'Le sexe en 10 temps','c08e6d951fac8e0d3ed01daae9b1ec14','10 Steps to Sex','французский',NULL),(217,'Sylvain le magnifique','902571664a80d9d83de7e842145f963e','Sylvain the magnificent','Французский','2018-05-18 11:57:00'),(219,'Le sexe en 10 temps','3597ccec9c4c48ba3faa4364ed007bc1','10 Steps to Sex','Французский',NULL),(221,'Вертикаль тест','eb89d71f5c69390b0c374be5494e7ca2','Вертикаль','Русский','2020-05-06 16:07:00');
/*!40000 ALTER TABLE `media__content__season` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__season__season`
--

DROP TABLE IF EXISTS `media__content__season__season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__season__season` (
  `id` bigint(19) unsigned NOT NULL,
  `season_id` bigint(19) unsigned NOT NULL,
  `num` int(11) unsigned NOT NULL COMMENT 'Номер сезона',
  `common_name` varchar(512) NOT NULL DEFAULT '',
  `default_poster` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`season_id`),
  KEY `media__content__season__season_2_media__content__season` (`season_id`),
  KEY `num` (`num`,`season_id`) USING BTREE,
  CONSTRAINT `media__content__season__season_2_media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__season__season_2_media__content__season` FOREIGN KEY (`season_id`) REFERENCES `media__content__season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сезоны сериала';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__season__season`
--

LOCK TABLES `media__content__season__season` WRITE;
/*!40000 ALTER TABLE `media__content__season__season` DISABLE KEYS */;
INSERT INTO `media__content__season__season` VALUES (52,51,1,'Сезон 1','a1fdbbe31fb497e59fe462148989db93'),(68,66,1,'Сезон 1','e26d0dba6d60d6d99d6ed46d68efa571'),(71,70,1,'1 сезон',NULL),(79,78,1,'1 сезон',NULL),(132,131,1,'Season 1','05e3c5ece969e2b560e2717af1144179'),(144,143,1,'1 сезон','ede2e226b172d4861a6cd433c5f828fc'),(175,167,1,'-','c0c133008fc8cf94b3b3ab845b53cb0c'),(189,188,1,'Season 1',NULL),(196,195,1,'Season 1','7638f42dd2ee8f805661bc649ae870ae'),(207,206,1,'Season 1',NULL),(215,214,1,'Season 1',NULL),(218,217,1,'Season 1',NULL),(222,221,1,'Season 1',NULL);
/*!40000 ALTER TABLE `media__content__season__season` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__season__series`
--

DROP TABLE IF EXISTS `media__content__season__series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__season__series` (
  `id` bigint(19) unsigned NOT NULL,
  `seasonseason_id` bigint(19) unsigned NOT NULL,
  `num` int(11) NOT NULL,
  `common_name` varchar(512) NOT NULL,
  `vertical` int(1) unsigned NOT NULL DEFAULT '0',
  `default_poster` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`seasonseason_id`),
  KEY `mediacontentseasonseries2seasonseason` (`seasonseason_id`),
  CONSTRAINT `mediacontentseasonseries2seasonseason` FOREIGN KEY (`seasonseason_id`) REFERENCES `media__content__season__season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mediacontentseasonseries_2_mediacontent` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__season__series`
--

LOCK TABLES `media__content__season__series` WRITE;
/*!40000 ALTER TABLE `media__content__season__series` DISABLE KEYS */;
INSERT INTO `media__content__season__series` VALUES (54,52,1,'Первая серия',0,'304411f8aa6edd936db4e64e8e2c7fa9'),(55,52,2,'Вторая серия',0,'1714c9f3ec7dcd5a96b4f6a8376c7955'),(56,52,3,'Третья серия',0,'7363f887624f8c154e147dd789d5a087'),(57,52,4,'Четвертая серия',0,'45ae95f9bf015afc3098157b79a98f6e'),(58,52,5,'Пятая серия',0,'94efda82a21081d421d4d0ba6064e9b3'),(59,52,6,'Шестая серия',0,'41343aa72d7ad98eeeabffa45c4338a6'),(60,52,7,'Седьмая серия',0,'c573ea87ff52bace455a38b21ba2341d'),(69,68,1,'Первая серия',0,'e371b4456c9507dfedf3d11bcee94c5d'),(72,71,1,'Jenny',0,'f5ea7b387e2a8794c5675a252dddabc0'),(74,71,2,'Peter',0,'ebc1a0711d9e1bdf0f0c8053b26f4714'),(75,71,3,'Lisa',0,'b184d78f3131b14153a99f6e07a067f8'),(76,71,4,'Concrete Head',0,'ba439f594342c6c75befcb110737e489'),(77,71,5,'Lisa is back',0,'b3de8744dfc70e3fa97e750aa04c592c'),(80,79,1,'Первая серия',0,'cb722eb03f187d026b1f29d99d861b48'),(81,79,2,'Вторая серия',0,'a6a34fb0084b71547b6bac34cbbf1b63'),(82,79,3,'Третья серия',0,'5df9aeeec57e40e138a99ee66495c916'),(83,79,4,'Четвертая серия',0,'c76c623b857db56dec0944178ca70da2'),(84,68,2,'Вторая серия',0,'0b716077ab95a5f7e32784fa19441944'),(85,68,3,'Третья серия',0,'5fa28ac37b4dd35f1cb70672c7f53386'),(86,68,4,'Четвертая серия',0,'14afa0bdeda5f3e2bd39d3773a8be4af'),(87,68,5,'Пятая серия',0,'f3c5b54e1e3a6467dec912a7f9743a80'),(88,68,6,'Шестая серия',0,'ba5dbaece7d323afb8333d0de4c95f48'),(89,68,7,'Седьмая серия',0,'19cac5890fd6516e83ec8ca535a5f5b1'),(90,68,8,'Восьмая серия',0,'0838d4461998a543736195556ece51ab'),(91,68,9,'Девятая серия',0,'455ff4f7945ecd18483561826a66f8e7'),(133,132,1,'Первая серия',0,'0280ad9ee46e9243e3df3d47d226a82b'),(134,132,2,'Вторая серия',0,'7316f1a0aa229b742e14cd8ab2186ce9'),(135,132,3,'Третья серия',0,'aca473978521a6ff6a6e8cf2f3862f68'),(136,132,4,'Четвертая серия',0,'08b827689287d3da9ace410a6013da77'),(137,132,5,'Пятая серия',0,'cc6413d7db42805f22c0745f111a2f53'),(138,132,6,'Шестая серия',0,'85a79b4fddfa5c7e3a59fad548a2a0ac'),(139,132,7,'Седьмая серия',0,'891e7f283b2b7452a72d74c790771452'),(140,132,8,'Восьмая серия',0,'6013f77076031cf54774b430a8d7a77c'),(141,132,9,'Девятая серия',0,'fafbdc12bc537e9f07950565098729b2'),(142,132,10,'Десятая серия',0,'dbfca9d6c11032834b1c93c417951297'),(145,144,1,'Первая серия',0,'aa2058950d58e5dbfa7413a11a5f2198'),(146,144,2,'Вторая серия',0,'d70c3d220b07a2a88f18239a9c9f6d3f'),(147,144,3,'Третья серия',0,'5bb37d7b8c914601ac879354bf29ae25'),(148,144,4,'Четвертая серия',0,'2e5b860264315cf497c044c66acf0e47'),(149,144,5,'Пятая серия',0,'1cb53827d1a6cb5d8811645bbc8c9b9a'),(150,144,6,'Шестая серия',0,'5a2306a91ccaa0376a7dd358600d8483'),(176,175,1,'Series',0,NULL),(190,189,1,'1 серия',0,'e26187487e3dc401e209a182e9b03e3b'),(191,189,2,'2 серия',0,'67857f89076906d2569ae10be156079d'),(192,189,3,'3 серия',0,'c663fc07ed841f360eecc143f4095600'),(193,189,4,'4 серия',0,'80ccbf3c424f801992896d92dfb80223'),(198,196,1,'Серия 1',0,'f9caf801231b51927c26b89720baf880'),(199,196,2,'Серия 2',0,'074a4375e896b3044643390fa0e7bd50'),(200,196,3,'Серия 3',0,'8334fe963498d670a7fa8824539b6f42'),(201,196,4,'Серия 4',0,'518df5e7e6889f849122fc5c0c2b8d84'),(202,196,5,'Серия 5',0,'1e7b1d0af8d8c9ef0c11a429a02b75c7'),(208,207,2,'2 серия',0,NULL),(209,207,2,'2 серия',0,NULL),(216,215,1,'Desir',0,'345e5d244f021aa8e1a71afd4d6d6d36'),(223,222,1,'Серия 1',1,'45d924b63edb99c7d558441083cefa08');
/*!40000 ALTER TABLE `media__content__season__series` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__season__strings__lang_en`
--

DROP TABLE IF EXISTS `media__content__season__strings__lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__season__strings__lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__season__strings__lang_en_2__media_content_season` FOREIGN KEY (`id`) REFERENCES `media__content__season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__season__strings__lang_en`
--

LOCK TABLES `media__content__season__strings__lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__season__strings__lang_en` DISABLE KEYS */;
INSERT INTO `media__content__season__strings__lang_en` VALUES (70,'The man for your sins',3,'<p>Священник принимает исповедь грешников сидя в контейнере в центре Берлина. Вольфганг был понижен в должности за то, что пил и вел темные дела. В контейнере он пытается выкупить себя, чтобы вернуть свой приход. Он помогает людям навести порядок в их жизни. К сожалению, его собственные проблемы остаются нерешенными.</p>','');
/*!40000 ALTER TABLE `media__content__season__strings__lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__season__strings__lang_ru`
--

DROP TABLE IF EXISTS `media__content__season__strings__lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__season__strings__lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__season__strings__lang_ru_2__media_content_season` FOREIGN KEY (`id`) REFERENCES `media__content__season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__season__strings__lang_ru`
--

LOCK TABLES `media__content__season__strings__lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__season__strings__lang_ru` DISABLE KEYS */;
INSERT INTO `media__content__season__strings__lang_ru` VALUES (51,'Anachronisme',3,'','<p>По вине Вальтера произошло падение самолета и погибли люди. Он закрылся в квартире и не выходит из нее уже семь лет. Его единственный собеседник - соседка, с которой он общается через тонкую стену дома. Но оказывается, что девушка за стеной - не просто соседка.</p>'),(66,'Когда я вырасту',3,'','<p>Два неудачника в Берлине примеряют на себя разные профессии и снимают про это сериал. Это теперь их работа.</p>'),(70,'Искупитель грехов',3,'<p>Священник принимает исповедь грешников сидя в контейнере в центре Берлина. Вольфганг был понижен в должности за то, что пил и вел темные дела. В контейнере он пытается выкупить себя, чтобы вернуть свой приход. Он помогает людям навести порядок в их жизни. К сожалению, его собственные проблемы остаются нерешенными.</p>',''),(78,'Соседи',3,'<p>К двум подругам заселяется сосед Хьюго. Их жизнь никогда не будет прежней...</p>',''),(131,'Деканат',3,'','<p>Spin-off известного сериала&ldquo;End it All Now&rdquo; о студентах Нью-Йоркского университета.</p>'),(143,'Витая в облаках',3,'','<p>Витая в облаках</p>'),(167,'костя',3,'',''),(188,'Мы все хотим убить президента',3,'','<p>Мы все хотим убить президента</p>'),(195,'Жизнь класса \"А\"',3,'','<p>Андреа не очень-то довольна своей жизнью &ndash; родители задавили гиперопекой, бойфренд, наоборот, не очень-то вкладывается в отношения. По пути на выпускной, девушка попадает в переплет: беглый преступник угрожает ей оружием и, запрыгнув в ее машину, фактически берет ее в заложницы. Но, как ни странно, это происшествие и опасные приключения, в которые ее втягивает бандит, заставят героиню впервые за долгое время почувствовать себя живой.</p>'),(206,'Идет съемка',3,'','<p>Сатира о съемках реалити-шоу, разрушающая четвертую, пятую, шестую и седьмую стены.</p>'),(214,'10 шагов к сексу',3,'','<p>В своем новом документальном фильме &laquo;Десять шагов к сексу&raquo; создатели сериала сосредоточены на исследованиях, проведенных мыслителями, учеными, художниками и терапевтами, которые стремятся раскрыть сложность того, как человеческое сердце, тело и дух соединяются с нашими занятиями любовью. Если вы хотите больше узнать о самой глубокой и истинной части нашего сексуального я, &laquo;Десять шагов к сексу&raquo; обещает раскрыть все тайны.</p>'),(217,'Сильван великолепный',3,'','<p>Сильван, известный как Великолепный - известный иллюзионист Квебека, который тайно оказывается настоящим волшебником. Циничный, разочарованный своей профессией и славой, Сильван пребывает в большом сомнении. Его личная жизнь несчастна. Конкуренция с соперником, иллюзионистом Барлутом Эстоном, больше не мотивирует его так, как раньше. Прибытие таинственного незнакомца может расстроить многое в его жизни.</p>'),(219,'Le sexe en 10 temps',3,'','<p>В своем новом документальном фильме &laquo;Десять шагов к сексу&raquo; создатели сериала сосредоточены на исследованиях, проведенных мыслителями, учеными, художниками и терапевтами, которые стремятся раскрыть сложность того, как человеческое сердце, тело и дух соединяются с нашими занятиями любовью. Если вы хотите больше узнать о самой глубокой и истинной части нашего сексуального я, &laquo;Десять шагов к сексу&raquo; обещает раскрыть все тайны.</p>'),(221,'Вертикаль тест',3,'','');
/*!40000 ALTER TABLE `media__content__season__strings__lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__seasonseason__strings__lang_en`
--

DROP TABLE IF EXISTS `media__content__seasonseason__strings__lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__seasonseason__strings__lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `mediacontent__sese_str_lang_en_2_media__constnt_sese` FOREIGN KEY (`id`) REFERENCES `media__content__season__season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__seasonseason__strings__lang_en`
--

LOCK TABLES `media__content__seasonseason__strings__lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__seasonseason__strings__lang_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__seasonseason__strings__lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__seasonseason__strings__lang_ru`
--

DROP TABLE IF EXISTS `media__content__seasonseason__strings__lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__seasonseason__strings__lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `mediacontent__sese_str_lang_ru_2_media__constnt_sese` FOREIGN KEY (`id`) REFERENCES `media__content__season__season` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__seasonseason__strings__lang_ru`
--

LOCK TABLES `media__content__seasonseason__strings__lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__seasonseason__strings__lang_ru` DISABLE KEYS */;
INSERT INTO `media__content__seasonseason__strings__lang_ru` VALUES (52,'Сезон 1',3,'',''),(68,'Сезон 1',3,'',''),(71,'1 сезон',3,'',''),(79,'1 сезон',3,'',''),(132,'Сезон 1',3,'',''),(144,'1 сезон',3,'',''),(175,'-',3,'',''),(189,'Сезон 1',3,'',''),(196,'Сезон 1',3,'',''),(207,'Сезон 1',3,'',''),(215,'Сезон 1',3,'',''),(218,'Сезон 1',3,'',''),(222,'Сезон 1',3,'','');
/*!40000 ALTER TABLE `media__content__seasonseason__strings__lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__studio__list`
--

DROP TABLE IF EXISTS `media__content__studio__list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__studio__list` (
  `media_id` bigint(19) unsigned NOT NULL,
  `studio_id` bigint(19) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`,`studio_id`),
  UNIQUE KEY `studio_id` (`studio_id`,`media_id`),
  KEY `sort` (`sort`),
  CONSTRAINT `media__content__studio__list__2__media` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__studio__list__2_studio` FOREIGN KEY (`studio_id`) REFERENCES `media__studio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Студии конкретного контента';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__studio__list`
--

LOCK TABLES `media__content__studio__list` WRITE;
/*!40000 ALTER TABLE `media__content__studio__list` DISABLE KEYS */;
INSERT INTO `media__content__studio__list` VALUES (51,13,0),(70,16,0),(78,18,0),(131,21,0),(143,22,0),(167,2,0),(188,2,0),(206,21,0),(214,13,0),(219,13,0),(221,10,0);
/*!40000 ALTER TABLE `media__content__studio__list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__tag`
--

DROP TABLE IF EXISTS `media__content__tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__tag` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__tag`
--

LOCK TABLES `media__content__tag` WRITE;
/*!40000 ALTER TABLE `media__content__tag` DISABLE KEYS */;
INSERT INTO `media__content__tag` VALUES (1,0),(2,0),(3,0),(4,0),(5,0),(6,0),(7,0),(8,0),(9,0),(10,0),(11,0),(12,0),(13,0),(14,0),(15,0),(16,0),(17,0);
/*!40000 ALTER TABLE `media__content__tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__tag__list`
--

DROP TABLE IF EXISTS `media__content__tag__list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__tag__list` (
  `media_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`,`tag_id`),
  UNIQUE KEY `tag_id` (`tag_id`,`media_id`),
  CONSTRAINT `media__content_tag__list_2__media` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content_tag__list_2__tags` FOREIGN KEY (`tag_id`) REFERENCES `media__content__tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__tag__list`
--

LOCK TABLES `media__content__tag__list` WRITE;
/*!40000 ALTER TABLE `media__content__tag__list` DISABLE KEYS */;
INSERT INTO `media__content__tag__list` VALUES (51,9,0),(66,3,0),(70,10,0),(78,11,0),(109,4,0),(110,5,0),(111,4,0),(112,1,0),(112,2,1),(113,6,0),(114,4,0),(115,2,1),(115,3,0),(115,4,2),(115,6,3),(116,4,0),(117,5,0),(118,7,0),(119,8,0),(120,8,0),(151,4,0),(155,11,0),(157,11,0),(158,11,0),(159,11,0),(160,11,0),(161,11,0),(167,6,0),(188,9,0),(206,10,0),(214,9,0),(217,14,0);
/*!40000 ALTER TABLE `media__content__tag__list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__tag__strings`
--

DROP TABLE IF EXISTS `media__content__tag__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__tag__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`) USING BTREE,
  UNIQUE KEY `language_id` (`language_id`,`id`),
  CONSTRAINT `media__content__tag__strings_2_language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__tag__strings_2_tag` FOREIGN KEY (`id`) REFERENCES `media__content__tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__tag__strings`
--

LOCK TABLES `media__content__tag__strings` WRITE;
/*!40000 ALTER TABLE `media__content__tag__strings` DISABLE KEYS */;
INSERT INTO `media__content__tag__strings` VALUES (1,'en','#Witcher2012'),(1,'ru','#Ведьмак2012'),(2,'en','#stayhome'),(2,'ru','#Сидидома'),(3,'en','#WIGU'),(3,'ru','#WIGU'),(4,'en','#онлайнкинотеатры'),(4,'ru','#онлайнкинотеатры'),(5,'en','#moretv'),(5,'ru','#moretv'),(6,'en','#театр'),(6,'ru','#театр'),(7,'en','#appletv'),(7,'ru','#appletv'),(8,'en','#фестиваль'),(8,'ru','#фестиваль'),(9,'en','#драмавесны'),(9,'ru','#драмавесны'),(10,'en','#комедиягода'),(10,'ru','#комедиягода'),(11,'en','#rumis'),(11,'ru','#rumis'),(13,'en','sylvainlemagnifique'),(14,'en','#comedy'),(14,'ru','#комедия'),(15,'en','#comedy'),(16,'ru','#магия'),(17,'ru','#фантастика');
/*!40000 ALTER TABLE `media__content__tag__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__text`
--

DROP TABLE IF EXISTS `media__content__text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__text` (
  `id` bigint(19) unsigned NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `default_poster` varchar(100) DEFAULT NULL,
  `post` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  CONSTRAINT `media__content__text_meco` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__text`
--

LOCK TABLES `media__content__text` WRITE;
/*!40000 ALTER TABLE `media__content__text` DISABLE KEYS */;
INSERT INTO `media__content__text` VALUES (109,'Вице-президент Disney Russia Игорь Макаров перешел в ivi','3fa05f536fdf4060eae09e4c5db07dbf','2020-04-17 12:37:00'),(110,'Сервис more.tv проведет кинофестиваль «Циолковский» онлайн','f19f22de72396fb48478b37bec27cd28','2020-04-03 12:37:00'),(111,'Хакамада, Донцова и Лукьяненко читают «Сказки на ночь» на Okko','ecedc8d82374911e57c02713b15a38fb','2020-04-10 12:37:00'),(112,'Канье Уэст вместе с церковным хором проведет онлайн пасхальную службу','7e787bac33c37a8e9b0ce36d2442a79b','2020-04-18 12:37:00'),(113,'Актеры Московского театра им. А. С. Пушкина прочитают онлайн «Пир во время чумы»','0211003e05f76aee2b67a6c3c818e2e3','2020-04-14 12:37:00'),(114,'«Лед-2», «Союз спасения» и «Корни» - видеосервис Wink рассказал, что смотрят люди на карантине','846e75c194dfc089183c2550c337bda7','2020-04-15 12:37:00'),(115,'Студия Михаила Зыгаря совместно с Яндексом расскажет, каким был «Настоящий 1945»','edbea8a0bcc204ac25ce561d30687e1a','2020-04-09 12:37:00'),(116,'START покажет «Хронику эпидемии»','b144e0ffb6b9d9c5daa01796b68aa086','2020-04-12 12:27:00'),(117,'На more.tv выйдет приквел «Гоморры» с Алексеем Гуськовым','e80b66f9dc13fec3d6a198ecbf68b4e2','2020-04-04 12:27:00'),(118,'Apple TV+ открывает бесплатный доступ к части своего контента','d4847e2a1f045d5b0bfa4d5db07bf689','2020-04-11 16:00:00'),(119,'Объявлен прием заявок на Третий международный фестиваль веб-сериалов REALIST WEB FEST','129fed56e9b24d5f47b4a09bd118993e','2020-04-01 15:00:00'),(120,'III  фестиваль веб-сериалов REALIST WEB FEST объявляет конкурс первых серий','fe034a8a413dabeac8865160fad72f40','2020-04-15 15:00:00'),(128,'Заголовок виден в ленте','fe9b101022f8da84fc896d5eb7d1e3ea','2020-04-15 13:00:00'),(151,'HBO Max Service Launches May 27','eb5ba49a9f3f16d33236a8bd1ff14854','2020-04-22 09:56:00');
/*!40000 ALTER TABLE `media__content__text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__text__strings__lang_en`
--

DROP TABLE IF EXISTS `media__content__text__strings__lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__text__strings__lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__text__strings_lang_en_mecotext` FOREIGN KEY (`id`) REFERENCES `media__content__text` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__text__strings__lang_en`
--

LOCK TABLES `media__content__text__strings__lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__text__strings__lang_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__text__strings__lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__text__strings__lang_ru`
--

DROP TABLE IF EXISTS `media__content__text__strings__lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__text__strings__lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__text__strings_lang_ru_mecotext` FOREIGN KEY (`id`) REFERENCES `media__content__text` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__text__strings__lang_ru`
--

LOCK TABLES `media__content__text__strings__lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__text__strings__lang_ru` DISABLE KEYS */;
INSERT INTO `media__content__text__strings__lang_ru` VALUES (109,'Вице-президент Disney Russia Игорь Макаров перешел в ivi',3,'','<p>Вице-президент Disney Russia Игорь Макаров перешел на работу в онлайн-кинотеатр ivi, где займется развитием маркетинга, пользовательских продуктов и детского контента онлайн-кинотеатра ivi. Об этом сообщает ТАСС со ссылкой на пресс-службу сервиса.<br />&laquo;Чтобы усилить направление пользовательского опыта и маркетинга, с 6 апреля к команде ivi присоединится Игорь Макаров, который на протяжении последних 13 лет был вице-президентом российского офиса Disney&raquo;, - отметили в пресс-службе ivi, указав, что Макаров займет должность заместителя директора компании.</p>\n<p>В ivi также перешла Кристина Сникерс, занимавшая должность креативного директора промо телеканала &laquo;Супер&raquo; итакже Алексей Федоров, отвечавший за имиджевую рекламу и спецпроекты ТНТ и запуски кинопроектов телеканала.</p>\n<p>Онлайн-кинотеатр ivi был запущен в 2010 году и сейчас является одним из лидеров российского стримингового рынка. По данным сервиса, в январе 2019 года его ежемесячная платящая аудитория превысила 2,5 млн пользователей.</p>'),(110,'Сервис more.tv проведет кинофестиваль «Циолковский» онлайн',3,'','<p>Международный кинофестиваль фильмов о космосе &laquo;Циолковский&raquo; (&laquo;Tsiolkovsky&raquo; Space Fest), который планировалось провести в этом году 9-12 апреля, из-за ситуации с коронавирусом и запретом на проведение массовых мероприятий пройдет онлайн на сервисе more.tv. На платформе будут показаны не только конкурсные фильмы, но и будут объявлены победители.</p>\n<p>&laquo;В текущих реалиях перенос фестиваля в онлайн-среду кажется вынужденной, но необходимой мерой, чтобы не оставлять зрителя одного в столь тревожное время и предложить ему удобный доступ к фильмам фестиваля&raquo;, - отметил генеральный директор more.tv Денис Горшков.<br />По данным организаторов, на участие в основном конкурсе претендовали более 1000 проектов из 80 стран мира. Подведению итогов фестиваля будет посвящена специальная программа, которая выйдет на сервисе 12 апреля 2020 года. В рамках этой программы будут объявлены лауреаты конкурса художественных, документальных фильмов и телепрограмм и обладатели основных призов фестиваля.</p>'),(111,'Хакамада, Донцова и Лукьяненко читают «Сказки на ночь» на Okko',3,'','<p>Сбербанк и онлайн-сервис Okko совместно с Российским движением школьников и &laquo;Литературной газетой&raquo; запустили детский онлайн-проект &laquo;Сказки на ночь&raquo;, где можно послушать марийские, алтайские, кабардинские и другие сказки.</p>\n<p>Как сообщили в пресс-службе Сбербанка, читать их будут под музыкальный аккомпанемент профессор Московской консерватории Евгения Кривицкая, русский поэт и актер Всеволод Емелин, русский писатель и публицист Александр Варламов, поэт, автор песен и музыкант Вадим Степанцов, писатели Сергей Лукьяненко, Гузель Яхина, Дарья Донцова, Валерий Воскобойников, Денис Драгунский и другие деятели культуры и искусства.</p>\n<p>Игра теней, съемка чтецов и тематические иллюстрации будут составлять визуальную часть сказки.</p>\n<p>Первая сказка &laquo;Айога&raquo; в исполнении Ирины Хакамады уже доступна в бесплатной медиатеке &laquo;Когда мы дома&raquo; на Okko. В разделе &laquo;Искусство Онлайн&raquo; здесь также есть спектакли и виртуальные экскурсии от ведущих театров и музеев, в &laquo;Шоу ON!&raquo; можно послушать концерты рок- и поп-звезд. Кроме того, в проекте &laquo;ФитнесПротивВируса&raquo; есть онлайн-тренировки от атлетов и актеров.</p>'),(112,'Канье Уэст вместе с церковным хором проведет онлайн пасхальную службу',3,'','<p>Отождествляющий себя с Богом рэпер Канье Уэст вместе со своим хором Sunday Service Choir примет участие в пасхальной службе, которую можно будет посмотреть онлайн 12 апреля. Как сообщает портал TMZ, его пригласил телепроповедник Джоэл Остин. Службу будет транслировать Лейквудская церковь в Хьюстоне. Помимо Уэста, в богослужении примут участие певица Мэрайя Кэри и актер Тайлер Перри.<br />Мэрайя Кэри, в частности, исполнит композицию &laquo;Hero&raquo;, посвященную подвигу медицинских работников в борьбе с коронавирусом. Перри при этом обратится к зрителям со словами ободрения. А Канье Уэст со своим хором будет участвовать в служении в Лос-Анджелесе.</p>\n<p>Неизвестно, будет ли это живое выступление или записанное заранее, однако оно будет призывать к вынужденному социальному дистанцированию в эпоху коронавируса.</p>\n<p>Это не первое выступление Уэста вместе с церковным хором. В начале 2019 года он запустил проект Sunday Service (&laquo;Воскресные службы&raquo;), в рамках которого проводит закрытые концерты вместе с госпел-хором и исполняет каверы на собственные песни, а также чужие композиции.</p>'),(113,'Актеры Московского театра им. А. С. Пушкина прочитают онлайн «Пир во время чумы»',3,'','<p>Информационный портал LIFE совместно с проектом #Москвастобой, созанным столичным Комитетом по туризму запускают вторую совместную онлайн-трансляцию театрального вечера москвовского драматического театра им. А.С. Пушкина, в рамках которого артисты прочитают одну из &laquo;Маленьких трагедий&raquo;. Трансляция начнется в эфир 9 апреля в 19:00 в социальных сетях LIFE.</p>\n<p>В основе программы &ndash; трагедия &laquo;Пир во время чумы&raquo;, которую Пушкин написал в разгар эпидемии холеры. В произведении затрагивается тема противоречий человеческого бытия, когда назло безжалостной смерти герои веселятся, показывают, что не боятся умереть, пытаются сохранить человеческое достоинство. Но как бы сильно пирующие ни старались отвлечься от трагической реальности, страшная действительность напоминает о себе.</p>\n<p>&laquo;Нам очень интересно попробовать новый формат. А в той ситуации, в которой мы все сегодня оказались, онлайн-спектакль практически единственная возможность творческой реализации для артистов. &laquo;Пир по время чумы&raquo; Александра Сергеевича Пушкина на сегодня, наверное, самый актуальный материал, и он прекрасно \"работает\" в таком решении&raquo;, - отметил художественный руководитель московского драматического театра им. А.С. Пушкина Евгений Писарев.</p>\n<p>В онлайн-трансляции принимают участие артисты Антон Феоктистов, Владимир Майзингер, Анна Кармакова, Анастасия Мытражик, Назар Сафонов. Режиссерами выступают Евгений Писарев и Наталья Волошина.</p>'),(114,'«Лед-2», «Союз спасения» и «Корни» - видеосервис Wink рассказал, что смотрят люди на карантине',3,'','<p>Видеосервис Wink компании &laquo;Ростелеком&raquo; представил рейтинг сериалов и фильмов, которые лидируют по просмотрам во время режима самоизоляции, введенного в стране для борьбы с распространением коронавируса.</p>\n<p>Как отметили в пресс-службе сервиса, просмотр ТВ и киносериального контента продолжает увеличиваться, помогая людям не чувствовать себя в культурной и информационной изоляции. Так, общее количество просмотров за неделю на Wink увеличилось на 23%. &laquo;При этом прослеживаются определенные тенденции в потреблении контента. Прежде всего, в рейтинг лидеров просмотров впервые попали новые категории &mdash; &laquo;Фитнес&raquo; и &laquo;Школьные уроки&raquo;, - отметили представители сервиса.</p>\n<p>Среди российских фильмов первую строчку по популярности занял &laquo;Лед-2&raquo;, который появился на Wink 2 апреля и сразу же пробился в лидеры. Сразу за ним идут &laquo;Союз спасения&raquo;, &laquo;Отель Белград&raquo;, &laquo;Вторжение&raquo; и &laquo;Холоп&raquo;. В топе иностранных - &laquo;Заражение&raquo; Стивена Содерберга, &laquo;Джентльмены&raquo;, &laquo;Бладшот&raquo;, &laquo;Джон Уик 3&raquo; и &laquo;Паразиты&raquo;.</p>\n<p>Среди сериалов самыми востребованными оказались &laquo;Корни&raquo;, &laquo;Филатов&raquo;, &laquo;Содержанки&raquo;, &laquo;Ходячие мертвецы&raquo;, &laquo;Друзья&raquo;, &laquo;Игра престолов&raquo;, &laquo;Как я встретил вашу маму&raquo; (в переводе Кураж-Бамбей), &laquo;Мир Дикого Запада&raquo;.</p>\n<p>Также очень популярной стала категория &laquo;Мультфильмы&raquo; особенно среди клиентов, пользующихся сервисом на телевизорах с ТВ-приставкой. Здесь в лидерах &laquo;Маша и медведь&raquo;, &laquo;Щенячий патруль&raquo;, &laquo;Губка Боб квадратные штаны&raquo;, &laquo;Босс-молокосос&raquo;.</p>\n<p>Видеосервис Wink 16 марта открыл бесплатный доступ к своему контенту. Там, в частности, можно посмотреть отечественные и зарубежные фильмы, сериалы, детский развлекательный и образовательный контент, телеканалы о культуре и искусстве (Mezzo, Mezzo live HD, &laquo;Театр&raquo;, Museum, iConcerts), также здесь есть фитнес-тренировки, кулинарные курсы, уроки школьной программы, видео-путешествия и экскурсии, спектакли, музеи и концерты.</p>'),(115,'Студия Михаила Зыгаря совместно с Яндексом расскажет, каким был «Настоящий 1945»',3,'','<p>Креативная студия &laquo;История будущего&raquo; Михаила Зыгаря совместно с издательством Яндекса запустила проект &laquo;Настоящий 1945&raquo;, в котором воссоздается жизнь последних недель перед Победой глазами тех, кто ее застал.</p>\n<p>Среди героев проекта &mdash; маршалы Георгий Жуков и Иван Конев, писатели Михаил Пришвин и Эрнест Хемингуэй, поэтессы Ольга Берггольц и Анна Ахматова, Давид Самойлов и премьер-министр Великобритании Уинстон Черчилль, переживший подростком холокост Отто Вольф и многие другие.</p>\n<p>&laquo;Обычно содержание дневников, писем и заметок доступно только историкам. Но с помощью Издательства Яндекса мы придумали проект, который позволит всем нам, сидя дома, узнать, какой была жизнь в 1945 году&raquo;, - сообщают создатели проекта. Он будет доступен с 9 апреля по 10 мая в Яндекс.Дзене, Яндекс.Эфире, Яндекс.Коллекциях.</p>\n<p>В Яндекс.Дзене истории и воспоминания людей о сегодняшнем дне в 1945 году будут появляться ежедневно. Здесь будут опубликованы больше сотни профайлов, дневников участников и очевидцев о том, что происходило в жизни не только политиков и военачальников, но и тех, кто ожидал их в тылу. Мысли и чувства людей, зафиксированные в блогах, фото и видео день за днем так, если бы 75 лет назад уже существовали социальные сети.</p>\n<p>В Яндекс.Эфире еженедельно будут появляться серии из коротких видео, рассказывающих о главных событиях апреля 1945 от первого лица. Если бы у участников, например, Ялтинской конференции и взятия Берлина были мобильные телефоны, что бы они писали своим соратникам.</p>\n<p>В Яндекс.Коллекциях будут впервые представлены подборки архивных фотографий, иллюстрирующих военную и мирную жизнь в разных странах в самом конце войны.</p>\n<p>Также проект покажет, с помощью прямых включений из пяти разных точек мира, как встречали окончание войны города-победители .<br />Консультантами проекта выступили доктор исторических наук Олег Будницкий, кандидат исторических наук Кирилл Болдовский, кандидат исторических наук, консультант по списку персонажей и дневников, использованных в &laquo;Настоящий 1945&raquo; Вера Дубина.</p>\n<p>Студия &laquo;История Будущего&raquo; работала над созданием Проекта 1917, посвященного 100-летию Октябрьской революции, а также онлайн-игры об истории СССР &laquo;Карта истории&raquo;. В сотрудничестве с Тимуром Бекмамбетовым они также сняли документальный веб-сериал 1968.Digital, который получил на первой российской премии в области веб-индустрии, организованной в ноябре 2018 года проектом The Digital Reporter, награду за оригинальное концептуальное решение и стал лучшим веб-сериалом по мнению прессы. В августе 2019 года стал обладателем приза за лучшую идею на единственном в России фестивале веб-сериалов REALIST WEB FEST, проходившем в Нижнем Новгороде со 2 по 6 августа.</p>\n<p>Также 19 июня студия презентовала проект &laquo;Мобильный Художественный Театр&raquo; (МХТ).</p>'),(116,'START покажет «Хронику эпидемии»',3,'','<p>Видеосервис START с 15 апреля начнет показывать канадский триллер &laquo;Хроника эпидемии&raquo; о том, как врачи начинают бороться с неизвестным вирусом, массово заражающим жителей Монреаля.</p>\n<p>Премьера сериала состоялась на TVA в Квебеке 7 января 2020 года, когда эпидемия коронавируса уже была в Китае и начала распространяться на Европу и США. Этот проект также открывал Fresh TV Formats на рынке контента MIPTV 2020.</p>\n<p>&laquo;Когда мы увидели этот сериал, мы были шокированы тем, насколько быстро художественная выдумка создателей стала реальностью, всего через несколько месяцев после завершения съемок&raquo;, - рассказал руководитель контентного направления видеосервиса START Михаил Клочков.</p>\n<p>Сюжет сериала строится вокруг инфекциониста Анн-Мари Леклерк (Жюли ЛеБретон), которая пытается сделать все возможное, чтобы остановить вирус, стремительно распространяющийся среди населения Монреаля и поражающий органы дыхания. У всех заболевших одинаковые симптомы: потемнение в глазах, повышенная температура и сильнейший кашель. Сначала жители относятся к вирусу скептически, но скоро он начинает распространяться с невероятной скоростью, и малейшие симптомы вызывают у людей панику и страх.</p>\n<p>&laquo;В сериале мы хотим обратить внимание зрителей на то, что во время эпидемии медицинские работники находятся на передовой и зачастую сами становятся жертвами. Врачи скорой помощи, медсестры и другой персонал принимают все основные меры предосторожности, но пара перчаток и маска не защищают от смертельного вируса&raquo;, - рассказала создатель &laquo;Хроники эпидемии&raquo; Анни Пьерар.</p>\n<p>Видеосервис START был основан студией Yellow, Black and White в октябре 2017 года. Он предлагает по подписке пользователям всего мира оригинальные сериалы и фильмы на русском языке и работает на всех платформах, включая сайт START.RU, мобильные устройства и телевизоры Smart TV.</p>'),(117,'На more.tv выйдет приквел «Гоморры» с Алексеем Гуськовым',3,'','<p>На видеосервисе more.tv 13 апреля выйдет фильм &laquo;Бессмертный&raquo;, являющийся приквелом популярного итальянского сериала &laquo;Гоморра&raquo;. Его режиссером стал исполнитель главной роли как в сериале, так и фильме Марко Д&rsquo;Аморе. Также одну из ролей в &laquo;Бессмертном&raquo; исполнил российский актер Алексей Гуськов, сыгравший крестного отца русской мафии.</p>\n<p>Сюжет фильма посвящен прошлому главного персонажа &laquo;Гоморры&raquo; Чиро Ди Марцио. Он получает пулевое ранение в грудь и, падая в воды неаполитанского залива, вспоминает всю свою жизнь. В 1980 году во время страшного землетрясения его, будучи еще младенцем, извлекли из-под завалов. С того самого дня его называли Бессмертным, это прозвище в преступном мире он оправдывал не раз. От мелких краж до настоящей мафиозной войны &ndash; он был готов к любым вызовам преступного мира, где бессмертие &ndash; просто еще одна форма проклятия.</p>\n<p>&laquo;Марко Д\'Аморе сразу сказал, что видит в роли своего антагониста именно меня. Посмотрев фильмы &laquo;Концерт&raquo; и &laquo;Признание&raquo;, где я работал вместе с его другом и коллегой Тони Сервилло, Марко настоял, чтобы итальянская студия со мной связалась, - рассказал Гуськов. - Вместе мы работали в первый раз и меня немного беспокоил тот факт, что неаполитанцы говорят на своем особом диалекте, который мне незнаком. Но благо с самого начала у нас сложился хороший контакт, поэтому мы понимали друг друга очень быстро&raquo;.</p>\n<p>more.tv &mdash; онлайн-сервис &laquo;Национальной Медиа Группы&raquo;, объединивший эксклюзивный контент и прямой эфир популярных российских телеканалов, зарубежные премьеры сериалов и фильмов, собственные проекты в линейке more originals, а также прямые трансляции спортивных событий. Контент можно смотреть на сервисе бесплатно с рекламой или в единой подписке.</p>'),(118,'Apple TV+ открывает бесплатный доступ к части своего контента',3,'','<p>Стриминговый сервис Apple TV+ открывает бесплатный доступ к части своих сериалов и фильмов, чтобы призвать людей по всему миру оставаться дома в режиме самоизоляции и предотвратить распространение коронавируса.</p>\n<p>Без подписки на сервис, в частности, будут доступны проекты для разных возрастных категорий и сфер интересов - документальный фильм о дикой природе &laquo;Королева слонов&raquo;, рассказанный Чиветелем Эджиофором, а также сериал о жизни иммигрантов &laquo;Маленькая Америка&raquo;, &laquo;Дом с прислугой&raquo; М. Найта Шьямалана, драма об астронавтах &laquo;Ради всего человечества&raquo;, сериал о знаменитой поэтессе &laquo;Дикинсон&raquo;, познавательные &laquo;Помощники&raquo; для дошкольников от создателей &laquo;Улицы Сезам&raquo;, перезапущенный проект &laquo;Послание призрака&raquo; и мультфильм &laquo;Снупи в космосе&raquo;.</p>\n<p>Режим &laquo;не выходи из комнаты&raquo;: что и где бесплатно смотреть и слушать&gt;&gt;</p>\n<p>Для пользователей из США бесплатный доступ открылся уже 9 апреля, в остальных 100 странах, где работает сервис, в том числе в России, функция стала доступной 10 апреля через приложение Apple TV.</p>\n<p>Ранее HBO объявил, что он предоставляет около 500 часов бесплатного контента, но только для американских зрителей. Среди них сериалы &laquo;Клан Сопрано&raquo;, &laquo;Барри&raquo;, &laquo;Силиконовая долина&raquo;, &laquo;Настоящая кровь&raquo; и другие.</p>'),(119,'Объявлен прием заявок на Третий международный фестиваль веб-сериалов REALIST WEB FEST',3,'','<p>Третий международный фестиваль веб-сериалов REALIST WEB FEST, который пройдет с 1 по 5 августа 2020 года в Нижнем Новгороде, открывает прием заявок.</p>\n<p>На участие в конкурсной программе смотра могут претендовать веб-сериалы, произведенные в 2019-2020 гг. на любом языке. Проект должен содержать не менее двух готовых эпизодов.</p>\n<p>Заявки принимаются с 28 февраля по 15 мая 2020 года через сайт webfestival.ru, электронную почту info@webfestival.ru и специализированную онлайн-платформу filmfreeway.com.</p>\n<p>Заявка должна содержать ссылки на видео (не более двух готовых эпизодов), название и синопсис веб-сериала. Обращаем ваше внимание на то, что заявки, присланные ПОСЛЕ указанного срока, НЕ ПРИНИМАЮТСЯ!</p>\n<p>&laquo;Всех победил интернет&raquo;: завершился второй REALIST WEB FEST&gt;&gt;</p>\n<p>REALIST WEB FEST &ndash; единственный в России фестиваль веб-сериалов. С конца 2018 года он стал частью Международного чемпионата веб-сериалов (Web Series World Cup, WSWC). Web Series World Cup представляет собой таблицу самых успешных онлайн-проектов года по итогам главных мировых веб-фестивалей. Очки сериалам присуждаются следующим образом: за каждое попадание в программу одного фестиваля участник получает 5 баллов, каждая номинация в специальной категории будет оценена в 3 очка, а каждая награда будет стоить 10 баллов. В итоге побеждает тот сериал, который набирает суммарно больше всего очков.</p>\n<p><strong>* ENGLISH *</strong></p>\n<p>REALIST-2020 opens submissions!</p>\n<p>All submissions are FREE. The deadline &ndash; May, 15 (applications submitted after that date will not be accepted). Each application must contain the links to the video files (two episodes of the web series), the project title and the series synopsis.</p>\n<p>To submit, please, use the special submission form on the official REALIST web site webfestival.ru/en/, e-mail: info@webfestival.ru or do it via FilmFreeway page - both have the full information about the event and the list of the festival rules and regulations.</p>\n<p>The third edition of the first in Russia (and the only one in Russia!) international web series festival REALIST WEB FEST &ndash; now the part of the Web Series World Cup - will take place on August, 1-5, in Nizhny Novgorod.</p>\n<p>Web series, submitted to the REALIST WEB FEST competition, should be produced in 2019-20 and has at least two episodes. We accept feature, documentary and animated web series of any genre, length, language, and country of origin.</p>'),(120,'III  фестиваль веб-сериалов REALIST WEB FEST объявляет конкурс первых серий',3,'','<p>Третий международный фестиваль веб-сериалов REALIST WEB FEST, который пройдет с 1 по 5 августа 2020 года в Нижнем Новгороде, объявляет конкурс первых серий веб-сериалов.</p>\n<p>На участие в конкурсной программе этого блока могут претендовать произведенные в 2019-2020 гг. первые эпизоды проектов веб-сериалов, снятые на любых языках. Хронометраж эпизода &ndash; до 24 минут.</p>\n<p>Заявки принимаются по 5 июня 2020 года по адресу info@webfestival.ru. В теме письма нужно указать: &laquo;Заявка на конкурс первых серий&raquo;. Заявка должна содержать ссылку на видео, название и синопсис веб-сериала. Обращаем ваше внимание на то, что заявки, присланные ПОСЛЕ указанного срока, НЕ ПРИНИМАЮТСЯ!</p>\n<p>Объявлен прием заявок на Третий международный фестиваль веб-сериалов REALIST WEB FEST&gt;&gt;</p>\n<p><strong>REALIST WEB FEST-2020 opens PILOT section!</strong></p>\n<p>The third edition of the international web series festival REALIST WEB FEST, which will be held on August 1-5, in Nizhny Novgorod, now opens submissions to the PILOT competition.</p>\n<p>Web series pilots, submitted to the REALIST WEB FEST, should be produced in 2019-2020. We accept feature, documentary and animated projects of any genre, language, and country of origin. The pilot length must be up to 24 minutes.</p>\n<p>All submissions are FREE. The deadline &ndash; June, 5 (applications submitted after that date will not be accepted). To submit, please, send an application to the info@webfestival.ru (with the &laquo;Pilot application&raquo; in the subject of the letter) or do it via FilmFreeway page https://filmfreeway.com/RealistWebFest.<br />Each application must contain the link to the video file, the project title and the series synopsis.</p>'),(128,'Заголовок',3,'<p>ппваппвкеп</p>','<p>вкпвкпвкп</p>'),(151,'Сервис HBO Max запустится 27 мая',0,'','<p style=\"margin-left: 0cm; margin-right: 0cm;\">Корпорация WarnerMedia объявила дату запуска своего стримингового сервиса HBO Max - он заработает 27 мая. </p>\n<p style=\"margin-left: 0cm; margin-right: 0cm;\">Сервис обещает 10 тысяч часов контента, где будут представлены как оригинальные проекты, созданные специально для HBO Max, так и хиты HBO, Warner Bros., New Line, DC, Looney Tunes и др. - от &laquo;Клана Сопрано&raquo; до &laquo;Игры престолов&raquo;.</p>\n<p style=\"margin-left: 0cm; margin-right: 0cm;\"><iframe src=\"//www.youtube.com/embed/9yLNhhHs3-k?feature=emb_title\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>\n<p style=\"margin-left: 0cm; margin-right: 0cm;\">Летом и осенью на платформе ожидаются новые серии &laquo;Рокового патруля&raquo; (Doom Patrol, 2019) и долгожданный специальный эпизод сериала &laquo;Друзья&raquo;.</p>\n<p style=\"margin-left: 0cm; margin-right: 0cm;\">О том, в каких странах заработает сервис, пока не уточняется, будет ли среди них Россия, тоже не известно. Стоимость подписки на платформу составит $14,99 в месяц.</p>\n\n\n<blockquote class=\"instagram-media\" data-instgrm-permalink=\"https://www.instagram.com/p/B_ffHzwHMc0/?utm_source=ig_embed&amp;utm_campaign=loading\" data-instgrm-version=\"12\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\"><div style=\"padding:16px;\"> <a href=\"https://www.instagram.com/p/B_ffHzwHMc0/?utm_source=ig_embed&amp;utm_campaign=loading\" style=\" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;\" target=\"_blank\"> <div style=\" display: flex; flex-direction: row; align-items: center;\"> <div style=\"background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;\"></div> <div style=\"display: flex; flex-direction: column; flex-grow: 1; justify-content: center;\"> <div style=\" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;\"></div> <div style=\" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;\"></div></div></div><div style=\"padding: 19% 0;\"></div> <div style=\"display:block; height:50px; margin:0 auto 12px; width:50px;\"><svg width=\"50px\" height=\"50px\" viewBox=\"0 0 60 60\" version=\"1.1\" xmlns=\"https://www.w3.org/2000/svg\" xmlns:xlink=\"https://www.w3.org/1999/xlink\"><g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g transform=\"translate(-511.000000, -20.000000)\" fill=\"#000000\"><g><path d=\"M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631\"></path></g></g></g></svg></div><div style=\"padding-top: 8px;\"> <div style=\" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;\"> Посмотреть эту публикацию в Instagram</div></div><div style=\"padding: 12.5% 0;\"></div> <div style=\"display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;\"><div> <div style=\"background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);\"></div> <div style=\"background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;\"></div> <div style=\"background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);\"></div></div><div style=\"margin-left: 8px;\"> <div style=\" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;\"></div> <div style=\" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)\"></div></div><div style=\"margin-left: auto;\"> <div style=\" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);\"></div> <div style=\" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);\"></div> <div style=\" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);\"></div></div></div> <div style=\"display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;\"> <div style=\" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;\"></div> <div style=\" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;\"></div></div></a><p style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\"><a href=\"https://www.instagram.com/p/B_ffHzwHMc0/?utm_source=ig_embed&amp;utm_campaign=loading\" style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;\" target=\"_blank\">Публикация от он же Никита (@askinskiy)</a> <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2020-04-27T17:02:29+00:00\">27 Апр 2020 в 10:02 PDT</time></p></div></blockquote> <script async src=\"//www.instagram.com/embed.js\"></script>');
/*!40000 ALTER TABLE `media__content__text__strings__lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__tracklang`
--

DROP TABLE IF EXISTS `media__content__tracklang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__tracklang` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__tracklang`
--

LOCK TABLES `media__content__tracklang` WRITE;
/*!40000 ALTER TABLE `media__content__tracklang` DISABLE KEYS */;
INSERT INTO `media__content__tracklang` VALUES (1,0),(2,0),(4,0);
/*!40000 ALTER TABLE `media__content__tracklang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__tracklang__strings`
--

DROP TABLE IF EXISTS `media__content__tracklang__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__tracklang__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  KEY `name` (`name`),
  CONSTRAINT `mctlstrings2language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mcttlstrings_2_mctl` FOREIGN KEY (`id`) REFERENCES `media__content__tracklang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__tracklang__strings`
--

LOCK TABLES `media__content__tracklang__strings` WRITE;
/*!40000 ALTER TABLE `media__content__tracklang__strings` DISABLE KEYS */;
INSERT INTO `media__content__tracklang__strings` VALUES (2,'en','EN'),(2,'ru','EN'),(4,'en','FR'),(4,'ru','FR'),(1,'en','RU'),(1,'ru','RU');
/*!40000 ALTER TABLE `media__content__tracklang__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__trailer`
--

DROP TABLE IF EXISTS `media__content__trailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__trailer` (
  `id` bigint(19) unsigned NOT NULL,
  `content_id` bigint(19) unsigned NOT NULL,
  `vertical` int(1) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `default_image` varchar(100) DEFAULT NULL,
  `target_url` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `id` (`content_id`,`id`) USING BTREE,
  CONSTRAINT `media__content__trailer_2_media__content` FOREIGN KEY (`content_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__trailer_as_media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__trailer`
--

LOCK TABLES `media__content__trailer` WRITE;
/*!40000 ALTER TABLE `media__content__trailer` DISABLE KEYS */;
INSERT INTO `media__content__trailer` VALUES (73,70,0,0,'0ab0cf5ab62a3daece1514bfe5b4f033','/Soap/70'),(130,51,0,0,'885d6e206c895a6ed0d6feb1453c7b36','/Soap/51'),(177,167,0,1,NULL,NULL),(203,195,0,0,'bb6811eaade8820141fd35817d7173c5','/Soap/195'),(220,195,0,0,'7e8720bc8b044e7c4a236c60f87db591','/Soap/195'),(224,221,1,0,'f8d11a09dbb6a6760b32f1a627ec43d7','/Soap/221');
/*!40000 ALTER TABLE `media__content__trailer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__trailer__strings`
--

DROP TABLE IF EXISTS `media__content__trailer__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__trailer__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  KEY `name` (`name`),
  CONSTRAINT `media__content__trailer__strings__2__language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__trailer__strings__2__media__content__trailer` FOREIGN KEY (`id`) REFERENCES `media__content__trailer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__trailer__strings`
--

LOCK TABLES `media__content__trailer__strings` WRITE;
/*!40000 ALTER TABLE `media__content__trailer__strings` DISABLE KEYS */;
INSERT INTO `media__content__trailer__strings` VALUES (177,'en','1'),(177,'ru','1'),(203,'en','A-Live'),(130,'en','Anachronisme'),(130,'ru','Anachronisme'),(224,'en','Вертикальный фильм'),(224,'ru','Вертикальный фильм'),(220,'ru','Жизнь класса \"А\" (1 серия)'),(203,'ru','Жизнь класса \"А\" (трейлер)'),(73,'en','Искупитель грехов'),(73,'ru','Искупитель грехов');
/*!40000 ALTER TABLE `media__content__trailer__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__user__access`
--

DROP TABLE IF EXISTS `media__content__user__access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__user__access` (
  `media_id` bigint(19) unsigned NOT NULL,
  `user_id` bigint(19) unsigned NOT NULL,
  `deadline` bigint(19) NOT NULL DEFAULT '0',
  `links` mediumblob NOT NULL,
  PRIMARY KEY (`media_id`,`user_id`),
  KEY `media__content__access_2_user` (`user_id`),
  CONSTRAINT `media__content__access_2__media__content` FOREIGN KEY (`media_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__content__access_2_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__user__access`
--

LOCK TABLES `media__content__user__access` WRITE;
/*!40000 ALTER TABLE `media__content__user__access` DISABLE KEYS */;
INSERT INTO `media__content__user__access` VALUES (54,1,1589013408,_binary '[{\"id\":\"5e94941e0e47cf3d98cc8e1e\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eb5c2e0ef3db561901384c4\",\"deadline\":1589013408}]'),(54,22,1587515595,_binary '[{\"id\":\"5e94941e0e47cf3d98cc8e1e\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9ee80bef3db51902795ba8\",\"deadline\":1587515595}]'),(55,1,1587386437,_binary '[{\"id\":\"5e9499b6ef3db50f7abdec27\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9cef850e47cf56f72ad01c\",\"deadline\":1587386437}]'),(55,22,1587436436,_binary '[{\"id\":\"5e9499b6ef3db50f7abdec27\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9db2d40e47cf56f72ae44f\",\"deadline\":1587436436}]'),(56,1,1587505578,_binary '[{\"id\":\"5e94a0870e47cf3d98cc8ec2\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9ec0ea0e47cf56f72aea12\",\"deadline\":1587505578}]'),(56,22,1587359264,_binary '[{\"id\":\"5e94a0870e47cf3d98cc8ec2\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9c8560ef3db5190278ed73\",\"deadline\":1587359264}]'),(69,1,1587530080,_binary '[{\"id\":\"5e9762ec0e47cf3d98cc9c27\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9f20a6ef3db5190279629b\",\"deadline\":1587530080}]'),(69,22,1587411166,_binary '[{\"id\":\"5e9762ec0e47cf3d98cc9c27\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9d501e0e47cf56f72add3e\",\"deadline\":1587411166}]'),(72,22,1589151515,_binary '[{\"id\":\"5eb6dce20e47cf684413d692\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eb7de5cef3db5619013a4c5\",\"deadline\":1589151515}]'),(74,22,1587374045,_binary '[{\"id\":\"5e972036ef3db50f7abe0f01\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9cbf1d0e47cf56f72ac7ad\",\"deadline\":1587374045}]'),(75,1,1590321876,_binary '[{\"id\":\"5eb6dcf90e47cf684413d6a2\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ec9ba14ef3db522b1346de0\",\"deadline\":1590321876}]'),(76,1,1590321889,_binary '[{\"id\":\"5eb6dc8aef3db53f42a3080f\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ec9ba21ef3db522b1346de1\",\"deadline\":1590321889}]'),(80,1,1589083304,_binary '[{\"id\":\"5e9724b6ef3db50f7abe0fb4\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eb6d3e8ef3db561901396be\",\"deadline\":1589083304}]'),(80,22,1587461486,_binary '[{\"id\":\"5e9724b6ef3db50f7abe0fb4\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9e14aeef3db51902794cb8\",\"deadline\":1587461486}]'),(82,22,1587355588,_binary '[{\"id\":\"5e975905ef3db50f7abe1417\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9c7705ef3db5190278ebf3\",\"deadline\":1587355588}]'),(83,22,1587355597,_binary '[{\"id\":\"5e9766e4ef3db50f7abe14df\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9c770eef3db5190278ebf4\",\"deadline\":1587355597}]'),(85,1,1587459303,_binary '[{\"id\":\"5e9769470e47cf3d98cc9c33\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9e0c270e47cf56f72ae724\",\"deadline\":1587459303}]'),(87,22,1587357134,_binary '[{\"id\":\"5e976b61ef3db50f7abe1537\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9c7d0eef3db5190278ecb2\",\"deadline\":1587357134}]'),(91,22,1587372246,_binary '[{\"id\":\"5e976ac7ef3db50f7abe1518\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5e9cb816ef3db5190278f0ae\",\"deadline\":1587372246}]'),(133,1,1589574215,_binary '[{\"id\":\"5eb6da86ef3db53f42a30753\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ebe5187ef3db561901478bd\",\"deadline\":1589574215}]'),(133,22,1589151567,_binary '[{\"id\":\"5eb6da86ef3db53f42a30753\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eb7de8fef3db5619013a4c6\",\"deadline\":1589151567}]'),(134,22,1588237253,_binary '[{\"id\":\"5e9f4216ef3db50f7abe78f9\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea9eb05ef3db52e73f46a87\",\"deadline\":1588237253}]'),(135,22,1588237646,_binary '[{\"id\":\"5e9f41afef3db50f7abe78f7\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea9ec8eef3db52e73f46aac\",\"deadline\":1588237646}]'),(137,22,1588237986,_binary '[{\"id\":\"5e9f4295ef3db50f7abe78fa\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea9ede20e47cf332401d51b\",\"deadline\":1588237986}]'),(145,1,1590972139,_binary '[{\"id\":\"5eb6db2cef3db53f42a30783\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed3a62bef3db522b1354b20\",\"deadline\":1590972139}]'),(145,22,1588998173,_binary '[{\"id\":\"5e9f6bb5ef3db50f7abe7ade\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eb5875def3db56190138019\",\"deadline\":1588998173}]'),(176,1,1587908659,_binary '[{\"id\":\"5ea49231ef3db514e77657b7\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":null,\"deadline\":null}]'),(176,22,1587889074,_binary '[{\"id\":\"5ea49231ef3db514e77657b7\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea49af2ef3db514e7765832\",\"deadline\":1587889074}]'),(190,1,1589503835,_binary '[{\"id\":\"5eb6dc06ef3db53f42a307d8\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ebd3e9bef3db56190146721\",\"deadline\":1589503835}]'),(190,22,1588234202,_binary '[{\"id\":\"5ea8904f0e47cf6844139e33\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea9df1aef3db52e73f469ad\",\"deadline\":1588234202}]'),(191,22,1590970680,_binary '[{\"id\":\"5eb6dc1eef3db53f42a307f0\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed3a078ef3db522b1354abe\",\"deadline\":1590970680}]'),(192,22,1588235655,_binary '[{\"id\":\"5ea8969eef3db53f42a268a4\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea9e4c7ef3db52e73f46a1f\",\"deadline\":1588235655}]'),(193,22,1588236297,_binary '[{\"id\":\"5ea896c7ef3db53f42a268c3\",\"size\":\"1280x720\",\"content_type\":\"video\\/webm\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ea9e74aef3db52e73f46a53\",\"deadline\":1588236297}]'),(198,1,1590971400,_binary '[{\"id\":\"5eb6dbfcef3db53f42a307cf\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed3a348ef3db522b1354af5\",\"deadline\":1590971400}]'),(198,22,1590970589,_binary '[{\"id\":\"5eb6dbfcef3db53f42a307cf\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed3a01d0e47cf47bc38b83e\",\"deadline\":1590970589}]'),(198,34,1590542737,_binary '[{\"id\":\"5eb6dbfcef3db53f42a307cf\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ecd18d1ef3db522b134cb99\",\"deadline\":1590542737}]'),(198,35,1590972782,_binary '[{\"id\":\"5eb6dbfcef3db53f42a307cf\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed3a8ae0e47cf47bc38b875\",\"deadline\":1590972782}]'),(199,1,1590969220,_binary '[{\"id\":\"5eb6dbdfef3db53f42a307b7\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed39ac4ef3db522b1354a2a\",\"deadline\":1590969220}]'),(199,34,1590472758,_binary '[{\"id\":\"5eb6dbdfef3db53f42a307b7\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ecc0776ef3db522b134b915\",\"deadline\":1590472758}]'),(200,1,1589574265,_binary '[{\"id\":\"5eb6dc780e47cf684413d66a\",\"size\":\"1280x720\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ebe51b9ef3db561901478c1\",\"deadline\":1589574265}]'),(216,1,1590808510,_binary '[{\"id\":\"5eb7fba0ef3db5619013a753\",\"size\":\"1920x1080\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed126ff0e47cf47bc38ac27\",\"deadline\":1590808510}]'),(216,22,1589159204,_binary '[{\"id\":\"5eb7fba0ef3db5619013a753\",\"size\":\"1920x1080\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eb7fc640e47cf713dfc5397\",\"deadline\":1589159204}]'),(223,1,1590531346,_binary '[{\"id\":\"5ebd3d75ef3db561901466f4\",\"size\":\"608x1080\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5eccec520e47cf47bc388ce6\",\"deadline\":1590531346}]'),(223,22,1589499904,_binary '[{\"id\":\"5ebbee28ef3db53f42a33967\",\"size\":\"608x1080\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ebd2f40ef3db56190146595\",\"deadline\":1589499904}]'),(223,31,1590970925,_binary '[{\"id\":\"5ebd3d75ef3db561901466f4\",\"size\":\"608x1080\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ed3a16def3db522b1354ad9\",\"deadline\":1590970925}]'),(223,34,1590472775,_binary '[{\"id\":\"5ebd3d75ef3db561901466f4\",\"size\":\"608x1080\",\"content_type\":\"video\\/mp4\",\"url\":\"w87gm8aee9.a.trbcdn.net\\/temp\\/5ecc0787ef3db522b134b916\",\"deadline\":1590472775}]');
/*!40000 ALTER TABLE `media__content__user__access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__video`
--

DROP TABLE IF EXISTS `media__content__video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__video` (
  `id` bigint(19) unsigned NOT NULL,
  `common_name` varchar(1024) NOT NULL,
  `vertical` int(1) unsigned NOT NULL DEFAULT '0',
  `cdn_id` varchar(100) DEFAULT NULL,
  `cdn_id_content` varchar(100) DEFAULT NULL,
  `year` int(10) unsigned DEFAULT NULL,
  `default_poster` varchar(100) DEFAULT NULL,
  `default_frame` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__video__2__media__content` FOREIGN KEY (`id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__video`
--

LOCK TABLES `media__content__video` WRITE;
/*!40000 ALTER TABLE `media__content__video` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__video__strings__lang_en`
--

DROP TABLE IF EXISTS `media__content__video__strings__lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__video__strings__lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__video__strings_lang_en_2_media__content__video` FOREIGN KEY (`id`) REFERENCES `media__content__video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__video__strings__lang_en`
--

LOCK TABLES `media__content__video__strings__lang_en` WRITE;
/*!40000 ALTER TABLE `media__content__video__strings__lang_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__video__strings__lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content__video__strings__lang_ru`
--

DROP TABLE IF EXISTS `media__content__video__strings__lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content__video__strings__lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__content__video__strings_lang_ru_2_media__content__video` FOREIGN KEY (`id`) REFERENCES `media__content__video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content__video__strings__lang_ru`
--

LOCK TABLES `media__content__video__strings__lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content__video__strings__lang_ru` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content__video__strings__lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content_seasonseries_strings__lang_en`
--

DROP TABLE IF EXISTS `media__content_seasonseries_strings__lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content_seasonseries_strings__lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `mediacontentseriesstrings_lang_en_2_seasonseries` FOREIGN KEY (`id`) REFERENCES `media__content__season__series` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content_seasonseries_strings__lang_en`
--

LOCK TABLES `media__content_seasonseries_strings__lang_en` WRITE;
/*!40000 ALTER TABLE `media__content_seasonseries_strings__lang_en` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__content_seasonseries_strings__lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__content_seasonseries_strings__lang_ru`
--

DROP TABLE IF EXISTS `media__content_seasonseries_strings__lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__content_seasonseries_strings__lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `mediacontentseriesstrings_lang_ru_2_seasonseries` FOREIGN KEY (`id`) REFERENCES `media__content__season__series` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__content_seasonseries_strings__lang_ru`
--

LOCK TABLES `media__content_seasonseries_strings__lang_ru` WRITE;
/*!40000 ALTER TABLE `media__content_seasonseries_strings__lang_ru` DISABLE KEYS */;
INSERT INTO `media__content_seasonseries_strings__lang_ru` VALUES (54,'Первая серия',3,'',''),(55,'Вторая серия',3,'',''),(56,'Третья серия',3,'',''),(57,'Четвертая серия',3,'',''),(58,'Пятая серия',3,'',''),(59,'Шестая серия',3,'',''),(60,'Седьмая серия',3,'',''),(69,'Первая серия',3,'',''),(72,'Jenny',3,'',''),(74,'Peter',3,'',''),(75,'Lisa',3,'',''),(76,'Бетонная голова',3,'',''),(77,'Возвращение Лизы',3,'',''),(80,'Первая серия',3,'',''),(81,'Вторая серия',3,'',''),(82,'Третья серия',3,'',''),(83,'Четвертая серия',3,'',''),(84,'Вторая серия',3,'',''),(85,'Третья серия',3,'',''),(86,'Четвертая серия',3,'',''),(87,'Пятая серия',3,'',''),(88,'Шестая серия',3,'',''),(89,'Седьмая серия',3,'',''),(90,'Восьмая серия',3,'',''),(91,'Девятая серия',3,'',''),(133,'Первая серия',3,'',''),(134,'Вторая серия',3,'',''),(135,'Третья серия',3,'',''),(136,'Четвертая серия',3,'',''),(137,'Пятая серия',3,'',''),(138,'Шестая серия',3,'',''),(139,'Седьмая серия',3,'',''),(140,'Восьмая серия',3,'',''),(141,'Девятая серия',3,'',''),(142,'Десятая серия',3,'',''),(145,'Первая серия',3,'',''),(146,'Вторая серия',3,'',''),(147,'Третья серия',3,'',''),(148,'Четвертая серия',3,'',''),(149,'Пятая серия',3,'',''),(150,'Шестая серия',3,'',''),(176,'Серия',3,'','<p>ОПИСАНИЕ</p>'),(190,'1 серия',3,'',''),(191,'2 серия',3,'',''),(192,'3 серия',3,'',''),(193,'4 серия',3,'',''),(198,'Серия 1',3,'',''),(199,'Серия 2',3,'',''),(200,'Серия 3',3,'',''),(201,'Серия 4',3,'',''),(202,'Серия 5',3,'',''),(208,'2 серия',3,'',''),(209,'2 серия',3,'',''),(216,'Желание',1,'',''),(223,'Серия 1',3,'','');
/*!40000 ALTER TABLE `media__content_seasonseries_strings__lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__emoji`
--

DROP TABLE IF EXISTS `media__emoji`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__emoji` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `image_name` varchar(255) DEFAULT NULL,
  `image` mediumblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__emoji`
--

LOCK TABLES `media__emoji` WRITE;
/*!40000 ALTER TABLE `media__emoji` DISABLE KEYS */;
INSERT INTO `media__emoji` VALUES (12,'Страх',0,NULL,''),(13,'Сарказм',0,NULL,''),(14,'Смех',0,NULL,''),(15,'Любовь',0,NULL,''),(16,'Рыдание',0,NULL,''),(17,'Расстройство',0,NULL,'');
/*!40000 ALTER TABLE `media__emoji` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__emoji__strings`
--

DROP TABLE IF EXISTS `media__emoji__strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__emoji__strings` (
  `id` bigint(19) unsigned NOT NULL,
  `language_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  UNIQUE KEY `language_id` (`language_id`,`id`),
  CONSTRAINT `media__emoji__strings_2_language` FOREIGN KEY (`language_id`) REFERENCES `language__language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `media__emoji__strings_2_media_emoji` FOREIGN KEY (`id`) REFERENCES `media__emoji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__emoji__strings`
--

LOCK TABLES `media__emoji__strings` WRITE;
/*!40000 ALTER TABLE `media__emoji__strings` DISABLE KEYS */;
INSERT INTO `media__emoji__strings` VALUES (12,'en','Страх'),(12,'ru','Страх'),(13,'en','Сарказм'),(13,'ru','Сарказм'),(14,'en','Смех'),(14,'ru','Смех'),(15,'en','Любовь'),(15,'ru','Любовь'),(16,'en','Рыдание'),(16,'ru','Рыдание'),(17,'en','Расстройство'),(17,'ru','Расстройство');
/*!40000 ALTER TABLE `media__emoji__strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__lent`
--

DROP TABLE IF EXISTS `media__lent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__lent` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`id`,`content_id`),
  UNIQUE KEY `content_id` (`content_id`,`id`),
  CONSTRAINT `media__lent__2_media__content` FOREIGN KEY (`content_id`) REFERENCES `media__content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COMMENT='адреснохронологическая связка';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__lent`
--

LOCK TABLES `media__lent` WRITE;
/*!40000 ALTER TABLE `media__lent` DISABLE KEYS */;
INSERT INTO `media__lent` VALUES (70,109),(72,110),(73,111),(74,112),(75,113),(76,114),(77,115),(78,116),(79,117),(80,78),(81,70),(82,66),(83,51),(97,118),(102,119),(103,120),(115,151),(119,157),(120,158),(121,143),(122,131),(123,159),(124,160),(125,161),(133,155),(142,181),(143,182),(144,183),(151,195);
/*!40000 ALTER TABLE `media__lent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__studio`
--

DROP TABLE IF EXISTS `media__studio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__studio` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `common_name` varchar(255) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__studio`
--

LOCK TABLES `media__studio` WRITE;
/*!40000 ALTER TABLE `media__studio` DISABLE KEYS */;
INSERT INTO `media__studio` VALUES (2,'test','d7e0e610921221ab5881abc790d82744'),(10,'Красныйковрик','d48c620d9cbf11e2a0870abe16aeb22b'),(13,'CINNAMON PRODUCTION',NULL),(16,'Jung-Film',NULL),(18,'Film & Maker LTDA',NULL),(21,'Stonestreet  Production',NULL),(22,'More Sauce',NULL);
/*!40000 ALTER TABLE `media__studio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__studio__properties`
--

DROP TABLE IF EXISTS `media__studio__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__studio__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `media__studio__properties_2_media__studio` FOREIGN KEY (`id`) REFERENCES `media__studio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__studio__properties`
--

LOCK TABLES `media__studio__properties` WRITE;
/*!40000 ALTER TABLE `media__studio__properties` DISABLE KEYS */;
INSERT INTO `media__studio__properties` VALUES (2,'a','aaa',0),(2,'b','bbb',0);
/*!40000 ALTER TABLE `media__studio__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__studio__strings__lang_en`
--

DROP TABLE IF EXISTS `media__studio__strings__lang_en`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__studio__strings__lang_en` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__studio__strings_lang_en_2_media__studio` FOREIGN KEY (`id`) REFERENCES `media__studio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__studio__strings__lang_en`
--

LOCK TABLES `media__studio__strings__lang_en` WRITE;
/*!40000 ALTER TABLE `media__studio__strings__lang_en` DISABLE KEYS */;
INSERT INTO `media__studio__strings__lang_en` VALUES (2,'super dupper testt',3,'<p><span style=\"color: #2fcc71;\">TA<strong>DAM</strong>TS!</span></p>','<p><span style=\"color: #3598db;\">PARA<span style=\"background-color: #e74c3c;\"><strong>BAB</strong></span>AMTS</span></p>');
/*!40000 ALTER TABLE `media__studio__strings__lang_en` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__studio__strings__lang_ru`
--

DROP TABLE IF EXISTS `media__studio__strings__lang_ru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__studio__strings__lang_ru` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `html_mode` int(10) unsigned NOT NULL DEFAULT '2',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `media__studio__strings_lang_ru_2_media__studio` FOREIGN KEY (`id`) REFERENCES `media__studio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__studio__strings__lang_ru`
--

LOCK TABLES `media__studio__strings__lang_ru` WRITE;
/*!40000 ALTER TABLE `media__studio__strings__lang_ru` DISABLE KEYS */;
INSERT INTO `media__studio__strings__lang_ru` VALUES (2,'тест',3,'<p><span style=\"color: #2fcc71;\">Т<span style=\"background-color: #c03030;\"><strong>адам</strong></span>ц</span></p>','<p><span style=\"color: #3598db;\">Пара<span style=\"background-color: #e67e23;\">бам</span>ц</span></p>'),(10,'RedCarpet',0,'Студия создана в 2001 году','Студия создана в 2001 году'),(13,'CINNAMON PRODUCTION',0,'Cinnamon Production promotes cinematographic, audiovisual and multimedia production through research, innovation and experimentation of new techniques and styles. In addition, the group aims to develop and implement initiatives and events of cultural and artistic interest, encouraging the production of original and quality works. These activities are carried out with the aim of bringing out and enhancing the cultural, artistic, anthropological and landscape heritage of the territories.','Supportare, promuovere e valorizzare la produzione cinematografica e audiovisiva, anche attraverso la formazione di nuovi talenti.'),(16,'Jung-Film',0,'Jung-Film specializes in corporate short movies for websites, campaigns, interactivity. We offer concept, production, publishing. We marry our knowledge of filmmaking to modern needs of online communication.','Jung-Film specializes in corporate short movies for websites, campaigns, interactivity. We offer concept, production, publishing. We marry our knowledge of filmmaking to modern needs of online communication.'),(18,'Film & Maker LTDA',3,'<p>Film &amp; Maker LTDA</p>','<p>Film &amp; Maker LTDA</p>'),(21,'Stonestreet  Production',1,'STONESTREET STUDIOS is celebrating 28 years as a New York City based fully operative, multi-purpose, visual motion picture production studio, founded by Alyssa Rallo Bennett and Gary O. Bennett, director/writer/producer team. Stonestreet’s work ranges from award-winning socially speculative feature films such as ReRUN (Christopher Lloyd), Rain Without Thunder (Jeff Daniels, Linda Hunt, Steve Zahn) andThe Pack aka Smoking Non-Smoking (Luci Arnaz, Elizabeth Moss), to pilots, web series and Micro-Movies released internationally and on Stonestreet’s growing platforms.','<p>Stonestreet has become known as an incubator of socially conscious, character driven and culturally provocative millennial and generation Y content, and talent such as <strong>Miles Teller</strong> (<em>Whiplash</em>,&nbsp;<em>Divergent</em>), <strong>Rachel Brosnahan</strong> (The Marvelous Mrs. Maisel, House of Cards),&nbsp;<strong>Camila Mendes </strong>(<em>Riverdale</em>), <strong>Beanie Feldstein</strong> (<em>Booksmart</em>), <strong>Xosha Roquemore</strong> (The <em>Mindy Project</em>), <strong>Francesca Reale</strong> (<em>Stranger Things</em>), <strong>Idina Menzel</strong> (Frozen) and more.</p>'),(22,'More Sauce',0,'More Sauce','More Sauce');
/*!40000 ALTER TABLE `media__studio__strings__lang_ru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_new_request`
--

DROP TABLE IF EXISTS `media_new_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_new_request` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `link` varchar(1024) NOT NULL,
  `ss_qty` varchar(255) NOT NULL,
  `series_length` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `producer` varchar(255) NOT NULL,
  `actor` varchar(512) NOT NULL,
  `trailer` varchar(1024) DEFAULT NULL,
  `facebook` varchar(512) DEFAULT NULL,
  `vk` varchar(512) DEFAULT NULL,
  `instagramm` varchar(512) DEFAULT NULL,
  `youtube` varchar(512) DEFAULT NULL,
  `annotation` mediumtext NOT NULL,
  `festival` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_new_request`
--

LOCK TABLES `media_new_request` WRITE;
/*!40000 ALTER TABLE `media_new_request` DISABLE KEYS */;
INSERT INTO `media_new_request` VALUES (2,'kolbasyan.vasyan@kremlin.gov','Васян Колбасян','Хуета хует','About fucked penis',2020,'https://kremlin.gov/porno_s_liliputinym.avi','100 серий 1 сезон','30 сек','Васян колбасян','Лилипутин','Лилипутин, и куча других пидарасов','https://kremlin.gov/porno_s_liliputinym_trailer.avi','фейспук','вк','инст','ютюб','Der grossel keik das kemska volost','Der festivalen in kemska volost');
/*!40000 ALTER TABLE `media_new_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `css_class` varchar(100) DEFAULT NULL,
  `description` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu__items`
--

DROP TABLE IF EXISTS `menu__items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu__items` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(19) unsigned NOT NULL,
  `parent_id` bigint(19) unsigned DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `name` varchar(1024) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `visible` int(1) unsigned NOT NULL DEFAULT '1',
  `css_class` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`menu_id`) USING BTREE,
  UNIQUE KEY `menu__items_rpm` (`menu_id`,`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `menu_items_2_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu__items`
--

LOCK TABLES `menu__items` WRITE;
/*!40000 ALTER TABLE `menu__items` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu__items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presets`
--

DROP TABLE IF EXISTS `presets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `presets` (
  `name` varchar(512) NOT NULL,
  `value` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presets`
--

LOCK TABLES `presets` WRITE;
/*!40000 ALTER TABLE `presets` DISABLE KEYS */;
INSERT INTO `presets` VALUES ('DADATA_KEY','e7774efcac8cab18b2ae86e69c4afce09c0471dd'),('FACEBOOK_ID','2229850933730516'),('FACEBOOK_SECRET','4936e302c153476788ccf80b8f0448ca'),('MAILER_DEFAULT_TO','eve@ironstar.pw'),('MAILER_FROM','admin@ironstar.pw'),('MAILER_FROM_NAME','Chill'),('MAILER_SMTP_HOST','ironstar.pw'),('MAILER_SMTP_PASSWORD','crysolite'),('MAILER_SMTP_PORT','25'),('MAILER_SMTP_USER','eve@ironstar.pw'),('MAIL_ABOUT_REQUEST','megafrog@yandex.ru'),('MAP_BOX_KEY','pk.eyJ1IjoiZTJpcm9uc3RhcnB3IiwiYSI6ImNqeXU1ajZkMDBhZm8zbXByOGpsdHc0eXkifQ.12dS-m-kND1_lHA6f10c0A'),('meta_og_locale','ru_RU'),('NOTIFICATION_EMAIL','eve@ironstar.pw'),('page_default_description','web-сериалы'),('page_default_keywords','chill'),('page_default_title','Chill'),('page_title_separator',' - '),('PAYPORT_OUTLET','31'),('PAYPORT_TOKEN','10a2e631cf26c997c7506238d614ca36f532999bd9ea5f654d38c027534995bc'),('TWITTER_KEY',''),('TWITTER_SECRET',''),('YMAP_API_KEY','');
/*!40000 ALTER TABLE `presets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `protected__gallery`
--

DROP TABLE IF EXISTS `protected__gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `protected__gallery` (
  `uid` varchar(64) NOT NULL,
  `owner_id` bigint(19) unsigned NOT NULL,
  `title` varchar(512) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `info` mediumblob NOT NULL,
  PRIMARY KEY (`uid`,`owner_id`),
  UNIQUE KEY `uid` (`uid`,`owner_id`),
  KEY `title` (`title`),
  KEY `sort` (`sort`),
  KEY `owner_id` (`owner_id`,`sort`),
  CONSTRAINT `protected__gallery_2_owner` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `protected__gallery`
--

LOCK TABLES `protected__gallery` WRITE;
/*!40000 ALTER TABLE `protected__gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `protected__gallery` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery_ai` AFTER INSERT ON `protected__gallery`
 FOR EACH ROW BEGIN
  INSERT INTO protected__gallery__dates(uid,owner_id,created,updated)
  VALUES(NEW.uid,NEW.owner_id,NOW(),NOW())
  ON DUPLICATE KEY UPDATE updated=VALUES(updated);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery_au` AFTER UPDATE ON `protected__gallery`
 FOR EACH ROW BEGIN
  INSERT INTO protected__gallery__dates(uid,owner_id,created,updated)
  VALUES(NEW.uid,NEW.owner_id,NOW(),NOW())
  ON DUPLICATE KEY UPDATE updated=VALUES(updated);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `protected__gallery__counter`
--

DROP TABLE IF EXISTS `protected__gallery__counter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `protected__gallery__counter` (
  `uid` varchar(64) NOT NULL,
  `owner_id` bigint(19) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`owner_id`),
  CONSTRAINT `protected__gallery_counter` FOREIGN KEY (`uid`, `owner_id`) REFERENCES `protected__gallery` (`uid`, `owner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `protected__gallery__counter`
--

LOCK TABLES `protected__gallery__counter` WRITE;
/*!40000 ALTER TABLE `protected__gallery__counter` DISABLE KEYS */;
/*!40000 ALTER TABLE `protected__gallery__counter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `protected__gallery__dates`
--

DROP TABLE IF EXISTS `protected__gallery__dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `protected__gallery__dates` (
  `uid` varchar(64) NOT NULL,
  `owner_id` bigint(19) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`uid`,`owner_id`),
  CONSTRAINT `protected_gallery_dates_2_protected_gallery` FOREIGN KEY (`uid`, `owner_id`) REFERENCES `protected__gallery` (`uid`, `owner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `protected__gallery__dates`
--

LOCK TABLES `protected__gallery__dates` WRITE;
/*!40000 ALTER TABLE `protected__gallery__dates` DISABLE KEYS */;
/*!40000 ALTER TABLE `protected__gallery__dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `protected__gallery__item`
--

DROP TABLE IF EXISTS `protected__gallery__item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `protected__gallery__item` (
  `uid` varchar(64) NOT NULL DEFAULT '',
  `gallery_uid` varchar(64) NOT NULL,
  `owner_id` bigint(19) unsigned NOT NULL,
  `title` varchar(256) NOT NULL,
  `type` varchar(50) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `aspect` double NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `updated` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `version` int(11) NOT NULL DEFAULT '0',
  `info` mediumblob NOT NULL,
  `preset` mediumblob NOT NULL,
  PRIMARY KEY (`uid`,`gallery_uid`,`owner_id`),
  UNIQUE KEY `gallery_uid` (`gallery_uid`,`owner_id`,`uid`) USING BTREE,
  KEY `title` (`title`),
  KEY `created` (`created`),
  KEY `updated` (`updated`),
  CONSTRAINT `protected__gallery__item_protected__gallery` FOREIGN KEY (`gallery_uid`, `owner_id`) REFERENCES `protected__gallery` (`uid`, `owner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `protected__gallery__item`
--

LOCK TABLES `protected__gallery__item` WRITE;
/*!40000 ALTER TABLE `protected__gallery__item` DISABLE KEYS */;
/*!40000 ALTER TABLE `protected__gallery__item` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery__item_bi` BEFORE INSERT ON `protected__gallery__item`
 FOR EACH ROW BEGIN
  SET NEW.created = NOW();
  SET NEW.updated = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery__item_ai` AFTER INSERT ON `protected__gallery__item`
 FOR EACH ROW BEGIN
  CALL UPDATE_PROTECTED_GALLERY_COUNTER(NEW.gallery_uid,NEW.owner_id);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery__item_bu` BEFORE UPDATE ON `protected__gallery__item`
 FOR EACH ROW BEGIN
  SET NEW.updated = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery__item_au` AFTER UPDATE ON `protected__gallery__item`
 FOR EACH ROW BEGIN
  IF NEW.gallery_uid != OLD.gallery_uid THEN 
     CALL UPDATE_PROTECTED_GALLERY_COUNTER(OLD.gallery_uid,OLD.owner_id);
     CALL UPDATE_PROTECTED_GALLERY_COUNTER(NEW.gallery_uid,NEW.owner_id);
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `protected__gallery__item_ad` AFTER DELETE ON `protected__gallery__item`
 FOR EACH ROW BEGIN
     CALL UPDATE_PROTECTED_GALLERY_COUNTER(OLD.gallery_uid,OLD.owner_id);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `public__gallery`
--

DROP TABLE IF EXISTS `public__gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(19) unsigned NOT NULL,
  `name` varchar(1024) NOT NULL,
  `visible` int(1) unsigned NOT NULL DEFAULT '1',
  `cover_aspect` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`owner_id`),
  UNIQUE KEY `uid` (`owner_id`,`id`),
  KEY `visible` (`visible`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery`
--

LOCK TABLES `public__gallery` WRITE;
/*!40000 ALTER TABLE `public__gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__gallery__counter`
--

DROP TABLE IF EXISTS `public__gallery__counter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__counter` (
  `id` bigint(19) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `pubgallerycounter_2_gallery` FOREIGN KEY (`id`) REFERENCES `public__gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__counter`
--

LOCK TABLES `public__gallery__counter` WRITE;
/*!40000 ALTER TABLE `public__gallery__counter` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__counter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__gallery__item`
--

DROP TABLE IF EXISTS `public__gallery__item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__item` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` bigint(19) unsigned NOT NULL,
  `title` varchar(256) NOT NULL,
  `active` int(1) unsigned NOT NULL DEFAULT '1',
  `type` varchar(50) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `aspect` double NOT NULL DEFAULT '0',
  `preview_aspect` double NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `info` mediumblob NOT NULL,
  PRIMARY KEY (`id`,`gallery_id`) USING BTREE,
  UNIQUE KEY `primary_rpm` (`gallery_id`,`id`) USING BTREE,
  KEY `title` (`title`),
  KEY `active` (`active`,`id`),
  KEY `created` (`created`,`gallery_id`,`id`) USING BTREE,
  CONSTRAINT `public__gallery__item_2_public__gallery` FOREIGN KEY (`gallery_id`) REFERENCES `public__gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__item`
--

LOCK TABLES `public__gallery__item` WRITE;
/*!40000 ALTER TABLE `public__gallery__item` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__item` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `public__gallery__item__bi` BEFORE INSERT ON `public__gallery__item`
 FOR EACH ROW BEGIN
SET NEW.created = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `public__gallery__item__bu` BEFORE UPDATE ON `public__gallery__item`
 FOR EACH ROW BEGIN
SET NEW.created = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `public__gallery__item__tag`
--

DROP TABLE IF EXISTS `public__gallery__item__tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__item__tag` (
  `item_id` bigint(19) unsigned NOT NULL,
  `tag_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`tag_id`),
  UNIQUE KEY `tag_id` (`tag_id`,`item_id`),
  CONSTRAINT `public__gallery__item__tag_2_item` FOREIGN KEY (`item_id`) REFERENCES `public__gallery__item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `public__gallery__item__tag_2_tag` FOREIGN KEY (`tag_id`) REFERENCES `public__tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__item__tag`
--

LOCK TABLES `public__gallery__item__tag` WRITE;
/*!40000 ALTER TABLE `public__gallery__item__tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__item__tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__gallery__item__tag__result`
--

DROP TABLE IF EXISTS `public__gallery__item__tag__result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__item__tag__result` (
  `item_id` bigint(19) unsigned NOT NULL,
  `tag_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`item_id`,`tag_id`),
  UNIQUE KEY `public__gallery__item__tag__result__2__tag` (`tag_id`,`item_id`) USING BTREE,
  CONSTRAINT `public__gallery__item__tag__result__2__tag` FOREIGN KEY (`tag_id`) REFERENCES `public__tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__item__tag__result`
--

LOCK TABLES `public__gallery__item__tag__result` WRITE;
/*!40000 ALTER TABLE `public__gallery__item__tag__result` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__item__tag__result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__gallery__tag`
--

DROP TABLE IF EXISTS `public__gallery__tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__tag` (
  `gallery_id` bigint(19) unsigned NOT NULL,
  `tag_id` bigint(19) unsigned NOT NULL,
  PRIMARY KEY (`gallery_id`,`tag_id`),
  UNIQUE KEY `tag_id` (`tag_id`,`gallery_id`),
  CONSTRAINT `pubgallerytag__2__gallery` FOREIGN KEY (`gallery_id`) REFERENCES `public__gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pubgallerytag__2__tag` FOREIGN KEY (`tag_id`) REFERENCES `public__tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__tag`
--

LOCK TABLES `public__gallery__tag` WRITE;
/*!40000 ALTER TABLE `public__gallery__tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__gallery__text`
--

DROP TABLE IF EXISTS `public__gallery__text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__text` (
  `id` bigint(19) unsigned NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `pubgallery_text_2_gallery` FOREIGN KEY (`id`) REFERENCES `public__gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__text`
--

LOCK TABLES `public__gallery__text` WRITE;
/*!40000 ALTER TABLE `public__gallery__text` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__gallery__up`
--

DROP TABLE IF EXISTS `public__gallery__up`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__gallery__up` (
  `id` bigint(19) unsigned NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `updated` (`updated`),
  CONSTRAINT `pubgalleryup_2_pubgallery` FOREIGN KEY (`id`) REFERENCES `public__gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__gallery__up`
--

LOCK TABLES `public__gallery__up` WRITE;
/*!40000 ALTER TABLE `public__gallery__up` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__gallery__up` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__tag`
--

DROP TABLE IF EXISTS `public__tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__tag` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`tag`) USING BTREE,
  UNIQUE KEY `tag` (`tag`,`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__tag`
--

LOCK TABLES `public__tag` WRITE;
/*!40000 ALTER TABLE `public__tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `public__user_fav_tags`
--

DROP TABLE IF EXISTS `public__user_fav_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `public__user_fav_tags` (
  `user_id` bigint(19) unsigned NOT NULL,
  `tag_id` bigint(19) unsigned NOT NULL,
  `weight` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`tag_id`),
  KEY `weight` (`weight`),
  KEY `user_fav_tags_2_tag` (`tag_id`),
  CONSTRAINT `user_fav_tags_2_tag` FOREIGN KEY (`tag_id`) REFERENCES `public__tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_fav_tags_2_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public__user_fav_tags`
--

LOCK TABLES `public__user_fav_tags` WRITE;
/*!40000 ALTER TABLE `public__user_fav_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `public__user_fav_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `redirects`
--

DROP TABLE IF EXISTS `redirects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `redirects` (
  `source` varchar(100) NOT NULL,
  `target` varchar(250) NOT NULL,
  PRIMARY KEY (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `redirects`
--

LOCK TABLES `redirects` WRITE;
/*!40000 ALTER TABLE `redirects` DISABLE KEYS */;
/*!40000 ALTER TABLE `redirects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `finished` int(1) NOT NULL DEFAULT '0',
  `company_name` varchar(512) NOT NULL,
  `profile_id` bigint(19) unsigned DEFAULT NULL,
  `profile_name` varchar(255) NOT NULL,
  `company_address` varchar(1024) NOT NULL,
  `position_name` varchar(1024) NOT NULL,
  `position_cost` double NOT NULL DEFAULT '0',
  `nds_pc` double NOT NULL DEFAULT '0',
  `nds_eur` double NOT NULL DEFAULT '0',
  `status_name` varchar(1024) NOT NULL,
  `status_color` varchar(1024) NOT NULL,
  `status_id` bigint(19) unsigned DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `telegramm` int(1) unsigned NOT NULL DEFAULT '1',
  `whatsapp` int(1) unsigned NOT NULL DEFAULT '1',
  `viber` int(1) unsigned NOT NULL DEFAULT '1',
  `requisites` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `by_user` (`user_id`,`id`),
  KEY `by_status` (`status_id`,`id`),
  KEY `by_profile` (`profile_id`,`id`),
  KEY `finished` (`finished`),
  CONSTRAINT `request_2_profile` FOREIGN KEY (`profile_id`) REFERENCES `request__profile` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `request_2_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `request__2__status` FOREIGN KEY (`status_id`) REFERENCES `request__status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES (7,23,'2020-02-08 10:36:39',1,'Тестовая',1,'Строительство','Тестовая улица','Заливка фундамента',200,0,0,'Завершен','#eb0000',4,'+8 (926) 835 12 31',0,1,1,'Тестовые реквизиты'),(8,18,'2020-02-08 12:03:44',0,'company_name',1,'Строительство','company_address','producttio_name',33.33,0,0,'Новый','#00ed00',2,'+7 (000) 000 00 00',0,1,0,'company_rq'),(9,18,'2020-02-08 12:17:32',0,'company_name',4,'Оптовая торговля','sxssxs','cdcdcdcdcd',3333,0,0,'Новый','#00ed00',2,'+7 (000) 000 00 00',0,1,1,'cdcdcdcdc'),(11,18,'2020-02-08 15:10:31',0,'dd',3,'IT','dd','dd',33,0,0,'Отправлен','#ffc200',6,'+7 (000) 000 00 00',1,0,0,'dd'),(15,1,'2020-03-06 21:28:40',0,'ddd',3,'IT','ddd','dfdfd',100,0,0,'Новый','#00ed00',2,'+7 (000) 000 00 00',1,0,0,'ddd'),(16,1,'2020-03-07 12:41:27',0,'eee',3,'IT','bb cc dd','iii',2000,3,300,'В работе','#0000ec',3,'+7 (000) 000 00 00',0,1,0,'aaabbbccc');
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request__profile`
--

DROP TABLE IF EXISTS `request__profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request__profile` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL,
  `color` varchar(50) NOT NULL DEFAULT '#000000',
  `sort` int(11) NOT NULL DEFAULT '0',
  `enabled` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request__profile`
--

LOCK TABLES `request__profile` WRITE;
/*!40000 ALTER TABLE `request__profile` DISABLE KEYS */;
INSERT INTO `request__profile` VALUES (1,'Строительство','#0000d1',-8,1),(2,'Транспорт','#000000',0,0),(3,'IT','#f7f026',0,1),(4,'Оптовая торговля','#000000',0,1),(5,'Услуги','#000000',0,0);
/*!40000 ALTER TABLE `request__profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request__status`
--

DROP TABLE IF EXISTS `request__status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request__status` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `color` varchar(50) NOT NULL DEFAULT '#000000',
  `final` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request__status`
--

LOCK TABLES `request__status` WRITE;
/*!40000 ALTER TABLE `request__status` DISABLE KEYS */;
INSERT INTO `request__status` VALUES (2,'Новый',-1,'#00ed00',0),(3,'В работе',10,'#0000ec',0),(4,'Завершен',1000,'#eb0000',1),(6,'Отправлен',24,'#ffc200',0);
/*!40000 ALTER TABLE `request__status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ribbon`
--

DROP TABLE IF EXISTS `ribbon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ribbon` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(50) NOT NULL DEFAULT '*',
  `active` int(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(512) NOT NULL,
  `published` datetime DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `link_type` varchar(50) DEFAULT NULL,
  `link_id` bigint(19) unsigned DEFAULT NULL,
  `link_uid` varchar(100) DEFAULT NULL,
  `intro_length` int(11) NOT NULL DEFAULT '0',
  `info_length` int(11) NOT NULL DEFAULT '0',
  `html_mode` int(1) unsigned NOT NULL DEFAULT '1',
  `html_mode_c` int(1) unsigned NOT NULL DEFAULT '1',
  `intro` mediumtext NOT NULL,
  `info` mediumtext NOT NULL,
  PRIMARY KEY (`id`,`target`) USING BTREE,
  UNIQUE KEY `ribbon__rpm` (`target`,`id`),
  KEY `active` (`active`,`id`),
  KEY `link_id` (`link_id`,`id`),
  KEY `link_uid` (`link_uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ribbon`
--

LOCK TABLES `ribbon` WRITE;
/*!40000 ALTER TABLE `ribbon` DISABLE KEYS */;
/*!40000 ALTER TABLE `ribbon` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `ribbon__bi` BEFORE INSERT ON `ribbon`
 FOR EACH ROW BEGIN
  IF NEW.active=1 AND NEW.published IS NULL THEN 
  SET NEW.published = NOW();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `ribbon__bu` BEFORE UPDATE ON `ribbon`
 FOR EACH ROW BEGIN
  IF NEW.active=1 AND NEW.published IS NULL THEN 
  SET NEW.published = NOW();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `title` varchar(1024) NOT NULL DEFAULT '',
  `layout` varchar(255) NOT NULL DEFAULT 'default',
  `timeout` int(11) NOT NULL DEFAULT '5000',
  `crop` int(1) unsigned NOT NULL DEFAULT '1',
  `crop_fill` int(1) unsigned NOT NULL DEFAULT '0',
  `background` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--

LOCK TABLES `slider` WRITE;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider__properties`
--

DROP TABLE IF EXISTS `slider__properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider__properties` (
  `id` bigint(19) unsigned NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_value` varchar(1024) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`property_name`),
  KEY `sort` (`sort`),
  CONSTRAINT `slider_props_2_slider` FOREIGN KEY (`id`) REFERENCES `slider` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider__properties`
--

LOCK TABLES `slider__properties` WRITE;
/*!40000 ALTER TABLE `slider__properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider__properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage`
--

DROP TABLE IF EXISTS `storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `visible` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  KEY `visible` (`visible`),
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='список доступных складов';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage`
--

LOCK TABLES `storage` WRITE;
/*!40000 ALTER TABLE `storage` DISABLE KEYS */;
/*!40000 ALTER TABLE `storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage__contents`
--

DROP TABLE IF EXISTS `storage__contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage__contents` (
  `hash` varchar(255) NOT NULL,
  `storage_id` bigint(19) unsigned NOT NULL,
  `product_id` bigint(19) unsigned NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  `size` bigint(19) unsigned DEFAULT NULL,
  `qty` int(11) unsigned NOT NULL,
  PRIMARY KEY (`hash`,`storage_id`) USING BTREE,
  UNIQUE KEY `storage_contents_rpm` (`storage_id`,`hash`),
  KEY `storage_id` (`storage_id`),
  KEY `product_id` (`product_id`),
  KEY `qty` (`qty`),
  KEY `storage__contents__color` (`color`) USING BTREE,
  KEY `storage__contents_size` (`size`),
  CONSTRAINT `storage__contents_2_product` FOREIGN KEY (`product_id`) REFERENCES `catalog__product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `storage__contents_2_size` FOREIGN KEY (`size`) REFERENCES `catalog__size__def` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `storage__contents_2_storage` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='табло наличия';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage__contents`
--

LOCK TABLES `storage__contents` WRITE;
/*!40000 ALTER TABLE `storage__contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `storage__contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage__flags`
--

DROP TABLE IF EXISTS `storage__flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage__flags` (
  `pkey` varchar(100) NOT NULL,
  `storage_id` bigint(19) unsigned DEFAULT NULL,
  PRIMARY KEY (`pkey`),
  KEY `storage_id` (`storage_id`),
  CONSTRAINT `storage__flags_2_stora` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='особые отметки складов';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage__flags`
--

LOCK TABLES `storage__flags` WRITE;
/*!40000 ALTER TABLE `storage__flags` DISABLE KEYS */;
/*!40000 ALTER TABLE `storage__flags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage__offline__shop`
--

DROP TABLE IF EXISTS `storage__offline__shop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage__offline__shop` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(1024) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `phone_alter` varchar(50) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `visible` int(1) unsigned NOT NULL DEFAULT '1',
  `storage_id` bigint(19) unsigned DEFAULT NULL,
  `works` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `address` (`address`),
  KEY `storage_id` (`storage_id`,`id`),
  KEY `visible` (`visible`,`id`),
  CONSTRAINT `storage__offline_shop_2_storage` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage__offline__shop`
--

LOCK TABLES `storage__offline__shop` WRITE;
/*!40000 ALTER TABLE `storage__offline__shop` DISABLE KEYS */;
/*!40000 ALTER TABLE `storage__offline__shop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage__partners`
--

DROP TABLE IF EXISTS `storage__partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storage__partners` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(512) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `phone_alter` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `enabled` int(1) unsigned NOT NULL DEFAULT '1',
  `works` varchar(512) DEFAULT NULL,
  `town` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`),
  KEY `town` (`town`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage__partners`
--

LOCK TABLES `storage__partners` WRITE;
/*!40000 ALTER TABLE `storage__partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `storage__partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(64) NOT NULL,
  `login` varchar(100) NOT NULL,
  `phone_strip` varchar(25) DEFAULT NULL,
  `pass` varchar(1024) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `is_approved` int(1) unsigned NOT NULL DEFAULT '0',
  `news` int(1) unsigned NOT NULL DEFAULT '1',
  `locked` int(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `birth_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `user_by_phonestrip` (`phone_strip`),
  KEY `news` (`news`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'','sycoraxa@gmail.com','70000000000','172616:0d1ab40fc2bbce601b5c8f90603b6f77dc2138d358919bd7259b98abb1777496f3b8bdfd5968b7c996af3fa6f9bfeeb28c048935b0faeed0d25edeabd948775472712eada0a15f7c859f00a3a799c20213d6e2456e013366dc37a3f58cda6561c0ba0c33882e0fcaa28ae828095c1cbaf2822666e7316ca29bb7f42201f1f127','admin',1,0,0,'2001-01-01 00:00:00','1970-04-14 00:00:00'),(18,'2ddd9f75-4a1a-11ea-82f6-001e5826d92c','e6@ironstar.pw','00000000000','30395:67f77c189829da520c6657c195cab0f39246bbdfe4bd4588bd9f1fd148b9e13c86079aa101f0574f2868065cf7efef0dfc271afdd6bf0c2c1e5ed2c0382cc685ec00e9db6f4e04e81b635dfa14f387b8371acd5a3a1ccae0c55c28005b057331f342fafa6e0d099caea443eacfd9d0f2bf687b014b67d35b11bcef2274b3967b','client',0,0,0,'2020-02-08 05:24:48','2001-01-01 00:00:00'),(22,'5ae9832a-4a45-11ea-82f6-001e5826d92c','test@test.test','79000000000','38491:2a307038f0f4df9e6cc3d299cced7a4f80f9e10a18c06d0901fa8d8eedc8e4252ac040638913870c4bf8cfca7488c1b0c9ab2107bb3849a592b40c9cce7f4f3913a33726a4ee147292feddf3834177c06edab98f86dd5656674c7e11e021061d1b2752773a807a810ae3efb64d65c1cd393ef2f90663d5602805cb461223e33b','admin',1,1,0,'2020-02-08 10:33:52','2001-01-01 00:00:00'),(23,'a145de0e-4a45-11ea-82f6-001e5826d92c','pokaccio@gmail.com',NULL,'149275:1984891491d04ef53c8db04efa8e6ce569c0cea382a046bdb75b674483efd936a63133b0b0ffbae14d59b27ba8476ade46486ce4402ad89bd57807771ca5c6737342c0733c0b9851268699ebc9b28e9a647a9f835ed8576423234d25536b6980086d095351b3d0e6337068dc3ea62294a04752a652473b30627910049cda5ef0','client',0,0,0,'2020-02-08 10:35:50','2001-01-01 00:00:00'),(27,'f1417678-7ca9-11ea-82f6-001e5826d92c','abc@d.e','990000000000','166399:37a5823ee28e1382c7beac51079cf4477d941b4c258602856c9a40341f117b2bed7a5f7233f1781e51792470f9772de4d62090b8fd53c41a5111a0dd416f434b0c4f22b27c3db394dc3ce1873e13c7ca62d90f5ca99d284b847d804cdca41b00192343d47effe8f193cf252061a5f37748bf188dd55c8200ff49eb5de049778d','client',1,1,0,'2020-04-12 13:39:52','2020-04-12 00:00:00'),(30,'9046bde9-8272-11ea-82f6-001e5826d92c','sycoraxa_gfgfgf@gmail.com',NULL,'43061:4824acc3e663bb15f840e36d39d2728e99da0563931951cc20b1601f327ffb9416db32591cc44312e73d0d467d3d24b7a51524463b1cdbf13f8b0bb21d8099bb99d58578750f8b13871deb8fc27162c8c3989c43993da0450662bd49802a3eaa9fd335e7cc475d44d6d7b36dba5e842717fee42b1ee9e3a90f2cc8a27ba3d2bb','client',0,1,0,'2020-04-19 22:18:34','2020-04-08 00:00:00'),(31,'5d5e8d6b-82f7-11ea-82f6-001e5826d92c','a@coffok.ru','79268351231','104211:32dc348db106031442780210569a0c6ae52ea89643fdd2729cbf756c8b9101cb3939fd450c96beb81971a6567fe1922b343becc9c70d6e175487ae3c9e79c41e3d761d05637ce57ac0aa7dbb2ec36e55505bc1d7ed304fe8f5afd7b9a94b3566cf6aea5ae82870bc4381144b50e97de06e79999df53e6b7d8c03cf810e5dc4f0','client',1,1,0,'2020-04-20 14:09:12','1989-03-14 00:00:00'),(32,'5c9ddab0-863d-11ea-82f6-001e5826d92c','vladmus@gmail.com','79250033003','45426:174aeada078b82b03c830ce4fafb22dbe357a4f88b4a9317363dc5e7013fdef3a4d48fe9bc35fcb6cdb988e5f61aa85b371d6a60b017ece1d3fe676ccfef9e22beb9ab9da38382049cb5e21e8d576655107edcdc87f9d30d1868f380c9379091397dd78f31e9c06a72efa6b385cb4e779ceb409d3faf767be2d62a19ee8dad6e','client',0,1,0,'2020-04-24 18:07:49','1978-05-09 00:00:00'),(33,'bcd105c5-8749-11ea-82f6-001e5826d92c','chillvisionru@gmail.com',NULL,'174678:1d3c257b1e9ee3ce039c4648ceab3eea812a41b0b90eb689f561cfd6e44e855ffb76be79de0247d9f5718ab7aa5e83c5fddadad15387d03dde1b48726c99f7e7f24757730727449ed8fdc807fa25084763ee3b17510bd6354ede8944f44d28422953dafac18fbf48d8bf253cbe62f597c2e24a6f449baa70784926276b2e4f3f','client',0,1,0,'2020-04-26 02:08:55','2001-01-01 00:00:00'),(34,'2e2e06d0-9eb1-11ea-afec-001e5826d92c','anton@chillvision.ru','71234567890','166497:ad910a7956bdacfe988e66b9fd2b6a130553fc35e6af4d8ee02f0f01d927b4fd676079b63702d86fe8e7c8648f6d8ff3226abd645c635522273d09a10c6c7193d0b9d48d78ed049845ce40d41cdcde699196a7c09397194e5cfc09aa467c89ce27ca1200630294355e7eed3f55730fb0dc455b4947d6a6907c9548985e0a32b0','client',1,1,0,'2020-05-25 20:57:20','1986-05-25 00:00:00'),(35,'64b2c86f-a33a-11ea-afec-001e5826d92c','test2@test.ru','890001112233','74771:415ed8d6923bc47129e03f3e5108224fa9a06b113342f6a6a42c601a05c939534f4a09f6767f9ac33efc7aa0f878c5e8eb5decde15dfd324768165107129a3e864e328a042f1745e5e002d3e02747ca11b9c0a3267fda891279ef07e907e621921eac7dd4e69409e18fa5d1d5fb1b43f1fb02db3777a0f3b43cc4fa6668265df','client',1,1,0,'2020-05-31 15:29:38','1952-05-31 00:00:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user__comment`
--

DROP TABLE IF EXISTS `user__comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user__comment` (
  `id` bigint(19) unsigned NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `user__comment__2__user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user__comment`
--

LOCK TABLES `user__comment` WRITE;
/*!40000 ALTER TABLE `user__comment` DISABLE KEYS */;
INSERT INTO `user__comment` VALUES (1,''),(18,''),(22,''),(27,''),(31,''),(34,''),(35,'');
/*!40000 ALTER TABLE `user__comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user__fields`
--

DROP TABLE IF EXISTS `user__fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user__fields` (
  `id` bigint(19) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `family` varchar(255) DEFAULT '',
  `eldername` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  CONSTRAINT `user__fields__2__user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user__fields`
--

LOCK TABLES `user__fields` WRITE;
/*!40000 ALTER TABLE `user__fields` DISABLE KEYS */;
INSERT INTO `user__fields` VALUES (1,'Васян Васянович','Колбасян Оглы','','+7 (000) 000 00 00'),(18,'John','Dow','','+0 (000) 000 00 00'),(22,'koka','moka','','+7 (900) 000 00 00'),(23,'Алексей','','',''),(27,'Абу-т-Тайиб','Аль Муттанабби','','+99 (000) 000 00 00'),(30,'Хрен','','',NULL),(31,'Alexey','12','','+7 (926) 835 12 31'),(32,'Влад','Алексеев','','+7 (925) 003 30 03'),(33,'Константин Иванов','','',NULL),(34,'Anton','Chill','','+7 (123) 456 78 90'),(35,'Test','Test','','+89 (000) 111 22 33');
/*!40000 ALTER TABLE `user__fields` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `user__fields_ai` AFTER INSERT ON `user__fields`
 FOR EACH ROW BEGIN
  INSERT INTO user__search (id,search_name,search_phone)
  VALUES (NEW.id,TRIM(CONCAT( COALESCE(NEW.family,''),' ',COALESCE(NEW.name,''),' ',COALESCE(NEW.eldername,'')  )),CLEAR_PHONE(NEW.phone) )
  ON DUPLICATE KEY UPDATE search_name=VALUES(search_name),search_phone=VALUES(search_phone);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `user__fields_au` AFTER UPDATE ON `user__fields`
 FOR EACH ROW BEGIN
  INSERT INTO user__search (id,search_name,search_phone)
  VALUES (NEW.id,TRIM(CONCAT( COALESCE(NEW.family,''),' ',COALESCE(NEW.name,''),' ',COALESCE(NEW.eldername,'')  )),CLEAR_PHONE(NEW.phone) )
  ON DUPLICATE KEY UPDATE search_name=VALUES(search_name),search_phone=VALUES(search_phone);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `user__history`
--

DROP TABLE IF EXISTS `user__history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user__history` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(19) NOT NULL,
  `ts` datetime NOT NULL,
  `action` varchar(255) NOT NULL,
  `param1` varchar(255) DEFAULT NULL,
  `param2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user__history`
--

LOCK TABLES `user__history` WRITE;
/*!40000 ALTER TABLE `user__history` DISABLE KEYS */;
INSERT INTO `user__history` VALUES (1,1,'2020-04-19 17:16:43','payment_local','content','37'),(2,1,'2020-04-19 18:15:26','payment_local','content','38'),(3,1,'2020-04-19 19:03:58','payment_local','content','80'),(4,22,'2020-04-19 19:04:13','payment_local','content','80'),(5,22,'2020-04-19 19:06:29','payment_local','content','82'),(6,22,'2020-04-19 19:06:38','payment_local','content','83'),(7,22,'2020-04-19 19:13:37','payment_local','content','54'),(8,22,'2020-04-19 19:32:14','payment_local','content','87'),(9,22,'2020-04-19 19:32:56','payment_local','content','69'),(10,1,'2020-04-19 19:34:57','payment_local','content','54'),(11,1,'2020-04-19 19:35:32','payment_local','content','54'),(12,1,'2020-04-19 20:01:34','payment_local','content','56'),(13,22,'2020-04-19 20:07:44','payment_local','content','56'),(14,22,'2020-04-19 23:05:57','payment_local','content','55'),(15,22,'2020-04-19 23:44:06','payment_local','content','91'),(16,22,'2020-04-20 00:14:05','payment_local','content','74'),(17,1,'2020-04-20 03:40:00','payment_local','content','54'),(18,1,'2020-04-20 03:40:37','payment_local','content','55'),(19,22,'2020-04-20 10:32:46','payment_local','content','69'),(20,22,'2020-04-20 10:33:13','payment_local','content','72'),(21,22,'2020-04-20 17:33:46','payment_local','content','54'),(22,22,'2020-04-20 17:33:56','payment_local','content','55'),(23,1,'2020-04-20 23:55:03','payment_local','content','85'),(24,22,'2020-04-21 00:31:26','payment_local','content','80'),(25,1,'2020-04-21 12:46:18','payment_local','content','56'),(26,22,'2020-04-21 15:33:15','payment_local','content','54'),(27,1,'2020-04-21 19:34:46','payment_local','content','69'),(28,1,'2020-04-22 01:30:42','payment_local','content','145'),(29,1,'2020-04-24 17:34:14','payment_local','content','80'),(30,1,'2020-04-25 19:34:41','payment_local','content','145'),(31,22,'2020-04-25 23:17:54','payment_local','content','176'),(32,1,'2020-04-26 04:44:19','payment_local','content','176'),(33,22,'2020-04-29 23:10:02','payment_local','content','190'),(34,22,'2020-04-29 23:21:26','payment_local','content','191'),(35,22,'2020-04-29 23:34:15','payment_local','content','192'),(36,22,'2020-04-29 23:44:58','payment_local','content','193'),(37,22,'2020-04-29 23:55:07','payment_local','content','133'),(38,22,'2020-04-30 00:00:53','payment_local','content','134'),(39,22,'2020-04-30 00:07:26','payment_local','content','135'),(40,22,'2020-04-30 00:13:06','payment_local','content','137'),(41,1,'2020-05-08 17:03:51','payment_local','content','198'),(42,22,'2020-05-08 19:22:53','payment_local','content','145'),(43,1,'2020-05-08 23:36:48','payment_local','content','54'),(44,1,'2020-05-09 18:49:39','payment_local','content','198'),(45,1,'2020-05-09 19:01:44','payment_local','content','80'),(46,1,'2020-05-09 19:51:48','payment_local','content','199'),(47,1,'2020-05-09 20:15:30','payment_local','content','145'),(48,22,'2020-05-10 13:58:36','payment_local','content','72'),(49,22,'2020-05-10 13:59:27','payment_local','content','133'),(50,22,'2020-05-10 16:06:44','payment_local','content','216'),(51,1,'2020-05-11 15:28:27','payment_local','content','198'),(52,1,'2020-05-13 16:07:08','payment_local','content','223'),(53,1,'2020-05-13 16:07:27','payment_local','content','223'),(54,1,'2020-05-13 16:14:09','payment_local','content','216'),(55,1,'2020-05-13 16:14:12','payment_local','content','216'),(56,1,'2020-05-13 22:06:57','payment_local','content','198'),(57,22,'2020-05-14 14:45:04','payment_local','content','223'),(58,1,'2020-05-14 15:49:06','payment_local','content','198'),(59,1,'2020-05-14 15:50:35','payment_local','content','190'),(60,1,'2020-05-15 11:23:35','payment_local','content','133'),(61,1,'2020-05-15 11:24:17','payment_local','content','198'),(62,1,'2020-05-15 11:24:25','payment_local','content','200'),(63,1,'2020-05-15 13:41:13','payment_local','content','223'),(64,1,'2020-05-24 03:04:30','payment_local','content','75'),(65,1,'2020-05-24 03:04:36','payment_local','content','75'),(66,1,'2020-05-24 03:04:49','payment_local','content','76'),(67,34,'2020-05-25 20:59:18','payment_local','content','199'),(68,34,'2020-05-25 20:59:35','payment_local','content','223'),(69,34,'2020-05-25 21:00:03','payment_local','content','198'),(70,1,'2020-05-26 13:15:46','payment_local','content','223'),(71,1,'2020-05-26 13:18:05','payment_local','content','198'),(72,1,'2020-05-26 13:18:09','payment_local','content','198'),(73,1,'2020-05-26 13:18:12','payment_local','content','198'),(74,1,'2020-05-26 13:18:25','payment_local','content','199'),(75,34,'2020-05-26 16:25:37','payment_local','content','198'),(76,1,'2020-05-29 18:15:11','payment_local','content','216'),(77,22,'2020-05-29 20:56:51','payment_local','content','198'),(78,1,'2020-05-31 14:53:40','payment_local','content','199'),(79,22,'2020-05-31 15:16:29','payment_local','content','198'),(80,22,'2020-05-31 15:18:00','payment_local','content','191'),(81,31,'2020-05-31 15:22:05','payment_local','content','223'),(82,1,'2020-05-31 15:30:00','payment_local','content','198'),(83,1,'2020-05-31 15:42:19','payment_local','content','145'),(84,35,'2020-05-31 15:53:02','payment_local','content','198');
/*!40000 ALTER TABLE `user__history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user__search`
--

DROP TABLE IF EXISTS `user__search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user__search` (
  `id` bigint(19) unsigned NOT NULL,
  `search_name` varchar(1024) NOT NULL DEFAULT '',
  `search_phone` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  CONSTRAINT `user__search_2_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user__search`
--

LOCK TABLES `user__search` WRITE;
/*!40000 ALTER TABLE `user__search` DISABLE KEYS */;
INSERT INTO `user__search` VALUES (1,'Колбасян Оглы Васян Васянович','70000000000'),(18,'Dow John','00000000000'),(22,'moka koka','79000000000'),(23,'Алексей',''),(27,'Аль Муттанабби Абу-т-Тайиб','990000000000'),(30,'Хрен',''),(31,'12 Alexey','79268351231'),(32,'Алексеев Влад','79250033003'),(33,'Константин Иванов',''),(34,'Chill Anton','71234567890'),(35,'Test Test','890001112233');
/*!40000 ALTER TABLE `user__search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user__wallet`
--

DROP TABLE IF EXISTS `user__wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user__wallet` (
  `id` bigint(19) unsigned NOT NULL,
  `money` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `user__wallet_2_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user__wallet`
--

LOCK TABLES `user__wallet` WRITE;
/*!40000 ALTER TABLE `user__wallet` DISABLE KEYS */;
INSERT INTO `user__wallet` VALUES (1,292.3),(18,30),(22,298),(27,30),(31,0),(33,60),(34,988),(35,94);
/*!40000 ALTER TABLE `user__wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'chill'
--

--
-- Dumping routines for database 'chill'
--
/*!50003 DROP FUNCTION IF EXISTS `CLEAR_PHONE` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` FUNCTION `CLEAR_PHONE`(phone VARCHAR(100)) RETURNS varchar(100) CHARSET utf8
    COMMENT 'стрип телефонного номера до цифр'
BEGIN
  DECLARE result_value VARCHAR(100) DEFAULT '';
  DECLARE cchar VARCHAR(2) DEFAULT '';
  DECLARE counter INT(11)  DEFAULT 0;
  DECLARE input_length INT(11)  DEFAULT 0;
  DECLARE input_val VARCHAR(100) DEFAULT '';
  SET input_val = COALESCE(TRIM(phone),'');
  SET input_length = CHAR_LENGTH(input_val);
  WHILE (counter<=input_length) DO
     SET cchar = SUBSTRING(input_val,counter,1);
     IF (cchar='0' OR cchar='1' OR cchar='2' OR cchar='3' OR cchar='4' OR cchar='5' OR cchar='6' OR cchar='7' OR cchar='8' OR cchar='9') THEN
       SET result_value=CONCAT(result_value,cchar);
     END IF;
     SET counter=counter+1;
  END WHILE;  
  RETURN result_value;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UPDATE_PROTECTED_GALLERY_COUNTER` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `UPDATE_PROTECTED_GALLERY_COUNTER`(IN `GALLERYUID` VARCHAR(64) CHARSET utf8, IN `OWNERID` BIGINT(19) UNSIGNED)
BEGIN

	DECLARE CN INT(11) UNSIGNED DEFAULT 0;
    SELECT COUNT(uid) INTO CN FROM protected__gallery__item
    WHERE gallery_uid=GALLERYUID AND owner_id=OWNERID;
	INSERT INTO protected__gallery__counter (uid,owner_id,qty)

    VALUES(GALLERYUID,OWNERID,COALESCE(CN,0)) ON DUPLICATE KEY UPDATE 
    qty=VALUES(qty);
    
    INSERT INTO protected__gallery__dates(uid,owner_id,created,updated)
    VALUES( GALLERYUID,OWNERID,NOW(),NOW() )
    ON DUPLICATE KEY UPDATE updated=VALUES(updated);
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-01 13:20:40
