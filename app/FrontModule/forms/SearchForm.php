<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Environment\Environment;
use Nette;
use Routers\FrontRoute;
use SearchGenerator\OptionGenerator;
use Service\Rental\RentalSearchService;

/**
 * SearchForm class
 *
 * @author Dávid Ďurika
 */
class SearchForm extends BaseForm
{

	/**
	 * @var array
	 */
	protected $defaults;

	/**
	 * @var \Service\Rental\RentalSearchService
	 */
	protected $search;

	/**
	 * @var \Nette\Application\UI\Presenter
	 */
	protected $presenter;

	/**
	 * @var OptionGenerator
	 */
	protected $searchOptionGenerator;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;


	/**
	 * @param array $defaults
	 * @param \Service\Rental\RentalSearchService $search
	 * @param \Nette\Application\UI\Presenter $presenter
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(array $defaults, RentalSearchService $search, Nette\Application\UI\Presenter $presenter,
								OptionGenerator $searchOptionGenerator, Environment $environment,
								EntityManager $em, Nette\Localization\ITranslator $translator)
	{
		$this->defaults = $defaults;
		$this->search = $search;
		$this->presenter = $presenter;
		$this->searchOptionGenerator = $searchOptionGenerator;
		$this->environment = $environment;
		$this->em = $em;
		parent::__construct($translator);
	}


	public function buildForm()
	{
		$countries = $this->searchOptionGenerator->generateCountries($this->presenter);

		$rentalTypes = $this->searchOptionGenerator->generateRentalType();
		/*placement $placement = $this->searchOptionGenerator->generatePlacement(); placement*/
		$prices = $this->searchOptionGenerator->generatePrice($this->environment->getCurrency());
		$capacity = $this->searchOptionGenerator->generateCapacity();
		$languages = $this->searchOptionGenerator->generateSpokenLanguage($this->environment->getPrimaryLocation());
		$boards = $this->searchOptionGenerator->generateBoard();

		$this->addSelect('country', 'o1070', $countries)
			->setPrompt('o1070')
			->setAttribute('data-placeholder', $this->translate('o1070'));

		$this->addHidden(FrontRoute::LOCATION)
			->setAttribute('data-placeholder', $this->translate('o1070'));

		$this->addSelect(FrontRoute::RENTAL_TYPE, 'o20926', $rentalTypes)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('151884'));

		/*placement
		$this->addSelect(FrontRoute::PLACEMENT, 'o100113', $placement)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('o100113'));
		placement*/

		$this->addSelect(FrontRoute::PRICE_FROM, 'o100093', [0 => '0 ' . $this->environment->getCurrency()->getIso()] + $prices)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('o100093'));

		$this->addSelect(FrontRoute::PRICE_TO, 'o100094', $prices)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('o100094'));

		$this->addSelect(FrontRoute::CAPACITY, 'o20928', $capacity)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('562'));

		$this->addSelect(FrontRoute::BOARD, 'o100080', $boards)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('o100080'));

		$this->addSelect(FrontRoute::SPOKEN_LANGUAGE, 'o20930', $languages)
			->setPrompt('')
			->setAttribute('data-placeholder', $this->translate('151885'));

		$this->addSubmit('submit', 'o100092', FALSE);
	}


	public function setDefaultsValues()
	{

	}


	public function getValues($asArray = FALSE)
	{
		$values = parent::getValues($asArray);

		if (isset($values->location)) {
			$locationRepository = $this->em->getRepository(LOCATION_ENTITY);
			$values->location = $locationRepository->find($values->location);
		}

		if (isset($values->rentalType)) {
			$rentalTypeRepository = $this->em->getRepository(RENTAL_TYPE_ENTITY);
			$values->rentalType = $rentalTypeRepository->find($values->rentalType);
		}

		/*placement
		if (isset($values->placement)) {
			$repository = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY);
			$values->placement = $repository->find($values->placement);
		}
		placement*/

		if (isset($values->spokenLanguage)) {
			$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
			$values->spokenLanguage = $languageRepository->find($values->spokenLanguage);
		}

		if (isset($values->board)) {
			$repository = $this->em->getRepository(RENTAL_AMENITY_ENTITY);
			$values->board = $repository->find($values->board);
		}

		return $values;
	}


}


interface ISearchFormFactory
{

	/**
	 * @param array $defaults
	 * @param \Service\Rental\RentalSearchService $search
	 * @param \Nette\Application\UI\Presenter $presenter
	 *
	 * @return SearchForm
	 */
	public function create(array $defaults, RentalSearchService $search, Nette\Application\UI\Presenter $presenter);
}
