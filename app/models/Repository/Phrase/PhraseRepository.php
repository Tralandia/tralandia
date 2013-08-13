<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Language;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Doctrine\ORM\QueryBuilder;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PhraseRepository extends \Repository\BaseRepository {

	protected $languageRepositoryAccessor;

	public function inject($centralLanguage, $languageRepositoryAccessor) {
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
	}

	/**
	 * Vrati vnorene pole ID-cok fraz zoskupene podla jazyka ktore treba prelozit
	 * @var TRUE|FALSE
	 * @return array
	 */
	public function findMissingTranslations($languages = NULL, \Nette\Utils\Paginator $paginator = NULL)
	{
		$languages = $this->languageRepositoryAccessor->get()->findSupported();

		$array = array();
		foreach ($languages as $language) {
			$translations = $this->findMissingTranslationsBy($language, $paginator);
			$array[$language->id] = $translations;
		}

		return $array;
	}


	/**
	 * @return QueryBuilder
	 */
	public function findTranslatedQb()
	{

		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')->from($this->_entityName, 'e')
			->innerJoin('e.type', 'type')
			->andWhere($qb->expr()->eq('type.translated', ':translated'))->setParameter('translated', TRUE);

		return $qb;
	}


	/**
	 * @return int|number
	 */
	public function getTranslatedCount()
	{
		$qb = $this->findTranslatedQb();
		$paginator = new Paginator($qb);
		return $paginator->count();
	}


	/**
	 * @param $status
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findByStatusQb($status)
	{
		$qb = $this->createQueryBuilder();

		$qb->andWhere($qb->expr()->eq('e.status', ':status'))->setParameter('status', $status);

		$qb = $this->filterTranslatedTypes($qb);
		return $qb;
	}


	/**
	 * @param $status
	 *
	 * @return int|number
	 */
	public function getCountByStatus($status)
	{
		return $this->getCount($this->findByStatusQb($status));
	}


	/**
	 * @param \Entity\Language $language
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findNotCheckedTranslationsQb(Language $language)
	{
		$qb = $this->createQueryBuilder();

		$qb->innerJoin('e.translations', 't')
			->andWhere($qb->expr()->eq('t.language', ':language'))->setParameter('language', $language)
			->andWhere($qb->expr()->eq('t.status', ':status'))->setParameter('status', Phrase::WAITING_FOR_CORRECTION_CHECKING);

		$qb = $this->filterTranslatedTypes($qb);
		return $qb;
	}


	/**
	 * @param \Entity\Language $language
	 *
	 * @return int|number
	 */
	public function getNotCheckedCount(Language $language)
	{
		return $this->getCount($this->findNotCheckedTranslationsQb($language));
	}


	/**
	 * @param Language $language
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findMissingTranslationsByQb(Language $language)
	{

		# vyberiem frazy ktore maju preklad v danom jazyku
		$qb2 = $this->_em->createQueryBuilder();
		$qb2->select('e.id')
			->from('\Entity\Phrase\Phrase', 'e')
			->innerJoin('e.translations', 'translations')
			->innerJoin('e.type', 'type')
			->where('type.translateTo = :supported')
			->andWhere('translations.language = :language');

		# vyberiem vsetky zvisne frazy ktore som nevybral v tom hornom query
		# cize su to tie frazy kde chyba transaltion entita v danom jazyku
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
			->from('\Entity\Phrase\Phrase', 'p')
			->innerJoin('p.type', 'ttt')
			->where($qb->expr()->notIn('p.id', $qb2->getDQL()))
			->andWhere('ttt.translateTo = :supported')
			->setParameter('language', $language->getId())
			->setParameter('supported', \Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED);

		return $qb;
	}


	/**
	 * @param Language $language
	 *
	 * @return int|number
	 */
	public function findMissingTranslationsCountBy(Language $language)
	{
		$qb = $this->findMissingTranslationsByQb($language);
		return $this->getCount($qb) > 0;
	}


	/**
	 * Vrati vnorene pole ID-cok fraz zoskupene podla jazyka ktore treba prelozit
	 * @return array
	 */
	public function findMissingTranslationsBy(\Entity\Language $language, $limit = NULL, $offset = NULL)
	{
		$qb = $this->findMissingTranslationsByQb($language);

		if ($limit) $qb->setMaxResults($limit);
		if ($offset) $qb->setFirstResult($offset);


		$return = $qb->getQuery()->getResult();

		return $return;
	}

	/**
	 * @param QueryBuilder $qb
	 *
	 * @return QueryBuilder
	 */
	public function filterTranslatedTypes(QueryBuilder $qb)
	{
		$qb->innerJoin('e.type', 'type');

		$qb->andWhere('type.translated = :translated');
		$qb->setParameter('translated', TRUE);
		return $qb;
	}

}
