INSERT INTO `language` (`id`, `name_id`, `translator_id`, `iso`, `supported`, `live`, `defaultCollation`, `genders`, `plurals`, `ppcPatterns`, `variationDetails`, `translationPrice`, `details`, `oldId`, `created`, `updated`)
VALUES
	(38, NULL, NULL, 'en', 1, 1, 'utf8_general_ci', '[]', '{\"rule\":\"($n != 1)\",\"names\":[\"Singular\",\"0, Plural\"]}', 'null', 'null', 0.016, '{\"\":null}', 38, '2013-06-16 21:31:21', '2013-07-12 17:02:49');


INSERT INTO `currency` (`id`, `name_id`, `iso`, `exchangeRate`, `rounding`, `searchInterval`, `oldId`, `created`, `updated`, `symbol`)
VALUES
	(1, NULL, 'EUR', 1, '2', 10, 1, '2013-06-16 21:32:43', '2013-06-16 21:32:43', '€');

INSERT INTO `phrase` (`id`, `type_id`, `status`, `details`, `oldId`, `created`, `updated`, `sourceLanguage_id`, `used`)
VALUES
	(1, NULL, 0, 'null', NULL, '2014-05-13 14:04:14', '2014-05-13 14:04:14', 38, 0);


INSERT INTO `invoicing_servicetype` (`id`, `name_id`, `slug`, `oldId`, `created`, `updated`)
VALUES
	(1, 1, 'featured', NULL, '2014-05-12 13:29:07', '2014-05-12 13:29:07');

INSERT INTO `invoicing_serviceduration` (`id`, `name_id`, `slug`, `strtotime`, `sort`, `separatorAfter`, `oldId`, `created`, `updated`)
VALUES
	(1, 1, '1 Week', '+1 week', 0, 0, NULL, '2014-05-13 14:04:14', '2014-05-13 14:04:14');


INSERT INTO `invoicing_service` (`id`, `type_id`, `duration_id`, `currency_id`, `company_id`, `priceDefault`, `priceCurrent`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES
	(1, 1, 1, 1, NULL, 90, 80, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);


INSERT INTO `invoicing_company` (`id`, `locality_id`, `name`, `slug`, `address`, `address2`, `postcode`, `companyId`, `companyVatId`, `vat`, `registrator`, `inEu`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES
	(1, NULL, 'Zero', 'zero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2014-01-01 00:00:00', '2014-01-01 00:00:00', NULL),
	(2, NULL, 'Tralandia s.r.o', 'tralandiaSro', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2014-01-01 00:00:00', '2014-01-01 00:00:00', NULL);





INSERT INTO `user` (`id`, `role_id`, `language_id`, `login`, `password`, `newsletter`, `details`, `oldId`, `created`, `updated`, `primaryLocation_id`, `invoicingInformation`)
VALUES
	(54, NULL, 38, 'informacio@hotelstabarbara.com', '2a43a259eea6aeee27ecf8c895a29ee8b91a5548', 1, 'null', 10199, '2013-06-16 21:35:21', '2013-06-16 21:35:21', NULL, '{\"default\":{\"clientName\":\"clientName\",\"clientPhone\":\"clientPhone\",\"clientAddress\":\"clientAddress\",\"clientAddress2\":\"clientAddress2\",\"clientLocality\":\"clientLocality\",\"clientPostcode\":\"clientPostcode\",\"clientPrimaryLocation\":52,\"clientLanguage\":144,\"clientCompanyName\":\"clientCompanyName\",\"clientCompanyId\":\"clientCompanyId\",\"clientCompanyVatId\":\"clientCompanyVatId\"}}');


INSERT INTO `rental_type` (`id`, `name_id`, `slug`, `classification`, `oldId`, `created`, `updated`)
VALUES
	(1, 1, 'hotel', 1, NULL, '2013-06-16 21:37:16', '2013-06-16 21:37:16');


INSERT INTO `contact_address` (`id`, `locality_id`, `status`, `address`, `postalCode`, `subLocality`, `latitude`, `longitude`, `oldId`, `created`, `updated`, `primaryLocation_id`, `formattedAddress`)
VALUES
	(1, NULL, 'ok', 'Plaça Major 1', 'AD300', '', 42.55685, 1.533245, NULL, '2013-06-16 21:37:19', '2013-06-16 21:37:19', NULL, NULL);


INSERT INTO `rental` (`id`, `user_id`, `type_id`, `address_id`, `name_id`, `teaser_id`, `phone_id`, `status`, `classification`, `rank`, `slug`, `contactName`, `url`, `personalSiteUrl`, `email`, `checkIn`, `checkOut`, `pricesUponRequest`, `priceFrom`, `priceTo`, `calendar`, `calendarUpdated`, `maxCapacity`, `bedroomCount`, `rooms`, `oldId`, `created`, `updated`, `editLanguage_id`, `harvested`, `emailSent`, `registeredFromEmail`, `lastUpdate`, `backlinkEmailSent`, `currency_id`, `description_id`)
VALUES
	(1, 54, 1, 1, 1, 1, NULL, 6, 3, 48, 'hotel-rural-santa-barbara-de-la-vall', 'Eva', 'http://www.hotelstabarbara.com/', NULL, 'informacio@hotelstabarbara.com', '16', '12', 0, 37, 37, '', '1970-01-01 01:00:00', 2, NULL, NULL, 11801, '2012-09-28 15:05:17', '2014-05-01 02:01:28', 38, 0, 1, NULL, NULL, 1, 1, 1);
