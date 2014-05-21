-- InvoicingCreateServiceDuration migration UP file



INSERT INTO `phrase_type` (`id`, `translateTo`, `entityName`, `entityAttribute`, `pluralVariationsRequired`, `genderRequired`, `genderVariationsRequired`, `locativesRequired`, `positionRequired`, `helpForTranslator`, `html`, `translated`, `oldId`, `created`, `updated`, `preFillForTranslator`)
VALUES (NULL, 'supported', '\\Entity\\Invoicing\\ServiceDuration', 'name', '0', '0', '0', '0', '0', NULL, '0', '1', NULL, '2013-06-16 21:31:21', '2013-06-16 21:31:21', '1');
