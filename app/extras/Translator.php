<?php

namespace Extras;

use Nette\Caching;
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

	public function translate($phrase, $count = NULL, array $variation = NULL, array $variables = NULL)
	{
		if(is_numeric($count) && !isset($variation[self::VARIATION_COUNT])) {
			$variation[self::VARIATION_COUNT] = $count;
		}
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
			$phraseId = $phrase->getEntity()->getId();
		} else if ($phrase instanceof \Entity\Phrase\Phrase){
			$phraseId = $phrase->getId();
		} else {
			$phraseId = $phrase;
		}

		if ($variation !== NULL) {
			$variationCount = Arrays::get($variation, self::VARIATION_COUNT, NULL);
			if(array_key_exists(self::VARIATION_COUNT, $variation)) {
				unset($variation[self::VARIATION_COUNT]);
			}

			$variation[self::VARIATION_PLURAL] = $this->language->getPlural($variationCount);
			$variation = array_merge(
				[
					self::VARIATION_PLURAL => $this->language->getPlural(NULL),
					self::VARIATION_GENDER => NULL,
					self::VARIATION_CASE => NULL
				],
				$variation);
		}


		$translationKey = $this->getCacheKey($phraseId, $variation);

		$translation = $this->cache->load($translationKey);
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

			if(!$phrase) {
				$translation = '{!'.$phraseId.'!}';
			}

			if (!$translation && $translations = $phrase->getMainTranslations($this->language)) {
				foreach($translations as $translationEntity) {
					/** @var $translationEntity \Entity\Phrase\Translation */
					$translationText = NULL;
					if ($variation === NULL) {
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

			if(!$translation) $translation = '{?'.$translationKey.'?}';
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
