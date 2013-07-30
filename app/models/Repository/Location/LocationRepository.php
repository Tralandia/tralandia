<?php
namespace Repository\Location;

use Doctrine\ORM\Query\Expr;
use Entity\Language;
use Entity\Location\Location;
use Environment\Collator;
use Model\Location\ILocationDecoratorFactory;
use Nette\Application\UI\Presenter;
use Nette\InvalidArgumentException;
use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Routers\BaseRoute;

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
			throw new InvalidArgumentException('$primaryLocation nie je primarna krajina!');
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


	/**
	 * @param $type
	 * @param null $order
	 * @param null $limit
	 * @param null $offset
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findByTypeQb($type, $order = NULL, $limit = NULL, $offset = NULL)
	{
		$qb = $this->createQueryBuilder();

		$qb->join('e.type', 't')
			->where($qb->expr()->eq('t.slug', ':type'))
			->setParameter('type', $type);

		if($limit) $qb->setMaxResults($limit);
		if($offset) $qb->setFirstResult($offset);

		return $qb;
	}


	/**
	 * @param null $order
	 * @param null $limit
	 * @param null $offset
	 *
	 * @return array
	 */
	public function findCountries($order = NULL, $limit = NULL, $offset = NULL)
	{
		$qb = $this->findByTypeQb('country');

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param null $order
	 * @param null $limit
	 * @param null $offset
	 *
	 * @return array
	 */
	public function findRegions($order = NULL, $limit = NULL, $offset = NULL)
	{
		$qb = $this->findByTypeQb('region');

		return $qb->getQuery()->getResult();
	}


	/**
	 * @return int|number
	 */
	public function getRegionsCount()
	{
		$qb = $this->findByTypeQb('region');

		return $this->getCount($qb);
	}


	/**
	 * @param null $order
	 * @param null $limit
	 * @param null $offset
	 *
	 * @return array
	 */
	public function findLocalities($order = NULL, $limit = NULL, $offset = NULL)
	{
		$qb = $this->findByTypeQb('locality', $order, $limit, $offset);

		return $qb->getQuery()->getResult();
	}


	/**
	 * @return int|number
	 */
	public function getLocalitiesCount()
	{
		$qb = $this->findByTypeQb('locality');

		return $this->getCount($qb);
	}



	/**
	 * @param \Nette\Localization\ITranslator $translator
	 * @param \Environment\Collator $collator
	 * @param \Nette\Application\UI\Presenter $presenter
	 * @param string $destination
	 *
	 * @return mixed
	 */
	public function getCountriesForSelect(ITranslator $translator, Collator $collator, Presenter $presenter = NULL, $destination = 'this')
	{
		$rows = $this->findCountries();
		$return = [];
		$sort = [];
		$elTemplate = Html::el('option');
		foreach($rows as $row) {
			/** @var $row \Entity\Location\Location */

			$parent = $row->getParent();
			$prefix = NULL;
			if($parent && $parent->getIso()) {
				$prefix = $translator->translate($parent->getName());
			}

			$text = ($prefix ? $prefix . ' - ' : '') . $translator->translate($row->getName());

			$key = $row->getId();
			if($presenter) {
				$link = $presenter->link($destination, [BaseRoute::PRIMARY_LOCATION => $row]);
				$el = clone $elTemplate;
				$return[$key] = $text;
				$sort[$key] = $el->value($key)->addAttributes(['data-redirect' => $link])->setText($text);
			} else {
				$return[$key] = $text;
			}
		}


		if($presenter) {
			$collator->asort($return);
			foreach($return as $key => $value) {
				$return[$key] = $sort[$key];
			}
		} else {
			$collator->asort($return);
		}

		return $return;
	}

	/**
	 * @param \Nette\Localization\ITranslator $translator
	 * @param \Environment\Collator $collator
	 *
	 * @return mixed
	 */
	public function getCountriesOrdered(ITranslator $translator, Collator $collator)
	{
		$sort = [];
		$locations = array();
		foreach($this->findCountries() as $row) {
			/** @var $row \Entity\Location\Location */
			// $parent = $row->getParent();
			// $prefix = NULL;
			// if($parent && $parent->getIso()) {
			// 	$prefix = $translator->translate($parent->getName());
			// }

			// $text = ($prefix ? $prefix . ' - ' : '') . $translator->translate($row->getName());
			$text = $translator->translate($row->getName());

			$sort[$text] = $row->id;
			$locations[$row->id] = [
				'entity' => $row,
				'name' => $text
			];
		}

		$collator->ksort($sort);
		$return = [];
		foreach($sort as $key => $id) {
			$return[$id] = $locations[$id];
		}

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


	public function findAllLocalityAndRegion()
	{
		$qb = $this->createQueryBuilder();
		$qb->leftJoin('e.type', 't')
			->andWhere($qb->expr()->in('t.slug', ':type'))->setParameter('type', ['locality', 'region']);

		return $qb->getQuery()->getResult();
	}

	public function findSuggestForLocalityAndRegion($search,Location $location, Language $language)
	{
		$qb = $this->createQueryBuilder();
		$qb->leftJoin('e.type', 't')
			->join('e.name', 'n')
			->join('n.translations', 'tt')
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

	public function findRegionsHavingPolygons($country = NULL)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->andWhere($qb->expr()->in('t.slug', ':type'))->setParameter('type', ['region'])
			->andWhere($qb->expr()->neq('e.polygons', ':polygons'))->setParameter('polygons', 'null');
			if ($country) {
				$qb->andWhere($qb->expr()->eq('e.parent', ':parent'))->setParameter('parent', $country);
			}

			//$qb->setMaxResults(100);

		return $qb->getQuery()->getResult();
	}

	public function getCountriesPhonePrefixes(Collator $collator) {

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

		$collator->asort($return);

		return $return;

	}


	public function findCountriesWithRentals()
	{
		$qb = $this->findByTypeQb('country');

		$qb->andWhere($qb->expr()->gte('e.rentalCount', ':minRentalCount'))->setParameter('minRentalCount', 1);

		return $qb->getQuery()->getResult();
	}
}
