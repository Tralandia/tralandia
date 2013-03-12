<?php

namespace SearchGenerator;

use Doctrine\ORM\EntityManager;
use Entity\Location\Location;
use Environment\Environment;
use Nette\Application\Application;
use Nette\ArrayHash;
use Service\Rental\RentalSearchService;

class OptionGenerator {

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var TopLocations
	 */
	protected $topLocations;

	/**
	 * @var \Extras\Translator
	 */
	protected $translator;

	/**
	 * @param \Environment\Environment $environment
	 * @param TopLocations $topLocations
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(Environment $environment, TopLocations $topLocations,
								EntityManager $em)
	{
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->topLocations = $topLocations;
		$this->em = $em;
	}


	/**
	 * @return array
	 */
	public function generateRentalType()
	{
		$rentalTypes = $this->em->getRepository(RENTAL_TYPE_ENTITY)->findAll();

		return $this->generateFromEntities($rentalTypes);
	}

	/**
	 * @return array
	 */
	public function generateRentalTypeLinks()
	{
		$rentalTypes = $this->em->getRepository(RENTAL_TYPE_ENTITY)->findAll();

		$links = [];
		foreach($rentalTypes as $value) {
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName()),
			];
		}

		$links = $this->sort($links);

		return ArrayHash::from($links);
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
		$top = $this->topLocations->getResults(20);
		$locations = $this->em->getRepository(LOCATION_ENTITY)->findById(array_keys($top));


		$links = [];
		foreach($locations as $value) {
			$links[$value->getId()] = [
				'entity' => $value,
				'name' => $this->translator->translate($value->getName()),
			];
		}

		$links = $this->sort($links);
		return ArrayHash::from($links);
	}

	/**
	 * @return array
	 */
	public function generateLanguage()
	{
		$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findAll();

		return $this->generateFromEntities($languages);
	}

	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return array
	 */
	public function generatePrice(Location $location)
	{
		$searchInterval = $location->defaultCurrency->searchInterval;

		$options = array();
		for ($i=1; $i < 10; $i++) {
			$key = $i * $searchInterval;

			$options[$key] = "$key ";
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
			$options[$i] = "$i ";
		}
		return $options;
	}

	/**
	 * @param $data
	 *
	 * @return \Nette\ArrayHash
	 */
	protected function generateFromEntities($data)
	{
		$options = [];
		foreach($data as $value) {
			$options[$value->getId()] = $this->translator->translate($value->getName());
		}

		$options = $this->sort($options);

		return $options;
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	protected function sort($data)
	{
		return $data;
	}

}