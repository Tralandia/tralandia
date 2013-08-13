<?php

namespace Extras;

use Entity\Phrase\Phrase;
use Nette\Caching\Cache;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Entity\Language;
use Model\Phrase\IPhraseDecoratorFactory;

class Translator implements \Nette\Localization\ITranslator {

	const DEFAULT_LANGUAGE = 38;

	const VARIATION_PLURAL = 'plural';
	const VARIATION_COUNT = 'count';
	const VARIATION_GENDER = 'gender';
	const VARIATION_CASE = 'case';

	/**
	 * @var \Entity\Language
	 */
	protected $language;

	/**
	 * @var \Nette\Caching\Cache
	 */
	protected $cache;

	protected $phraseRepositoryAccessor;


	public function __construct(Language $language, $phraseRepositoryAccessor, Cache $translatorCache) {
		$this->language = $language;
		$this->phraseRepositoryAccessor = $phraseRepositoryAccessor;
		$this->cache = $translatorCache;
	}

	public function setLanguage(Language $language)
	{
        $this->language = $language;
		return $this;
	}

	public function translate($phrase, $count = NULL, array $variation = NULL, array $variables = NULL,Language $language = NULL)
	{
		if(is_numeric($count) && !isset($variation[self::VARIATION_COUNT])) {
			$variation[self::VARIATION_COUNT] = $count;
		}

		if($phrase == 'o32989') {
			$t = 1;
		}

		if(!$language) $language = $this->language;

		$translation = $this->getTranslation($phrase, $variation, $language);

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
	 * @param \Entity\Language $language
	 *
	 * @return bool|float|int|mixed|NULL|string
	 */
	protected function getTranslation($phrase, array $variation = NULL, Language $language)
	{
		if($phrase instanceof \Service\Phrase\PhraseService) {
			$phraseId = $phrase->getEntity()->getId();
		} else if ($phrase instanceof \Entity\Phrase\Phrase){
			$phraseId = $phrase->getId();
		} else {
			if(!Strings::match($phrase, '~o[0-9]+~') && !is_numeric($phrase)) {
				return $phrase;
			}
			$phraseId = $phrase;
		}

		if ($variation !== NULL) {
			$variationCount = Arrays::get($variation, self::VARIATION_COUNT, NULL);
			unset($variation[self::VARIATION_COUNT]);

			if($variationCount !== NULL) {
				$variation[self::VARIATION_PLURAL] = $language->getPlural($variationCount);
			}

			$variation = array_merge(
				[
					self::VARIATION_PLURAL => $language->getPlural(NULL),
					self::VARIATION_GENDER => NULL,
					self::VARIATION_CASE => NULL
				],
				$variation);

			ksort($variation);
		}

		$translation = $this->loadTranslationFromCache($phraseId, $language, $variation);

		//$translation = NULL;
		if($translation === NULL) {

			if(is_scalar($phrase)) {
				if(Strings::match($phrase, '~o[0-9]+~')) {
					$phrase = $this->phraseRepositoryAccessor->get()->findOneByOldId(substr($phrase, 1));
				} else if(is_numeric($phrase)) {
					$phrase = $this->phraseRepositoryAccessor->get()->find($phrase);
				} else {
					return $phrase;
					//throw new \Nette\InvalidArgumentException('Argument "$phrase" does not match with the expected value');
				}
			}

			if(!$phrase) $translation = FALSE;

//			# Mark as Used
//			if($phrase instanceof Phrase && !$phrase->getUsed()) {
//				$phrase->setUsed(TRUE);
//				$this->phraseRepositoryAccessor->get()->update($phrase);
//			}

			if ($translation === NULL && $translations = $phrase->getMainTranslations($language)) {
				$firstIteration = TRUE;
				foreach($translations as $translationEntity) {
					/** @var $translationEntity \Entity\Phrase\Translation */
					$translationText = NULL;
					if ($variation === NULL || !$firstIteration) {
						$translationText = $translationEntity->getDefaultVariation();
					} else {
						$plural = $variation[self::VARIATION_PLURAL];
						$gender = $variation[self::VARIATION_GENDER];
						$case = $variation[self::VARIATION_CASE];

						$translationText = $translationEntity->getVariation($plural, $gender, $case);

						if(!$translationText) {
							$translationText = $translationEntity->getDefaultVariation();
						}
					}

					if($translationText) {
						$translation = $translationText;
						break;
					}
				}
			}

			//if(!$translation) $translation = '{?'.$translationKey.'?}';
			if(!$translation) $translation = FALSE;

			$this->saveTranslationToCache($phraseId, $language, $variation, $translation);
		}

		return $translation;
	}


	private function loadTranslationFromCache($phraseId, Language $language, array $variation = NULL)
	{
		$phraseCache = $this->cache->load($phraseId);

		if(!$phraseCache) return NULL;


		$translationCache =  Arrays::get($phraseCache, $language->getIso(), NULL);

		if($translationCache) {
			if($variation === NULL) {
				return Arrays::get($translationCache, 'default', NULL);
			} else {
				return Arrays::get($translationCache, implode('_', $variation), NULL);
			}
		} else {
			return NULL;
		}
	}


	private function saveTranslationToCache($phraseId, Language $language, array $variation = NULL, $value)
	{
		$phraseCache = $this->cache->load($phraseId);

		if($variation === NULL) {
			$phraseCache[$language->getIso()]['default'] = $value;
		} else {
			$phraseCache[$language->getIso()][implode('_', $variation)] = $value;
		}

		$this->cache->save($phraseId, $phraseCache, [
			Cache::TAGS => ['translator'],
		]);

	}

}
