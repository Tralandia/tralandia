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


ALTER TABLE `searchlog` ADD `count` INT(11)  UNSIGNED  NOT NULL  DEFAULT '1'  AFTER `text`;


/* 09:39:24 _Tralandia */ ALTER TABLE `searchlog` ADD `created` DATETIME  NOT NULL  AFTER `primaryLocation_id`;
/* 09:39:36 _Tralandia */ ALTER TABLE `searchlog` ADD `updated` DATETIME  NOT NULL  AFTER `created`;

