-- InvoicingInit migration DOWN file

SET FOREIGN_KEY_CHECKS = 0;

delete p from phrase p inner join invoicing_servicetype st ON st.name_id = p.id;

DROP TABLE `invoicing_servicetype`;
DROP TABLE `invoicing_serviceduration`;
DROP TABLE `invoicing_service`;
DROP TABLE `invoicing_invoice`;
DROP TABLE `invoicing_company`;

SET FOREIGN_KEY_CHECKS = 1;

delete from `phrase_type` where `entityName` = '\\Entity\\Invoicing\\ServiceType' and `entityAttribute` = 'name';
