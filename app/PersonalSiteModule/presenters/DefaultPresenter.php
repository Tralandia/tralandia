<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 12:56
 */

namespace PersonalSiteModule;


use Nette;
use PersonalSiteModule\Amenities\IAmenitiesControlFactory;
use PersonalSiteModule\Calendar\ICalendarControlFactory;
use PersonalSiteModule\Contact\IContactControlFactory;
use PersonalSiteModule\Gallery\IGalleryControlFactory;
use PersonalSiteModule\Prices\IPricesControlFactory;
use PersonalSiteModule\WelcomeScreen\IWelcomeScreenControlFactory;
use Tralandia\Rental\Rental;

class DefaultPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\Rental\RentalRepository
	 */
	protected $rentalRepository;

	/**
	 * @var \Tralandia\Rental\Rental
	 */
	protected $currentRental;

	protected function startup()
	{
		parent::startup();

		\MyTimer::log('startup');
	}


	public function actionDefault($rentalSlug)
	{
		$rental = $this->getRental();
		$rentalNameId = $rental->type->getNameId();
		$locationNameId = $rental->address->locality->getNameId();
		$this->template->usedLanguages = $this->getUsedLanguages($rental);
		$this->template->heading = $this->translate($rentalNameId) . ' ' . $this->translate($locationNameId);
		$this->template->rental = $rental;
	}


	protected function createComponentWelcomeScreen(IWelcomeScreenControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRental());
	}

	protected function createComponentAmenities(IAmenitiesControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRental());
	}

	protected function createComponentGallery(IGalleryControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRental());
	}

	protected function createComponentPrices(IPricesControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRental());
	}

	protected function createComponentCalendar(ICalendarControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRental());
	}

	protected function createComponentContact(IContactControlFactory $controlFactory)
	{
		return $controlFactory->create($this->getRental());
	}


	/**
	 * @return \Tralandia\Rental\Rental
	 */
	protected function getRental()
	{
		if(!$this->currentRental) {
			//$this->currentRental = $rentalDao->findOneBy(['slug', $this->getParameter('rentalSlug')]);
			$this->currentRental = $this->rentalRepository->findOneBy(['id' => 15729]);
		}

		return $this->currentRental;
	}


	protected function getUsedLanguages(Rental $rental)
	{
		$usedLanguages = [];
		$defaultLanguage = $rental->getPrimaryLocation()->defaultLanguage;
		$usedLanguages[$defaultLanguage->id] = $defaultLanguage;

		if($rental->isFirstQuestionAnswered()) {
			$answer = $rental->getFirstAnswer();
			foreach($answer->answer->translations as $translation) {
				$usedLanguages[$translation->language->id] = $translation->language;
			}
		}

		return $usedLanguages;
	}

}