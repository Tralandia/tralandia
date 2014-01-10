<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/25/13 2:49 PM
 */

namespace Tralandia\Rental;


use Entity\Location\Location;
use Entity\Rental\Rental;
use Entity\Rental\EditLog;
use Kdyby\Doctrine\QueryBuilder;
use Nette;
use Tralandia\BaseDao;

class Rentals {


	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationsDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $editLogDao;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	/**
	 * @param \Tralandia\BaseDao $rentalDao
	 * @param BaseDao $locationsDao
	 * @param \Tralandia\BaseDao $editlogDao
	 */
	public function __construct(BaseDao $rentalDao, BaseDao $locationsDao, BaseDao $editLogDao, \Environment\Environment $environment)
	{
		$this->locationsDao = $locationsDao;
		$this->rentalDao = $rentalDao;
		$this->editLogDao = $editLogDao;
		$this->environment = $environment;
	}


	/**
	 * @return int
	 */
	public function worldwideCount()
	{
		$qb = $this->locationsDao->createQueryBuilder('l');

		$qb->select('sum(l.rentalCount) as total');

		return $qb->getQuery()->getSingleScalarResult();
	}


	/**
	 * @param Location $primaryLocation
	 * @param null $live
	 * @param \DateTime $dateFrom
	 * @param \DateTime $dateTo
	 *
	 * @return array
	 */
	public function getCounts(Location $primaryLocation = NULL, $live = NULL, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL)
	{
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->select('l.id locationId', 'COUNT(r.id) as c')
			->innerJoin('r.address', 'a')
			->innerJoin('a.primaryLocation', 'l');

		if ($primaryLocation) {
			$qb->where($qb->expr()->eq('a.primaryLocation', $primaryLocation->id));
		} else {
			$qb->groupBy('a.primaryLocation');
		}

		if ($live) {
			$qb->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE));
		}

		if ($dateFrom && $dateTo) {
			$qb->andWhere($qb->expr()->gte('r.created', '?1'));
			$qb->andWhere($qb->expr()->lt('r.created', '?2'));
			$qb->setParameter(1, $dateFrom, \Doctrine\DBAL\Types\Type::DATETIME);
			$qb->setParameter(2, $dateTo, \Doctrine\DBAL\Types\Type::DATETIME);
		}

		$result = $qb->getQuery()->getResult();
		$myResult = array();
		foreach ($result as $key => $value) {
			$myResult[$value['locationId']] = $value['c'];
		}
		return $myResult;
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return bool
	 */
	public function isFeatured(\Entity\Rental\Rental $rental) {
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->join('r.services', 's')
			->where($qb->expr()->eq('r.id', $rental->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
		;
		return (bool) $qb->getQuery()->getOneOrNullResult();
	}


	/**
	 * @param int $limit
	 *
	 * @return \Entity\Rental\Rental[]
	 */
	public function getFeaturedRentals($limit=60)
	{
		$now = new \Nette\DateTime();
		$query = "SELECT (
SELECT r0_.id
FROM rental r0_
left JOIN rental_service r1_ ON r0_.id = r1_.rental_id
INNER JOIN contact_address c2_ ON r0_.address_id = c2_.id
WHERE r0_.status = 6 AND
c2_.primaryLocation_id = l3_.id
ORDER BY if(r1_.serviceType = 'featured' AND r1_.dateFrom <= '$now' AND r1_.dateTo > '$now', 1, 0) DESC, r0_.rank DESC limit 1) AS rId
FROM location l3_
ORDER BY l3_.rentalCount DESC
LIMIT $limit";
		$query = $this->rentalDao->getEntityManager()->getConnection()->query($query);

		$data = $query->fetchAll();

		$rentalIds = [];
		foreach($data as $value) {
			$rentalIds[] = $value['rId'];
		}

		$rentals = $this->rentalDao->findBy(['id' => $rentalIds]);

		$rentals = \Tools::sortArrayByArray($rentals, $rentalIds, function($entity){return $entity->getId();});

		return $rentals;
	}


	/**
	 * @param Location $location
	 *
	 * @return mixed
	 */
	public function findFeatured(Location $location) {
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->select('r.id')
			->innerJoin('r.address', 'a')
			->innerJoin('r.services', 's')
			->where($qb->expr()->eq('a.primaryLocation', $location->id))
			->andWhere($qb->expr()->eq('r.status', \Entity\Rental\Rental::STATUS_LIVE))
			->andWhere($qb->expr()->eq('s.serviceType', '?1'))
			->andWhere($qb->expr()->lte('s.dateFrom', '?2'))
			->andWhere($qb->expr()->gt('s.dateTo', '?2'))
			->setParameter(1, 'featured')
			->setParameter(2, new \Nette\DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
		;

		return $qb->getQuery()->getResult();
	}




	/**
	 * @param Location $location
	 * @param null $status
	 * @param array $order
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findByPrimaryLocationQB(Location $location, $status = NULL, array $order = NULL)
	{
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->innerJoin('r.address', 'a')
			->andWhere($qb->expr()->eq('a.primaryLocation', $location->getId()));

		if ($status != NULL) {
			$qb->andWhere($qb->expr()->eq('r.status', $status ? Rental::STATUS_LIVE : Rental::STATUS_DRAFT));
		}

		if($order) {
			foreach($order as $key => $value) {
				$qb->addOrderBy($key, $value);
			}
		}

		return $qb;
	}


	/**
	 * @param Location $location
	 * @param null $status
	 * @param array $order
	 *
	 * @return \Entity\Rental\Rental[]
	 */
	public function findByPrimaryLocation(Location $location, $status = NULL, array $order = NULL)
	{
		$qb = $this->findByPrimaryLocationQB($location, $status, $order);

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param $email
	 *
	 * @return mixed
	 */
	public function findByEmailOrUserEmail($email)
	{
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->innerJoin('r.user', 'u')
			->where($qb->expr()->eq('r.email', ':email'))
			->orWhere($qb->expr()->eq('u.login', ':email'))
			->setParameter('email', $email);

		return $qb->getQuery()->getResult();
	}

	/**
	 * @param Location $primaryLocation
	 * @param null $harvested
	 * @param \DateTime $dateFrom
	 * @param \DateTime $dateTo
	 *
	 * @return array
	 */
	public function getEditCounts(Location $primaryLocation = NULL, $harvested = NULL, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL)
	{
		$qb = $this->editLogDao->createQueryBuilder('e');

		$qb->select('pl.iso AS iso, count(e) AS c')
			->andWhere('e.created >= ?2')->setParameter('2', $dateFrom)
			->andWhere('e.created < ?1')->setParameter('1', $dateTo)
			->innerJoin('e.rental', 'r');
		if ($harvested) {
			$qb->andWhere('r.harvested = ?3')->setParameter('3', 1);
		}

		$qb->innerJoin('r.address', 'a')
			->innerJoin('a.primaryLocation', 'pl')
			->groupBy('a.primaryLocation');

		$result = $qb->getQuery()->getResult();
		$myResult = array();
		foreach ($result as $key => $value) {
			$myResult[$value['iso']] = $value['c'];
		}
		return $myResult;
	}


	public function getCountsInCountries($latitudeA, $longitudeA, $latitudeB, $longitudeB)
	{
		$qb = $this->findRentalsBetween($latitudeA, $longitudeA, $latitudeB, $longitudeB);

		$qb->select('l.id AS lId');

		$qb->innerJoin('a.primaryLocation', 'l')
			->groupBy('l.id');

		$result = $qb->getQuery()->getArrayResult();

		$counts = [];
		foreach($result as $row) {
			$counts[$row['lId']] = $row;
		}
		$locations = $this->locationsDao->findBy(['id' => array_keys($counts)]);


		$return = [];
		/** @var $location \Entity\Location\Location */
		foreach($locations as $location) {
			$return[] = $this->getCountryInfo($location);
		}

		$this->environment->getLocale()->getCollator()->asortByKey($return, 'name');

		return $return;

	}


	protected function getCountryInfo(Location $location)
	{
		$translator = $this->environment->getTranslator();

		$center = $location->getGps();
		return [
			'id' => $location->getId(),
			'iso' => $location->getIso(),
			'name' => $translator->translate($location->getName()),
			'flag' => STATIC_DOMAIN . 'flags/' . $location->getIso() . '.png',
			'latitude' => $center->getLatitude(),
			'longitude' => $center->getLongitude(),
			'rentalCount' => $location->getRentalCount(),
		];
	}


	public function getCountsInLocalities($latitudeA, $longitudeA, $latitudeB, $longitudeB)
	{
		$qb = $this->findRentalsBetween($latitudeA, $longitudeA, $latitudeB, $longitudeB);

		$qb->select('count(a.locality) AS c, l.id AS lId, AVG(a.longitude) AS longitude, AVG(a.latitude) AS latitude');

		$qb->innerJoin('a.locality', 'l')
			->groupBy('l.id')
			->orderBy('c', 'DESC');

		$result = $qb->getQuery()->getArrayResult();


		$counts = [];
		foreach($result as $row) {
			$counts[$row['lId']] = $row;
		}
		$locations = $this->locationsDao->findBy(['id' => array_keys($counts)]);

		$translator = $this->environment->getTranslator();
		$return = [];
		/** @var $location \Entity\Location\Location */
		foreach($locations as $location) {
			$id = $location->getId();
			$return[] = [
				'id' => $id,
				'iso' => $location->getIso(),
				'name' => $translator->translate($location->getName()),
				'country' => $this->getCountryInfo($location->getPrimaryParent()),
				'latitude' => $counts[$id]['latitude'],
				'longitude' => $counts[$id]['longitude'],
			];
		}

		return $return;
	}


	public function getRentalsBetween($latitudeA, $longitudeA, $latitudeB, $longitudeB)
	{
		$qb = $this->findRentalsBetween($latitudeA, $longitudeA, $latitudeB, $longitudeB);

		$rentals = $qb->getQuery()->getResult();

		$data = [];
		$translator = $this->environment->getTranslator();
		/** @var $rental \Entity\Rental\Rental */
		foreach($rentals as $rental) {
			$type = $translator->translate($rental->type->name);
			$locality = $translator->translate($rental->address->locality->name, null, ['case' => 'locative']);

			$amenities = [];
			foreach($rental->getImportantAmenities() as $amenity) {
				$amenities[] = $translator->translate($amenity->name);
			}

			$images = [];
			foreach($rental->getSortedImages(2) as $image) {
				$images[] = [
					'sort' => $image->getSort(),
					'filePath' => STATIC_DOMAIN . 'rental_images' . $image->getFilePath() . '/medium.jpeg',
				];
			}

			if(!count($images)) {
				$images[] = [
					'sort' => 0,
					'filePath' => STATIC_DOMAIN . 'no-rental-image.png',
				];

			}

			$data[] = [
				'id' => $rental->id,
				'name' => $translator->translate($rental->name),
				'subTitle' => ucfirst($type) . ' ' . $locality,
				'price' => $rental->getPrice(),
				'maxCapacity' => $rental->getMaxCapacity(),
				'amenities' => implode(', ', $amenities),
				'isPetAllowed' => $rental->getPetAmenity() ? TRUE : FALSE,
				// jedlo ?
				'email' => $rental->getContactEmail(),
				'phone' => $rental->getPhone()->getInternational(),
				'photos' => $images,
			];
		}

		return $data;
	}


	protected function findRentalsBetween($latitudeA, $longitudeA, $latitudeB, $longitudeB)
	{
		$qb = $this->rentalDao->createQueryBuilder('r');

		$qb->innerJoin('r.address', 'a');

		$qb = $this->findBetween($qb, $latitudeA, $longitudeA, $latitudeB, $longitudeB);

		return $qb;
	}


	protected function findBetween(QueryBuilder $qb, $latitudeA, $longitudeA, $latitudeB, $longitudeB)
	{
		$qb->andWhere('a.latitude < ?1')->setParameter('1', $latitudeA)
			->andWhere('a.longitude > ?2')->setParameter('2', $longitudeA)
			->andWhere('a.latitude < ?3')->setParameter('3', $latitudeB)
			->andWhere('a.longitude > ?4')->setParameter('4', $longitudeB);

		$qb->setMaxResults(200);

		return $qb;
	}



}
