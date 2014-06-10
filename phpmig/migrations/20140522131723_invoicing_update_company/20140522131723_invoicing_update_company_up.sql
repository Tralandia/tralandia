-- InvoicingUpdateCompany migration UP file

ALTER TABLE `invoicing_company` DROP FOREIGN KEY `FK_685A45C588823A92`;
ALTER TABLE `invoicing_company` DROP INDEX `IDX_685A45C588823A92`;
ALTER TABLE `invoicing_company` CHANGE `locality_id` `locality` VARCHAR(255)  NULL  DEFAULT NULL;
UPDATE `invoicing_company` SET `locality` = 'Locality' WHERE `id` = '2';
