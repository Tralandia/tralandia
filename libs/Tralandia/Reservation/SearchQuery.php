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


	public function __construct(array $rentals, $from = NULL, $to = NULL, $fulltext = NULL)
	{
		parent::__construct();
		$this->rentals = $rentals;
		$this->from = $from;
		$this->to = $to;
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

		$qb->orderBy('e.created', 'DESC');

		return $qb;

	}

}
