<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/27/13 12:48 PM
 */

namespace Tralandia\Routing;


use Entity\Language;
use Entity\Location\Location;
use Entity\Rental\Type;
use Nette;
use Routers\FrontRoute;
use Tralandia\BaseDao;

class PathSegments {


	/**
	 * @var \Tralandia\BaseDao
	 */
	private $pathSegmentDao;


	/**
	 * @param BaseDao $pathSegmentDao
	 */
	public function __construct(BaseDao $pathSegmentDao)
	{
		$this->pathSegmentDao = $pathSegmentDao;
	}


	/**
	 * @param \Entity\Language $language
	 * @param \Entity\Location\Location $location
	 * @param array $pathSegments
	 *
	 * @return \Entity\Routing\PathSegment[]
	 */
	public function findForRouter(Language $language, Location $location ,array $pathSegments)
	{
		$qb = $this->getBasicQB($language, $location);

		$qb->andWhere($qb->expr()->in('e.pathSegment', $pathSegments))
			->orderBy($qb->expr()->asc('e.type'));

		$t = $qb->getQuery()->getResult();
		return $t;
	}


	/**
	 * @param Language $language
	 * @param Location $location
	 * @param $pathSegment
	 *
	 * @return \Entity\Routing\PathSegment|null
	 */
	public function findOneForRouter(Language $language, Location $location , $pathSegment)
	{
		$qb = $this->getBasicQB($language, $location);

		$qb->andWhere($qb->expr()->eq('e.pathSegment', ':pathSegment'))
			->setParameter(':pathSegment', $pathSegment);

		$qb->setMaxResults(1)
			->orderBy('e.id', 'DESC');

		$t = $qb->getQuery()->getOneOrNullResult();

		return $t;
	}

	/**
	 * @param \Entity\Language $language
	 *
	 * @return \Entity\Routing\PathSegment[]
	 */
	public function findRentalTypes(Language $language)
	{
		$qb = $this->getBasicQB($language);

		$qb->andWhere($qb->expr()->eq('e.type', FrontRoute::$pathSegmentTypes[FrontRoute::RENTAL_TYPE]));

		$t = $qb->getQuery()->getResult();
		return $t;
	}

	/**
	 * @param \Entity\Language $language
	 * @param \Entity\Rental\Type $rentalType
	 *
	 * @return \Entity\Routing\PathSegment[]
	 */
	public function findRentalType(Language $language, Type $rentalType)
	{
		$qb = $this->getBasicQB($language);

		$qb->andWhere($qb->expr()->eq('e.type', FrontRoute::$pathSegmentTypes[FrontRoute::RENTAL_TYPE]))
			->andWhere($qb->expr()->eq('e.entityId', $rentalType->getId()));

		$t = $qb->getQuery()->getOneOrNullResult();
		return $t;
	}


	/**
	 * @param \Entity\Language $language
	 * @param \Entity\Location\Location $location
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getBasicQB(Language $language = NULL, Location $location = NULL)
	{
		$qb = $this->pathSegmentDao->createQueryBuilder('e');

		$languageOr = $qb->expr()->orx();
		if($language) $languageOr->add($qb->expr()->eq('e.language', $language->getId()));
		$languageOr->add($qb->expr()->isNull('e.language'));

		$primaryLocationOr = $qb->expr()->orx();
		if($location) $primaryLocationOr->add($qb->expr()->eq('e.primaryLocation', $location->getId()));
		$primaryLocationOr->add($qb->expr()->isNull('e.primaryLocation'));

		$qb->where($languageOr)
			->andWhere($primaryLocationOr);

		return $qb;
	}

}
