# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.9)
# Database: tralandia
# Generation Time: 2012-01-12 10:32:01 +0100
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Article`;

CREATE TABLE `Article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` datetime DEFAULT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CD8737FAA76ED395` (`user_id`),
  CONSTRAINT `FK_CD8737FAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Country
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Country`;

CREATE TABLE `Country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) DEFAULT NULL,
  `iso` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9CCEF0FA82F1BAF4` (`language_id`),
  CONSTRAINT `FK_9CCEF0FA82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `Language` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `Country` WRITE;
/*!40000 ALTER TABLE `Country` DISABLE KEYS */;

INSERT INTO `Country` (`id`, `language_id`, `iso`, `created`, `updated`)
VALUES
	(1,1,'sk','2011-11-11 00:00:00','2011-11-11 00:00:00'),
	(2,2,'hu','2011-11-11 00:00:00','2011-11-11 00:00:00');

/*!40000 ALTER TABLE `Country` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Language
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Language`;

CREATE TABLE `Language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `Language` WRITE;
/*!40000 ALTER TABLE `Language` DISABLE KEYS */;

INSERT INTO `Language` (`id`, `iso`, `active`, `created`, `updated`)
VALUES
	(1,'sk',1,'2011-11-11 00:00:00','2011-11-11 00:00:00'),
	(2,'hu',1,'2011-11-11 00:00:00','2011-11-11 00:00:00');

/*!40000 ALTER TABLE `Language` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Rental
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Rental`;

CREATE TABLE `Rental` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `nameUrl` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_11B5C74BA76ED395` (`user_id`),
  KEY `IDX_11B5C74BF92F3E70` (`country_id`),
  CONSTRAINT `FK_11B5C74BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  CONSTRAINT `FK_11B5C74BF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `Country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

LOCK TABLES `Rental` WRITE;
/*!40000 ALTER TABLE `Rental` DISABLE KEYS */;

INSERT INTO `Rental` (`id`, `user_id`, `country_id`, `status`, `nameUrl`, `created`, `updated`)
VALUES
	(1,1,1,'live','google.com','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(2,2,1,'live','sme.sk','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(3,3,2,'live','facebook.com','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(4,1,2,'live','uns.sk','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(5,3,1,'live','uns.sk','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(6,2,1,'live','google.sk','2011-11-10 10:24:14','2011-11-10 10:24:14'),
	(7,3,2,'checked','google.hu','2011-11-10 11:03:45','2011-11-10 11:03:45'),
	(8,5,2,'live','live.sk','2011-11-10 15:33:01','2011-11-10 15:33:01');

/*!40000 ALTER TABLE `Rental` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table User
# ------------------------------------------------------------

DROP TABLE IF EXISTS `User`;

CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2DA17977F92F3E70` (`country_id`),
  CONSTRAINT `FK_2DA17977F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `Country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;

INSERT INTO `User` (`id`, `country_id`, `login`, `password`, `active`, `created`, `updated`)
VALUES
	(1,2,'david','adsfasdf',1,'2010-11-11 00:00:00','2011-10-10 00:00:00'),
	(2,1,'brano','adsfasdf',1,'2010-11-11 00:00:00','2011-10-10 00:00:00'),
	(3,2,'jano','adsfasdf',1,'2010-11-11 00:00:00','2011-10-10 00:00:00'),
	(4,1,'test','heslo',0,'2011-11-10 14:31:17','2011-11-10 14:31:17'),
	(5,2,'Rado','tajneheslo',0,'2011-11-10 15:33:01','2011-11-10 15:33:01');

/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
