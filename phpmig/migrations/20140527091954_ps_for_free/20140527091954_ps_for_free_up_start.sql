-- PsForFree migration UP file



ALTER TABLE `rental` DROP FOREIGN KEY `FK_1619C27D91BA7309`;
ALTER TABLE `rental` DROP INDEX `UNIQ_1619C27D91BA7309`;



insert into personalsite_configuration (url, template, created, updated) select concat(r.slug,'.',d.domain), 'second', '2014-05-19 13:59:34', '2014-05-19 13:59:34' from rental r
inner join contact_address a on a.id = r.address_id
inner join location l on l.id = a.primaryLocation_id
inner join domain d on d.id = l.domain_id
where r.personalSiteUrl is null or r.personalSiteUrl = '';


update rental
inner join contact_address a on a.id = rental.address_id
inner join location l on l.id = a.primaryLocation_id
inner join domain d on d.id = l.domain_id
inner join personalsite_configuration c on c.url = concat(rental.slug,'.',d.domain)
set personalSiteConfiguration_id = c.id;
