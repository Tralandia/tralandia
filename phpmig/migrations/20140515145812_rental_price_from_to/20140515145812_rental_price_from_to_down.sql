-- RentalPriceFromTo migration DOWN file


/* 15:00:31 _Tralandia */ ALTER TABLE `rental` CHANGE `priceFrom` `price` INT(11)  NULL  DEFAULT NULL;
/* 15:00:40 _Tralandia */ ALTER TABLE `rental` DROP `priceTo`;
