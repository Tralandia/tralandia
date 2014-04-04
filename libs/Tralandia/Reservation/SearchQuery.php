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

	/**
	 * @var null
	 */
	private $status;


	public function __construct(array $rentals, $status = NULL, $fulltext = NULL)
	{
		parent::__construct();
		$this->rentals = $rentals;
		$this->status = $status;
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

		if($this->status) {
			$qb->andWhere('e.status = :status')->setParameter('status', $this->status);
		}

		if($this->fulltext) {
			$qb->andWhere($qb->expr()->like('e.senderEmail', ':containFulltext'));
			$qb->andWhere($qb->expr()->like('e.senderName', ':containFulltext'));
			$qb->setParameter('containFulltext', "%{$this->fulltext}%");
		}

		$qb->orderBy('e.created', 'DESC');

		return $qb;

	}

}
