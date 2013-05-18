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


	public function getResult($string, Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$qb = $this->createQb($string, $language)
			->setMaxResults($limit)
			->setFirstResult($offset);

		return $qb->getQuery()->getResult();
	}


	public function getResultCount($string, Language $language = NULL)
	{
		$qb = $this->createQb($string, $language);
		return $this->translationRepository->getCount($qb);
	}


	/**
	 * @param $string
	 * @param \Entity\Language $language
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function createQb($string, Language $language = NULL)
	{
		$qb = $this->translationRepository->createQueryBuilder();

		$qb->andWhere($qb->expr()->eq('e.language', ':language'))->setParameter('language', $language);
		$qb->andWhere($qb->expr()->like('e.variations', ':string'))->setParameter('string', "%$string%");

		return $qb;
	}

}
