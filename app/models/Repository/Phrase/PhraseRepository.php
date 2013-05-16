<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
	public function findMissingTranslations(\Nette\Utils\Paginator $paginator=NULL)
	{
		$languages = $this->languageRepositoryAccessor->get()->findSupported();

		$array = array();
		foreach ($languages as $language) {
			$translations = $this->findMissingTranslationsBy($language, $paginator);
			$array[$language->id] = $translations;
		}

		return $array;
	}


	public function findTranslatedQb()
	{

		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')->from($this->_entityName, 'e')
			->leftJoin('e.type', 'type')
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
	 * Vrati vnorene pole ID-cok fraz zoskupene podla jazyka ktore treba prelozit
	 * @return array
	 */
	public function findMissingTranslationsBy(\Entity\Language $language, \Nette\Utils\Paginator $paginator=NULL)
	{
		# vyberiem frazy ktore maju preklad v danom jazyku
		$qb2 = $this->_em->createQueryBuilder();
		$qb2->select('e.id')
			->from('\Entity\Phrase\Phrase', 'e')
			->leftJoin('e.translations', 'translations')
			->leftJoin('e.type', 'type')
			->where('type.translateTo = :supported')
			->andWhere('translations.language = :language');

		# vyberiem vsetky zvisne frazy ktore som nevybral v tom hornom query
		# cize su to tie frazy kde chyba transaltion entita v danom jazyku
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
			->from('\Entity\Phrase\Phrase', 'p')
			->leftJoin('p.type', 'ttt')
			->where($qb->expr()->notIn('p.id', $qb2->getDQL()))
			->andWhere('ttt.translateTo = :supported')
			->setParameter('language', $language->id)
			->setParameter('supported', \Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED);

		if ($paginator) {
			$qb->setFirstResult(($paginator->page - 1) * $paginator->itemsPerPage);
			$qb->setMaxResults($paginator->itemsPerPage);
		}

		$return = $qb->getQuery()->getResult();

		return $return;
	}

}
