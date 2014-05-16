-- RentalPriceFromTo migration UP file


ALTER TABLE `rental` ADD `priceTo` INT(11)  NULL  DEFAULT NULL  AFTER `price`;
ALTER TABLE `rental` CHANGE `price` `priceFrom` INT(11)  NULL  DEFAULT NULL;


update rental set priceTo = priceFrom;
