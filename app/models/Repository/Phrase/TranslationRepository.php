<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

	public function toCheckCount(Language $language)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')->from($this->_entityName, 'e')
			->where($qb->expr()->eq('e.checked', ':checked'))->setParameter('checked', FALSE)
			->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);

		$paginator = new Paginator($qb);
		return $paginator->count();
	}

	public function findLastTranslationDate(Language $language)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('MAX(e.timeTranslated)')->from($this->_entityName, 'e')
			->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);

		$qb = $this->filterTranslatedTypes($qb);

		return $qb->getQuery()->getSingleScalarResult();
	}


	/**
	 * @param QueryBuilder $qb
	 *
	 * @return QueryBuilder
	 */
	public function filterTranslatedTypes(QueryBuilder $qb)
	{
		$qb->leftJoin('e.phrase', 'phrase');
		$qb->leftJoin('phrase.type', 'type');

		$qb->andWhere('type.translated = :translated');
		$qb->setParameter('translated', TRUE);
		return $qb;
	}


	public function calculateWordsCountToPay(Language $language)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')->from($this->_entityName, 'e')
			->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->eq('e.paid', ':paid'))->setParameter('paid', FALSE)
			->andWhere($qb->expr()->eq('e.checked', ':checked'))->setParameter('checked', TRUE);

		$qb = $this->filterTranslatedTypes($qb);

		$translations = $qb->getQuery()->getResult();
		$totalCount = 0;
		/** @var $translation \Entity\Phrase\Translation */
		foreach($translations as $translation) {
			$totalCount += $translation->getPhrase()->getWordsCount($language);
		}

		return $totalCount;
	}

}
