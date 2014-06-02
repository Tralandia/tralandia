-- InvoicingNewServices migration UP file

INSERT INTO `invoicing_service` (`id`, `type_id`, `duration_id`, `currency_id`, `company_id`, `priceDefault`, `priceCurrent`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES (NULL, '1', '5', '1', NULL, 0, 0, NULL, '2014-01-01', '2014-01-01', NULL);


INSERT INTO `invoicing_service` (`id`, `type_id`, `duration_id`, `currency_id`, `company_id`, `priceDefault`, `priceCurrent`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES (NULL, '2', '5', '1', NULL, '99', '69', NULL, '2014-01-01', '2014-01-01', NULL);


INSERT INTO `invoicing_service` (`id`, `type_id`, `duration_id`, `currency_id`, `company_id`, `priceDefault`, `priceCurrent`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES (NULL, '2', '5', '1', NULL, '159', '99', NULL, '2014-01-01', '2014-01-01', NULL);
