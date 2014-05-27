-- PsForFree migration UP file


update rental
inner join contact_address a on a.id = rental.address_id
inner join location l on l.id = a.primaryLocation_id
inner join domain d on d.id = l.domain_id
inner join personalsite_configuration c on c.url = concat(rental.id,'.',d.domain)
set personalSiteConfiguration_id = c.id;

update rental
inner join contact_address a on a.id = rental.address_id
inner join location l on l.id = a.primaryLocation_id
inner join domain d on d.id = l.domain_id
inner join personalsite_configuration c on c.url = concat(rental.slug,'.',d.domain)
set personalSiteConfiguration_id = c.id
where personalSiteConfiguration_id is null;
