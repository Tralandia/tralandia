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
		$qb->select('p.id AS pId')
			->from('\Entity\Phrase\Phrase', 'p')
			->leftJoin('p.translations', 't')
			->where($qb->expr()->isNull('t.id'))
			->andWhere('t.language = 1');

		return $qb->getQuery()->getResult();
	}

}

// select * from phrase where id not in (
// SELECT p0_.id
// FROM phrase p0_ 
// LEFT JOIN phrase_translation p1_ ON p0_.id = p1_.phrase_id 
// where p1_.language_id = 38)