<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class PhraseRepository extends \Repository\BaseRepository {

	protected $languageRepositoryAccessor;

	protected $centralLanguage;

	public function inject($centralLanguage, $languageRepositoryAccessor) {
		$this->centralLanguage = $centralLanguage;
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
	}

	/**
	 * Vrati vnorene pole ID-cok fraz zoskupene podla jazyka ktore treba prelozit
	 * @return array
	 */
	public function findMissingTranslations()
	{
		$languages = $this->languageRepositoryAccessor->get()->findSupported();
		$array = array();
		foreach ($languages as $language) {
			
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

			$array[$language->id] = $qb->getQuery()->getResult();
		}

		return $array;
	}

}