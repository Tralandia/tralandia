<?php

namespace SearchGenerator;

use Doctrine\ORM\EntityManager;
use Entity\Currency;
use Entity\Language;
use Entity\Location\Location;
use Environment\Environment;
use Tralandia\Amenity\Amenities;
use Tralandia\Localization\Translator;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\ArrayHash;
use Service\Rental\IRentalSearchServiceFactory;
use Service\Rental\RentalSearchService;
use Tralandia\Location\Countries;
use Tralandia\Routing\PathSegments;

class OptionGenerator
{

	const PRICE_ITERATION_COUNT = 10;

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
	 * @var \Tralandia\Localization\Translator
	 */
	protected $translator;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @var \Tralandia\Routing\PathSegments
	 */
	private $pathSegments;

	/**
	 * @var \Tralandia\Amenity\Amenities
	 */
	private $amenities;


	/**
	 * @param \Environment\Environment $environment
	 * @param TopLocations $topLocations
	 * @param SpokenLanguages $spokenLanguages
	 * @param \Service\Rental\IRentalSearchServiceFactory $searchFactory
	 * @param \Tralandia\Routing\PathSegments $pathSegments
	 * @param \Tralandia\Location\Countries $countries
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(Environment $environment, TopLocations $topLocations, SpokenLanguages $spokenLanguages,
								IRentalSearchServiceFactory $searchFactory, PathSegments $pathSegments,
								Countries $countries, Amenities $amenities, EntityManager $em)
	{
		$this->environment = $environment;
		$this->searchFactory = $searchFactory;
		$this->translator = $environment->getTranslator();
		$this->topLocations = $topLocations;
		$this->spokenLanguages = $spokenLanguages;
		$this->em = $em;
		$this->countries = $countries;
		$this->pathSegments = $pathSegments;
		$this->amenities = $amenities;
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
		$pathSegments = $this->pathSegments->findRentalTypes($this->environment->getLanguage());

		$typeSegments = [];
		foreach ($pathSegments as $pathSegment) {
			$typeSegments[$pathSegment->getEntityId()] = $pathSegment->getPathSegment();
		}

		$options = [];
		foreach ($rentalTypes as $value) {
			$key = $typeSegments[$value->getId()];
			$options[$key] = $this->translate($value->getName(), NULL, [Translator::VARIATION_COUNT => 2]);
		}

		$options = $this->sort($options);

		return $options;

	}


	/**
	 * @param RentalSearchService $search
	 *
	 * @return ArrayHash
	 */
	public function generateRentalTypeLinks(RentalSearchService $search)
	{
		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_RENTAL_TYPE);

		if(!count($collection)) return ArrayHash::from([]);

		$rentalTypes = $this->em->getRepository(RENTAL_TYPE_ENTITY)->findById(array_keys($collection));

