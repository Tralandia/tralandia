<?php

namespace Dictionary;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Doctrine\ORM\EntityManager;
use Tralandia\Phrase\Translations;

class FindOutdatedTranslations {

	/**
	 * @var EntityManager
	 */
	protected $_em;

	/**
	 * @var \Tralandia\Phrase\Translations
	 */
	private $translations;


	public function __construct(Translations $translations, EntityManager $entityManager)
	{
		$this->_em = $entityManager;
		$this->translations = $translations;
	}

	public function getWaitingForCentral(\Entity\Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$query = $this->getTranslationsQuery($language, $limit, $offset);
		$query->andWhere('e.status = :status');
		$query->setParameter('status', Translation::WAITING_FOR_CENTRAL);

		return $query->getQuery()->getResult();
	}

	public function getWaitingForCentralCount(\Entity\Language $language = NULL)
	{
		$query = $this->getTranslationsQuery($language);
		$query->andWhere('e.status = :status');
		$query->setParameter('status', Translation::WAITING_FOR_CENTRAL);

		$p = new Paginator($query);
		return $p->count();
	}


	public function getWaitingForTranslation(\Entity\Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$query = $this->getTranslationsQuery($language, $limit, $offset);
		$query->andWhere('e.status = :status');
		$query->setParameter('status', Translation::WAITING_FOR_TRANSLATION);

		return $query->getQuery()->getResult();
	}

	public function getWaitingForTranslationCount(\Entity\Language $language = NULL)
	{
		$query = $this->getTranslationsQuery($language);
		$query->andWhere('e.status = :status');
		$query->setParameter('status', Translation::WAITING_FOR_TRANSLATION);

		$p = new Paginator($query);
		return $p->count();
	}


	/**
	 * @param \Entity\Language $language
	 * @param null $limit
	 * @param null $offset
	 *
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	private function getTranslationsQuery(\Entity\Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$query = $this->_em->createQueryBuilder();
		$query->select('e');
		$query->from('\Entity\Phrase\Translation', 'e');

		$query = $this->translations->filterTranslatedTypes($query);

		if ($language) {
			$query->andWhere('e.language = :language');
			$query->setParameter('language', $language);
		}

		if ($limit) $query->setMaxResults($limit);
		if ($offset) $query->setFirstResult($offset);

		return $query;
	}


}
