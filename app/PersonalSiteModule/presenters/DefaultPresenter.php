<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 12:56
 */

namespace PersonalSiteModule;


use Nette;
use PersonalSiteModule\Amenities\IAmenitiesControlFactory;
use PersonalSiteModule\Gallery\IGalleryControlFactory;
use PersonalSiteModule\Prices\IPricesControlFactory;
use PersonalSiteModule\WelcomeScreen\IWelcomeScreenControlFactory;

class DefaultPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \PersonalSite\IRentalDataFactory
	 */
	protected $rentalDataFactory;

	/**
	 * @var \PersonalSite\RentalData
	 */
	protected $currentRental;


	public function actionDefault($rentalSlug)
	{
		$rental = $this->getRentalData();
		$this->template->heading = $rental->getType() . ' ' . $rental->getLocation();
		$this->template->mainPhoto = $rental->getMainPhoto();
	}


	protected function createComponentWelcomeScreen(IWelcomeScreenControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRentalData());
	}

	protected function createComponentAmenities(IAmenitiesControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRentalData());
	}

	protected function createComponentGallery(IGalleryControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRentalData());
	}

	protected function createComponentPrices(IPricesControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRentalData());
	}

	protected function getRentalData()
	{
		if(!$this->currentRental) {
			$this->currentRental = $this->rentalDataFactory->create($this->getParameter('rentalSlug'));
		}

		return $this->currentRental;
	}

}
