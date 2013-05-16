<?php

namespace Extras\Forms\Container;

use Doctrine\ORM\EntityManager;
use Entity\Language;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Environment\Environment;
use Nette\Utils\Arrays;

class PhraseContainer extends BaseContainer
{

	/**
	 * @var \Entity\Phrase\Phrase
	 */
	protected $phrase;

	/**
	 * @var array
	 */
	protected $sourceLanguages;

	/**
	 * @var \Entity\Language|NULL
	 */
	protected $fromLanguage;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	public function __construct(Phrase $phrase, \SupportedLanguages $supportedLanguages, EntityManager $em)
	{
		$this->phrase = $phrase;
		$this->sourceLanguages = $supportedLanguages->getForSelect();

		$this->fromLanguage = $phrase->getSourceLanguage();
		parent::__construct();
		$this->em = $em;
	}


	/**
	 * @param Language $fromLanguage
	 */
	public function setTranslateFrom(Language $fromLanguage)
	{
		$this->fromLanguage = $fromLanguage;
	}


	public function getMainControl()
	{
		return $this['sourceLanguage'];
	}


	public function getPhraseTypeString()
	{
		$phraseType = $this->phrase->getType();
		return $phraseType->getEntityName() . '.' . $phraseType->getEntityAttribute();
	}


	public function build($settings) {
		$this->setSettings($settings);
		$phrase = $this->phrase;
		$phraseType = $phrase->getType();

		$sourceLanguage = $this->addSelect('sourceLanguage', 'Source Language:', $this->sourceLanguages);

		$disableSourceLanguage = $this->getSettings('disableSourceLanguageInput');
		if($disableSourceLanguage !== NULL) {
			$sourceLanguage->setDisabled($disableSourceLanguage);
		}

		$readyControl = $this->addCheckbox('ready', 'Ready');
		$disableReadyInput = $this->getSettings('disableReadyInput');
		if($disableReadyInput !== NULL) {
			$readyControl->setDisabled($disableReadyInput);
		}

		$correctedControl = $this->addCheckbox('corrected', 'Corrected');
		$disableCorrectedInput = $this->getSettings('disableCorrectedInput');
		if($disableCorrectedInput !== NULL) {
			$correctedControl->setDisabled($disableCorrectedInput);
		}

		$fromTranslation = $phrase->getTranslation($this->fromLanguage);
		$this['fromVariations'] = new TranslationVariationContainer($fromTranslation, TRUE);
		$this['fromVariations']->setDefaults($fromTranslation->getVariations());


		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);

		$to = $this->addContainer('to');

		$toLanguages = $this->getSettings('translatorLanguages');
		if($toLanguages instanceof Language) {
			$toLanguages = [$toLanguages];
		}
		if(is_array($toLanguages)) {
			$translations = [];
			foreach($toLanguages as $language) {
				$translations[] = $phrase->getTranslation($language);
			}
 		} else {
			$translations = $phrase->getTranslations();
		}

		foreach($translations as $translation) {
			if(!$translation instanceof Translation) continue;
			$language = $translation->getLanguage();
			$languageContainer = $to->addContainer($language->getIso());
			$languageContainer['variations'] = new TranslationVariationContainer($translation, FALSE);
			$languageContainer['variations']->setDefaults($translation->getVariations());

			$genderList = $language->getGenders();
			if($phraseType->getGenderRequired() && count($genderList)) {
				$languageContainer->addSelect('gender', 'Gender', $genderList)
					->setDefaultValue($translation->getGender());
			}

			if($phraseType->getPositionRequired() && count($positionList)) {
				$languageContainer->addSelect('position', 'Position', $positionList)
					->setDefaultValue($translation->getPosition());
			}
		}

	}

	/**
	 * @return \Entity\Phrase\Phrase
	 */
	public function getPhrase()
	{
		return $this->phrase;
	}

	public function getFromLanguage()
	{
		return $this->fromLanguage;
	}

	public function setDefaultValues() {
		$phrase = $this->phrase;

		$this->setDefaults(array(
			//'phraseType' => $phrase->getType()->getId(),
			'sourceLanguage' => $phrase->getSourceLanguage()->getId(),
			'ready' => $phrase->getReady(),
			'corrected' => $phrase->getCorrected(),
			'fromTranslations' => $phrase->getTranslation($this->fromLanguage)->getVariations(),
		));
	}


	/**
	 * @param null $name
	 *
	 * @return array
	 */
	public function getSettings($name = NULL)
	{
		if($name === NULL) {
			return $this->settings;
		} else {
			return Arrays::get($this->settings, $name, NULL);
		}
	}


	/**
	 * @param $name
	 * @param null $value
	 */
	public function setSettings($name, $value = NULL)
	{
		if(is_array($name)) {
			$this->settings = $name;
		} else {
			$this->settings[$name] = $value;
		}
	}


	public function getFormattedValues()
	{
		$values = $this->getValues(TRUE);
		$phrase = $this->phrase;
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);

		if(array_key_exists('ready', $values)) $phrase->setReady($values['ready']);
		if(array_key_exists('corrected', $values)) $phrase->setCorrected($values['corrected']);
		if(array_key_exists('sourceLanguage', $values)) {
			$language = $languageRepository->find($values['sourceLanguage']);
			$phrase->setSourceLanguage($language);
		}
		foreach($values['to'] as $languageIso => $variations) {
			$language = $languageRepository->findOneByIso($languageIso);
			$variations = $variations['variations'];
			$translation = $phrase->getTranslation($language);

			$translation->setVariations($variations);
		}

		$values['phrase'] = $phrase;
		return $values;
	}
}
