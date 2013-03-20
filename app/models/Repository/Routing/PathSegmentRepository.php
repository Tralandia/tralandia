<?php
namespace Repository\Routing;

use Doctrine\ORM\Query\Expr;
use Entity\Language;
use Entity\Location\Location;
use Routers\FrontRoute;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PathSegmentRepository extends \Repository\BaseRepository {

	/**
	 * @param \Entity\Language $language
	 * @param \Entity\Location\Location $location
	 * @param array $pathSegments
	 *
	 * @return array
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
	 * @param \Entity\Language $language
	 *
	 * @return array
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
	 * @param \Entity\Location\Location $location
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getBasicQB(Language $language = NULL, Location $location = NULL)
	{
		$qb = $this->_em->createQueryBuilder();

		$languageOr = $qb->expr()->orx();
		if($language) $languageOr->add($qb->expr()->eq('e.language', $language->getId()));
		$languageOr->add($qb->expr()->isNull('e.language'));

		$primaryLocationOr = $qb->expr()->orx();
		if($location) $primaryLocationOr->add($qb->expr()->eq('e.primaryLocation', $location->getId()));
		$primaryLocationOr->add($qb->expr()->isNull('e.primaryLocation'));

		$qb->select('e')
			->from($this->_entityName, 'e')
			->where($languageOr)
			->andWhere($primaryLocationOr);

		return $qb;
	}
}
