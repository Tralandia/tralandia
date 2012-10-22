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
			->join('\Entity\Language', 'l', Expr\Join::ON, 'l.supported = 1')
			->leftJoin('\Entity\Phrase\Translation', 't', Expr\Join::ON, 'p.id = t.phrase AND l.id = t.language')
			->where($qb->expr()->idNull('t.id'));
		return $qb->getQuery()->getResult();
	}

}