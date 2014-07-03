update user_rentalreservation r
left join _reservation_unit ru on ru.reservation_id = r.id
set r.rental_id = NULL
where r.rental_id is not null
and ru.unit_id is not null;
