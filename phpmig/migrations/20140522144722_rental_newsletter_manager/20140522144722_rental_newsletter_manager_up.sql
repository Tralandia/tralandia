-- RentalNewsletterManager migration UP file


ALTER TABLE rental ADD newsletterSent TINYINT(1) NOT NULL;
