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
	 * @var \Extras\Translator
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

	public function __construct(\Extras\Translator $translator, Environment $environment, EntityManager $em)
	{
		parent::__construct();
		$this->environment = $environment;
		$this->translator = $translator;
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->rentalRepository = $em->getRepository(RENTAL_ENTITY);
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
				$countries[$us->getId()]['children'][$country['entity']->getId()] = $country;
				unset($countries[$key]);
			} else if($country['entity']->getParent()->getId() == $ca->getId()) {
				$countries[$ca->getId()]['entity'] = $us;
				$countries[$ca->getId()]['children'][$country['entity']->getId()] = $country;
				unset($countries[$key]);
			} else if($country['entity']->getParent()->getId() == $au->getId()) {
				$countries[$au->getId()]['entity'] = $us;
				$countries[$au->getId()]['children'][$country['entity']->getId()] = $country;
				unset($countries[$key]);
			}
		}

		return $countries;
	}

}
