<?php

namespace Statistics;


use Doctrine\ORM\EntityManager;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class Registrations implements IDataSource {
	
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var Service\Statistics\RentalRegistrations
	 */
	protected $rentalRegistrationsStats;

	public function __construct(EntityManager $em, \Service\Statistics\RentalRegistrations $rentalRegistrationsStats)
	{
		$this->em = $em;
		$this->rentalRegistrationsStats = $rentalRegistrationsStats;
	}

	public function getData($filter, $order, Paginator $paginator = NULL) {
		return \Nette\ArrayHash::from($this->rentalRegistrationsStats->getData());
	}
}
