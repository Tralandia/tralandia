<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;
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
		$qb = $this->createQueryBuilder();

		$qb->where($qb->expr()->eq('e.status', ':status'))
			->setParameter('status', Translation::WAITING_FOR_TRANSLATION);

		if($languages) {
			$qb->andWhere($qb->expr()->in('r.languages', $languages));
		}

		return $qb->getQuery()->getResult();
	}


	/**
	 * @return mixed
	 */
	public function haveDuplicates()
	{
		$dql = 'SELECT count(t) AS c FROM \Entity\Phrase\Translation AS t GROUP BY t.phrase, t.language HAVING c > 1';
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setMaxResults(1);

		return (bool) count($query->getResult());
	}


	public function toCheckQB(Language $language = NULL)
	{
		$qb = $this->createQueryBuilder();

		$qb->where($qb->expr()->eq('e.status', ':status'))->setParameter('status', Translation::WAITING_FOR_CHECKING);

		if($language) $qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);

		$qb = $this->filterTranslatedTypes($qb);

		return $qb;
	}


	public function toCheckCount(Language $language)
	{
		$qb = $this->toCheckQB($language);
		$paginator = new Paginator($qb);
		return $paginator->count();
	}

	/**
	 * @param QueryBuilder $qb
	 *
	 * @return QueryBuilder
	 */
	public function filterTranslatedTypes(QueryBuilder $qb)
	{
		$qb->innerJoin('e.phrase', 'phrase');
		$qb->innerJoin('phrase.type', 'type');

		$qb->andWhere('type.translated = :translated');
		$qb->setParameter('translated', TRUE);
		return $qb;
	}


	public function findNotPaidQb(Language $language)
	{
		$qb = $this->createQueryBuilder();

		$qb->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->eq('e.status', ':status'))->setParameter('status', Translation::WAITING_FOR_PAYMENT);

		$qb = $this->filterTranslatedTypes($qb);

		return $qb;
	}

	public function findNotPaid(Language $language)
	{
		$qb = $this->findNotPaidQb($language);

		$translations = $qb->getQuery()->getResult();

		return $translations;
	}


	public function calculateWordsCountToPay(Language $language)
	{
		$translations = $this->findNotPaid($language);
		$totalCount = 0;
		/** @var $translation \Entity\Phrase\Translation */
		foreach($translations as $translation) {
			$totalCount += $translation->getPhrase()->getWordsCount($language);
		}

		return $totalCount;
	}


	public function sumUnpaidAmount(Language $language, $status)
	{
		$qb = $this->createQueryBuilder();

		$qb->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->eq('e.status', ':status'))->setParameter('status', $status);

		$qb = $this->filterTranslatedTypes($qb);

		$qb->select('sum(e.unpaidAmount)');

		$sum = $qb->getQuery()->getSingleScalarResult();

		return $sum ? $sum : 0;
	}


	public function calculateTranslatedWordsCount(Language $language)
	{
		$qb = $this->createQueryBuilder();

		$qb->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->in('e.status', ':status'))->setParameter('status', [Translation::WAITING_FOR_PAYMENT, Translation::WAITING_FOR_CHECKING]);

		$qb = $this->filterTranslatedTypes($qb);

		$translations = $qb->getQuery()->getResult();
		return $this->calculateWordsInTranslations($translations);
	}


	public function calculateWordsInTranslations(array $translations)
	{
		$totalCount = 0;
		/** @var $translation \Entity\Phrase\Translation */
		foreach($translations as $translation) {
			$totalCount += $translation->getPhrase()->getWordsCount($translation->getLanguage());
		}

		return $totalCount;
	}

}
