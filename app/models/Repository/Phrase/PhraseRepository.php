<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PhraseRepository extends \Repository\BaseRepository {

	public function findMissingTranslations() {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p.id AS pId, count(p.id) AS c')
			->from('\Entity\Phrase\Phrase', 'p')
			->leftJoin('p.translations', 't')
			->join('t.language', 'l')
			->where($qb->expr()->isNull('t.id'))
			->andWhere('l.supported = 1')
			->groupBy('p.id');

		return $qb->getQuery()->getResult();
	}

}