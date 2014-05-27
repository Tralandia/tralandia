-- PsForFree migration UP file



ALTER TABLE `personalsite_configuration` DROP INDEX `url`;
ALTER TABLE `personalsite_configuration` ADD UNIQUE INDEX (`url`);
