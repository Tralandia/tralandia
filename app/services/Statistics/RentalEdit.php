<?php

namespace Statistics;


use DataSource\IDataSource;
use Doctrine\ORM\EntityManager;
use Nette\ArrayHash;
use Nette\Utils\Paginator;

class RentalEdit implements IDataSource {

	/**
	 * @var \Service\Statistics\RentalEdit
	 */
	protected $rentalEditStats;

	public function __construct(\Service\Statistics\RentalEdit $rentalEditStats)
	{
		$this->rentalEditStats = $rentalEditStats;
	}

	public function getData($filter, $order, Paginator $paginator = NULL) {
		return \Nette\ArrayHash::from($this->rentalEditStats->getData());
	}
}
