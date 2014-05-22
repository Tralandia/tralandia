-- InvoicingUpdateCompany migration UP file

/* 13:18:42 _Tralandia */ ALTER TABLE `invoicing_company` DROP FOREIGN KEY `FK_685A45C588823A92`;
/* 13:18:49 _Tralandia */ ALTER TABLE `invoicing_company` DROP INDEX `IDX_685A45C588823A92`;
/* 13:19:01 _Tralandia */ ALTER TABLE `invoicing_company` CHANGE `locality_id` `locality` VARCHAR(255)  NULL  DEFAULT NULL;