		$links = [];
		foreach ($rentalTypes as $value) {
			if (!isset($collection[$value->getId()])) continue;
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName(), 2),
				'count' => count($collection[$value->getId()]),
			];
		}

		$links = $this->sort($links, 'name');

		return ArrayHash::from($links);
	}


	/**
	 * @param RentalSearchService $search
	 *
	 * @return ArrayHash
	 */
	public function generatePlacementLinks(RentalSearchService $search)
	{
		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_PLACEMENT);

		if(!count($collection)) return ArrayHash::from([]);

		$rentalTypes = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY)->findById(array_keys($collection));

		$links = [];
		foreach ($rentalTypes as $value) {
			if (!isset($collection[$value->getId()])) continue;
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName(), 2),
				'count' => count($collection[$value->getId()]),
			];
		}

		$links = $this->sort($links, 'name');

		return ArrayHash::from($links);
	}


	/**
	 * @return array
	 */
	public function generatePlacement()
	{
		$placement = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY)->findAll();

		return $this->generateFromEntities($placement, 'Id');
	}


	/**
	 * @param \Nette\Application\UI\Presenter $presenter
	 *
	 * @return mixed
	 */
	public function generateCountries(Presenter $presenter)
	{
		$locations = $this->countries->getForSelect($presenter, ':Front:Home:');

		return $locations;
	}


	/**
	 * @param Location $defaultLocation
	 *
	 * @return array
	 */
	public function generateLocation(Location $defaultLocation = NULL)
	{
		$top = $this->topLocations->getResults(20);

		$ids = array_keys($top);
		if ($defaultLocation) $ids[] = $defaultLocation->getId();

		$locations = $this->em->getRepository(LOCATION_ENTITY)->findById($ids);

		return $this->generateFromEntities($locations);
	}


	/**
	 * @param $count
	 * @param \Service\Rental\RentalSearchService $search
	 *
	 * @return ArrayHash
	 */
	public function generateLocationLinks($count, RentalSearchService $search)
	{
		$topLocation = $this->topLocations;
		$top = $topLocation->getResults($count, $search);

		if(!count($top)) return ArrayHash::from([]);

		$locations = $this->em->getRepository(LOCATION_ENTITY)->findById(array_keys($top));

		$links = [];
		foreach ($locations as $value) {
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
		$languages = $this->spokenLanguages->getUsed();

		if(!$languages) return [];

		return $this->generateFromEntities($languages, 'Id');
	}


	/**
	 * @param \Service\Rental\RentalSearchService $search
	 *
	 * @return array
	 */
	public function generateSpokenLanguageLinks(RentalSearchService $search)
	{
		$languages = $this->spokenLanguages->getUsed();

		if(!$languages) return ArrayHash::from([]);

		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_SPOKEN_LANGUAGE);

		$links = [];
		foreach ($languages as $value) {
			if (!isset($collection[$value->getId()])) continue;
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName()),
				'count' => count($collection[$value->getId()]),
			];
		}

		$links = $this->sort($links, 'name');

		return ArrayHash::from($links);
	}


	/**
	 * @return array
	 */
	public function generateBoard()
	{
		$boards = $this->amenities->findByBoardTypeForSelect();
		return $boards;
	}


	/**
	 * @param RentalSearchService $search
	 *
	 * @return ArrayHash
	 */
	public function generateBoardLinks(RentalSearchService $search)
	{
		$boards = $this->em->getRepository(RENTAL_AMENITY_ENTITY)->findAll();

		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_BOARD);

		$links = [];
		foreach ($boards as $value) {
			if (!isset($collection[$value->getId()])) continue;
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName()),
				'count' => count($collection[$value->getId()]),
			];
		}

		$links = $this->sort($links, 'name');

		return ArrayHash::from($links);
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
		for ($i = 1; $i <= self::PRICE_ITERATION_COUNT; $i++) {
			$key = $i * $searchInterval;

			$options[$key] = "$key $iso";
		}

		return $options;
	}


	/**
	 * @param \Entity\Currency $currency
	 * @param \Service\Rental\RentalSearchService $search
	 *
	 * @return array
	 */
	public function generatePriceLinks(Currency $currency, RentalSearchService $search, $from = NULL)
	{
		$searchInterval = $currency->getSearchInterval();

		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_PRICE);

		$options = array();
		$iso = $currency->getIso();
		for ($i = $from ? : 1; $i <= self::PRICE_ITERATION_COUNT; $i++) {
			$key = $i * $searchInterval;
			if (!isset($collection[$key])) continue;

			$options[$i] = [
				'entity' => $key,
				'name' => "$key $iso",
				'count' => count($collection[$key]),
			];
		}

		return ArrayHash::from($options);
	}


	/**
	 * @return array
	 */
	public function generateCapacity()
	{
		$options = array();
		for ($i = 1; $i <= RentalSearchService::CAPACITY_MAX; $i++) {
			$options[$i] = $i . ' ' . $this->translate('o490', $i);
		}

		return $options;
	}


	/**
	 * @param \Service\Rental\RentalSearchService $search
	 *
	 * @return array
	 */
	public function generateCapacityLinks(RentalSearchService $search)
	{
		$collection = $search->getCollectedResults(RentalSearchService::CRITERIA_CAPACITY);

		$options = array();
		for ($i = 1; $i <= RentalSearchService::CAPACITY_MAX; $i++) {
			if (!isset($collection[$i])) continue;

			$options[$i] = [
				'entity' => $i,
				'name' => $i . ' ' . $this->translate('o490', $i),
				'count' => count($collection[$i]),
			];
		}

		return ArrayHash::from($options);
	}


	/**
	 * @param $data
	 * @param string $key
	 * @param array $variation
	 *
	 * @return array
	 */
	protected function generateFromEntities($data, $key = 'Slug', array $variation = NULL)
	{
		$options = [];
		foreach ($data as $value) {
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
		if ($key) {
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
