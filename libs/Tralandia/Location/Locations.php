<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/8/13 10:14 AM
 */

namespace Tralandia\Location;


use Entity\Location\Location;
use Entity\Rental\Rental;
use InvalidArgumentException;
use Model\Location\ILocationDecoratorFactory;
use Nette;
use Nette\Utils\Strings;
use Tralandia\BaseDao;

class Locations
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $typeDao;

	/**
	 * @var \Model\Location\ILocationDecoratorFactory
	 */
	private $locationDecoratorFactory;


	/**
	 * @param \Tralandia\BaseDao $locationDao
	 * @param \Tralandia\BaseDao $typeDao
	 * @param \Model\Location\ILocationDecoratorFactory $locationDecoratorFactory
	 */
	public function __construct(BaseDao $locationDao, BaseDao $typeDao, ILocationDecoratorFactory $locationDecoratorFactory)
	{
		$this->locationDao = $locationDao;
		$this->typeDao = $typeDao;
		$this->locationDecoratorFactory = $locationDecoratorFactory;
	}


	/**
	 * @param string $locality
	 * @param Location $primaryLocation
	 *
	 * @throws InvalidArgumentException
	 * @return Location
	 */
	public function findOrCreateLocality($locality, Location $primaryLocation)
	{
		if(!$primaryLocation->isPrimary()) {
			throw new InvalidArgumentException('$primaryLocation nie je primarna krajina!');
		}

		$locationType = $this->typeDao->findOneBySlug('locality');
		$webalizedName = Strings::webalize($locality);
		$localityEntity = $this->locationDao->findOneBy(array(
			'type' => $locationType,
			'parent' => $primaryLocation,
			'slug' => $webalizedName
		));

		if (!$localityEntity) {
			/** @var $localityEntity \Entity\Location\Location */
			$localityEntity = $this->locationDao->createNew();
			$newLocalityDecorator = $this->locationDecoratorFactory->create($localityEntity);

			$namePhrase = $localityEntity->getName();
			$namePhrase->setSourceLanguage($primaryLocation->getDefaultLanguage());
			$translation = $namePhrase->getTranslation($primaryLocation->getDefaultLanguage());
			if(!$translation) {
				$translation = $namePhrase->createTranslation($primaryLocation->getDefaultLanguage());
			}
			$translation->setTranslation($locality);

			$localityEntity->parent = $primaryLocation;
			$localityEntity->type = $locationType;

			// We must save the new location to be able to work on it's slug
			$this->save($localityEntity);

			$newLocalityDecorator->setName($namePhrase);

			$this->save($localityEntity); // ?? neviem naco to tu je...
		}

		return $localityEntity;
	}


	/**
	 * @param $iso
	 *
	 * @return \Entity\Location\Location
	 */
	public function findOneByIso($iso)
	{
		return $this->locationDao->findOneByIso($iso);
	}

}
