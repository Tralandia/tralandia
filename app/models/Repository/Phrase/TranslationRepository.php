<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;
use Entity\Language;
use Entity\Phrase\Translation;

/**
 * TranslationRepository class
 *
 * @author Dávid Ďurika
 */
class TranslationRepository extends \Repository\BaseRepository {

	/**
	 * Vyberie preklady kt. treba prelozit (aktualizovat)
	 *
	 * @param array|null $languages
	 *
	 * @return array
	 */
	public function toTranslate($languages = NULL) {
		if($languages !== NULL && !is_array($languages)) {
			$languages = [$languages];
		}
		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')->from($this->_entityName, 'e')
			->where($qb->expr()->eq('e.translationStatus', ':status'))
			->setParameter('status', Translation::WAITING_FOR_TRANSLATION);

		if($languages) {
			$qb->andWhere($qb->expr()->in('r.languages', $languages));
		}

		return $qb->getQuery()->getResult();
	}

}
