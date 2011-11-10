/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Country
# ------------------------------------------------------------

TRUNCATE TABLE `Country`;

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

TRUNCATE TABLE `Language`;

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

TRUNCATE TABLE `Rental`;

LOCK TABLES `Rental` WRITE;
/*!40000 ALTER TABLE `Rental` DISABLE KEYS */;

INSERT INTO `Rental` (`id`, `user_id`, `country_id`, `status`, `nameUrl`, `created`, `updated`)
VALUES
	(1,1,1,'live','google.com','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(2,2,1,'live','sme.sk','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(3,3,2,'live','facebook.com','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(4,1,2,'live','uns.sk','2010-10-10 00:00:00','2010-11-11 00:00:00'),
	(5,3,1,'live','uns.sk','2010-10-10 00:00:00','2010-11-11 00:00:00');


/*!40000 ALTER TABLE `Rental` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table User
# ------------------------------------------------------------

TRUNCATE TABLE `User`;

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;

INSERT INTO `User` (`id`, `country_id`, `login`, `password`, `active`, `created`, `updated`)
VALUES
	(1,2,'david','adsfasdf',1,'2010-11-11 00:00:00','2011-10-10 00:00:00'),
	(2,2,'brano','adsfasdf',1,'2010-11-11 00:00:00','2011-10-10 00:00:00'),
	(3,2,'jano','adsfasdf',1,'2010-11-11 00:00:00','2011-10-10 00:00:00');

/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
