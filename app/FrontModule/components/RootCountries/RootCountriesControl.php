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
		$presenter = $this->getPresenter();

		$template->rentalCounts = $this->rentalRepository->getCounts(NULL, TRUE);
		$template->countries = $this->getCountries();

		$template->render();
	}

	private function getCountries()
	{
		return $this->locationRepository->getCountriesOrdered(
			$this->translator,
			$this->environment->getLocale()->getCollator()
		);
	}

}
