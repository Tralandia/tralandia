-- InvoicingInit migration UP file


CREATE TABLE invoicing_company (id INT AUTO_INCREMENT NOT NULL, locality_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, companyId VARCHAR(255) DEFAULT NULL, companyVatId VARCHAR(255) DEFAULT NULL, vat DOUBLE PRECISION DEFAULT NULL, registrator VARCHAR(255) DEFAULT NULL, inEu TINYINT(1) NOT NULL, oldId INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, primaryLocation_id INT DEFAULT NULL, INDEX IDX_685A45C588823A92 (locality_id), INDEX IDX_685A45C5DD3EB247 (primaryLocation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE invoicing_invoice (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, rental_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, givenFor ENUM('Share', 'Backlink', 'Paid Invoice'), number INT NOT NULL, variableNumber INT NOT NULL, timeDue DATETIME NOT NULL, timePaid DATETIME DEFAULT NULL, clientName VARCHAR(255) DEFAULT NULL, clientPhone VARCHAR(255) DEFAULT NULL, clientEmail VARCHAR(255) DEFAULT NULL, clientUrl VARCHAR(255) DEFAULT NULL, clientAddress VARCHAR(255) DEFAULT NULL, clientAddress2 VARCHAR(255) DEFAULT NULL, clientLocality VARCHAR(255) DEFAULT NULL, clientPostcode VARCHAR(255) DEFAULT NULL, clientCompanyName VARCHAR(255) DEFAULT NULL, clientCompanyId VARCHAR(255) DEFAULT NULL, clientCompanyVatId VARCHAR(255) DEFAULT NULL, createdBy VARCHAR(255) NOT NULL, vat DOUBLE PRECISION DEFAULT NULL, notes LONGTEXT DEFAULT NULL, paymentInfo LONGTEXT DEFAULT NULL, timeFrom DATETIME DEFAULT NULL, timeTo DATETIME DEFAULT NULL, durationStrtotime VARCHAR(255) DEFAULT NULL, durationName VARCHAR(255) DEFAULT NULL, durationNameEn VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, priceEur DOUBLE PRECISION DEFAULT NULL, serviceName VARCHAR(255) DEFAULT NULL, serviceNameEn VARCHAR(255) DEFAULT NULL, oldId INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, clientPrimaryLocation_id INT DEFAULT NULL, clientLanguage_id INT DEFAULT NULL, serviceType_id INT DEFAULT NULL, INDEX IDX_B7805BCE979B1AD6 (company_id), INDEX IDX_B7805BCEA7CF2329 (rental_id), INDEX IDX_B7805BCE66965E86 (clientPrimaryLocation_id), INDEX IDX_B7805BCEF772F81A (clientLanguage_id), INDEX IDX_B7805BCE38248176 (currency_id), INDEX IDX_B7805BCECD0557BA (serviceType_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE invoicing_serviceduration (id INT AUTO_INCREMENT NOT NULL, name_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, strtotime VARCHAR(255) NOT NULL, sort INT NOT NULL, separatorAfter TINYINT(1) NOT NULL, oldId INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_49ABCC1B71179CD6 (name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE invoicing_service (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, duration_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, company_id INT DEFAULT NULL, priceDefault DOUBLE PRECISION DEFAULT NULL, priceCurrent DOUBLE PRECISION DEFAULT NULL, oldId INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, primaryLocation_id INT DEFAULT NULL, INDEX IDX_C678D658C54C8C93 (type_id), INDEX IDX_C678D65837B987D8 (duration_id), INDEX IDX_C678D65838248176 (currency_id), INDEX IDX_C678D658979B1AD6 (company_id), INDEX IDX_C678D658DD3EB247 (primaryLocation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE invoicing_servicetype (id INT AUTO_INCREMENT NOT NULL, name_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, oldId INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_9E82AD9D71179CD6 (name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE invoicing_company ADD CONSTRAINT FK_685A45C588823A92 FOREIGN KEY (locality_id) REFERENCES location (id);
ALTER TABLE invoicing_company ADD CONSTRAINT FK_685A45C5DD3EB247 FOREIGN KEY (primaryLocation_id) REFERENCES location (id);
ALTER TABLE invoicing_invoice ADD CONSTRAINT FK_B7805BCE979B1AD6 FOREIGN KEY (company_id) REFERENCES invoicing_company (id);
ALTER TABLE invoicing_invoice ADD CONSTRAINT FK_B7805BCEA7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) ON DELETE SET NULL;
ALTER TABLE invoicing_invoice ADD CONSTRAINT FK_B7805BCE66965E86 FOREIGN KEY (clientPrimaryLocation_id) REFERENCES location (id);
ALTER TABLE invoicing_invoice ADD CONSTRAINT FK_B7805BCEF772F81A FOREIGN KEY (clientLanguage_id) REFERENCES language (id);
ALTER TABLE invoicing_invoice ADD CONSTRAINT FK_B7805BCE38248176 FOREIGN KEY (currency_id) REFERENCES currency (id);
ALTER TABLE invoicing_invoice ADD CONSTRAINT FK_B7805BCECD0557BA FOREIGN KEY (serviceType_id) REFERENCES invoicing_servicetype (id);
ALTER TABLE invoicing_serviceduration ADD CONSTRAINT FK_49ABCC1B71179CD6 FOREIGN KEY (name_id) REFERENCES phrase (id);
ALTER TABLE invoicing_service ADD CONSTRAINT FK_C678D658C54C8C93 FOREIGN KEY (type_id) REFERENCES invoicing_servicetype (id);
ALTER TABLE invoicing_service ADD CONSTRAINT FK_C678D65837B987D8 FOREIGN KEY (duration_id) REFERENCES invoicing_serviceduration (id);
ALTER TABLE invoicing_service ADD CONSTRAINT FK_C678D65838248176 FOREIGN KEY (currency_id) REFERENCES currency (id);
ALTER TABLE invoicing_service ADD CONSTRAINT FK_C678D658979B1AD6 FOREIGN KEY (company_id) REFERENCES invoicing_company (id);
ALTER TABLE invoicing_service ADD CONSTRAINT FK_C678D658DD3EB247 FOREIGN KEY (primaryLocation_id) REFERENCES location (id);
ALTER TABLE invoicing_servicetype ADD CONSTRAINT FK_9E82AD9D71179CD6 FOREIGN KEY (name_id) REFERENCES phrase (id);

ALTER TABLE invoicing_company ADD slug VARCHAR(255) DEFAULT NULL;
ALTER TABLE `invoicing_company` MODIFY COLUMN `slug` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `name`;

ALTER TABLE invoicing_invoice ADD dateDue DATE NOT NULL, ADD datePaid DATE DEFAULT NULL, ADD dateFrom DATE DEFAULT NULL, ADD dateTo DATE DEFAULT NULL, DROP timeDue, DROP timePaid, DROP timeFrom, DROP timeTo, CHANGE givenFor givenFor ENUM('Share', 'Backlink', 'Paid Invoice', 'Membership');
ALTER TABLE `invoicing_invoice` MODIFY COLUMN `dateFrom` DATE DEFAULT NULL AFTER `paymentInfo`;
ALTER TABLE `invoicing_invoice` MODIFY COLUMN `dateTo` DATE DEFAULT NULL AFTER `dateFrom`;
ALTER TABLE `invoicing_invoice` MODIFY COLUMN `dateDue` DATE NOT NULL AFTER `rental_id`;
ALTER TABLE `invoicing_invoice` MODIFY COLUMN `datePaid` DATE DEFAULT NULL AFTER `dateDue`;

ALTER TABLE `invoicing_invoice` ADD INDEX (`number`);
ALTER TABLE `invoicing_invoice` ADD UNIQUE INDEX (`company_id`, `number`);


INSERT INTO `invoicing_company` (`id`, `locality_id`, `name`, `slug`, `address`, `address2`, `postcode`, `companyId`, `companyVatId`, `vat`, `registrator`, `inEu`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES (NULL, NULL, 'Zero', 'zero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2014-1-1', '2014-1-1', NULL);

INSERT INTO `invoicing_company` (`id`, `locality_id`, `name`, `slug`, `address`, `address2`, `postcode`, `companyId`, `companyVatId`, `vat`, `registrator`, `inEu`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES (NULL, NULL, 'Tralandia s.r.o', 'tralandiaSro', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2014-1-1', '2014-1-1', NULL);
UPDATE `invoicing_company` SET `address` = 'Address', `address2` = 'Address 2', `postcode` = '12 345', `companyId` = '123456789', `companyVatId` = 'VAT123456789', `vat` = '0.19' WHERE `id` = '2';
UPDATE `invoicing_company` SET `registrator` = 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. ', `inEu` = '1' WHERE `id` = '2';


INSERT INTO `phrase_type` (`id`, `translateTo`, `entityName`, `entityAttribute`, `pluralVariationsRequired`, `genderRequired`, `genderVariationsRequired`, `locativesRequired`, `positionRequired`, `helpForTranslator`, `html`, `translated`, `oldId`, `created`, `updated`, `preFillForTranslator`)
VALUES (NULL, 'supported', '\\Entity\\Invoicing\\ServiceType', 'name', '0', '0', '0', '0', '0', NULL, '0', '1', NULL, '2013-06-16 21:31:21', '2013-06-16 21:31:21', '1');
