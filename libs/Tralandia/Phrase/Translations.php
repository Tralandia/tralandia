<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/11/13 9:53 AM
 */

namespace Tralandia\Phrase;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Language;
use Entity\Phrase\Translation;
use Entity\User\User;
use Nette;
use Tralandia\BaseDao;

class Translations
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $translationDao;


	public function __construct(BaseDao $translationDao)
	{
		$this->translationDao = $translationDao;
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
		$qb = $this->translationDao->createQueryBuilder('e');

		$qb->where($qb->expr()->eq('e.status', ':status'))
			->setParameter('status', Translation::WAITING_FOR_TRANSLATION);

		if($languages) {
			$qb->andWhere($qb->expr()->in('r.languages', $languages));
		}

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param Language $language
	 *
	 * @return QueryBuilder
	 */
	public function toCheckQB(Language $language = NULL)
	{
		$qb = $this->translationDao->createQueryBuilder('e');

		$qb->where($qb->expr()->eq('e.status', ':status'))->setParameter('status', Translation::WAITING_FOR_CHECKING);

		if($language) $qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);

		$qb = $this->filterTranslatedTypes($qb);

		return $qb;
	}


	/**
	 * @param Language $language
	 *
	 * @return int|number
	 */
	public function toCheckCount(Language $language)
	{
		$qb = $this->toCheckQB($language);
		$paginator = new Paginator($qb);
		return $paginator->count();
	}


	/**
	 * @param Language $language
	 *
	 * @return QueryBuilder
	 */
	public function findNotPaidQb(Language $language)
	{
		$qb = $this->translationDao->createQueryBuilder('e');

		$qb->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->eq('e.status', ':status'))->setParameter('status', Translation::WAITING_FOR_PAYMENT);

		$qb = $this->filterTranslatedTypes($qb);

		return $qb;
	}


	/**
	 * @param Language $language
	 *
	 * @return \Entity\Phrase\Translation[]
	 */
	public function findNotPaid(Language $language)
	{
		$qb = $this->findNotPaidQb($language);

		$translations = $qb->getQuery()->getResult();

		return $translations;
	}


	/**
	 * @param Language $language
	 *
	 * @return float|int
	 */
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


	/**
	 * @param Language $language
	 *
	 * @return float|int
	 */
	public function calculateTranslatedWordsCount(Language $language)
	{
		$qb = $this->translationDao->createQueryBuilder('e');

		$qb->where($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->in('e.status', ':status'))->setParameter('status', [Translation::WAITING_FOR_PAYMENT, Translation::WAITING_FOR_CHECKING]);

		$qb = $this->filterTranslatedTypes($qb);

		$translations = $qb->getQuery()->getResult();
		return $this->calculateWordsInTranslations($translations);
	}


	/**
	 * @param array $translations
	 *
	 * @return float|int
	 */
	public function calculateWordsInTranslations(array $translations)
	{
		$totalCount = 0;
		/** @var $translation \Entity\Phrase\Translation */
		foreach($translations as $translation) {
			$totalCount += $translation->getPhrase()->getWordsCount($translation->getLanguage());
		}

		return $totalCount;
	}


	/**
	 * @param Language $language
	 * @param User $responsibleUser
	 *
	 * @return array
	 */
	public function markAsPaid(Language $language, User $responsibleUser)
	{
		$notPaidQb = $this->findNotPaidQb($language)
			->select('e.id');

		$ids = $notPaidQb->getQuery()->getResult();
		$ids = \Tools::arrayMap($ids, 'id');

		if(count($ids)) {
			$qb = $this->translationDao->createQueryBuilder();
			$qb->update(TRANSLATION_ENTITY, 'e')
				->set('e.status', ':status')->setParameter('status', Translation::UP_TO_DATE)
				->set('e.unpaidAmount', ':unpaidAmount')->setParameter('unpaidAmount', NULL)
				->where($qb->expr()->in('e.id', $ids));

			$qb->getQuery()->execute();
		}
		return $ids;
	}

}
