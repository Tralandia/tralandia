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
	private $fulltext;

	/**
	 * @var null
	 */
	private $period;

	/**
	 * @var \Extras\Books\Phone
	 */
	private $phoneBook;


	public function __construct(array $rentals, $period = NULL, $fulltext = NULL, \Extras\Books\Phone $phoneBook)
	{
		parent::__construct();
		$this->rentals = $rentals;
		$this->period = $period;
		$this->fulltext = $fulltext;
		$this->phoneBook = $phoneBook;
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
			$fulltextOr = $qb->expr()->orX();
			$fulltextOr->add($qb->expr()->like('e.senderEmail', ':containFulltext'));
			$fulltextOr->add($qb->expr()->like('e.senderName', ':containFulltext'));
			$fulltextOr->add($qb->expr()->like('e.message', ':containFulltext'));
			$fulltextOr->add($qb->expr()->like('e.ownersNote', ':containFulltext'));
			$qb->setParameter('containFulltext', "%{$this->fulltext}%");

			$number = str_replace(['+', ' ', '(', ')', '/'], NULL, $this->fulltext);
			if(is_numeric($number)) {
				$phone = $this->phoneBook->find($number);
				if($phone) {
					$fulltextOr->add($qb->expr()->eq('e.senderPhone', ':senderPhone'));
					$qb->setParameter('senderPhone', $phone);
				}
			}

			$qb->andWhere($fulltextOr);
		}

		$qb->orderBy('e.arrivalDate', 'ASC');

		return $qb;

	}

}

interface ISearchQueryFactory
{
	public function create(array $rentals, $period = NULL, $fulltext = NULL);
}