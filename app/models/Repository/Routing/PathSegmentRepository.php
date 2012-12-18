<?php
namespace Repository\Routing;

use Doctrine\ORM\Query\Expr;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PathSegmentRepository extends \Repository\BaseRepository {

	public function findForRouter($language, $primaryLocation ,array $pathSegments)
	{
		$qb = $this->_em->createQueryBuilder();

		$languageOr = $qb->expr()->orx();
		$languageOr->add($qb->expr()->eq('e.language', $language->id));
		$languageOr->add($qb->expr()->isNull('e.language'));

		$primaryLocationOr = $qb->expr()->orx();
		$primaryLocationOr->add($qb->expr()->eq('e.primaryLocation', $primaryLocation->id));
		$primaryLocationOr->add($qb->expr()->isNull('e.primaryLocation'));

		$qb->select('e')
			->from($this->_entityName, 'e')
			->where($languageOr)
			->andWhere($primaryLocationOr)
			->andWhere($qb->expr()->in('e.pathSegment', $pathSegments))
			->orderBy($qb->expr()->asc('e.type'));

		$t = $qb->getQuery()->getResult();
		return $t;
	}
}