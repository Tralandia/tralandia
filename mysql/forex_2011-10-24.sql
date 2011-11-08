# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.40-log)
# Database: forex
# Generation Time: 2011-10-24 05:41:14 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article`;

CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `status` varchar(10) NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` datetime DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23A0E66A76ED395` (`user_id`),
  KEY `IDX_23A0E6612469DE2` (`category_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `article_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;

INSERT INTO `article` (`id`, `user_id`, `title`, `content`, `status`, `views`, `created`, `modified`, `published`, `category_id`)
VALUES
	(1,1,'Prvy clanok !!! - 1319051649','<p><strong>OBSAH</strong> juchuuu …. !!!!!!!!</p>\r\n<p> </p>\r\n<p>asdfasdfa sd</p>','draft',0,'2011-10-17 00:00:00','2011-10-19 21:23:12',NULL,13),
	(3,1,'sadfasdfa 00000','<p>sdfasdfasdf</p>','draft',0,'2011-10-19 21:25:43','2011-10-19 21:25:43',NULL,8),
	(4,1,'fgdf','<p>sdf</p>','draft',0,'2011-10-19 21:44:59','2011-10-19 21:44:59',NULL,1);

/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `name`)
VALUES
	(1,'nová kategória'),
	(6,'Moja Novučička Kategória XXX!!!'),
	(7,'dalsia kat kat'),
	(8,'huhuhuhu'),
	(9,'huhuhuhu'),
	(10,'aaaa'),
	(11,'aaaa'),
	(12,'aaaa'),
	(13,'aaaa');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table currency
# ------------------------------------------------------------

DROP TABLE IF EXISTS `currency`;

CREATE TABLE `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;

INSERT INTO `currency` (`id`, `code`, `name`)
VALUES
	(1,'EUR',NULL),
	(2,'USD',NULL),
	(3,'CAD',NULL),
	(4,'GPB',NULL),
	(5,'AUD',NULL),
	(6,'JPY',NULL),
	(7,'CNY',NULL),
	(8,'NZD',NULL),
	(9,'CHF',NULL);

/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `unit` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;

INSERT INTO `event` (`id`, `name`, `code`, `unit`)
VALUES
	(1,'Rightmove HPI m/m','Rightmove HPI m/m','%'),
	(2,'New Motor Vehicle Sales m/m','New Motor Vehicle Sales m/m','%'),
	(3,'Revised Industrial Production m/m','Revised Industrial Production m/m','%'),
	(4,'Foreign Securities Purchases','Foreign Securities Purchases','B'),
	(5,'ahoj','ahoj','%'),
	(6,'cxxxxxxx','cxxxxxxx','B'),
	(7,'cxxxxxxx','cxxxxxxx','B'),
	(8,'uuuuuuuuuuuu','uuuuuuuuuuuu','u'),
	(9,'oooooooooo','oooooooooo','ooo'),
	(10,'Niečo niečo ine q/q','nieco-nieco-ine-q-q','%');

/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table indexes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `indexes`;

CREATE TABLE `indexes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `actual` double DEFAULT NULL,
  `forecast` double DEFAULT NULL,
  `previous` double DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `measures` varchar(255) DEFAULT NULL,
  `usualEffect` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `nextRelease` datetime DEFAULT NULL,
  `ffNotes` longtext,
  `whyTradersCare` varchar(255) DEFAULT NULL,
  `alsoCalled` varchar(255) DEFAULT NULL,
  `acroExpand` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` datetime DEFAULT NULL,
  `impact` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A92E85238248176` (`currency_id`),
  KEY `IDX_5A92E85271F7E88B` (`event_id`),
  CONSTRAINT `FK_5A92E85271F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `indexes_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `indexes` WRITE;
/*!40000 ALTER TABLE `indexes` DISABLE KEYS */;

INSERT INTO `indexes` (`id`, `event_id`, `currency_id`, `actual`, `forecast`, `previous`, `source`, `measures`, `usualEffect`, `frequency`, `nextRelease`, `ffNotes`, `whyTradersCare`, `alsoCalled`, `acroExpand`, `created`, `modified`, `published`, `impact`)
VALUES
	(1,2,1,9,9,9,'sss','','','','2011-10-27 14:11:00','','','','ad','2011-10-20 14:11:14','2011-10-23 16:47:04','2011-10-23 16:47:00',3),
	(2,10,1,9,9,9,'source XX','','','','2011-10-29 11:01:00','','','','ad','2011-10-20 14:13:10','2011-10-23 16:46:58','2011-10-23 16:46:00',1),
	(3,2,2,4,4,4,'asd','sadf','hgasdf','asdf','2011-10-22 14:14:00','fasdf','','','','2011-10-22 13:57:36','2011-10-23 17:30:28','2011-10-24 14:14:00',2),
	(4,1,1,3,3,3,'','','','','2011-10-08 03:24:00','','','','','2011-10-22 19:15:07','2011-10-22 19:15:07','2011-10-01 13:22:00',0),
	(5,2,1,8,8,8,'','','','','2011-10-26 04:27:00','','','','','2011-10-22 19:15:39','2011-10-23 16:54:49','2011-10-22 05:43:00',1),
	(6,1,3,0,0,0,'','','','','2011-11-30 00:00:00','','','','','2011-10-23 17:25:45','2011-10-23 17:30:08','2011-10-31 00:00:00',1);

/*!40000 ALTER TABLE `indexes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;

INSERT INTO `tag` (`id`, `name`)
VALUES
	(1,'Breaking News'),
	(2,'Forex Industry News'),
	(3,'asdfasdf');

/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `email`, `password`, `role`, `fullname`, `created`, `modified`)
VALUES
	(1,'wacco@wacco.sk','a99245a5d2ff99bfc2c4780ae1bd8f0b8c363226','admin','Branislav Vaculčiak','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
