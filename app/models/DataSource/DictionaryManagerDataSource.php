<?php

namespace DataSource;


use Dictionary\FindOutdatedTranslations;
use Doctrine\ORM\EntityManager;
use Nette\ArrayHash;
use Nette\Forms\Controls\SelectBox;
use Nette\Utils\Paginator;

class DictionaryManagerDataSource extends BaseDataSource
{

	/**
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	private $outdatedTranslations;

	/**
	 * @var \SupportedLanguages
	 */
	private $supportedLanguages;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	public function __construct(\SupportedLanguages $supportedLanguages, EntityManager $em, FindOutdatedTranslations $outdatedTranslations)
	{
		$this->outdatedTranslations = $outdatedTranslations;
		$this->supportedLanguages = $supportedLanguages;
		$this->em = $em;
	}


	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		/** @var $translationRepository \Repository\Phrase\TranslationRepository */
		$translationRepository = $this->em->getRepository(TRANSLATION_ENTITY);
		$languages = $this->supportedLanguages->getSortedByIso();

		if(array_key_exists('id', $filter) && is_array($filter['id']) && count($filter['id'])) {
			$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
			$languages = $languageRepository->findById($filter['id']);
		}

		$return = [];
		/** @var $language \Entity\Language */
		foreach ($languages as $key => $language) {

			$lastTranslation = $translationRepository->findLastTranslationDate($language);
			//$lastTranslation = new \DateTime($lastTranslation);

			$row = new ArrayHash;
			$row['id'] = $language->getId();
			$row['language'] = $language;
			$row['lastTranslation'] = $lastTranslation;
			$row['toTranslate'] = $this->outdatedTranslations->getWaitingForTranslationCount($language);
			$row['toCheck'] = $translationRepository->toCheckCount($language);
			$row['translator'] = $language->getTranslator();
			$row['wordsToPay'] = $translationRepository->calculateWordsCountToPay($language);

			$return[$key] = $row;
		}

		return $return;
	}

}
