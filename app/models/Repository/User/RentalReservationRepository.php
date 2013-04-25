<?php
namespace Repository\User;

use Entity\Rental\Rental;

/**
 * RentalReservationRepository class
 *
 * @author Dávid Ďurika
 */
class RentalReservationRepository extends \Repository\BaseRepository {

	public function findByRental(Rental $rental)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from($this->_entityName, 'e')
			->andWhere($qb->expr()->eq('e.rental', ':rental'))
			->setParameter('rental', $rental);

		return $qb->getQuery()->getResult();

	}


}