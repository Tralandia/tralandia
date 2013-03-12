<?php

use Nette\Http\Request;
use Repository\Rental\RentalRepository;

class FavoriteList extends \Nette\Object
{

	protected $rentalRepositoryAccessor;

	/**
	 * @var Nette\Http\Request
	 */
	protected $httpRequest;

	public function __construct($rentalRepositoryAccessor, Request $httpRequest)
	{
		$this->rentalRepositoryAccessor = $rentalRepositoryAccessor;
		$this->httpRequest = $httpRequest;
	}

	public function getRentalList()
	{
		$ids = $this->getRentalIds();

		if(count($ids)) {
			$rentals = $this->rentalRepositoryAccessor->get()->findById($ids);
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

	public function getVisitedRentals()
	{
		$list = $this->httpRequest->getCookie('favoritesVisitedList');
		return array_unique(array_filter(explode(',', $list)));
	}

	protected function getRentalIds()
	{
		$list = $this->httpRequest->getCookie('favoritesList');
		return array_unique(array_filter(explode(',', $list)));
	}

}