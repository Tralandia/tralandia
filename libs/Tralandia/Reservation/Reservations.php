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
	 * @param User $user
	 * @param null $status
	 * @param \DateTime $departureFrom
	 *
	 * @return \Entity\User\RentalReservation[]
	 */
	public function getUsersReservations(User $user, $status = NULL,\DateTime $departureFrom = NULL)
	{
		$query = 'select r.id from ' . RENTAL_ENTITY . ' r where r.user = :user';
		$query = $this->reservationDao->createQuery($query);
		$query->setParameter('user', $user);
		$rentals = $query->getArrayResult();

		$rentalsIds = \Tools::arrayMap($rentals, 'id');

		$query = 'select u.id from ' . UNIT_ENTITY . ' u where u.rental IN (:rentals)';
		$query = $this->reservationDao->createQuery($query);
		$query->setParameter('rentals', array_values($rentalsIds));
		$units = $query->getArrayResult();

		$unitsIds = \Tools::arrayMap($units, 'id');

		$qb = $this->reservationDao->createQueryBuilder('e');
		$qb->innerJoin('e.units', 'u');
		$qb->andWhere($qb->expr()->orX(
			$qb->expr()->in('e.rental', $rentalsIds),
			$qb->expr()->in('u.id', $unitsIds)
		));

		if($status) {
			$qb->andWhere($qb->expr()->eq('e.status', ':status'))->setParameter('status', $status);
		}

		if($departureFrom) {
			$qb->andWhere($qb->expr()->gte('e.departureDate', ':departureDate'))->setParameter('departureDate', $departureFrom);
		}

		return $qb->getQuery()->getResult();
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
