<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/10/13 8:42 AM
 */

namespace Tralandia\Reservation;


use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Rental\Rental;
use Entity\User\User;
use Nette;
use Tralandia\BaseDao;

class Reservations
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $reservationDao;


	/**
	 * @param BaseDao $reservationDao
	 */
	public function __construct(BaseDao $reservationDao)
	{
		$this->reservationDao = $reservationDao;
	}


	/**
	 * @param User $user
	 *
	 * @return int
	 */
	public function getReservationsCountByUser(User $user)
	{
		$qb = $this->reservationDao->createQueryBuilder('e');
		$qb->select('count(e) as total');

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
		$qb = $this->reservationDao->createQueryBuilder('e');

		$qb->leftJoin('e.units', 'u');

		$qb->andWhere($qb->expr()->orX(
			$qb->expr()->eq('e.rental', ':rental'),
			$qb->expr()->eq('u.rental', ':rental')
		))
			->setParameter('rental', $rental);

		return $qb;
	}



}
