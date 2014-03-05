<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 12:56
 */

namespace PersonalSiteModule;


use Nette;
use PersonalSiteModule\WelcomeScreen\IWelcomeScreenControlFactory;

class DefaultPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\PersonalSite\IRentalDataFactory
	 */
	protected $rentalDataFactory;

	/**
	 * @var \Tralandia\PersonalSite\RentalData
	 */
	protected $currentRental;


	public function actionDefault($rentalSlug)
	{

	}


	protected function createComponentWelcomeScreen(IWelcomeScreenControlFactory $controlFactory)
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
