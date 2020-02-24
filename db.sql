/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 10.4.8-MariaDB : Database - accreditation
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`accreditation` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `accreditation`;

/*Table structure for table `academic_qualification` */

DROP TABLE IF EXISTS `academic_qualification`;

CREATE TABLE `academic_qualification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `level` enum('Diploma','Higher Diploma','Bachelor','Masters','PhD','Certificate') NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `certificate` varbinary(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `academic_qualification_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `company_staff` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `academic_qualification` */

insert  into `academic_qualification`(`id`,`staff_id`,`level`,`course_name`,`certificate`,`date_created`,`last_updated`) values 
(1,1,'Diploma','Mazematics 2','uploads/academic_certs/1-Diploma-0.25750700 1581519561.pdf','2020-02-12 17:59:21','2020-02-13 08:36:48'),
(5,1,'Masters','Chemistry','uploads/academic_certs/1-Masters-0.31542300 1581581496.pdf','2020-02-13 11:11:36','2020-02-13 11:11:36');

/*Table structure for table `accreditation_type` */

DROP TABLE IF EXISTS `accreditation_type`;

CREATE TABLE `accreditation_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `accreditation_type` */

insert  into `accreditation_type`(`id`,`name`,`description`,`date_created`,`last_updated`) values 
(1,'Cloud Computing','CC services to be modified','2020-02-14 09:30:26','2020-02-14 09:30:26'),
(2,'Systems and Applications','Systems and Applications','2020-02-14 09:30:43','2020-02-14 09:30:43');

/*Table structure for table `application` */

DROP TABLE IF EXISTS `application`;

CREATE TABLE `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `accreditation_type_id` int(11) DEFAULT NULL,
  `financial_status_amount` double(15,2) DEFAULT NULL,
  `financial_status_link` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `declaration` enum('1','0') DEFAULT NULL,
  `initial_approval_date` date DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `user_id` (`user_id`),
  KEY `accreditation_type_id` (`accreditation_type_id`),
  CONSTRAINT `application_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company_profile` (`id`),
  CONSTRAINT `application_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `application_ibfk_3` FOREIGN KEY (`accreditation_type_id`) REFERENCES `accreditation_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `application` */

insert  into `application`(`id`,`company_id`,`accreditation_type_id`,`financial_status_amount`,`financial_status_link`,`user_id`,`status`,`declaration`,`initial_approval_date`,`date_created`,`last_updated`) values 
(3,1,1,1000000.00,'http://google.go.ke',1,'ApplicationWorkflow/completed','1','2020-02-17','2020-02-15 10:21:59','2020-02-18 16:25:21'),
(4,1,2,1000000.00,'1556663',1,'ApplicationWorkflow/draft','1',NULL,'2020-02-15 10:48:49','2020-02-15 12:51:10'),
(5,3,1,1000000.00,'http://google.go.ke/my-company-status',1,'ApplicationWorkflow/completed','1',NULL,'2020-02-18 15:22:04','2020-02-18 16:23:46'),
(6,4,2,1000000.00,'http://google.go.ke/my-company-statusuopdtvn',1,'ApplicationWorkflow/completed','1','2020-02-01','2020-02-18 17:49:32','2020-02-18 18:35:05');

/*Table structure for table `application_classification` */

DROP TABLE IF EXISTS `application_classification`;

