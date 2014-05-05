INSERT INTO `user` (`id`, `role_id`, `language_id`, `login`, `password`, `newsletter`, `details`, `oldId`, `created`, `updated`, `primaryLocation_id`)
VALUES
	(1, NULL, NULL, 'jacqueline.boidet@wanadoo.fr', 'gepetto10150', NULL, 'null', NULL, '2014-05-02 08:43:52', '2014-05-02 08:43:52', NULL);


INSERT INTO `rental` (`id`, `user_id`, `type_id`, `address_id`, `name_id`, `teaser_id`, `phone_id`, `status`, `classification`, `rank`, `slug`, `contactName`, `url`, `personalSiteUrl`, `email`, `checkIn`, `checkOut`, `pricesUponRequest`, `price`, `calendar`, `calendarUpdated`, `maxCapacity`, `bedroomCount`, `rooms`, `oldId`, `created`, `updated`, `editLanguage_id`, `harvested`, `emailSent`, `registeredFromEmail`, `lastUpdate`, `backlinkEmailSent`, `currency_id`, `description_id`)
VALUES
	(1, 1, NULL, NULL, NULL, NULL, NULL, 6, 4, 66, 'el-tajil', 'Sady Yajaira Bonilla', 'http://www.eltajil.es/', NULL, 'segurconst@gmail.com', 'byAgreement', 'byAgreement', 0, 18, NULL, NULL, 8, 3, '2+2+3', NULL, '2014-04-09 21:51:05', '2014-04-10 00:21:24', NULL, 0, 0, NULL, '2014-04-09 23:59:50', 1, NULL, NULL),
	(2, 1, NULL, NULL, NULL, NULL, NULL, 6, 4, 66, 'el-tajil', 'Sady Yajaira Bonilla', 'http://www.eltajil.es/', NULL, 'segurconst@gmail.com', 'byAgreement', 'byAgreement', 0, 18, NULL, NULL, 8, 3, '2+2+3', NULL, '2014-04-09 21:51:05', '2014-04-10 00:21:24', NULL, 0, 0, NULL, '2014-04-09 23:59:50', 1, NULL, NULL);



INSERT INTO `rental_unit` (`id`, `rental_id`, `name`, `maxCapacity`, `oldId`, `created`, `updated`)
VALUES
	(1, 1, '1A', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(2, 1, '1B', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(3, 2, '2A', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(4, 2, '2B', 10, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');



INSERT INTO `user_rentalreservation` (`id`, `language_id`, `rental_id`, `senderEmail`, `senderName`, `arrivalDate`, `departureDate`, `adultsCount`, `childrenCount`, `message`, `oldId`, `created`, `updated`, `senderPhone_id`, `senderRemoteAddress`, `status`, `childrenAge`, `ownersNote`, `referrer`, `totalPrice`, `paidPrice`, `currency_id`)
VALUES
	(1, NULL, 1, 'email@gmail.com', NULL, '###arrivalDate1###', '###departureDate1###', 2, 0, 'Hi', NULL, '2014-05-02 00:06:04', '2014-05-02 00:06:04', NULL, '92.232.167.179', 'confirmed', NULL, NULL, NULL, NULL, 0, NULL),
	(2, NULL, 1, 'email@gmail.com', NULL, '###arrivalDate2###', '###departureDate2###', 2, 0, 'Hi', NULL, '2014-05-02 00:06:04', '2014-05-02 00:06:04', NULL, '92.232.167.179', 'confirmed', NULL, NULL, NULL, NULL, 0, NULL),
	(3, NULL, 1, 'email@gmail.com', NULL, '###arrivalDate3###', '###departureDate3###', 2, 0, 'Hi', NULL, '2014-05-02 00:06:04', '2014-05-02 00:06:04', NULL, '92.232.167.179', 'confirmed', NULL, NULL, NULL, NULL, 0, NULL);


INSERT INTO `_reservation_unit` (`reservation_id`, `unit_id`)
VALUES
	(1, 1),
	(1, 2),
	(1, 3),
	(1, 4),
	(2, 3),
	(3, 4);