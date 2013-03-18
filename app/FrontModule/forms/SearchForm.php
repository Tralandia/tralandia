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
		$countries = $this->searchOptionGenerator->generateCountries();
		$locations = $this->searchOptionGenerator->generateLocation();
		$rentalTypes = $this->searchOptionGenerator->generateRentalType();
		$prices = $this->searchOptionGenerator->generatePrice($this->environment->getPrimaryLocation());
		$capacity = $this->searchOptionGenerator->generateCapacity();
		$languages = $this->searchOptionGenerator->generateLanguage();

		$this->addSelect('country', 'o1070', $countries)
			->setPrompt('o1070')
			->setAttribute('data-placeholder',$this->translate('o1070'));

		$this->addSelect('location', 'o1070', $locations)
			->setPrompt('')
			->setAttribute('data-placeholder',$this->translate('o1070'));

		$this->addSelect('rentalType', 'o20926', $rentalTypes)
			->setPrompt('')
			->setAttribute('data-placeholder',$this->translate('o20926'));

		$this->addSelect('priceFrom', 'o100093', $prices)
			->setPrompt('')
			->setAttribute('data-placeholder',$this->translate('o100093'));

		$this->addSelect('priceTo', 'o100094', $prices)
			->setPrompt('')
			->setAttribute('data-placeholder',$this->translate('o100094'));

		$this->addSelect('capacity', 'o20928', $capacity)
			->setPrompt('')
			->setAttribute('data-placeholder',$this->translate('o20928'));

		$this->addSelect('spokenLanguage', 'o20930', $languages)
			->setPrompt('')
			->setAttribute('data-placeholder',$this->translate('o20930'));

		$this->addSubmit('submit', 'o100092');
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
