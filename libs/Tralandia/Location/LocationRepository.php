<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 12/03/14 09:51
 */

namespace Tralandia\Location;


use Nette;
use Tralandia\Lean\BaseRepository;

/**
 * Class UnitRepository
 * @package Tralandia\Rental
 *
 * @method save(\Tralandia\Location\Location $entity)
 * @method \Tralandia\Location\Location createNew()
 * @method \Tralandia\Location\Location find()
 * @method \Tralandia\Location\Location findOneBy()
 * @method \Tralandia\Location\Location[] findBy()
 * @method \Tralandia\Location\Location[] findAll()
 */
class LocationRepository extends BaseRepository
{

	public function findTopLocationsForSearch($rentalsIds, $maxResults = NULL)
	{
		$rentalsIds = array_values($rentalsIds);
		$result = $this->connection->query('(select l.id, count(*) c from location l
inner join _location_address la on la.location_id = l.id
inner join contact_address a on a.id = la.address_id
inner join rental r on r.address_id = a.id
where r.id IN %in', $rentalsIds, '
group by l.id
order by c DESC
%if', $maxResults, 'limit %i',$maxResults,'%end)
union
(select l.id, count(*) c from location l
inner join contact_address a on a.locality_id = l.id
inner join rental r on r.address_id = a.id
where r.id IN %in', $rentalsIds, '
group by l.id
order by c DESC
%if', $maxResults, 'limit %i',$maxResults,'%end)
%if', $maxResults, 'limit %i',$maxResults,'%end')->fetchPairs('id', 'c');

		return $result;
	}
}
