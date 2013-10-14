<?php
namespace FrontModule\Components\RootCountries;

use Environment\Environment;
use Doctrine\ORM\EntityManager;
use Tralandia\Location\Countries;
use Tralandia\Rental\Rentals;

class RootCountriesControl extends \BaseModule\Components\BaseControl {

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Tralandia\Localization\Translator
	 */
	protected $translator;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;


	/**
	 * @var \ResultSorter
	 */
	private $resultSorter;

	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;


	public function __construct(\Tralandia\Localization\Translator $translator, Environment $environment,
								Rentals $rentals, Countries $countries,
								EntityManager $em, \ResultSorter $resultSorter)
	{
		parent::__construct();
		$this->environment = $environment;
		$this->translator = $translator;
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->resultSorter = $resultSorter;
		$this->rentals = $rentals;
		$this->countries = $countries;
	}

	public function render()
	{
		$template = $this->template;

		$template->rentalCounts = $rentalCounts = $this->rentals->getCounts(NULL, TRUE);
		$template->countries = $this->getCountries();

		$template->render();
	}

	private function getCountries()
	{
		$countries =  $this->countries->findAll();

		$us = $this->locationRepository->findOneByIso('us');
		$ca = $this->locationRepository->findOneByIso('ca');
		$au = $this->locationRepository->findOneByIso('au');
		$countriesTemp = [];
		foreach($countries as $key => $country) {
			if($country->getParent()->getId() == $us->getId()) {
				$countriesTemp[$us->getId()]['entity'] = $us;
				$countriesTemp[$us->getId()]['children'][$country->getId()] = [
					'entity' => $country,
				];
			} else if($country->getParent()->getId() == $ca->getId()) {
				$countriesTemp[$ca->getId()]['entity'] = $ca;
				$countriesTemp[$ca->getId()]['children'][$country->getId()] = [
					'entity' => $country,
				];
			} else if($country->getParent()->getId() == $au->getId()) {
				$countriesTemp[$au->getId()]['entity'] = $au;
				$countriesTemp[$au->getId()]['children'][$country->getId()] = [
					'entity' => $country,
				];
			} else {
				$countriesTemp[$country->getId()] = [
					'entity' => $country,
				];
			}
		}

		$countries = $this->resultSorter->translateAndSort($countriesTemp, function($v) {return $v['entity']->getName();});

		return $countries;
	}

}
