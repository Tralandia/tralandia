<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 16/12/13 16:27
 */

namespace Tralandia\Console\EmailManager;


use Environment\Environment;
use Listener\BaseEmailListener;
use Nette;
use Tralandia\BaseDao;

class Newsletter extends EmailManager
{

	const NAME = 'em_newsletter';

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \Listener\PsOfferEmailListener
	 */
	private $emailListener;

	/**
	 * @var \Doctrine\ORM\Query
	 */
	protected $query = null;

	/**
	 * @var \Doctrine\ORM\Query
	 */
	protected $totalQuery = null;



	/**
	 * @param BaseDao $rentalDao
	 * @param \Tralandia\BaseDao $language
	 * @param \Listener\PsOfferEmailListener $emailListener
	 */
	public function __construct(BaseDao $rentalDao, BaseDao $languageDao, \Listener\PsOfferEmailListener $emailListener)
	{
		$this->rentalDao = $rentalDao;
		$this->emailListener = $emailListener;
		$this->en = $languageDao->find(38);
	}


	public function next()
	{
		$query = $this->getQuery();

		$query->setMaxResults(1);

		/** @var $rental \Entity\Rental\Rental */
		$rental = $query->getOneOrNullResult();

		$this->rental = $rental;
	}


	public function totalCount()
	{
		$query = $this->getTotalQuery();
		return (new \Doctrine\ORM\Tools\Pagination\Paginator($query))->count();
	}


	public function toSentCount()
	{
		$query = $this->getQuery();
		return (new \Doctrine\ORM\Tools\Pagination\Paginator($query))->count();
	}

	/**
	 * @return bool
	 */
	public function isEnd()
	{
		return !(bool) $this->rental;
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
		$this->rental->newsletterSent = TRUE;
		$this->rentalDao->save($this->rental);
	}

	public function resetManager()
	{
		$qb = $this->rentalDao->createQueryBuilder();

		$qb->update(RENTAL_ENTITY, 'r')
			->set('r.newsletterSent', ':newsletterSent')->setParameter('newsletterSent', FALSE);

		$qb->getQuery()->execute();
	}

	protected function getQuery()
	{
		if(!$this->query) {
			$qbTotal = $this->rentalDao->createQueryBuilder('r');

			$qbTotal->innerJoin('r.user', 'u')
				->andWhere($qbTotal->expr()->eq('u.newsletter', ':newsletter'))->setParameter('newsletter', TRUE)
				->andWhere($qbTotal->expr()->in('u.language', [62, 60]));

			$qb = clone $qbTotal;
			$qb->andWhere($qbTotal->expr()->eq('r.newsletterSent', ':newsletterSent'))->setParameter('newsletterSent', FALSE);

			$this->totalQuery = $qbTotal->getQuery();
			$this->query = $qb->getQuery();
		}

		return $this->query;
	}

	protected function getTotalQuery()
	{
		if(!$this->totalQuery) {
			$this->getQuery();
		}

		return $this->totalQuery;
	}
}
