<?php

namespace FrontModule\Forms;

use Nette;
use Nette\Localization\ITranslator;
use Entity\Location\Location;
use Repository\Location\LocationRepository;
use Repository\LanguageRepository;

/**
 * RegistrationForm class
 *
 * @author Dávid Ďurika
 */
class RegistrationForm extends \FrontModule\Forms\BaseForm {

	/**
	 * @var \Entity\Location\Location
	 */
	private $country;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	private $locationRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	private $languageRepository;

	public function __construct(Location $country, LocationRepository $locationRepository, LanguageRepository $languageRepository, ITranslator $translator)
	{
		$this->country = $country;
		$this->locationRepository = $locationRepository;
		$this->languageRepository = $languageRepository;
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->locationRepository->getCountriesForSelect();
		$languages = $this->languageRepository->getForSelect();

		$this->addSelect('country', 'Country', $countries);
		$this->addSelect('language', 'Language', $languages);
		$this->addText('referrer', 'Referrer');
		$this->addText('email', 'Email');

		$this->addSelect('legalForm', 'Legal Form');
		$this->addText('clientName', 'Client name');
		$this->addSelect('clientCountry', 'Client country', $countries);

		$this->onSuccess[] = callback($this, 'process');
	}

	public function setDefaultsValues()
	{
		$this->setDefaults([
			'country' => $this->country->id,
			'clientCountry' => $this->country->id,
		]);
	}

	public function process(ReservationForm $form)
	{
		$values = $form->getValues();
	}

}