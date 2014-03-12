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
	 * @autowire
	 * @var \Tralandia\Rental\RentalDao
	 */
	protected $rentalDao;

	/**
	 * @var \Tralandia\Rental\Rental
	 */
	protected $currentRental;


	public function actionDefault($rentalSlug)
	{
		$rental = $this->getRental();
		$this->template->heading = $this->translate($rental->type->getNameId()) . ' ' . $rental;
		$this->template->rental = $rental;
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


	/**
	 * @return \Tralandia\Rental\Rental
	 */
	protected function getRental()
	{
		if(!$this->currentRental) {
			//$this->currentRental = $rentalDao->findOneBy(['slug', $this->getParameter('rentalSlug')]);
			$this->currentRental = $this->rentalDao->findOneBy(['id' => 21933]);
		}

		return $this->currentRental;
	}

}
