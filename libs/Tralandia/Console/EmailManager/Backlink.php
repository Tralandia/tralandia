<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 16/12/13 16:27
 */

namespace Tralandia\Console\EmailManager;


use Environment\Environment;
use Listener\BacklinkEmailListener;
use Nette;
use Tralandia\BaseDao;

class Backlink extends EmailManager
{

	const NAME = 'em_backlink';

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var BacklinkEmailListener
	 */
	private $emailListener;


	/**
	 * @param BaseDao $rentalDao
	 * @param BacklinkEmailListener $emailListener
	 */
	public function __construct(BaseDao $rentalDao, BacklinkEmailListener $emailListener)
	{
		$this->rentalDao = $rentalDao;
		$this->emailListener = $emailListener;
	}


	public function next()
	{
		$qb = $this->rentalDao->createQueryBuilder('r');
		$qb->leftJoin('r.backLinks', 'bl')
			->where($qb->expr()->eq('r.backlinkEmailSent', ':backlinkEmailSent'))->setParameter('backlinkEmailSent', FALSE)
			->andWhere($qb->expr()->isNull('bl.id'))
			->andWhere($qb->expr()->isNotNull('r.lastUpdate'))
			->andWhere($qb->expr()->isNotNull('r.url'))
			->andWhere($qb->expr()->gte('r.rank', ':minRank'))->setParameter('minRank', 50)
			->setMaxResults(1);

		/** @var $rental \Entity\Rental\Rental */
		$rental = $qb->getQuery()->getOneOrNullResult();

		$this->rental = $rental;
	}

	public function getEmail()
	{
		return $this->rental->getContactEmail();
	}

	public function getRowId()
	{
		return $this->rental->getId();
	}

	public function resetEnvironment(Environment $environment)
	{
		$user = $this->rental->getUser();
		$environment->resetTo($user->getPrimaryLocation(), $user->getLanguage());
	}

	public function send()
	{
		$this->emailListener->onSuccess($this->rental);
		$this->markAsSent();
	}

	public function wrongEmail()
	{
		$this->markAsSent();
	}

	private function markAsSent()
	{
		$this->rental->backlinkEmailSent = TRUE;
		$this->rentalDao->save($this->rental);
	}

	public function resetManager()
	{
		$qb = $this->rentalDao->createQueryBuilder();

		$qb->update(RENTAL_ENTITY, 'r')
			->set('r.backlinkEmailSent', ':backlinkEmailSent')->setParameter('backlinkEmailSent', FALSE);

		$qb->getQuery()->execute();
	}
}
