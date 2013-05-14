<?php

namespace Extras\Forms\Container;

use Entity\Language;
use Entity\Phrase\Phrase;
use Environment\Environment;

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

	public function __construct(Phrase $phrase, \SupportedLanguages $supportedLanguages)
	{
		$this->phrase = $phrase;
		$this->sourceLanguages = $supportedLanguages->getForSelect(
			function($key, $value){return $value->getId();},
			function($value) {return $value->getIso();}
		);

		$this->fromLanguage = $phrase->getSourceLanguage();
		parent::__construct();
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


	public function build() {
		$phrase = $this->phrase;
		$phraseType = $phrase->getType();

		$this->addText('phraseType', 'Phrase type')
			->setDefaultValue($phrase->getType()->getEntityName() . '.' . $phrase->getType()->getEntityAttribute())
			->setDisabled();

		$this->addSelect('sourceLanguage', 'Source Language:', $this->sourceLanguages);

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$fromTranslation = $phrase->getTranslation($this->fromLanguage);
		$this['fromVariations'] = new TranslationVariationContainer($fromTranslation, TRUE);
		$this['fromVariations']->setDefaults($fromTranslation->getVariations());


		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);

		$changeToLanguageList = [];
		$to = $this->addContainer('to');
		foreach($phrase->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$languageContainer = $to->addContainer($language->getIso());
			$languageContainer['variations'] = new TranslationVariationContainer($translation, FALSE);
			$languageContainer['variations']->setDefaults($translation->getVariations());

			if($phraseType->getGenderRequired()) {
				$genderList = $language->getGenders();
				$languageContainer->addSelect('gender', 'Gender', $genderList)
					->setDefaultValue($translation->getGender());
			}

			if($phraseType->getPositionRequired()) {
				$languageContainer->addSelect('position', 'Position', $positionList)
					->setDefaultValue($translation->getPosition());
			}

			$changeToLanguageList[$language->getId()] = $language->getName()->getTranslationText($language);
		}
		$this->addSelect('changeToLanguage', 'changeToLanguage', $changeToLanguageList);

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
}
