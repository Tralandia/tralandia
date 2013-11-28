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
use Environment\IEnvironmentFactory;
use Mail\ICompilerFactory;
use Nette;
use Doctrine\ORM\EntityManager;

abstract class RentalEditLogListener extends Nette\Object implements \Kdyby\Events\Subscriber
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
		$$rental->getCreated();
		/** @var $qb QueryBuilder */
		$qb = $editLogDao->createQueryBuilder('r')
			->where('r.rental_id = ?1')->setParameter(1, $rental->getId())
			->andWhere('e.created >= ?2')->setParameter('2', $value['from'])
			->andWhere('e.created < ?1')->setParameter('1', $value['to']);

		$count = (new Paginator($qb))->count();

		/** @var $log \Entity\Rental\EditLog */
		$log = $editLogDao->createNew();
		$log->setRental($rental);

		$this->em->persist($log);
		$this->em->flush($log);
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

