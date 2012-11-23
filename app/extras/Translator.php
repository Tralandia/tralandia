<?php

namespace Extras;

use Nette\Caching;
use Model\Phrase\IPhraseDecoratorFactory;

class Translator implements \Nette\Localization\ITranslator {

	const DEFAULT_LANGUAGE = 38;

	protected $language = 38;
	protected $cache;
	protected $phraseRepositoryAccessor;
	protected $phraseDecoratorFactory;

	public function __construct(Environment $environment, $phraseRepositoryAccessor, Caching\Cache $translatorCache, IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->language = $environment->getLanguage();
		$this->phraseRepositoryAccessor = $phraseRepositoryAccessor;
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
		$this->cache = $translatorCache;
	}
	
	public function translate($phrase, $node = NULL, $count = NULL, array $variables = NULL) {
		$translation = $this->getTranslation($phrase);

		return $translation;
	}

	public function getDefaultLanguage() {

		return D\Language::get(self::DEFAULT_LANGUAGE);
	}

	
	protected function getTranslation($phrase) {
		//d($phrase);
		if($phrase instanceof \Service\Phrase\Phrase) {
			$phraseId = $phrase->getEntity()->id;
		} else if ($phrase instanceof \Entity\Phrase\Phrase){
			$phraseId = $phrase->id;
		} else {
			$phraseId = $phrase;
		}
		//d($phraseId, $phrase);
		$translationKey = $phraseId.'_'.$this->language->id;
		
		$translation = $this->cache->load($translationKey);
		if($translation === NULL) {
			$translation = NULL;
			
			if(is_scalar($phrase)) {
				$phrase = $this->phraseRepositoryAccessor->get()->find($phrase);
			}

			if ($phrase instanceof \Entity\Phrase\Phrase){
				$phrase = $this->phraseDecoratorFactory->create($phrase);
			} 

			if(!$phrase) {
				$translation = '{!'.$phraseId.'!}';
			} else {
			}

			if (!$translation && $translation = $phrase->getTranslation($this->language)) {
				$translation = $translation->translation;
			}

			if($translation === NULL) $translation = '{'.$phraseId.'|'.$this->language->iso.'}';
			$this->cache->save($translationKey, $translation);
		}
		return $translation;
	}

}