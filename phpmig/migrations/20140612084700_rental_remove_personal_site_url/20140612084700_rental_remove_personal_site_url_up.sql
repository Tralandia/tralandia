-- RentalRemovePersonalSiteUrl migration UP file


DROP INDEX personalSiteUrl ON rental;
ALTER TABLE rental DROP personalSiteUrl;
