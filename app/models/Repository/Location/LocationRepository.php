<?php
namespace Repository\Location;

use Doctrine\ORM\Query\Expr;
use Entity\Location\Location;
use Environment\Collator;
use Model\Location\ILocationDecoratorFactory;
use Nette\InvalidArgumentException;
use Nette\Localization\ITranslator;
use Nette\Utils\Strings;

/**
 * LocationRepository class
 */
class LocationRepository extends \Repository\BaseRepository {

	/**
	 * @var ILocationDecoratorFactory
	 */
	protected $locationDecoratorFactory;

	/**
	 * @param \Model\Location\ILocationDecoratorFactory $locationDecoratorFactory
	 */
	public function inject(ILocationDecoratorFactory $locationDecoratorFactory)
	{
		$this->locationDecoratorFactory = $locationDecoratorFactory;
	}

	/**
	 * DataSource pre grid
	 * @param $type
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getDefaultDataSource($type) {
		return $this->_em->createQueryBuilder()
			->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->where('t.slug = :slug')->setParameter('slug', $type)
			->groupBy('e.id');
	}

	/**
	 * @param string $locality
	 * @param Location $primaryLocation
	 *
	 * @throws \Nette\InvalidArgumentException
	 * @return Location
	 */
	public function findOrCreateLocality($locality, Location $primaryLocation)
	{
		if(!$primaryLocation->isPrimary()) {
			throw new InvalidArgumentException;
		}

		$locationType = $this->related('type')->findOneBySlug('locality');
		$webalizedName = Strings::webalize($locality);
		$localityEntity = $this->findOneBy(array(
			'type' => $locationType,
			'parent' => $primaryLocation,
			'slug' => $webalizedName
		));

		if (!$localityEntity) {
			/** @var $localityEntity \Entity\Location\Location */
			$localityEntity = $this->createNew();
			$newLocalityDecorator = $this->locationDecoratorFactory->create($localityEntity);

			$namePhrase = $localityEntity->getName();
			$namePhrase->setSourceLanguage($primaryLocation->getDefaultLanguage());
			$translation = $namePhrase->getTranslation($primaryLocation->getDefaultLanguage());
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

	public function getItems() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e')->from($this->_entityName, 'e')->setMaxResults(60);
		return $query->getQuery()->getResult();
	}

	public function getWorldwideRentalCount() {
		$qb = $this->_em->createQueryBuilder();

		$qb->select('sum(l.rentalCount) as total')
			->from($this->_entityName, 'l');

		return $qb->getQuery()->getResult()[0]['total'];
	}

	public function getCountriesForSelect(ITranslator $translator, Collator $collator)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id, n.id AS name')
			->from($this->_entityName, 'l')
			->join('l.type', 't')
			->join('l.name', 'n')
			->where($qb->expr()->eq('t.slug', ':country'))
			->setParameter('country', 'country');

		$return = [];
		$rows = $qb->getQuery()->getResult();
		foreach($rows as $row) {
			$return[$row['id']] = $translator->translate($row['name']);
		}

		$collator->asort($return);

		return $return;
	}

	public function findSuggestForCountries($search)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->join('e.name', 'n')
			->join('n.translations', 'tt')
			->andWhere($qb->expr()->eq('t.slug', ':type'))->setParameter('type', 'country')
			->andWhere($qb->expr()->like('tt.translation', '?1'))->setParameter(1, "%$search%");

		return $qb->getQuery()->getResult();
	}

	public function findSuggestForLocalityAndRegion($search, $location)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->join('e.name', 'n')
			->join('n.translations', 'tt')
			->andWhere($qb->expr()->in('t.slug', ':type'))->setParameter('type', ['locality', 'region'])
			->andWhere($qb->expr()->eq('e.parent', ':parent'))->setParameter('parent', $location)
			->andWhere($qb->expr()->like('tt.translation', '?1'))->setParameter(1, "%$search%");

		return $qb->getQuery()->getResult();
	}

	public function getCountriesPhonePrefixes() {

		$qb = $this->_em->createQueryBuilder();

		$qb->select('l.id, l.iso, l.phonePrefix')
			->from($this->_entityName, 'l')
			->join('l.type', 't')
			->where($qb->expr()->eq('t.slug', ':country'))
			->setParameter('country', 'country')
			->groupBy('l.phonePrefix')
			->orderBy('l.iso')
			;

		$return = [];
		$rows = $qb->getQuery()->getResult();
		$continentType = $this->related('type')->findOneBySlug('continent');
		$usa = $this->findOneBy(array('slug' => 'usa', 'type' => $continentType));
		$australia = $this->findOneBy(array('slug' => 'australia', 'type' => $continentType));

		foreach($rows as $row) {
			if ($row['phonePrefix'] == 1) {
				$return[$row['phonePrefix']] = strtoupper($usa->iso) . ' (+'.$row['phonePrefix'].')';
			} else if ($row['phonePrefix'] == 61) {
				$return[$row['phonePrefix']] = strtoupper($australia->iso) . ' (+'.$row['phonePrefix'].')';
			} else {
				$return[$row['phonePrefix']] = strtoupper($row['iso']) . ' (+'.$row['phonePrefix'].')';
			}
		}

		return $return;

	}
}