CREATE TABLE `application_classification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `icta_committee_id` int(11) NOT NULL,
  `score` float(5,2) unsigned DEFAULT NULL,
  `classification` varchar(30) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `rejection_comment` varchar(150) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_id` (`application_id`,`icta_committee_id`),
  KEY `icta_committee` (`icta_committee_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `application_classification_ibfk_1` FOREIGN KEY (`icta_committee_id`) REFERENCES `icta_committee` (`id`),
  CONSTRAINT `application_classification_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `application_classification_ibfk_3` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `application_classification` */

insert  into `application_classification`(`id`,`application_id`,`icta_committee_id`,`score`,`classification`,`status`,`rejection_comment`,`user_id`,`date_created`,`last_updated`) values 
(1,3,1,63.00,'ICTA 4',1,NULL,1,'2020-02-17 12:18:41','2020-02-17 15:51:10'),
(4,3,2,0.00,'ICTA 6',1,NULL,1,'2020-02-17 17:36:59','2020-02-18 09:55:43'),
(9,5,1,76.00,'ICTA 2',1,'not bad',1,'2020-02-18 15:40:53','2020-02-18 15:48:43'),
(12,5,2,78.00,'ICTA 2',1,'This can go ahead',1,'2020-02-18 15:53:14','2020-02-18 15:53:14'),
(13,6,1,75.00,'ICTA 2',1,'',1,'2020-02-18 18:23:33','2020-02-18 18:23:33'),
(14,6,2,92.00,'ICTA 1',1,'hello there',1,'2020-02-18 18:27:14','2020-02-18 18:27:14');

/*Table structure for table `application_committe_member` */

DROP TABLE IF EXISTS `application_committe_member`;

CREATE TABLE `application_committe_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `committee_member_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `committee_member_id` (`committee_member_id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `application_committe_member_ibfk_1` FOREIGN KEY (`committee_member_id`) REFERENCES `icta_committee_member` (`id`),
  CONSTRAINT `application_committe_member_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

/*Data for the table `application_committe_member` */

insert  into `application_committe_member`(`id`,`application_id`,`committee_member_id`,`date_created`,`last_updated`) values 
(1,3,1,'2020-02-17 16:52:22','2020-02-17 16:52:22'),
(2,3,2,'2020-02-17 16:52:39','2020-02-17 16:52:39'),
(5,3,3,'2020-02-17 16:53:03','2020-02-17 16:53:03'),
(6,3,5,'2020-02-17 16:56:40','2020-02-17 16:56:40'),
(7,5,1,'2020-02-18 15:33:40','2020-02-18 15:33:40'),
(8,5,2,'2020-02-18 15:33:40','2020-02-18 15:33:40'),
(9,5,3,'2020-02-18 15:49:34','2020-02-18 15:49:34'),
(10,5,5,'2020-02-18 15:49:34','2020-02-18 15:49:34'),
(11,6,1,'2020-02-18 18:00:38','2020-02-18 18:00:38'),
(12,6,2,'2020-02-18 18:00:38','2020-02-18 18:00:38'),
(13,6,5,'2020-02-18 18:24:00','2020-02-18 18:24:00');

/*Table structure for table `application_company_experience` */

DROP TABLE IF EXISTS `application_company_experience`;

CREATE TABLE `application_company_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `experience_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `experience_id` (`experience_id`),
  CONSTRAINT `application_company_experience_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`),
  CONSTRAINT `application_company_experience_ibfk_2` FOREIGN KEY (`experience_id`) REFERENCES `company_experience` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `application_company_experience` */

insert  into `application_company_experience`(`id`,`application_id`,`experience_id`,`date_created`,`last_updated`) values 
(1,3,1,'2020-02-15 10:22:00','2020-02-15 10:22:00'),
(2,4,1,'2020-02-15 10:48:49','2020-02-15 10:48:49'),
(3,5,3,'2020-02-18 15:22:04','2020-02-18 15:22:04'),
(4,6,4,'2020-02-18 17:49:33','2020-02-18 17:49:33'),
(5,6,5,'2020-02-18 17:49:33','2020-02-18 17:49:33');

/*Table structure for table `application_score` */

DROP TABLE IF EXISTS `application_score`;

CREATE TABLE `application_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `score_item_id` int(11) DEFAULT NULL,
  `score` tinyint(4) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `committee_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_id` (`application_id`,`score_item_id`,`committee_id`),
  KEY `score_item_id` (`score_item_id`),
  KEY `committee_id` (`committee_id`),
  CONSTRAINT `application_score_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`),
  CONSTRAINT `application_score_ibfk_2` FOREIGN KEY (`score_item_id`) REFERENCES `score_item` (`id`),
  CONSTRAINT `application_score_ibfk_3` FOREIGN KEY (`committee_id`) REFERENCES `icta_committee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4;

/*Data for the table `application_score` */

insert  into `application_score`(`id`,`application_id`,`score_item_id`,`score`,`comment`,`user_id`,`committee_id`,`date_created`,`last_updated`) values 
(1,3,24,NULL,NULL,1,1,'2020-02-17 08:51:43','2020-02-17 15:47:03'),
(2,3,25,4,NULL,1,1,'2020-02-17 08:51:43','2020-02-17 15:51:10'),
(3,3,5,2,NULL,1,1,'2020-02-17 08:51:43','2020-02-17 15:51:10'),
(4,3,4,NULL,NULL,1,1,'2020-02-17 08:51:43','2020-02-17 12:17:19'),
(5,3,2,2,NULL,1,1,'2020-02-17 08:51:43','2020-02-17 15:51:10'),
(6,3,1,2,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 15:51:10'),
(7,3,3,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 08:51:44'),
(8,3,20,5,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 15:51:10'),
(9,3,21,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 15:47:03'),
(10,3,19,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 08:51:44'),
(11,3,22,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 12:17:19'),
(12,3,18,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 15:51:10'),
(13,3,15,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 08:51:44'),
(14,3,16,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 12:17:19'),
(15,3,14,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 08:51:44'),
(16,3,17,3,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 15:51:10'),
(17,3,13,NULL,NULL,1,1,'2020-02-17 08:51:44','2020-02-17 08:51:44'),
(18,3,11,7,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 15:51:10'),
(19,3,10,NULL,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 15:51:10'),
(20,3,12,NULL,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 08:51:45'),
(21,3,23,5,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 15:51:10'),
(22,3,9,NULL,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 08:51:45'),
(23,3,6,NULL,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 08:51:45'),
(24,3,7,NULL,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 08:51:45'),
(25,3,8,NULL,NULL,1,1,'2020-02-17 08:51:45','2020-02-17 15:47:02'),
(26,3,1,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-18 09:38:59'),
(27,3,2,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-18 09:38:59'),
(28,3,3,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-17 16:56:40'),
(29,3,4,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-17 16:56:40'),
(30,3,5,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-18 09:38:59'),
(31,3,6,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-17 16:56:40'),
(32,3,7,NULL,NULL,1,2,'2020-02-17 16:56:40','2020-02-17 16:56:40'),
(33,3,8,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(34,3,9,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(35,3,10,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(36,3,11,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-18 09:38:59'),
(37,3,12,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(38,3,13,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(39,3,14,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(40,3,15,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(41,3,16,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(42,3,17,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(43,3,18,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-18 09:38:59'),
(44,3,19,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(45,3,20,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(46,3,21,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(47,3,22,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(48,3,23,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-18 09:38:59'),
(49,3,24,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-17 16:56:41'),
(50,3,25,NULL,NULL,1,2,'2020-02-17 16:56:41','2020-02-18 09:39:00'),
(51,5,1,2,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:40:53'),
(52,5,2,2,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:40:53'),
(53,5,3,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(54,5,4,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(55,5,5,2,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:40:53'),
(56,5,6,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(57,5,7,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(58,5,8,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(59,5,9,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(60,5,10,14,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:48:43'),
(61,5,11,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:47:30'),
(62,5,12,NULL,NULL,1,1,'2020-02-18 15:33:41','2020-02-18 15:33:41'),
(63,5,13,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(64,5,14,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(65,5,15,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(66,5,16,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(67,5,17,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(68,5,18,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(69,5,19,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(70,5,20,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(71,5,21,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(72,5,22,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(73,5,23,5,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:47:30'),
(74,5,24,NULL,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:33:42'),
(75,5,25,4,NULL,1,1,'2020-02-18 15:33:42','2020-02-18 15:40:53'),
(76,5,1,2,NULL,1,2,'2020-02-18 15:49:34','2020-02-18 15:53:13'),
(77,5,2,2,NULL,1,2,'2020-02-18 15:49:34','2020-02-18 15:53:13'),
(78,5,3,NULL,NULL,1,2,'2020-02-18 15:49:34','2020-02-18 15:49:34'),
(79,5,4,NULL,NULL,1,2,'2020-02-18 15:49:34','2020-02-18 15:49:34'),
(80,5,5,2,NULL,1,2,'2020-02-18 15:49:34','2020-02-18 15:53:13'),
(81,5,6,NULL,NULL,1,2,'2020-02-18 15:49:34','2020-02-18 15:49:34'),
(82,5,7,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(83,5,8,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(84,5,9,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(85,5,10,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(86,5,11,7,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:53:13'),
(87,5,12,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(88,5,13,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(89,5,14,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(90,5,15,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(91,5,16,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(92,5,17,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(93,5,18,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(94,5,19,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(95,5,20,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(96,5,21,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(97,5,22,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(98,5,23,5,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:53:13'),
(99,5,24,NULL,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:49:35'),
(100,5,25,4,NULL,1,2,'2020-02-18 15:49:35','2020-02-18 15:53:13'),
(101,6,1,2,NULL,1,1,'2020-02-18 18:00:38','2020-02-18 18:23:32'),
(102,6,2,2,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:23:32'),
(103,6,3,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(104,6,4,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(105,6,5,2,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:23:32'),
(106,6,6,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(107,6,7,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(108,6,8,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(109,6,9,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(110,6,10,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(111,6,11,7,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:23:32'),
(112,6,12,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(113,6,13,14,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:23:32'),
(114,6,14,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(115,6,15,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(116,6,16,NULL,NULL,1,1,'2020-02-18 18:00:39','2020-02-18 18:00:39'),
(117,6,17,NULL,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:00:40'),
(118,6,18,10,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:23:32'),
(119,6,19,NULL,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:00:40'),
(120,6,20,NULL,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:00:40'),
(121,6,21,NULL,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:00:40'),
(122,6,22,NULL,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:00:40'),
(123,6,23,5,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:23:32'),
(124,6,24,NULL,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:00:40'),
(125,6,25,4,NULL,1,1,'2020-02-18 18:00:40','2020-02-18 18:23:33'),
(126,6,1,2,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:27:14'),
(127,6,2,2,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:27:14'),
(128,6,3,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(129,6,4,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(130,6,5,2,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:27:14'),
(131,6,6,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(132,6,7,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(133,6,8,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(134,6,9,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(135,6,10,14,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:27:14'),
(136,6,11,NULL,NULL,1,2,'2020-02-18 18:24:01','2020-02-18 18:24:01'),
(137,6,12,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(138,6,13,14,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:27:14'),
(139,6,14,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(140,6,15,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(141,6,16,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(142,6,17,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(143,6,18,10,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:27:14'),
(144,6,19,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(145,6,20,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(146,6,21,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(147,6,22,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(148,6,23,5,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:27:14'),
(149,6,24,NULL,NULL,1,2,'2020-02-18 18:24:02','2020-02-18 18:24:02'),
(150,6,25,4,NULL,1,2,'2020-02-18 18:24:03','2020-02-18 18:27:14');

/*Table structure for table `application_staff` */

DROP TABLE IF EXISTS `application_staff`;

CREATE TABLE `application_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `role` varchar(30) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `application_staff_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`),
  CONSTRAINT `application_staff_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `company_staff` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `application_staff` */

insert  into `application_staff`(`id`,`application_id`,`staff_id`,`role`,`date_created`,`last_updated`) values 
(5,3,1,'Staff','2020-02-15 10:21:59','2020-02-15 10:21:59'),
(6,3,2,'Staff','2020-02-15 10:21:59','2020-02-15 10:21:59'),
(7,3,3,'Staff','2020-02-15 10:21:59','2020-02-15 10:21:59'),
(8,3,4,'Staff','2020-02-15 10:22:00','2020-02-15 10:22:00'),
(9,4,2,'Staff','2020-02-15 10:48:49','2020-02-15 10:48:49'),
(10,4,4,'Staff','2020-02-15 10:48:49','2020-02-15 10:48:49'),
(11,4,1,'Staff','2020-02-15 11:10:01','2020-02-15 11:10:01'),
(12,5,5,'Staff','2020-02-18 15:22:04','2020-02-18 15:22:04'),
(13,6,6,'Technical Director','2020-02-18 17:49:32','2020-02-18 17:49:32'),
(14,6,7,'Director','2020-02-18 17:49:33','2020-02-18 17:49:33');

/*Table structure for table `company_document` */

DROP TABLE IF EXISTS `company_document`;

CREATE TABLE `company_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `company_type_doc_id` int(11) DEFAULT NULL,
  `upload_file` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `company_document_ibfk_1` (`company_type_doc_id`),
  CONSTRAINT `company_document_ibfk_1` FOREIGN KEY (`company_type_doc_id`) REFERENCES `company_type_document` (`id`),
  CONSTRAINT `company_document_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `company_profile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `company_document` */

insert  into `company_document`(`id`,`company_id`,`company_type_doc_id`,`upload_file`,`date_created`,`last_updated`) values 
(1,1,2,'uploads/company_documents/1-KRA PIN-0.48986200 1581600264.pdf','2020-02-13 16:11:40','2020-02-13 16:24:24'),
(3,3,2,'uploads/company_documents/3-KRA PIN-0.07931800 1582027928.pdf','2020-02-18 15:12:08','2020-02-18 15:12:08'),
(4,4,2,'uploads/company_documents/4-KRA PIN-0.28324900 1582035347.pdf','2020-02-18 17:15:47','2020-02-18 17:15:47');

/*Table structure for table `company_experience` */

DROP TABLE IF EXISTS `company_experience`;

CREATE TABLE `company_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `organization_type` enum('Public','Private') DEFAULT NULL,
  `project_name` varchar(250) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Ongoing','Completed','Suspended','Terminated') DEFAULT NULL,
  `project_cost` double(15,2) DEFAULT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `company_experience_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company_profile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `company_experience` */

insert  into `company_experience`(`id`,`company_id`,`organization_type`,`project_name`,`start_date`,`end_date`,`status`,`project_cost`,`attachment`,`date_created`,`last_updated`) values 
(1,1,'Public','Go to Naivasha 2','2002-02-13','2009-02-05','Completed',145658263.65,'uploads/company_exp_docs/1-0.75188500 1581605873.pdf','2020-02-13 17:57:53','2020-02-13 18:09:34'),
(3,3,'Public','Implementation of xyza blah blah blah','2003-02-25','2009-02-05','Completed',NULL,'uploads/company_exp_docs/3-0.67724000 1582028111.pdf','2020-02-18 15:15:11','2020-02-18 15:15:11'),
(4,4,'Public','Implementation of xyza2 blah blah blah','2002-02-01','2009-02-05','Ongoing',2566885.65,'uploads/company_exp_docs/4-0.67429000 1582035714.pdf','2020-02-18 17:21:54','2020-02-18 17:21:54'),
(5,4,'Private','Implementation of xyza2 blah blah blah blah','2008-02-01','2009-02-05','Completed',2566885.65,'uploads/company_exp_docs/4-0.97009400 1582035832.pdf','2020-02-18 17:23:52','2020-02-18 17:23:52');

/*Table structure for table `company_profile` */

DROP TABLE IF EXISTS `company_profile`;

CREATE TABLE `company_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_reg_no` varchar(20) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `county` varchar(20) DEFAULT NULL,
  `town` varchar(40) DEFAULT NULL,
  `building` varchar(40) DEFAULT NULL,
  `floor` varchar(20) DEFAULT NULL,
  `telephone_number` varchar(100) DEFAULT NULL,
  `company_email` varchar(100) DEFAULT NULL,
  `company_type_id` int(11) DEFAULT NULL,
  `postal_address` varchar(50) DEFAULT NULL,
  `company_categorization` enum('Open','Youth','Women','People With Disability') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `company_type_id` (`company_type_id`),
  CONSTRAINT `company_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `company_profile_ibfk_2` FOREIGN KEY (`company_type_id`) REFERENCES `company_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `company_profile` */

insert  into `company_profile`(`id`,`business_reg_no`,`company_name`,`registration_date`,`county`,`town`,`building`,`floor`,`telephone_number`,`company_email`,`company_type_id`,`postal_address`,`company_categorization`,`user_id`,`date_created`,`last_updated`) values 
(1,'12133','fintech','0000-00-00','nyamira','nyamira','nyatero','2','0723243173','sammayng800@gmail.com',1,'73-40500 nyamira','Youth',23,'2020-02-11 13:54:49','2020-02-14 12:08:47'),
(3,'123456789','Kenya XYZA','2020-02-18','Kilifi','Kilifi','xyza bld','1','021563366','xyza@xyza.org',1,'281 kilifi','Youth',1,'2020-02-18 15:10:51','2020-02-18 15:10:51'),
(4,'987654321','Kenya XYZA2','2020-02-18','Kilifi','Kilifi','xyza bld','1','0215633665','xyzaa@xyza.org',3,'281 kilifi','Youth',1,'2020-02-18 17:14:55','2020-02-18 17:14:55');

/*Table structure for table `company_staff` */

DROP TABLE IF EXISTS `company_staff`;

CREATE TABLE `company_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `national_id` int(10) DEFAULT NULL,
  `kra_pin` varchar(15) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `disability_status` enum('Yes','No') DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `staff_type` enum('Staff','Technical Director','Director') DEFAULT NULL,
  `status` enum('1','0') DEFAULT NULL COMMENT '0 for inactive, 1 for active',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `company_staff_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company_profile` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `company_staff` */

insert  into `company_staff`(`id`,`company_id`,`first_name`,`last_name`,`national_id`,`kra_pin`,`gender`,`dob`,`disability_status`,`title`,`staff_type`,`status`,`date_created`,`last_updated`) values 
(1,1,'John','hama',25663622,'Pbhj56365','Male','0000-00-00','Yes','Software developer','Staff','1','2020-02-12 11:11:12','2020-02-12 11:11:12'),
(2,1,'Jane','hama',25663622,'Pbhj563655','Male','2001-02-19','No','Software developer','Staff','1','2020-02-12 12:16:50','2020-02-12 12:16:50'),
(3,1,'Emma','Ndiko',35621562,'p45h6562','Male','1999-02-13','No','Software developer','Staff','1','2020-02-12 12:26:36','2020-02-12 12:26:36'),
(4,1,'John','Karisa',10266332,'Pbhj563655','Male','1996-02-01','Yes','Software developer','Staff','1','2020-02-12 18:43:32','2020-02-12 18:43:32'),
(5,3,'Clement','Piri',26565626,'Pni586212','Male','1986-02-13','No','Network engineer','Staff','1','2020-02-18 15:21:09','2020-02-18 15:21:09'),
(6,4,'Clement','hama',256636225,'Pbhj56365569','Male','1978-02-03','No','Network engineer','Technical Director','1','2020-02-18 17:28:27','2020-02-18 17:28:27'),
(7,4,'Jane','Karisa',2147483647,'Pbhj56365532','Female','1978-02-03','No','HR officer','Director','1','2020-02-18 17:29:12','2020-02-18 17:29:12');

/*Table structure for table `company_type` */

DROP TABLE IF EXISTS `company_type`;

CREATE TABLE `company_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `company_type` */

insert  into `company_type`(`id`,`name`,`date_created`,`last_updated`) values 
(-1000,'General','2020-02-13 11:58:07','2020-02-13 11:58:07'),
(1,'Sole proprietor','2020-02-13 11:41:04','2020-02-13 11:44:20'),
(2,'Partnership','2020-02-13 11:44:29','2020-02-13 11:44:29'),
(3,'Corporation','2020-02-13 11:44:34','2020-02-13 11:44:34'),
(4,'Private Company','2020-02-13 11:44:39','2020-02-13 11:44:39'),
(5,'Limited company','2020-02-13 11:44:44','2020-02-13 11:44:44'),
(6,'Co-operative','2020-02-13 11:44:46','2020-02-13 11:44:46');

/*Table structure for table `company_type_document` */

DROP TABLE IF EXISTS `company_type_document`;

CREATE TABLE `company_type_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_type_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_type_id` (`company_type_id`),
  KEY `document_type_id` (`document_type_id`),
  CONSTRAINT `company_type_document_ibfk_1` FOREIGN KEY (`company_type_id`) REFERENCES `company_type` (`id`),
  CONSTRAINT `company_type_document_ibfk_2` FOREIGN KEY (`document_type_id`) REFERENCES `document_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `company_type_document` */

insert  into `company_type_document`(`id`,`company_type_id`,`document_type_id`,`user_id`,`date_created`,`last_updated`) values 
(1,1,2,1,'2020-02-13 15:21:25','2020-02-13 15:47:21'),
(2,-1000,1,1,'2020-02-13 15:21:47','2020-02-13 15:21:47'),
(3,2,3,1,'2020-02-13 15:59:03','2020-02-13 15:59:03');

/*Table structure for table `document_type` */

DROP TABLE IF EXISTS `document_type`;

CREATE TABLE `document_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `document_type` */

insert  into `document_type`(`id`,`name`,`date_created`,`last_updated`) values 
(1,'KRA PIN','2020-02-13 15:20:54','2020-02-13 15:20:54'),
(2,'Business Permit','2020-02-13 15:47:01','2020-02-13 15:47:01'),
(3,'Partnership Agreement','2020-02-13 15:58:27','2020-02-13 15:58:27');

/*Table structure for table `icta_accreditation_level` */

DROP TABLE IF EXISTS `icta_accreditation_level`;

CREATE TABLE `icta_accreditation_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `accreditation_fee` double(8,2) DEFAULT NULL,
  `renewal_fee` double(8,2) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `icta_accreditation_level` */

insert  into `icta_accreditation_level`(`id`,`name`,`accreditation_fee`,`renewal_fee`,`date_created`,`last_updated`) values 
(1,'ICTA 8',2500.00,830.00,'2020-02-18 15:57:30','2020-02-18 16:11:58'),
(2,'ICTA 7',5000.00,1600.00,'2020-02-18 15:57:37','2020-02-18 16:12:00'),
(3,'ICTA 6',10000.00,3300.00,'2020-02-18 15:57:41','2020-02-18 16:12:03'),
(4,'ICTA 5',12000.00,4000.00,'2020-02-18 15:57:47','2020-02-18 16:12:05'),
(5,'ICTA 4',15000.00,5000.00,'2020-02-18 15:57:51','2020-02-18 16:12:08'),
(6,'ICTA 3',20000.00,6600.00,'2020-02-18 15:57:54','2020-02-18 16:12:10'),
(7,'ICTA 2',25000.00,8300.00,'2020-02-18 15:57:58','2020-02-18 16:12:13'),
(8,'ICTA 1',30000.00,10000.00,'2020-02-18 15:58:05','2020-02-18 16:12:15');

/*Table structure for table `icta_committee` */

DROP TABLE IF EXISTS `icta_committee`;

CREATE TABLE `icta_committee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `purpose` varchar(100) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `icta_committee` */

insert  into `icta_committee`(`id`,`name`,`purpose`,`date_created`,`last_updated`) values 
(1,'Secretariat','internal review','2020-02-16 15:33:52','2020-02-16 15:33:52'),
(2,'Committee',NULL,'2020-02-16 15:34:00','2020-02-16 15:34:00');

/*Table structure for table `icta_committee_member` */

DROP TABLE IF EXISTS `icta_committee_member`;

CREATE TABLE `icta_committee_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `committee_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `committee_id` (`committee_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `icta_committee_member_ibfk_1` FOREIGN KEY (`committee_id`) REFERENCES `icta_committee` (`id`),
  CONSTRAINT `icta_committee_member_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `icta_committee_member` */

insert  into `icta_committee_member`(`id`,`user_id`,`committee_id`,`date_created`,`last_updated`) values 
(1,25,1,'2020-02-16 18:10:48','2020-02-16 18:10:48'),
(2,26,1,'2020-02-16 18:10:49','2020-02-16 18:10:49'),
(3,29,2,'2020-02-17 16:28:37','2020-02-17 16:28:37'),
(5,27,2,'2020-02-17 16:39:55','2020-02-17 16:39:55');

/*Table structure for table `password_reset` */

DROP TABLE IF EXISTS `password_reset`;

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `hash` varchar(120) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `password_reset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `password_reset` */

insert  into `password_reset`(`id`,`user_id`,`status`,`hash`,`date_created`,`last_updated`) values 
(1,1,0,'hKm2LW7!OvAtxNIkFlPXuCDMiHTLFJC94tn3lsdyoGVZvaqZsbaAQINOy5Vi6Tqk0ep5Yu3Q21@gz8Er0$DPMXHnpceobj8zWY4g','2020-02-18 12:37:05','2020-02-18 13:11:12');

/*Table structure for table `payment` */

DROP TABLE IF EXISTS `payment`;

CREATE TABLE `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `billable_amount` double(7,2) DEFAULT NULL,
  `mpesa_code` varchar(20) DEFAULT NULL,
  `receipt` varchar(100) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `status` enum('new','paid','confirmed','rejected') NOT NULL DEFAULT 'new',
  `comment` varchar(200) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `payment` */

insert  into `payment`(`id`,`application_id`,`billable_amount`,`mpesa_code`,`receipt`,`level`,`status`,`comment`,`date_created`,`last_update`) values 
(1,3,1500.00,NULL,'uploads/receipts/3-1-0.81528700 1581837151.pdf',1,'confirmed',NULL,'2020-02-16 09:49:12','2020-02-16 11:06:00'),
(2,3,5000.00,NULL,'uploads/receipts/3-2-0.34128800 1581950255.pdf',2,'confirmed',NULL,'2020-02-17 17:37:35','2020-02-17 17:39:16'),
(3,5,1500.00,NULL,'uploads/receipts/5-1-0.99798500 1582028916.pdf',1,'confirmed',NULL,'2020-02-18 15:28:37','2020-02-18 15:29:40'),
(4,5,25000.00,NULL,'uploads/receipts/5-2-0.59543200 1582032159.pdf',2,'confirmed',NULL,'2020-02-18 16:22:39','2020-02-18 16:22:55'),
(5,6,1500.00,NULL,'uploads/receipts/6-1-0.63360700 1582037947.pdf',1,'confirmed',NULL,'2020-02-18 17:59:07','2020-02-18 17:59:56'),
(6,6,30000.00,NULL,'uploads/receipts/6-2-0.96762700 1582039762.pdf',2,'confirmed',NULL,'2020-02-18 18:29:22','2020-02-18 18:29:31');

/*Table structure for table `professional_certification` */

DROP TABLE IF EXISTS `professional_certification`;

CREATE TABLE `professional_certification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `qualification_type` varchar(50) DEFAULT NULL,
  `other_description` varchar(50) DEFAULT NULL,
  `certificate` varchar(250) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `professional_certification_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `company_staff` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `professional_certification` */

insert  into `professional_certification`(`id`,`staff_id`,`qualification_type`,`other_description`,`certificate`,`date_created`,`last_updated`) values 
(1,1,'MySQL for Developers2','','uploads/professional_certs/1-MySQL for Developers-0.68266500 1581576787.pdf','2020-02-13 09:53:07','2020-02-13 10:00:38');

/*Table structure for table `score_item` */

DROP TABLE IF EXISTS `score_item`;

CREATE TABLE `score_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL,
  `specific_item` varchar(70) DEFAULT NULL,
  `score_item` varchar(500) DEFAULT NULL,
  `maximum_score` float(3,1) DEFAULT NULL,
  `checkboxes` tinyint(4) NOT NULL DEFAULT 1,
  `each_checkbox_marks` tinyint(4) NOT NULL DEFAULT 1,
  `group` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`,`specific_item`,`score_item`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;

/*Data for the table `score_item` */

insert  into `score_item`(`id`,`category`,`specific_item`,`score_item`,`maximum_score`,`checkboxes`,`each_checkbox_marks`,`group`,`status`,`date_created`,`last_updated`) values 
(1,'A) Directors\' Qualification and work experience.','i) Technical Director ','1. Degree (at least BSc in Computer Science, IT or related)',2.0,1,1,NULL,1,'2020-02-16 13:21:34','2020-02-17 14:29:11'),
(2,'A) Directors\' Qualification and work experience.','i) Technical Director ','2. Certification in Project Management ',2.0,1,1,NULL,1,'2020-02-16 13:21:34','2020-02-17 14:29:16'),
(3,'A) Directors\' Qualification and work experience.','i) Technical Director ','3. Work experience in (3) similar assignments as team leader,1 mark per assignment',3.0,3,1,NULL,1,'2020-02-16 13:21:34','2020-02-17 14:29:29'),
(4,'A) Directors\' Qualification and work experience.','ii) Other directors','1. Work experience in (3) similar assignments as team leader,1 mark per assignment ',3.0,3,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:29:37'),
(5,'A) Directors\' Qualification and work experience.','ii) Other directors','2. Certification in Project Management',2.0,1,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:29:42'),
(6,'B) Staff qualification','i) Technical staff in specialized area ','1. Degree (at least BSc Computer Science, /IT or related(2 marks per person)',10.0,5,2,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:37:11'),
(7,'B) Staff qualification','i) Technical staff in specialized area ','2. Professional certification (1 mark per person)',5.0,5,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:38:56'),
(8,'B) Staff qualification','i) Technical staff in specialized area ','3. Work experience in 3 similar assignments (1 mark per person)',5.0,5,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:38:57'),
(9,'B) Staff qualification','i) Technical staff in specialized area ','4. Certification in project management (1 mark per person)',5.0,5,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:38:58'),
(10,'C) Financial status','i) Turnover','1. High turnover (over 100m)',14.0,1,1,'turnover',1,'2020-02-16 13:21:35','2020-02-17 14:44:31'),
(11,'C) Financial status','i) Turnover','2. Average turnover (5-100m)',7.0,1,1,'turnover',1,'2020-02-16 13:21:35','2020-02-17 14:44:32'),
(12,'C) Financial status','i) Turnover','3. Low turnover (below 5m)',3.5,1,1,'turnover',1,'2020-02-16 13:21:35','2020-02-17 15:24:19'),
(13,'C) Financial status','ii) Largest projects in area of Specialization for the last 5 years','1. Over 100m',14.0,1,1,'project_income',1,'2020-02-16 13:21:35','2020-02-17 15:25:53'),
(14,'C) Financial status','ii) Largest projects in area of Specialization for the last 5 years ','2. 75 - 100m',10.5,1,1,'project_income',1,'2020-02-16 13:21:35','2020-02-17 15:45:21'),
(15,'C) Financial status','ii) Largest projects in area of Specialization for the last 5 years','3. 25 - 75m',7.0,1,1,'project_income',1,'2020-02-16 13:21:35','2020-02-17 15:25:56'),
(16,'C) Financial status','ii) Largest projects in area of Specialization for the last 5 years','4. 5 -25m',5.0,1,1,'project_income',1,'2020-02-16 13:21:35','2020-02-17 15:25:57'),
(17,'C) Financial status','ii) Largest projects in area of Specialization for the last 5 years','5. Below 5m',3.0,1,1,'project_income',1,'2020-02-16 13:21:35','2020-02-17 15:26:02'),
(18,'C) Financial status','iii) Cash flow in KES','1. Over 100m',10.0,1,1,'cash_flow',1,'2020-02-16 13:21:35','2020-02-17 15:26:22'),
(19,'C) Financial status','iii) Cash flow in KES','2. 75 - 100m',7.5,1,1,'cash_flow',1,'2020-02-16 13:21:35','2020-02-17 15:44:48'),
(20,'C) Financial status','iii) Cash flow in KES','3. 25 - 75m',5.0,1,1,'cash_flow',1,'2020-02-16 13:21:35','2020-02-17 15:26:26'),
(21,'C) Financial status','iii) Cash flow in KES','4. 5 -25m',3.0,1,1,'cash_flow',1,'2020-02-16 13:21:35','2020-02-17 15:26:27'),
(22,'C) Financial status','iii) Cash flow in KES','5. Below 5m',1.0,1,1,'cash_flow',1,'2020-02-16 13:21:35','2020-02-17 15:26:29'),
(23,'D) Office and service facilities','i) Adequate office space [5 marks]','1. Business permit',5.0,1,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 14:31:58'),
(24,'E) Company experience','i) Details of development projects undertaken in area of specializatio','1. Demonstrable capacity at company level by providing evidence of 4 relevant works carried out for the last 5 years, evidenced by copy of purchase order or contract and contact details, job completion certificates/ Client testimonials/contracts.<br/>- 4 marks for each job carried out in Kenyan government institutions and <br/>- 2 marks for each job carried out in private organizations',16.0,8,2,NULL,1,'2020-02-16 13:21:35','2020-02-17 15:29:11'),
(25,'E) Company experience','i) Details of development projects undertaken in area of specializatio','2. Relevant compliance certificates (Government, Manufacturer) as per category',4.0,1,1,NULL,1,'2020-02-16 13:21:35','2020-02-17 13:06:55');

/*Table structure for table `staff_experience` */

DROP TABLE IF EXISTS `staff_experience`;

CREATE TABLE `staff_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `organization` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `assignment` varchar(200) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `staff_experience_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `company_staff` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `staff_experience` */

insert  into `staff_experience`(`id`,`staff_id`,`organization`,`role`,`assignment`,`start_date`,`end_date`,`date_created`,`last_updated`) values 
(1,1,'KWTRP','DBA','xyz','2002-02-12','2009-02-13','2020-02-13 10:18:00','2020-02-13 10:21:55'),
(2,6,'KWTRP','DBA','to do a b cd','2009-02-13','2020-02-18','2020-02-18 17:33:46','2020-02-18 17:33:46');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kra_pin_number` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` enum('Admin','Secretariat','Committee member','Applicant') DEFAULT 'Applicant',
  `status` enum('1','0') DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `kra_pin_number` (`kra_pin_number`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user` */

insert  into `user`(`id`,`kra_pin_number`,`email`,`first_name`,`last_name`,`password`,`role`,`status`,`date_created`,`last_updated`) values 
(1,'p50pihjjj','kenmagua@gmail.com','Kenneth','Magua','$2y$10$48Fbraov9cI0q/oKXD7Weujk/FdeHr0mfrwexzD7PcwYk3MjDPcx2','Admin','1','2020-02-11 08:56:12','2020-02-18 14:12:35'),
(20,'p50pihjxj','jhama@gmail.com','John','hama','$2y$10$W5RRcfdiKoQOLRKFO.sFSOkDfB.jE3MnYLdfT96C1GAPwvcXEV69m','Applicant','1','2020-02-11 16:16:58','2020-02-16 11:14:07'),
(23,'p50pihjvj','kkamau@gmail.com','ken','kamau','$2y$10$f11X4tXKvpil7SHDTeY9TO0u3sXvyZ5HsIm44JPqEBC6i8cStmBEu','Applicant','1','2020-02-11 16:38:26','2020-02-16 11:14:11'),
(24,'p50pihjcj','samuelmayenga@gmail.com','Sam','May','$2y$10$DelXuftbnrfzPhDrpl1tFuGn0VnWDDxMpV/K5jCSWuecmsE1/RnP.','Applicant','1','2020-02-14 15:45:27','2020-02-16 11:14:16'),
(25,'p50pihjqj','samuelmayenga2@gmail.com','Jean','Jay','$2y$10$DelXuftbnrfzPhDrpl1tFuGn0VnWDDxMpV/K5jCSWuecmsE1/RnP.','Secretariat','1','2020-02-14 15:45:27','2020-02-16 11:14:16'),
(26,'p50pihjddj','samuelmayenga3@gmail.com','Sean','Karisa','$2y$10$DelXuftbnrfzPhDrpl1tFuGn0VnWDDxMpV/K5jCSWuecmsE1/RnP.','Secretariat','1','2020-02-14 15:45:27','2020-02-16 11:14:16'),
(27,'p50ihjddj','samuelmayenga5@gmail.com','Dean','Don','$2y$10$DelXuftbnrfzPhDrpl1tFuGn0VnWDDxMpV/K5jCSWuecmsE1/RnP.','Committee member','1','2020-02-14 15:45:27','2020-02-16 11:14:16'),
(29,'p0pihjddj','samuelmayenga6@gmail.com','Anthony','Kazungu','$2y$10$DelXuftbnrfzPhDrpl1tFuGn0VnWDDxMpV/K5jCSWuecmsE1/RnP.','Committee member','1','2020-02-14 15:45:27','2020-02-16 11:14:16'),
(30,'','','','','$2y$10$2z/1CwaOQNU4y4kfdbfdc.gPpoU2j6jwEtKvLG0jD6wgPW5wK3Z5G','Applicant','0','2020-02-17 19:02:13','2020-02-17 19:02:13');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;