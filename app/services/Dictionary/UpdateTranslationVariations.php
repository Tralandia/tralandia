<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/25/13 3:18 PM
 */

namespace Dictionary;


use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Nette;

class UpdateTranslationVariations {

	public function update(Translation $translation)
	{
		$matrix = $translation->getPhrase()->getTranslationVariationsMatrix($translation->getLanguage());
		$variations = $translation->getVariations();

		$newVariations = $matrix;
		foreach ($matrix as $pluralKey => $genders) {
			foreach ($genders as $genderKey => $cases) {
				foreach ($cases as $caseKey => $caseValue) {
					$variation = Nette\Utils\Arrays::get($variations, array($pluralKey, $genderKey, $caseKey), NULL);
					if($variation) $newVariations[$pluralKey][$genderKey][$caseKey] = $variation;
				}
			}
		}
		$translation->setVariations($newVariations);
	}


	public function updatePhrase(Phrase $phrase)
	{
		foreach($phrase->getTranslations() as $translation) {
			$this->update($translation);
		}
	}

}
