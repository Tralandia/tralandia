<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;

class RentalPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;

	/**
	 * @autowire
	 * @var \Extras\Cache\IRentalOrderCachingFactory
	 */
	protected $rentalOrderCachingFactory;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\Rental\IReservationFormFactory
	 */
	protected $reservationFormFactory;

	public function actionDetail($rental) {
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalDecoratorFactory->create($rental);
		$interviewAnswers = $rentalService->getInterviewAnswers($this->environment->primaryLocation->defaultLanguage);

		$locality = $rental->address->locality;
		$link = $this->link('//list', array('location' => $locality));
		$localitySeo = $this->seoFactory->create($link, $this->getLastCreatedRequest());

		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
		$this->template->locality = $localitySeo;
		$this->template->interviewAnswers = $interviewAnswers;

		$this->setLayout('detailLayout');
	}


	public function actionList($primaryLocation, $location, $rentalType) {

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);
		$orderCache = $this->rentalOrderCachingFactory->create($primaryLocation);

		if ($location) {
			$search->setLocationCriterium($location);
		}

		if ($rentalType) {
			$search->setRentalTypeCriterium($rentalType);
		}

		$vp = $this['p'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = \Service\Rental\RentalSearchService::COUNT_PER_PAGE;
		$paginator->itemCount = $search->getRentalsCount();

		$this->template->totalResultsCount = $paginator->itemCount;

		$rentals = $search->getRentalsIds($paginator->getPage());//@todo


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
		d($id);
		$rental = $this->rentalRepositoryAccessor->get()->find($id);
		return $this->rentalDecoratorFactory->create($rental);
	}

	//
	// COMPONENTS
	// 


	protected function createComponentReservationForm()
	{
		$form = $this->reservationFormFactory->create($this->getParameter('rental'));
		//$form->buildForm();
	
		$form->onSuccess[] = function ($form) { 
			if ($form->valid) $form->presenter->redirect('this'); 
		};
	
		return $form;
	}

	protected function createComponentP() {
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';
		return $vp;
	}
}
