INSERT INTO `language` (`id`, `name_id`, `translator_id`, `iso`, `supported`, `live`, `defaultCollation`, `genders`, `plurals`, `ppcPatterns`, `variationDetails`, `translationPrice`, `details`, `oldId`, `created`, `updated`)
VALUES
	(38, NULL, NULL, 'en', 1, 1, 'utf8_general_ci', '[]', '{\"rule\":\"($n != 1)\",\"names\":[\"Singular\",\"0, Plural\"]}', 'null', 'null', 0.016, '{\"\":null}', 38, '2013-06-16 21:31:21', '2013-07-12 17:02:49');



INSERT INTO `user` (`id`, `role_id`, `language_id`, `login`, `password`, `newsletter`, `details`, `oldId`, `created`, `updated`, `primaryLocation_id`, `invoicingInformation`)
VALUES
	(1, NULL, 38, 'informacio@hotelstabarbara.com', '2a43a259eea6aeee27ecf8c895a29ee8b91a5548', 1, 'null', 10199, '2013-06-16 21:35:21', '2013-06-16 21:35:21', NULL, '{\"rule\":\"($n != 1)\",\"names\":[\"Singular\",\"0, Plural\"]}');
