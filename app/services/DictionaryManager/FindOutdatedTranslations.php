<?php

namespace Dictionary;

use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Doctrine\ORM\EntityManager;

class FindOutdatedTranslations {

    /**
     * @var EntityManager
     */
    protected $_em;

	public function __construct(EntityManager $entityManager)
	{
		$this->_em = $entityManager;
	}

	public function getWaitingForCentral(\Entity\Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$query = $this->getTranslationsQuery($language, $limit, $offset);
		$query->select('e');
		$query->andWhere('e.translationStatus = :status');
		$query->setParameter('status', \Entity\Phrase\Translation::WAITING_FOR_CENTRAL);

		return $query->getQuery()->getResult();
	}

	public function getWaitingForCentralCount(\Entity\Language $language = NULL)
	{
		$query = $this->getTranslationsQuery($language);
		$query->select('count(e) as count');
		$query->andWhere('e.translationStatus = :status');
		$query->setParameter('status', \Entity\Phrase\Translation::WAITING_FOR_CENTRAL);

		return $query->getQuery()->getResult()[0]->count;
	}


	public function getWaitingForTranslation(\Entity\Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$query = $this->getTranslationsQuery($language);
		$query->select('e');
		$query->andWhere('e.translationStatus = :status');
		$query->setParameter('status', \Entity\Phrase\Translation::WAITING_FOR_TRANSLATION);

		return $query->getQuery()->getResult();
	}

	public function getWaitingForTranslationCount(\Entity\Language $language = NULL)
	{
		$query = $this->getTranslationsQuery($language);
		$query->select('count(e) as count');
		$query->andWhere('e.translationStatus = :status');
		$query->setParameter('status', \Entity\Phrase\Translation::WAITING_FOR_TRANSLATION);

		return $query->getQuery()->getResult()[0]->count;
	}

	private function getTranslationsQuery(\Entity\Language $language = NULL, $limit = NULL, $offset = NULL)
	{
		$query = $this->_em->createQueryBuilder();
		$query->from('\Entity\Phrase\Translation', 'e');
		$query->leftJoin('e.phrase', 'phrase');
		$query->leftJoin('phrase.type', 'type');

		$query->andWhere('type.translated = :translated');
		$query->setParameter('translated', TRUE);

		if ($language) {
			$query->andWhere('e.language = :language');
			$query->setParameter('language', $language);
		}

		if ($limit) $query->setMaxResults($limit);
		if ($offset) $query->setFirstResult($offset);

		return $query;
	}


}
