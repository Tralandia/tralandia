<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;
use Nette\Utils\Strings;

/**
 * @persistent(searchBar)
 */
class RentalListPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \LastSearch
	 */
	protected $lastSearch;

	public function actionDefault($favoriteList) {

		if($favoriteList) {
			$rentals = $favoriteList->getRentals();
			$itemCount = $rentals->count();
		} else {
			$search = $this['searchBar']->getSearch();

			$itemCount = $search->getRentalsCount();

			$lastSearch = $this->lastSearch;
			$lastSearch->setRentals($search->getRentalsIds(NULL));
			$lastSearch->setUrl($this->pageSeo->getUrl());
			$lastSearch->setHeading($this->pageSeo->getH1());
		}

		$vp = $this['p'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = \Service\Rental\RentalSearchService::COUNT_PER_PAGE;
		$paginator->itemCount = $itemCount;

		$this->template->totalResultsCount = $paginator->itemCount;

		if(isset($search)) {
			$rentals = $search->getRentalsIds($paginator->getPage());
		}


		//d($rentalsEntities);
//		$rentals = array();
//		foreach ($rentalsEntities as $rental) {
//			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
//			$rentals[$rental->id]['entity'] = $rental;
//			$rentals[$rental->id]['featured'] = $orderCache->isFeatured($rental);
//		}

		$this->template->rentals = $rentals;
		$this->template->findRental = array($this, 'findRental');
	}

	public function findRental($id)
	{
		//d($id);
		if($id instanceof \Entity\Rental\Rental) {
			$rental = $id;
		} else {
			$rental = $this->rentalRepositoryAccessor->get()->find($id);
		}

		return $this->rentalDecoratorFactory->create($rental);
	}

	protected function createComponentP() {
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';
		return $vp;
	}

}
