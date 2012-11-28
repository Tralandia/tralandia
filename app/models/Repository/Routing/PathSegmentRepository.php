<?php
namespace Repository\Routing;

use Doctrine\ORM\Query\Expr;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PathSegmentRepository extends \Repository\BaseRepository {

	public function findForRouter($language, $country ,array $pathSegments)
	{
		$qb = $this->_em->createQueryBuilder();

		$languageOr = $qb->expr()->orx();
		$languageOr->add($qb->expr()->eq('e.language', $language));
		$languageOr->add($qb->expr()->isNull('e.language'));

		$countryOr = $qb->expr()->orx();
		$countryOr->add($qb->expr()->eq('e.country', $country));
		$countryOr->add($qb->expr()->isNull('e.country'));

		$qb->select('e')
			->from($this->_entityName, 'e')
			->where($languageOr)
			->andWhere($countryOr)
			->andWhere($qb->expr()->in('e.pathSegment', $pathSegments))
			->orderBy($qb->expr()->asc('e.type'));

		return $qb->getQuery()->getResult();
	}
}