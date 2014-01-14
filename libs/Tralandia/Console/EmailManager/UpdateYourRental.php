<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 16/12/13 16:27
 */

namespace Tralandia\Console\EmailManager;


use Environment\Environment;
use Listener\NotificationEmailListener;
use Nette;
use Tralandia\BaseDao;

class UpdateYourRental extends EmailManager
{

	const NAME = 'em_update-your-rental';

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \Listener\NotificationEmailListener
	 */
	private $emailListener;


	/**
	 * @param BaseDao $rentalDao
	 */
	public function __construct(BaseDao $rentalDao, NotificationEmailListener $emailListener)
	{
		$this->rentalDao = $rentalDao;
		$this->emailListener = $emailListener;
	}


	public function next()
	{
		$qb = $this->rentalDao->createQueryBuilder('r');
		$qb->where($qb->expr()->eq('r.emailSent', ':emailSent'))->setParameter('emailSent', FALSE)
			->andWhere($qb->expr()->lt('r.rank', ':requiredRank'))->setParameter('requiredRank', 75)
//			->andWhere($qb->expr()->eq('r.harvested', ':harvestedOnly'))->setParameter('harvestedOnly', TRUE)
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
		$this->rental->emailSent = TRUE;
		$this->rentalDao->save($this->rental);
	}

	public function resetManager()
	{
		$qb = $this->rentalDao->createQueryBuilder();

		$qb->update(RENTAL_ENTITY, 'r')
			->set('r.emailSent', ':emailSent')->setParameter('emailSent', FALSE);

		$qb->getQuery()->execute();
	}
}
