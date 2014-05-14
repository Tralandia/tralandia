-- InvoicingCreateServiceDuration migration DOWN file


delete from `phrase_type` where `entityName` = '\\Entity\\Invoicing\\ServiceDuration' and `entityAttribute` = 'name';


SET FOREIGN_KEY_CHECKS = 0;
/* 14:02:57 _Tralandia */ TRUNCATE TABLE `invoicing_serviceduration`;
SET FOREIGN_KEY_CHECKS = 1;
