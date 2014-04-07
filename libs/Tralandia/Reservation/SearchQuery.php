<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 01/04/14 13:25
 */

namespace Tralandia\Reservation;


use Kdyby\Doctrine\QueryObject;
use Kdyby;
use Nette;

class SearchQuery extends QueryObject
{

	const PERIOD_PAST = 'past';
	const PERIOD_PRESENT = 'present';
	const PERIOD_FUTURE = 'future';

	/**
	 * @var array
	 */
	private $rentals;

	/**
	 * @var null
	 */
	private $from;

	/**
	 * @var null
	 */
	private $to;

	/**
	 * @var null
	 */
	private $fulltext;

	/**
	 * @var null
	 */
	private $period;


	public function __construct(array $rentals, $period = NULL, $fulltext = NULL)
	{
		parent::__construct();
		$this->rentals = $rentals;
		$this->period = $period;
		$this->fulltext = $fulltext;
	}


	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 *
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$qb = $repository->createQueryBuilder('e');

		$qb->leftJoin('e.units', 'u');

		$qb->andWhere($qb->expr()->orX(
			$qb->expr()->in('e.rental', ':rentals'),
			$qb->expr()->in('u.rental', ':rentals')
		))
			->setParameter('rentals', $this->rentals);

		if($this->period) {
			$today = Nette\DateTime::from(strtotime('today'));
			if($this->period == self::PERIOD_PAST) {
				$qb->andWhere('e.departureDate < :departureDate')->setParameter('departureDate', $today);
			} else if($this->period == self::PERIOD_PRESENT) {
				$qb->andWhere('e.arrivalDate <= :arrivalDate')->setParameter('arrivalDate', $today);
				$qb->andWhere('e.departureDate >= :departureDate')->setParameter('departureDate', $today);
			} else if($this->period == self::PERIOD_FUTURE) {
				$qb->andWhere('e.arrivalDate > :arrivalDate')->setParameter('arrivalDate', $today);
			}
		}

		if($this->fulltext) {
			$qb->andWhere($qb->expr()->like('e.senderEmail', ':containFulltext'));
			$qb->andWhere($qb->expr()->like('e.senderName', ':containFulltext'));
			$qb->setParameter('containFulltext', "%{$this->fulltext}%");
		}

		$qb->orderBy('e.arrivalDate', 'ASC');

		return $qb;

	}

}
