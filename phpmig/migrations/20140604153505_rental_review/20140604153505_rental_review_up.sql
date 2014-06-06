-- RentalReview migration UP file


ALTER TABLE user_rentalreview DROP FOREIGN KEY FK_E85C9FB73BBB662B;
DROP INDEX IDX_E85C9FB73BBB662B ON user_rentalreview;
ALTER TABLE user_rentalreview ADD senderFirstName VARCHAR(255) NOT NULL, ADD senderLastName VARCHAR(255) DEFAULT NULL, ADD `group` VARCHAR(255) NOT NULL, ADD messageNegative LONGTEXT NOT NULL, ADD messageLocality LONGTEXT NOT NULL, ADD messageRegion LONGTEXT NOT NULL, ADD pointsLocation INT NOT NULL, ADD pointsCleanness INT NOT NULL, ADD pointsAmenities INT NOT NULL, ADD pointsServices INT NOT NULL, ADD pointsAttractions INT NOT NULL, ADD pointsPrice INT NOT NULL, DROP senderName, DROP message, DROP senderPhone_id, CHANGE capacity messagePros LONGTEXT NOT NULL;

ALTER TABLE user_rentalreview ADD status INT NOT NULL;
ALTER TABLE user_rentalreview ADD pointsPersonal INT NOT NULL;

ALTER TABLE user_rentalreview ADD messagePositives LONGTEXT NOT NULL, ADD messageNegatives LONGTEXT NOT NULL, ADD ratingLocation INT NOT NULL, ADD ratingCleanness INT NOT NULL, ADD ratingAmenities INT NOT NULL, ADD ratingPersonal INT NOT NULL, ADD ratingServices INT NOT NULL, ADD ratingAttractions INT NOT NULL, ADD ratingPrice INT NOT NULL, DROP messagePros, DROP messageNegative, DROP pointsLocation, DROP pointsCleanness, DROP pointsAmenities, DROP pointsServices, DROP pointsAttractions, DROP pointsPrice, DROP pointsPersonal;
