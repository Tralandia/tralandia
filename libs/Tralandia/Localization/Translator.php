<?php

namespace Tralandia\Localization;

use Entity\Phrase\Phrase;
use Nette\Caching\Cache;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Entity\Language;
use Tralandia\BaseDao;
use Tralandia\Dictionary\Translatable;

class Translator implements \Nette\Localization\ITranslator {

	const ALIAS_PREFIX = 'a';
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

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $phraseDao;

	/**
	 * @var bool
	 */
	protected $development;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $aliasesDao;


	public function __construct(Language $language, BaseDao $phraseDao, BaseDao $aliasesDao, Cache $translatorCache) {
		$this->language = $language;
		$this->cache = $translatorCache;
		$this->phraseDao = $phraseDao;
		$this->aliasesDao = $aliasesDao;
	}

	public function setDevelopment($development)
	{
		$this->development = $development;
	}

	public function setLanguage(Language $language)
	{
        $this->language = $language;
		return $this;
	}

	public function translate($phrase, $count = NULL, array $variation = NULL, array $variables = NULL,Language $language = NULL)
	{
		if($phrase instanceof Translatable) {
			return $phrase->translate($this);
		}

		if(is_numeric($count) && !isset($variation[self::VARIATION_COUNT])) {
			$variation[self::VARIATION_COUNT] = $count;
		}

		if(!$language) $language = $this->language;

		if(is_scalar($phrase) && Strings::match($phrase, '~a[0-9]+~')) {
			$aliasId = substr($phrase, 1);
			$phrase = $this->resolveAlias($aliasId);
		}

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
		if($phrase instanceof \Tralandia\Phrase\Phrase) {
			$phrase = $phrase->id;
		}

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
					$phrase = $this->phraseDao->findOneByOldId(substr($phrase, 1));
				} else if(is_numeric($phrase)) {
					$phrase = $this->phraseDao->find($phrase);
				} else {
					return $phrase;
					//throw new \Nette\InvalidArgumentException('Argument "$phrase" does not match with the expected value');
				}
			}

			if(!$phrase) $translation = FALSE;

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
			if(!$translation) $translation = $this->development ? '{.'.$phraseId.'.}' : FALSE;

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


	/**
	 * @param $aliasId
	 *
	 * @return int|NULL
	 */
	private function resolveAlias($aliasId)
	{
		$cacheKey = self::getAliasCacheKey($aliasId);
		$phraseId = $this->cache->load($cacheKey);

		if(!$phraseId) {
			$alias = $this->aliasesDao->find($aliasId);
			if($alias) {
				if($alias->phrase) {
					$phraseId = $alias->phrase->getId();
				} else {
					$phraseId = '{'.$cacheKey.':'.$alias->help.'}';
				}
			} else {
				$phraseId = '{'.$cacheKey.':INVALID_ALIAS}';
			}
			$this->cache->save($cacheKey, $phraseId, [
				Cache::TAGS => ['translator', 'translator.alias'],
			]);
		}

		return $phraseId;
	}


	/**
	 * @param $aliasId
	 *
	 * @return string
	 */
	public static function getAliasCacheKey($aliasId)
	{
		return self::ALIAS_PREFIX . $aliasId;
	}
}


interface ITranslatorFactory {
	/**
	 * @param \Entity\Language $language
	 *
	 * @return Translator
	 */
	function create(\Entity\Language $language);
}
