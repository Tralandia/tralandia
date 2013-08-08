<?php


namespace Dictionary;


use Doctrine\ORM\EntityManager;
use Entity\Language;

class FulltextSearch {

	/**
	 * @var \Repository\Phrase\TranslationRepository
	 */
	private $translationRepository;


	public function __construct(EntityManager $em)
	{
		$this->translationRepository = $em->getRepository(TRANSLATION_ENTITY);
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
		return $this->translationRepository->getCount($qb);
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
		$qb = $this->translationRepository->createQueryBuilder();

		$phraseIsJoined = false;

		$qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);
		if(is_numeric($string)) {
			$searchInUserContent = TRUE; // lebo hlada podla ID-cka
			$qb->leftJoin('e.phrase', 'p');
			$phraseIsJoined = true;
			$qb->andWhere($qb->expr()->eq('p.id', ':string'))->setParameter('string', $string);
		} else {
			$qb->andWhere($qb->expr()->like('e.variations', ':string'))->setParameter('string', "%$string%");
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
