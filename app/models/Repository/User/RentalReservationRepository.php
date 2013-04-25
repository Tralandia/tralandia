<?php
namespace Repository\User;

use Entity\Rental\Rental;
use Entity\User\User;

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

	public function getReservationsCountByUser(User $user)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('count(e) as total')
			->from($this->_entityName, 'e');

		$rentals = $user->getRentals();
		foreach ($rentals as $rental) {
			$qb->orWhere($qb->expr()->eq('e.rental', ':rental'))
				->setParameter('rental', $rental);
		}

		return $qb->getQuery()->getResult()[0]['total'];
	}


}