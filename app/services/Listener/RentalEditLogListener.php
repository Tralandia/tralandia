<?php
/**
 * This file is part of the tralandia.
 * User: lukaskajanovic
 * Created at: 28/11/13 10:36
 */

namespace Listener;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Language;
use Doctrine\ORM\QueryBuilder;
use Entity\Location\Location;
use Entity\Rental\Rental;
use Environment\IEnvironmentFactory;
use Mail\ICompilerFactory;
use Nette;
use Doctrine\ORM\EntityManager;

class RentalEditLogListener extends Nette\Object implements \Kdyby\Events\Subscriber
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @param Rental $rental
	 *
	 * @return \Entity\Rental\EditLog
	 */
	public function createLog(Rental $rental)
	{
		$editLogDao = $this->em->getRepository(EDIT_LOG_ENTITY);

		$d = new \Nette\DateTime();

		/** @var $qb QueryBuilder */
		$qb = $editLogDao->createQueryBuilder('e')
			->where('e.rental = ?1')->setParameter(1, $rental->getId())
			->andWhere('e.created >= ?2')->setParameter(2, $d->modifyClone('first day of this month'), \Doctrine\DBAL\Types\Type::DATETIME)
			->andWhere('e.created <= ?3')->setParameter(3, $d->modifyClone('last day of this month'), \Doctrine\DBAL\Types\Type::DATETIME)
			->setMaxResults(1);

		$count = (new Paginator($qb))->count();

		if(!$count) {
			/** @var $log \Entity\Rental\EditLog */
			$log = $editLogDao->createNew();
			$log->setRental($rental);

			$this->em->persist($log);
			$this->em->flush($log);

			$rental->setLastUpdate(new \DateTime());
			$this->em->flush($rental);
		}
	}

	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			'FormHandler\RentalEditHandler::onSuccess' => 'createLog',
		];
	}
}

