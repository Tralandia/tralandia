-- PersonalsiteConfiguration migration UP file


CREATE TABLE personalsite_configuration (id INT AUTO_INCREMENT NOT NULL,
url VARCHAR(255) NOT NULL,
oldId INT DEFAULT NULL,
created DATETIME NOT NULL,
updated DATETIME NOT NULL,
INDEX url (url), PRIMARY KEY(id))
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

ALTER TABLE rental ADD personalSite_id INT DEFAULT NULL;
ALTER TABLE rental ADD CONSTRAINT FK_1619C27D34BF244E FOREIGN KEY (personalSite_id) REFERENCES personalsite_configuration (id) ON DELETE CASCADE;
CREATE UNIQUE INDEX UNIQ_1619C27D34BF244E ON rental (personalSite_id);


insert into personalsite_configuration (url, created, updated) select rental.personalSiteUrl, '2014-05-19 13:59:34', '2014-05-19 13:59:34' from rental where rental.personalSiteUrl is not null and rental.personalSiteUrl != '';

ALTER TABLE `personalsite_configuration` COLLATE = utf8_general_ci;
ALTER TABLE `personalsite_configuration` CHANGE `url` `url` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_general_ci  NOT NULL  DEFAULT '';


update rental r inner join personalsite_configuration c on c.url = r.personalSiteUrl set r.personalSite_id = c.id;


ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D34BF244E;
DROP INDEX UNIQ_1619C27D34BF244E ON rental;
ALTER TABLE rental CHANGE personalsite_id personalSiteConfiguration_id INT DEFAULT NULL;
ALTER TABLE rental ADD CONSTRAINT FK_1619C27D91BA7309 FOREIGN KEY (personalSiteConfiguration_id) REFERENCES personalsite_configuration (id) ON DELETE CASCADE;
CREATE UNIQUE INDEX UNIQ_1619C27D91BA7309 ON rental (personalSiteConfiguration_id);
