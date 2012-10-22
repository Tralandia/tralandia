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
		$qb->select('p.id AS pId, l.id AS lId')
			->from('\Entity\Phrase\Phrase', 'p')
			->leftJoin('p.translations', 't', Expr\Join::ON, 'p.id = t.phrase AND l.id = t.language')
			->join('t.language', 'l', Expr\Join::ON, 'l.supported = 1')
			->where($qb->expr()->isNull('t.id'));

		return $qb->getQuery()->getResult();
	}

}