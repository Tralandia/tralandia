<?php

namespace Extras;

use Nette\Caching;
use Nette\Utils\Strings;
use Entity\Language;
use Model\Phrase\IPhraseDecoratorFactory;

class Translator implements \Nette\Localization\ITranslator {

	const DEFAULT_LANGUAGE = 38;

	const VARIATION_COUNT = 'count';
	const VARIATION_GENDER = 'gender';
	const VARIATION_CASE = 'case';

	protected $language;
	protected $cache;
	protected $phraseRepositoryAccessor;


	public function __construct(Language $language, $phraseRepositoryAccessor, Caching\Cache $translatorCache) {
		$this->language = $language;
		$this->phraseRepositoryAccessor = $phraseRepositoryAccessor;
		$this->cache = $translatorCache;
	}

	public function setLanguage(Language $language)
	{
        $this->language = $language;
		return $this;
	}
	
	public function translate($phrase, $note = NULL, array $variation = NULL, array $variables = NULL)
	{
		$translation = $this->getTranslation($phrase, $variation);

		if($translation && count($variables)) {
			$keys = array_keys($variables);
			$keys = array_map(function($v) { return "[$v]"; }, $keys);
			$translation = str_replace($keys, $variables, $translation);
		}

		return $translation;
	}

	/**
	 * @param $phrase
	 * @param array $variation
	 *
	 * @return bool|float|int|mixed|NULL|string
	 */
	protected function getTranslation($phrase, array $variation = NULL)
	{
		//d($phrase);
		if($phrase instanceof \Service\Phrase\PhraseService) {
			$phraseId = $phrase->getEntity()->id;
		} else if ($phrase instanceof \Entity\Phrase\Phrase){
			$phraseId = $phrase->id;
		} else {
			$phraseId = $phrase;
		}

		if ($variation !== NULL) {
			$variation = array_merge([self::VARIATION_COUNT => NULL, self::VARIATION_GENDER => NULL, self::VARIATION_CASE => NULL], $variation);
		}
		$translationKey = $this->getCacheKey($phraseId, $variation);

		$translation = $this->cache->load($translationKey);
		if($translation === NULL) {

			if(is_scalar($phrase)) {
				if(Strings::startsWith($phrase, 'o')) {
					$phrase = $this->phraseRepositoryAccessor->get()->findOneByOldId(substr($phrase, 1));
				} else if(is_numeric($phrase)) {
					$phrase = $this->phraseRepositoryAccessor->get()->find($phrase);
				} else {
					return $phrase;
//					throw new \Nette\InvalidArgumentException('Argument "$phrase" does not match with the expected value');
				}
			}

			if(!$phrase) {
				$translation = '{!'.$phraseId.'!}';
			}

			if (!$translation && $translation = $phrase->getTranslation($this->language, TRUE)) {
				if ($variation === NULL) {
					$translation = $translation->translation;
				} else {
					$plural = $translation->language->getPlural($variation[self::VARIATION_COUNT]);
					$gender = $variation[self::VARIATION_GENDER];
					$case = $variation[self::VARIATION_CASE];
					$translationText = $translation->getVariation(
						$plural,
						$gender,
						$case
					);
					if(!$translationText) {
						$translationText = $translation->getDefaultVariation();
					}
					if(!$translationText) {
						$translationText = sprintf('{%d|%s:%s:%s:%s}',
							$translation->getPhrase()->getId(),
							$translation->getLanguage()->getIso(),
							$plural,
							$gender,
							$case ? substr($case, 0, 1) : NULL
						);
					}
					$translation = $translationText;
				}
			}

			if($translation === NULL) $translation = '{'.$phraseId.'|'.$this->language->iso.'}';
			$this->cache->save($translationKey, $translation);
		}

		return $translation;
	}

	/**
	 * @param $phraseId
	 * @param array $variation
	 *
	 * @return string
	 */
	private function getCacheKey($phraseId, array $variation = NULL)
	{
		if($variation === NULL) {
			$translationKey = $phraseId.'_'.$this->language->id;
		} else {
			// if (!isset($variation['count'])) $variation['count'] = NULL;
			// if (!isset($variation['gender'])) $variation['gender'] = NULL;
			// if (!isset($variation['case'])) $variation['case'] = NULL;
			$translationKey = $phraseId.'_'.$this->language->id.'_'.implode('_', $variation);
		}

		return $translationKey;
	}

}
