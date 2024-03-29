<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/9/13 12:33 PM
 */

namespace Tralandia\Dictionary;


use Doctrine\ORM\EntityManager;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\Phrase\Type;
use Nette;
use Tralandia\Language\Languages;

class PhraseManager {

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $translationDao;

	/**
	 * @var \Tralandia\Language\Languages
	 */
	private $languages;


	/**
	 * @param Languages $languages
	 * @param EntityManager $em
	 */
	public function __construct(Languages $languages, EntityManager $em)
	{
		$this->translationDao = $em->getRepository(TRANSLATION_ENTITY);
		$this->languages = $languages;
	}


	public function updateTranslations(Phrase $phrase, array $translationsVariations)
	{
		$return = [
			'oldVariations' => [],
			'changedTranslations' => [],
			'displayedTranslations' => []
		];
		$languages = $this->languages->findPairsByIso(array_keys($translationsVariations));
		$sourceLanguage = $phrase->getSourceLanguage()->getIso();
		$centralLanguage = $this->languages->findCentral()->getIso();
		foreach($translationsVariations as $languageIso => $variations) {
			$isSourceLanguage = $sourceLanguage == $languageIso;
			$isCentralLanguage = $centralLanguage == $languageIso;

			# zistim ci updatujem vsetky variacie alebo len defaultnu
			if(is_array($variations)) {
				$deleteTranslation = !$isSourceLanguage && !$isCentralLanguage && $this->isVariationsEmpty($variations);
			} else {
				$defaultTranslation = $variations;
				$deleteTranslation = !$isSourceLanguage && !$isCentralLanguage && !strlen(trim($defaultTranslation));
			}


			$language = $languages[$languageIso];
			$translation = $phrase->getTranslation($language);

			# zmazem translation a konec
			if($deleteTranslation && $phrase->getType()->getTranslateTo() == Type::TRANSLATE_TO_NONE) {
				if($translation) {
					$this->removeTranslation($phrase, $translation);
				}
				continue;
			}

			# vytvorim novy translation ak treba
			if(!$translation) {
				$translation = $phrase->createTranslation($language);
			}

			# skontrolujem ci sa translation realne zmenil
			if(isset($defaultTranslation)) {
				$oldDefaultTranslation = $translation->getTranslation();
				$translationIsChanged = $oldDefaultTranslation != $defaultTranslation;
			} else {
				$oldVariations = $translation->getVariations();
				$translationIsChanged = $oldVariations != $variations;
			}

			if($translationIsChanged) {
				$return['changedTranslations'][] = $translation;
				$return['oldVariations'][$translation->getId()] = $translation->getVariations();

				# update translation
				if(isset($defaultTranslation)) {
					$translation->setTranslation($defaultTranslation);
				} else {
					$translation->updateVariations($variations);
				}
			}

			$return['displayedTranslations'][] = $translation;

		}

		return $return;
	}


	private function removeTranslation(Phrase $phrase, Translation $translation)
	{
		$phrase->removeTranslation($translation);
		$this->translationDao->delete($translation);
	}


	private function isVariationsEmpty(array $variations)
	{
		$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($variations));
		foreach($iterator as $value) {
			if(strlen(trim($value))) return FALSE;
		}
		return TRUE;
	}

}
