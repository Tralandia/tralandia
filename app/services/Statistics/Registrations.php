<?php

namespace Statistics;


use Doctrine\ORM\EntityManager;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class Registrations implements IDataSource {

	/**
	 * @var Service\Statistics\RentalRegistrations
	 */
	protected $rentalRegistrationsStats;

	public function __construct(\Service\Statistics\RentalRegistrations $rentalRegistrationsStats)
	{
		$this->rentalRegistrationsStats = $rentalRegistrationsStats;
	}

	public function getData($filter, $order, Paginator $paginator = NULL) {
		return \Nette\ArrayHash::from($this->rentalRegistrationsStats->getData());
	}
}
