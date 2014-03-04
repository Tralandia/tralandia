<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 16/12/13 16:27
 */

namespace Tralandia\Console\EmailManager;


use Environment\Environment;
use Listener\NotificationEmailListener;
use Listener\PotentialMemberEmailListener;
use Nette;
use Tralandia\BaseDao;

class PotentialMember extends EmailManager
{

	const NAME = 'em_potential-member';

	/**
	 * @var \Entity\Contact\PotentialMember
	 */
	private $potentialMember;

	/**
	 * @var \Listener\PotentialMemberEmailListener
	 */
	private $emailListener;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $potentialMemberDao;


	/**
	 * @param \Tralandia\BaseDao $potentialMemberDao
	 * @param \Listener\PotentialMemberEmailListener $emailListener
	 */
	public function __construct(BaseDao $potentialMemberDao, PotentialMemberEmailListener $emailListener)
	{
		$this->potentialMemberDao = $potentialMemberDao;
		$this->emailListener = $emailListener;
	}


	public function next()
	{
		$excludedCountries = [
			193, // DE
			232, // AT
		];

		// select count(*) from contact_potentialMember where unsubscribed = 0 and (emailSent = 0 or emailSent is null)
		$qb = $this->potentialMemberDao->createQueryBuilder('m');
		$qb->where($qb->expr()->eq('m.emailSent', ':emailSent'))->setParameter('emailSent', FALSE)
			->andWhere($qb->expr()->eq('m.unsubscribed', ':unsubscribed'))->setParameter('unsubscribed', FALSE)
			->andWhere($qb->expr()->notIn('m.primaryLocation', $excludedCountries))
			->setMaxResults(1);

		$this->potentialMember = $qb->getQuery()->getOneOrNullResult();
	}

	public function getEmail()
	{
		return $this->potentialMember->getEmail();
	}

	public function getRowId()
	{
		return $this->potentialMember->getId();
	}

	public function resetEnvironment(Environment $environment)
	{
		$environment->resetTo($this->potentialMember->getPrimaryLocation(), $this->potentialMember->getLanguage());
	}

	public function send()
	{
		$this->emailListener->onSuccess($this->potentialMember);
		$this->markAsSent();
	}

	public function wrongEmail()
	{
		$this->markAsSent();
	}

	private function markAsSent()
	{
		$this->potentialMember->emailSent = TRUE;
		$this->potentialMemberDao->save($this->potentialMember);
	}

	public function resetManager()
	{
		$qb = $this->potentialMemberDao->createQueryBuilder();

		$qb->update(POTENTIAL_MEMBER, 'm')
			->set('m.emailSent', ':emailSent')->setParameter('emailSent', FALSE);

		$qb->getQuery()->execute();
	}
}
