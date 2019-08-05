# noinspection SqlNoDataSourceInspectionForFile
-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2016 at 06:45 AM
-- Server version: 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ogledi`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `neighborhood` varchar(128) CHARACTER SET utf8 NOT NULL,
  `street` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lat` decimal(10,6) NOT NULL,
  `lng` decimal(10,6) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `city_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_addresses_cities` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `username` varchar(64) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 NOT NULL,
  `gender` enum('f','m') CHARACTER SET utf8 DEFAULT NULL,
  `postion` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `invalid_login_count` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `user_status_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_admins_user_statuses_idx` (`user_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `username`, `first_name`, `last_name`, `password`, `gender`, `postion`, `invalid_login_count`, `date_created`, `date_updated`, `user_status_id`) VALUES
(3, 'test@gmail.com', 'test', 'test', 'test', '$2y$14$kA8AkFuqnmk7Gp5SzXGA4.xIlhyxbU8nncxj.o7CIh9seZBGTDwPe', NULL, NULL, 0, '2016-09-24 00:00:00', '2016-09-24 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE IF NOT EXISTS `admin_permissions` (
  `admin_id` int(11) NOT NULL,
  `permission_id` smallint(6) NOT NULL,
  PRIMARY KEY (`admin_id`,`permission_id`),
  KEY `FK_admin_permissions_permissions` (`permission_id`) USING BTREE,
  KEY `FK_admin_permissions_users` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

DROP TABLE IF EXISTS `agencies`;
CREATE TABLE IF NOT EXISTS `agencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `agency_type_id` tinyint(4) NOT NULL,
  `name_bg` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description_bg` text CHARACTER SET utf8 NOT NULL,
  `description_en` text CHARACTER SET utf8,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_agencies_users_idx` (`user_id`),
  KEY `fk_agencies_agency_types_idx` (`agency_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agency_types`
--

DROP TABLE IF EXISTS `agency_types`;
CREATE TABLE IF NOT EXISTS `agency_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `user_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`agency_id`),
  KEY `fk_agents_agencies_idx` (`agency_id`),
  KEY `fk_agents_users_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `announcement` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL,
  `date_published` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `meta_title` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `meta_description` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `language_id` tinyint(4) DEFAULT NULL,
  `category_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_articles_languages_idx` (`language_id`),
  KEY `fk_articles_categories_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `position` tinyint(4) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `banner` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `language_id` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_banners_languages_idx` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `building_types`
--

DROP TABLE IF EXISTS `building_types`;
CREATE TABLE IF NOT EXISTS `building_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `offer_id` int(11) NOT NULL,
  `photo_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gallery_offers_idx` (`offer_id`),
  KEY `fk_gallery_photo_type_idx` (`photo_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `heating_systems`
--

DROP TABLE IF EXISTS `heating_systems`;
CREATE TABLE IF NOT EXISTS `heating_systems` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `language` varchar(2) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
CREATE TABLE IF NOT EXISTS `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `active` tinyint(1) NOT NULL,
  `top_offer` tinyint(1) DEFAULT '0',
  `vip_offer` tinyint(1) DEFAULT '0',
  `address_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` enum('bgn','eur') CHARACTER SET utf8 NOT NULL DEFAULT 'eur',
  `construction_year` year(4) DEFAULT NULL,
  `area` smallint(6) NOT NULL,
  `floor` tinyint(4) DEFAULT NULL,
  `bedrooms` tinyint(4) NOT NULL,
  `bathrooms` tinyint(4) NOT NULL,
  `total_rooms` tinyint(4) NOT NULL,
  `parking_spaces` smallint(6) DEFAULT NULL,
  `information` text CHARACTER SET utf8,
  `youtube_code_1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `youtube_code_2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `google_360` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `panorama_file` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `garden` tinyint(1) DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `meta_title` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `meta_description` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `language_id` tinyint(4) DEFAULT NULL,
  `offer_status_id` tinyint(4) NOT NULL,
  `offer_type_id` tinyint(4) NOT NULL,
  `building_type_id` tinyint(4) NOT NULL,
  `property_type_id` tinyint(4) NOT NULL,
  `heating_system_id` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_offers_languages_idx` (`language_id`),
  KEY `fk_offers_offer_statuses_idx` (`offer_status_id`),
  KEY `fk_offers_offer_types_idx` (`offer_type_id`),
  KEY `fk_offers_building_types_idx` (`building_type_id`),
  KEY `fk_offers_property_types_idx` (`property_type_id`),
  KEY `fk_offers_heating_systems_idx` (`heating_system_id`),
  KEY `fk_offers_users_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_ property_features`
--

DROP TABLE IF EXISTS `offer_ property_features`;
CREATE TABLE IF NOT EXISTS `offer_ property_features` (
  `offers_id` int(11) NOT NULL,
  `property_features_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`offers_id`,`property_features_id`),
  KEY `fk_offer_ property_features_offers_idx` (`offers_id`),
  KEY `fk_offer_ property_features_property_features_idx` (`property_features_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_statuses`
--

DROP TABLE IF EXISTS `offer_statuses`;
CREATE TABLE IF NOT EXISTS `offer_statuses` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_status_change_logs`
--

DROP TABLE IF EXISTS `offer_status_change_logs`;
CREATE TABLE IF NOT EXISTS `offer_status_change_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) NOT NULL,
  `offer_status_id` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_offer_status_change_logs_offers` (`offer_id`),
  KEY `FK_offer_status_change_logs_offer_statuses` (`offer_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_types`
--

DROP TABLE IF EXISTS `offer_types`;
CREATE TABLE IF NOT EXISTS `offer_types` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_views`
--

DROP TABLE IF EXISTS `offer_views`;
CREATE TABLE IF NOT EXISTS `offer_views` (
  `views` int(11) NOT NULL DEFAULT '0',
  `offer_id` int(11) NOT NULL,
  PRIMARY KEY (`offer_id`),
  KEY `fk_offer_views_offers_idx` (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `meta_title` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `meta_description` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `language_id` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pages_languages` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `module` varchar(64) CHARACTER SET utf8 NOT NULL,
  `controller` varchar(64) CHARACTER SET utf8 NOT NULL,
  `action` varchar(16) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photographers_ schedules`
--

DROP TABLE IF EXISTS `photographers_ schedules`;
CREATE TABLE IF NOT EXISTS `photographers_ schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `area` smallint(6) NOT NULL,
  `photo_service_id` tinyint(4) NOT NULL,
  `contact_phone` varchar(32) CHARACTER SET utf8 NOT NULL,
  `contact_email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `revised` tinyint(1) NOT NULL DEFAULT '0',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `user_datetime` datetime DEFAULT NULL,
  `photo_day` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `offer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_photographers_ schedules_users_idx` (`user_id`),
  KEY `fk_photographers_ schedules_offers_idx` (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photo_types`
--

DROP TABLE IF EXISTS `photo_types`;
CREATE TABLE IF NOT EXISTS `photo_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
CREATE TABLE IF NOT EXISTS `prices` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `min_offers` smallint(6) NOT NULL,
  `max_offers` smallint(6) NOT NULL,
  `property_photoshoot_price` decimal(10,2) NOT NULL,
  `weekly_price` decimal(10,2) NOT NULL,
  `vip_price` decimal(10,2) NOT NULL,
  `top_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_features`
--

DROP TABLE IF EXISTS `property_features`;
CREATE TABLE IF NOT EXISTS `property_features` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_types`
--

DROP TABLE IF EXISTS `property_types`;
CREATE TABLE IF NOT EXISTS `property_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `username` varchar(64) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user_status_id` tinyint(4) NOT NULL,
  `phone` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `subscribed` tinyint(1) DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `user_type_id` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_status_id` (`user_status_id`),
  KEY `fk_users_user_types_idx` (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `first_name`, `last_name`, `password`, `logo`, `user_status_id`, `phone`, `subscribed`, `date_created`, `date_updated`, `user_type_id`) VALUES
(2, 'test@gmail.com', 'test', 'test', 'test', '$2y$14$kA8AkFuqnmk7Gp5SzXGA4.xIlhyxbU8nncxj.o7CIh9seZBGTDwPe', NULL, 1, NULL, 0, '2016-09-24 00:00:00', '2016-09-24 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_offer_lists`
--

DROP TABLE IF EXISTS `user_offer_lists`;
CREATE TABLE IF NOT EXISTS `user_offer_lists` (
  `date_created` datetime NOT NULL,
  `offer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`offer_id`,`user_id`),
  KEY `fk_user_offer_lists_offers_idx` (`offer_id`),
  KEY `fk_user_offer_lists_users_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_prices`
--

DROP TABLE IF EXISTS `user_prices`;
CREATE TABLE IF NOT EXISTS `user_prices` (
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `price_id` smallint(6) NOT NULL,
  PRIMARY KEY (`price_id`,`user_id`),
  KEY `fk_user_prices_users_idx` (`user_id`),
  KEY `fk_user_prices_prices_idx` (`price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_statuses`
--

DROP TABLE IF EXISTS `user_statuses`;
CREATE TABLE IF NOT EXISTS `user_statuses` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_statuses`
--

INSERT INTO `user_statuses` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Disapproved'),
(3, 'Blocked');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE IF NOT EXISTS `user_tokens` (
  `request_key` varchar(32) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `user_token_statuses_id` tinyint(4) NOT NULL,
  `users_id` int(11) NOT NULL,
  PRIMARY KEY (`request_key`),
  KEY `fk_user_tokens_user_token_statuses_idx` (`user_token_statuses_id`),
  KEY `fk_user_tokens_users_idx` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_token_statuses`
--

DROP TABLE IF EXISTS `user_token_statuses`;
CREATE TABLE IF NOT EXISTS `user_token_statuses` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
CREATE TABLE IF NOT EXISTS `user_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `FK_addresses_cities` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admins_user_statuses` FOREIGN KEY (`user_status_id`) REFERENCES `user_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD CONSTRAINT `FK_user_permissions_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_user_permissions_users` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `agencies`
--
ALTER TABLE `agencies`
  ADD CONSTRAINT `fk_agencies_agency_types` FOREIGN KEY (`agency_type_id`) REFERENCES `agency_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agencies_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `fk_agents_agencies` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_agents_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_articles_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `fk_banners_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `fk_gallery_offers` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gallery_photo_type` FOREIGN KEY (`photo_type_id`) REFERENCES `photo_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `fk_offers_building_types` FOREIGN KEY (`building_type_id`) REFERENCES `building_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offers_heating_systems` FOREIGN KEY (`heating_system_id`) REFERENCES `heating_systems` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offers_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offers_offer_statuses` FOREIGN KEY (`offer_status_id`) REFERENCES `offer_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offers_offer_types` FOREIGN KEY (`offer_type_id`) REFERENCES `offer_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offers_property_types` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_offers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `offer_ property_features`
--
ALTER TABLE `offer_ property_features`
  ADD CONSTRAINT `fk_offer_ property_features_offers` FOREIGN KEY (`offers_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_offer_ property_features_property_features` FOREIGN KEY (`property_features_id`) REFERENCES `property_features` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offer_status_change_logs`
--
ALTER TABLE `offer_status_change_logs`
  ADD CONSTRAINT `FK_offer_status_change_logs_offer_statuses` FOREIGN KEY (`offer_status_id`) REFERENCES `offer_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_offer_status_change_logs_offers` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `offer_views`
--
ALTER TABLE `offer_views`
  ADD CONSTRAINT `fk_offer_views_offers` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `FK_pages_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `photographers_ schedules`
--
ALTER TABLE `photographers_ schedules`
  ADD CONSTRAINT `fk_photographers_ schedules_offers` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_photographers_ schedules_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`user_status_id`) REFERENCES `user_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_user_types` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  ADD  CONSTRAINT `fk_users_prices` FOREIGN KEY (`price_id`) REFERENCES `prices`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_offer_lists`
--
ALTER TABLE `user_offer_lists`
  ADD CONSTRAINT `fk_user_offer_lists_offers` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_offer_lists_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_prices`
--
ALTER TABLE `user_prices`
  ADD CONSTRAINT `fk_user_prices_prices` FOREIGN KEY (`price_id`) REFERENCES `prices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_prices_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `fk_user_tokens_user_token_statuses` FOREIGN KEY (`user_token_statuses_id`) REFERENCES `user_token_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_tokens_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
