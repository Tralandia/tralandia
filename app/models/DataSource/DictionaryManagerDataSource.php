<?php

namespace DataSource;


use Dictionary\FindOutdatedTranslations;
use Doctrine\ORM\EntityManager;
use Entity\Language;
use Nette\ArrayHash;
use Nette\Forms\Controls\SelectBox;
use Nette\Utils\Paginator;
use Tralandia\Phrase\Translations;

class DictionaryManagerDataSource extends BaseDataSource
{

	/**
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	private $outdatedTranslations;


	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \Tralandia\Phrase\Translations
	 */
	private $translations;


	public function __construct(Translations $translations, EntityManager $em, FindOutdatedTranslations $outdatedTranslations)
	{
		$this->outdatedTranslations = $outdatedTranslations;
		$this->em = $em;
		$this->translations = $translations;
	}


	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		$languages = $languageRepository->findBySupported(Language::SUPPORTED);

		if(array_key_exists('id', $filter) && is_array($filter['id']) && count($filter['id'])) {
			$languages = $languageRepository->findById($filter['id']);
		}

		$return = [];
		/** @var $language \Entity\Language */
		foreach ($languages as $key => $language) {

			//$lastTranslation = $translationRepository->findLastTranslationDate($language);

			$row = new ArrayHash;
			$row['id'] = $language->getId();
			$row['language'] = $language;
			//$row['lastTranslation'] = $lastTranslation;
			$row['toTranslate'] = $this->outdatedTranslations->getWaitingForTranslationCount($language);
			$translationsToTranslate = $this->outdatedTranslations->getWaitingForTranslation($language);
			$row['wordsToTranslate'] = $this->translations->calculateWordsInTranslations($translationsToTranslate);
			$row['priceToTranslate'] = $language->getTranslationPriceForWords($row['wordsToTranslate']);

			$row['toCheck'] = $this->translations->toCheckCount($language);
			$translationsToCheck = $this->translations->toCheckQb($language)->getQuery()->getResult();
			$row['wordsToCheck'] = $this->translations->calculateWordsInTranslations($translationsToCheck);
			$row['priceToCheck'] = $language->getTranslationPriceForWords($row['wordsToCheck']);

			$row['wordsToPay'] = $this->translations->calculateWordsCountToPay($language);
			$row['translator'] = $language->getTranslator();

			$row['toTranslate'] = $row['toTranslate'] == 0 ? '' : $row['toTranslate'];
			$row['toCheck'] = $row['toCheck'] == 0 ? '' : $row['toCheck'];

			$row['wordsToPay'] = $row['wordsToPay'] == 0 ? '' : $row['wordsToPay'];
			$row['priceToPay'] = $language->getTranslationPriceForWords($row['wordsToPay']);


			$return[$key] = $row;
		}

		return $return;
	}

}
