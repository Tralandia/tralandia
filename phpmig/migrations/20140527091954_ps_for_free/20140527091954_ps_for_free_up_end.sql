-- PsForFree migration UP file



ALTER TABLE `personalsite_configuration` DROP INDEX `url`;
ALTER TABLE `personalsite_configuration` ADD UNIQUE INDEX (`url`);

ALTER TABLE rental ADD CONSTRAINT FK_1619C27D91BA7309 FOREIGN KEY (personalSiteConfiguration_id) REFERENCES personalsite_configuration (id) ON DELETE CASCADE;
CREATE UNIQUE INDEX UNIQ_1619C27D91BA7309 ON rental (personalSiteConfiguration_id);

