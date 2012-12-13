<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PhraseRepository extends \Repository\BaseRepository {

	public function findMissingTranslations(array $languages) {

		$array = array();
		foreach ($languages as $language) {
			$qb = $this->_em->createQueryBuilder();
			$qb2 = $this->_em->createQueryBuilder();
			$qb2->select('e.id')
				->from('\Entity\Phrase\Phrase', 'e')
				->leftJoin('e.translations', 't')
				->where('t.language = :lll')->setParameter(':lll', $language->id);

			$qb->select('p.id')
				->from('\Entity\Phrase\Phrase', 'p')
				->where($qb->expr()->notIn('p.id', $qb2->getDQL()));

			$array[$language->id] = $qb->getQuery()->getResult();
		}

		return $array;
	}

}