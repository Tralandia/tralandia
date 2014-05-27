-- PsUnsMembership migration UP file


insert into rental_service (rental_id, givenFor, serviceType, dateFrom, dateTo, created, updated)
select r.id, 'Membership', 'premium-ps', '2014-01-01 00:00:00', '2111-11-11 11:11:11', '2014-01-01 00:00:00', '2014-01-01 00:00:00'
from rental r inner join contact_address a on a.id = r.address_id
where a.primaryLocation_id = 52 and r.personalSiteUrl is not NULL and r.personalSiteUrl != '';
