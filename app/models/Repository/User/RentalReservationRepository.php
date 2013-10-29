<?php
namespace Repository\User;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Rental\Rental;
use Entity\User\User;

/**
 * RentalReservationRepository class
 *
 * @author Dávid Ďurika
 */
class RentalReservationRepository extends \Repository\BaseRepository {

	/**
	 * @param User $user
	 *
	 * @return int
	 */
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


	/**
	 * @param Rental $rental
	 *
	 * @return int|number
	 */
	public function getCountForRental(Rental $rental)
	{
		$qb = $this->findForRentalQb($rental);

		$paginator = new Paginator($qb);
		return $paginator->count();
	}


	/**
	 * @param Rental $rental
	 * @param $limit
	 * @param $offset
	 *
	 * @return array
	 */
	public function findForRental(Rental $rental, $limit, $offset)
	{
		$qb = $this->findForRentalQb($rental);
		$qb->orderBy('e.created', 'DESC')
			->setMaxResults($limit)
			->setFirstResult($offset);

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param Rental $rental
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function findForRentalQb(Rental $rental)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from($this->_entityName, 'e');

		$qb->andWhere($qb->expr()->eq('e.rental', ':rental'))
			->setParameter('rental', $rental);

		return $qb;
	}


}
