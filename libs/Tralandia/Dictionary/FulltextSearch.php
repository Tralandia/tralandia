<?php


namespace Tralandia\Dictionary;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Language;
use Tralandia\BaseDao;

class FulltextSearch {

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $translationDao;


	public function __construct(BaseDao $translationDao)
	{
		$this->translationDao = $translationDao;
	}


	public function getResult($string, Language $language = NULL, $searchInUserContent = FALSE, $limit = NULL, $offset = NULL)
	{
		$qb = $this->createQb($string, $language, $searchInUserContent)
			->setMaxResults($limit)
			->setFirstResult($offset);

		return $qb->getQuery()->getResult();
	}


	public function getResultCount($string, Language $language = NULL, $searchInUserContent = FALSE)
	{
		$qb = $this->createQb($string, $language, $searchInUserContent);
		return (new Paginator($qb))->count();
	}


	/**
	 * @param $string
	 * @param \Entity\Language $language
	 * @param bool $searchInUserContent
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function createQb($string, Language $language = NULL, $searchInUserContent = FALSE)
	{
		$qb = $this->translationDao->createQueryBuilder('e');

		$phraseIsJoined = FALSE;
		$joinLanguages = TRUE;

		if(is_numeric($string)) {
			$searchInUserContent = TRUE; // lebo hlada podla ID-cka
			$qb->leftJoin('e.phrase', 'p');
			$phraseIsJoined = TRUE;
			$qb->andWhere($qb->expr()->eq('p.id', ':string'))->setParameter('string', $string);
			$joinLanguages = FALSE;
		} else {
			$qb->andWhere($qb->expr()->like('e.variations', ':string'))->setParameter('string', "%$string%");
		}

		if($joinLanguages) {
			$qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);
		} else {
			$qb->groupBy('p.id');
		}

		if(!$searchInUserContent) {
			if(!$phraseIsJoined) {
				$qb->leftJoin('e.phrase', 'p');
				$phraseIsJoined = true;
			}
			$qb->innerJoin('p.type', 't');
			$qb->andWhere($qb->expr()->notIn('t.entityName', ['\Entity\Rental\Rental', '\Entity\Rental\InterviewAnswer']));
		}

		return $qb;
	}

}
