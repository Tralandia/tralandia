-- SearchLogInit migration UP file



CREATE TABLE `searchlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL DEFAULT '',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `primaryLocation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `primaryLocation_id` (`primaryLocation_id`),
  CONSTRAINT `searchlog_ibfk_1` FOREIGN KEY (`primaryLocation_id`) REFERENCES `location` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
