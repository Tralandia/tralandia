<?php

namespace SearchGenerator;

use Doctrine\ORM\EntityManager;
use Entity\Currency;
use Entity\Location\Location;
use Environment\Environment;
use Extras\Translator;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\ArrayHash;
use Service\Rental\IRentalSearchServiceFactory;
use Service\Rental\RentalSearchService;

class OptionGenerator {

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $searchFactory;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var TopLocations
	 */
	protected $topLocations;

	/**
	 * @var SpokenLanguages
	 */
	protected $spokenLanguages;

	/**
	 * @var \Extras\Translator
	 */
	protected $translator;

	/**
	 * @param \Environment\Environment $environment
	 * @param TopLocations $topLocations
	 * @param SpokenLanguages $spokenLanguages
	 * @param \Service\Rental\IRentalSearchServiceFactory $searchFactory
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(Environment $environment, TopLocations $topLocations, SpokenLanguages $spokenLanguages,
								IRentalSearchServiceFactory $searchFactory, EntityManager $em)
	{
		$this->environment = $environment;
		$this->searchFactory = $searchFactory;
		$this->translator = $environment->getTranslator();
		$this->topLocations = $topLocations;
		$this->spokenLanguages = $spokenLanguages;
		$this->em = $em;
	}

	/**
	 * @return \Environment\Collator
	 */
	protected function getCollator()
	{
		return $this->environment->getLocale()->getCollator();
	}


	/**
	 * @return array
	 */
	public function generateRentalType()
	{
		$rentalTypes = $this->em->getRepository(RENTAL_TYPE_ENTITY)->findAll();

		return $this->generateFromEntities($rentalTypes, 'Slug', [Translator::VARIATION_COUNT => 2]);
	}

	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return array
	 */
	public function generateRentalTypeLinks(Location $location)
	{
		$rentalTypes = $this->em->getRepository(RENTAL_TYPE_ENTITY)->findAll();
		$search = $this->searchFactory->create($location->getPrimaryParent());
		$search->setLocationCriterion($location);
		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_RENTAL_TYPE);

		$links = [];
		foreach($rentalTypes as $value) {
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName(), NULL, [Translator::VARIATION_COUNT => 2]),
				'count' => isset($collection[$value->getId()]) ? count($collection[$value->getId()]) : 0,
			];
		}

		$links = $this->sort($links, 'name');

		return ArrayHash::from($links);
	}

	/**
	 * @param \Nette\Application\UI\Presenter $presenter
	 *
	 * @return mixed
	 */
	public function generateCountries(Presenter $presenter)
	{
		$locations = $this->em->getRepository(LOCATION_ENTITY)
			->getCountriesForSelect($this->translator, $this->getCollator(), $presenter, ':Front:Home:');

		return $locations;
	}

	/**
	 * @return array
	 */
	public function generateLocation()
	{
		$top = $this->topLocations->getResults(20);
		$locations = $this->em->getRepository(LOCATION_ENTITY)->findById(array_keys($top));

		return $this->generateFromEntities($locations);
	}

	/**
	 * @return array
	 */
	public function generateLocationLinks()
	{
		$top = $this->topLocations->getResults(200);
		$locations = $this->em->getRepository(LOCATION_ENTITY)->findById(array_keys($top));

		$links = [];
		foreach($locations as $value) {
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName()),
				'count' => count($top[$value->getId()]),
			];
		}

		$links = $this->sort($links, 'name');
		return ArrayHash::from($links);
	}

	/**
	 * @return array
	 */
	public function generateSpokenLanguage()
	{
		$languagesIds = $this->spokenLanguages->getUsed();
		$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findById($languagesIds);

		return $this->generateFromEntities($languages, 'Id');
	}

	/**
	 * @param \Entity\Currency $currency
	 *
	 * @return array
	 */
	public function generatePrice(Currency $currency)
	{
		$searchInterval = $currency->getSearchInterval();

		$options = array();
		$iso = $currency->getIso();
		for ($i=1; $i < 10; $i++) {
			$key = $i * $searchInterval;

			$options[$key] = "$key $iso";
		}
		return $options;
	}

	/**
	 * @return array
	 */
	public function generateCapacity()
	{
		$options = array();
		for ($i=1; $i <= RentalSearchService::CAPACITY_MAX; $i++) {
			$options[$i] = $i . ' ' . $this->translate('o490', $i);
		}
		return $options;
	}

	/**
	 * @param $data
	 * @param string $key
	 * @param array $variation
	 *
	 * @return \Nette\ArrayHash
	 */
	protected function generateFromEntities($data, $key = 'Slug', array $variation = NULL)
	{
		$options = [];
		foreach($data as $value) {
			$methodName = "get$key";
			$options[$value->{$methodName}()] = $this->translate($value->getName(), NULL, $variation);
		}

		$options = $this->sort($options);

		return $options;
	}

	/**
	 * @param $data
	 * @param null $key
	 *
	 * @return mixed
	 */
	protected function sort($data, $key = NULL)
	{
		$collator = $this->environment->getLocale()->getCollator();
		if($key) {
			$collator->asortByKey($data, $key);
		} else {
			$collator->asort($data);
		}
		return $data;
	}

	/**
	 * @return string
	 */
	public function translate()
	{
		$args = func_get_args();
		return call_user_func_array(array($this->translator, 'translate'), $args);
	}

}
