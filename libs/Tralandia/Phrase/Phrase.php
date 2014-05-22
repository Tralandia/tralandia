<?php

namespace Tralandia\Phrase;

use Nette;


/**
 * @property \Tralandia\Phrase\Translation[] $translations m:belongsToMany
 * @property \Tralandia\Language\Language $sourceLanguage m:hasOne(sourceLanguage_id:)
 */
class Phrase extends \Tralandia\Lean\BaseEntity
{

	const REQUESTED = 1;
	const CENTRAL = 5;
	const SOURCE = 10;


	/**
	 * @return int
	 */
	public function getTranslationsCount()
	{
		return count($this->translations);
	}


	/**
	 * Vrati translation-y v ziadanom, centralom a source jazyku, ak existuju
	 *
	 * @param \Tralandia\Language\Language $language
	 * @return \Tralandia\Phrase\Translation[]
	 */
	public function getMainTranslations(\Tralandia\Language\Language $language = NULL) {
		$t = array();

		foreach ($this->translations as $value) {
			if ($language && $value->language->id == $language->id) {
				$t[self::REQUESTED] = $value;
			}

			if ($value->language->id == CENTRAL_LANGUAGE) {
				$t[self::CENTRAL] = $value;
			}

			if ($this->sourceLanguage && $value->language->id == $this->sourceLanguage->id) {
				$t[self::SOURCE] = $value;
			}
		}

		ksort($t);

		return $t;
	}


	/**
	 * @return Translation|null
	 */
	public function getCentralTranslation() {
		$mainTranslations = $this->getMainTranslations();
		return array_key_exists(self::CENTRAL, $mainTranslations) ? $mainTranslations[self::CENTRAL] : NULL;
	}


	/**
	 * @return NULL|string
	 */
	public function getCentralTranslationText() {
		$translation = $this->getCentralTranslation();
		return $translation ? $translation->translation : NULL;
	}



}
