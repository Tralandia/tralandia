-- RentalReview migration UP file


ALTER TABLE user_rentalreview DROP FOREIGN KEY FK_E85C9FB73BBB662B;
DROP INDEX IDX_E85C9FB73BBB662B ON user_rentalreview;
ALTER TABLE user_rentalreview ADD senderFirstName VARCHAR(255) NOT NULL, ADD senderLastName VARCHAR(255) DEFAULT NULL, ADD `group` VARCHAR(255) NOT NULL, ADD messageNegative LONGTEXT NOT NULL, ADD messageLocality LONGTEXT NOT NULL, ADD messageRegion LONGTEXT NOT NULL, ADD pointsLocation INT NOT NULL, ADD pointsCleanness INT NOT NULL, ADD pointsAmenities INT NOT NULL, ADD pointsServices INT NOT NULL, ADD pointsAttractions INT NOT NULL, ADD pointsPrice INT NOT NULL, DROP senderName, DROP message, DROP senderPhone_id, CHANGE capacity messagePros LONGTEXT NOT NULL;

ALTER TABLE user_rentalreview ADD status INT NOT NULL;
ALTER TABLE user_rentalreview ADD pointsPersonal INT NOT NULL;
