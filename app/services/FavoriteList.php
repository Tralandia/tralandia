<?php

use Nette\Http\Request;
use Repository\Rental\RentalRepository;

class FavoriteList extends \Nette\Object
{

	/**
	 * @var Nette\Http\Request
	 */
	protected $httpRequest;

	/**
	 * @var Tralandia\BaseDao
	 */
	private $rentalDao;


	public function __construct(\Tralandia\BaseDao $rentalDao, Request $httpRequest)
	{
		$this->httpRequest = $httpRequest;
		$this->rentalDao = $rentalDao;
	}

	public function getRentalList()
	{
		$ids = $this->getRentalIds();

		if(count($ids)) {
			$rentals = $this->rentalDao->findById($ids);
		}

		if(isset($rentals)) {
			$sort = array_fill_keys($ids, NULL);
			foreach($rentals as $rental) {
				$id = $rental->getId();
				$sort[$id] = $rental;
			}
			$rentals = array_filter($sort);

			return $rentals;
		} else {
			return [];
		}
	}

	protected function getRentalIds()
	{
		$list = $this->httpRequest->getCookie('favoritesList');
		return array_unique(array_filter(explode(',', $list)));
	}

}
