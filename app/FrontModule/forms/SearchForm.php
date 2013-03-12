<?php

namespace FrontModule\Forms;

use Doctrine\ORM\EntityManager;
use Environment\Environment;
use Nette;
use SearchGenerator\OptionGenerator;

/**
 * SearchForm class
 *
 * @author Dávid Ďurika
 */
class SearchForm extends BaseForm {

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
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(OptionGenerator $searchOptionGenerator, Environment $environment, EntityManager $em,  Nette\Localization\ITranslator $translator)
	{
		$this->searchOptionGenerator = $searchOptionGenerator;
		$this->environment = $environment;
		$this->em = $em;
		parent::__construct($translator);
	}

	public function buildForm()
	{
		$locations = $this->searchOptionGenerator->generateLocation();
		$rentalTypes = $this->searchOptionGenerator->generateRentalType();
		$prices = $this->searchOptionGenerator->generatePrice($this->environment->getPrimaryLocation());
		$capacity = $this->searchOptionGenerator->generateCapacity();
		$languages = $this->searchOptionGenerator->generateLanguage();

		$this->addSelect('location', '#location', $locations)
			->setPrompt('#je mi to jedno');
		$this->addSelect('rentalType', '#Rental type', $rentalTypes)
			->setPrompt('#je mi to jedno');

		$this->addSelect('priceFrom', '#price from', $prices)
			->setPrompt('#nezalezi, som milionar');
		$this->addSelect('priceTo', '#price to', $prices)
			->setPrompt('#nezalezi, som milionar');

		$this->addSelect('capacity', '#capacity', $capacity)
			->setPrompt('#je mi to jedno');
		$this->addSelect('spokenLanguage', '#spokenLanguage', $languages)
			->setPrompt('#je mi to jedno');

		$this->addSubmit('submit', '#GO');
	}

	public function setDefaultsValues() 
	{
		
	}

	public function getValues($asArray = FALSE) {
		$values = parent::getValues($asArray);

		if(isset($values->location)) {
			$locationRepository = $this->em->getRepository(LOCATION_ENTITY);
			$values->location = $locationRepository->find($values->location);
		}
		if(isset($values->rentalType)) {
			$rentalTypeRepository = $this->em->getRepository(RENTAL_TYPE_ENTITY);
			$values->rentalType = $rentalTypeRepository->find($values->rentalType);
		}

		if(isset($values->spokenLanguage)) {
			$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
			$values->spokenLanguage = $languageRepository->find($values->spokenLanguage);
		}

		return $values;
	}


}

interface ISearchFormFactory {
	/**
	 * @return SearchForm
	 */
	public function create();
}
