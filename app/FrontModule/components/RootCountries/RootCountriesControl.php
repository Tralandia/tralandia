<?php
namespace FrontModule\Components\RootCountries;

use Environment\Environment;
use Doctrine\ORM\EntityManager;

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
	 * @var \Repository\Rental\RentalRepository
	 */
	protected $rentalRepository;

	/**
	 * @var \ResultSorter
	 */
	private $resultSorter;


	public function __construct(\Tralandia\Localization\Translator $translator, Environment $environment,
								EntityManager $em, \ResultSorter $resultSorter)
	{
		parent::__construct();
		$this->environment = $environment;
		$this->translator = $translator;
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->rentalRepository = $em->getRepository(RENTAL_ENTITY);
		$this->resultSorter = $resultSorter;
	}

	public function render()
	{
		$template = $this->template;

		$template->rentalCounts = $rentalCounts = $this->rentalRepository->getCounts(NULL, TRUE);
		$template->countries = $this->getCountries();

		$template->render();
	}

	private function getCountries()
	{
		$countries =  $this->locationRepository->getCountriesOrdered(
			$this->translator,
			$this->environment->getLocale()->getCollator()
		);

		$us = $this->locationRepository->findOneByIso('us');
		$ca = $this->locationRepository->findOneByIso('ca');
		$au = $this->locationRepository->findOneByIso('au');
		foreach($countries as $key => $country) {
			if($country['entity']->getParent()->getId() == $us->getId()) {
				$countries[$us->getId()]['entity'] = $us;
				$countries[$us->getId()]['name'] = $this->translator->translate($us->getName());
				$countries[$us->getId()]['children'][$country['entity']->getId()] = $country;
				unset($countries[$key]);
			} else if($country['entity']->getParent()->getId() == $ca->getId()) {
				$countries[$ca->getId()]['entity'] = $ca;
				$countries[$ca->getId()]['name'] = $this->translator->translate($ca->getName());
				$countries[$ca->getId()]['children'][$country['entity']->getId()] = $country;
				unset($countries[$key]);
			} else if($country['entity']->getParent()->getId() == $au->getId()) {
				$countries[$au->getId()]['entity'] = $au;
				$countries[$au->getId()]['name'] = $this->translator->translate($au->getName());
				$countries[$au->getId()]['children'][$country['entity']->getId()] = $country;
				unset($countries[$key]);
			}
		}

		$countries = $this->resultSorter->translateAndSort($countries, function($v){return $v['name'];});

		return $countries;
	}

}
