<?php

namespace Extras;

use Nette\Caching;

class Translator implements \Nette\Localization\ITranslator {

	const DEFAULT_LANGUAGE = 38;

	protected $language = 38;
	protected $cache;

	protected $phraseRepository;

	public function __construct(Environment $environment, $phraseRepository, Caching\IStorage $cacheStorage) {
		$this->language = $environment->getLanguage();
		$this->phraseRepository = $phraseRepository;
		$this->cache = new Caching\Cache($cacheStorage, 'Translator');
	}
	
	public function translate($phrase, $node = NULL, $count = NULL, array $variables = NULL) {
		$translation = $this->getTranslation($phrase);

		return (gettype($phrase)=='object'? $phrase->id: $phrase);
	}

	public function getDefaultLanguage() {

		return D\Language::get(self::DEFAULT_LANGUAGE);
	}

	
	protected function getTranslation($phrase) {
		if($phrase instanceof \Service\Phrase\Phrase) {
			$phraseId = $phrase->getEntity()->id;
		} else if ($phrase instanceof \Entity\Phrase\Phrase){
			$phraseId = $phrase->id;
		} else {
			$phraseId = $phrase;
		}
		$translationKey = $phraseId.'_'.$this->language->id;
		
		if(!$translation = $this->cache->load($translationKey)) {
			$translation = null;
			
			if(!$phrase instanceof \Service\Phrase\Phrase) {
				$phrase = $this->phraseRepository->find($phrase);
				if(!$phrase) {
					$translation = '{!'.$phraseId.'!}';
				}
			}

			if (!$translation && $translation = $phrase->getTranslation($this->language)) {
				$translation = $translation->translation;
			}
			$this->cache->save($translationKey, $translation);
		}
		if($translation === NULL) $translation = '{'.$phraseId.'|'.$this->language->iso.'}';
		return $translation;
	}

}