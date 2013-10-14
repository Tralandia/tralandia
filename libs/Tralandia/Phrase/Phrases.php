<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/11/13 10:18 AM
 */

namespace Tralandia\Phrase;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Language;
use Entity\Phrase\Phrase;
use Nette;
use Tralandia\BaseDao;

class Phrases
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $phraseDao;


	/**
	 * @param BaseDao $phraseDao
	 */
	public function __construct(BaseDao $phraseDao)
	{
		$this->phraseDao = $phraseDao;
	}


	/**
	 * @param $status
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function findByStatusQb($status)
	{
		$qb = $this->phraseDao->createQueryBuilder('e');

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


	/**
	 * @param Language $language
	 *
	 * @return QueryBuilder
	 */
	public function findNotCheckedTranslationsQb(Language $language)
	{
		$qb = $this->phraseDao->createQueryBuilder('e');

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
	 * @param $qb
	 *
	 * @return int|number
	 */
	private function getCount($qb)
	{
		return (new Paginator($qb))->count();
	}

}
