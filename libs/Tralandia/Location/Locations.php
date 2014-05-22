<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/8/13 10:14 AM
 */

namespace Tralandia\Location;


use Entity\Language;
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
	 * @param \Entity\Location\Location $primaryLocation
	 *
	 * @throws InvalidArgumentException
	 * @return \Entity\Location\Location
	 */
	public function findOrCreateLocality($locality, \Entity\Location\Location $primaryLocation)
	{
		if(!$primaryLocation->isPrimary()) {
			throw new InvalidArgumentException('$primaryLocation nie je primarna krajina!');
		}

		if(!\Tools::isFirstUpper($locality)) {
			$locality = Strings::capitalize($locality);
		}

		$locationType = $this->typeDao->findOneBySlug('locality');
		$webalizedName = \Tools::transliterate($locality);
		$webalizedName = Strings::webalize($webalizedName);
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
			$this->locationDao->save($localityEntity);

			$newLocalityDecorator->setName($namePhrase);

			$this->locationDao->save($localityEntity);
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


	public function findAllLocalityAndRegion()
	{
		$qb = $this->locationDao->createQueryBuilder('e');
		$qb->leftJoin('e.type', 't')
			->andWhere($qb->expr()->in('t.slug', ':type'))->setParameter('type', ['locality', 'region']);

		return $qb->getQuery()->getResult();
	}

	public function findSuggestForLocalityAndRegion($search,\Entity\Location\Location $location, Language $language)
	{
		$qb = $this->locationDao->createQueryBuilder('e');
		$qb->leftJoin('e.type', 't')
			->innerJoin('e.name', 'n')
			->leftJoin('n.translations', 'tt')
			->andWhere($qb->expr()->in('t.slug', ':type'))->setParameter('type', ['locality', 'region'])
			->andWhere($qb->expr()->eq('e.parent', ':parent'))->setParameter('parent', $location)
			->andWhere($qb->expr()->orx(
				$qb->expr()->eq('tt.language', ':language'),
				$qb->expr()->eq('tt.language', 'n.sourceLanguage')
			))
			->andWhere($qb->expr()->like('tt.translation', '?1'))->setParameter(1, "%$search%")
			->setParameter('language', $language);

		return $qb->getQuery()->getResult();
	}


}
