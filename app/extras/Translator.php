<?php

namespace Extras;

use Service\Dictionary as D,
	Nette\Caching;

class Translator implements \Nette\Localization\ITranslator {

	const DEFAULT_LANGUAGE = 38;

	protected $language = 144;
	protected $cache;

	public function __construct(Environment $environment, Caching\IStorage $cacheStorage) {
		$this->language = $environment->getLanguage();
		$this->cache = new Caching\Cache($cacheStorage, 'Translator');
	}
	
	public function translate($phrase, $node = NULL, $count = NULL, array $variables = NULL) {
		$translation = $this->getTranslation($phrase);

		return $translation;
	}
	
	protected function getTranslation($phrase) {
		if($phrase instanceof D\Phrase || $phrase instanceof \Entity\Dictionary\Phrase) {
			$translationKey = $phrase->id;
		} else {
			$translationKey = $phrase;
		}
		$translationKey .= '_'.$this->language->id;
		
		if(!$translation = $this->cache->load($translationKey)) {
			if(!$phrase instanceof D\Phrase) {
				$phrase = D\Phrase::get($phrase);
			}

			$translation = null;
			if ($translation = $phrase->getTranslation($this->language)) {
				$translation = $translation->translation;
			}
			$this->cache->save($translationKey, $translation);
		}

		return $translation;
	}

}