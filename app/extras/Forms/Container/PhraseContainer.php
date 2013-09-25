<?php

namespace Extras\Forms\Container;

use Doctrine\ORM\EntityManager;
use Entity\Language;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Environment\Environment;
use Nette\NotImplementedException;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Tralandia\Dictionary\PhraseManager;

class PhraseContainer extends BaseContainer
{

	/**
	 * @var \Entity\Phrase\Phrase
	 */
	protected $phrase;


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

	/**
	 * @var \Tralandia\Dictionary\PhraseManager
	 */
	private $phraseManager;


	public function __construct(Phrase $phrase, PhraseManager $phraseManager, EntityManager $em)
	{
		$this->phrase = $phrase;

		$this->fromLanguage = $phrase->getSourceLanguage();
		parent::__construct();
		$this->em = $em;
		$this->phraseManager = $phraseManager;
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
		throw new NotImplementedException();
	}


	public function getPhraseTypeString()
	{
		$phraseType = $this->phrase->getType();

		$return = $phraseType->getEntityParsedName();
		$return[] = ucfirst($phraseType->getEntityAttribute());
		return implode(' -> ', array_unique($return));
	}

	public function getSourceLanguage()
	{
		return $this->phrase->getSourceLanguage();
	}

	public function getStatusLabel()
	{
		return $this->phrase->getStatusLabel();
	}


	public function getHelpDescription()
	{
		return $this->phrase->getHelpDescription();;
	}

	/**
	 * @return bool
	 */
	public function isHtmlText()
	{
		return $this->phrase->getType()->isHtml();
	}


	/**
	 * @return bool
	 */
	public function isLatteType()
	{
		return Strings::startsWith($this->phrase->getType()->getEntityName(), 'Latte');
	}


	public function build($settings) {
		$this->setSettings($settings);
		$phrase = $this->phrase;
		$phraseType = $phrase->getType();

		$fromTranslation = $phrase->getTranslation($this->fromLanguage);
		$this['fromVariations'] = new TranslationVariationContainer($fromTranslation, TRUE);
		$this['fromVariations']->setDefaults($fromTranslation->getVariations());


		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);

		$to = $this->addContainer('to');

		$editableLanguages = $this->getSettings('editableLanguages');
		if($editableLanguages instanceof Language) {
			$editableLanguages = [$editableLanguages];
		}

		$languages = $this->em->getRepository(LANGUAGE_ENTITY)->findSupported();

		$preFillTranslations = $this->getSettings('preFillTranslations');
		/** @var $language \Entity\Language */
		foreach($languages as $language) {
			if(is_array($editableLanguages) && !in_array($language, $editableLanguages)) {
				continue;
			}

			$translation = $phrase->getTranslation($language);
			if(!$translation) {
				$translation = $phrase->createTranslation($language);
			}

			$languageContainer = $to->addContainer($language->getIso());
			$languageContainer['variations'] = new TranslationVariationContainer($translation, FALSE);
			if($preFillTranslations) {
				$languageContainer['variations']->setDefaults($translation->getVariations());
			}

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


	public function getFormattedValues($asArray = false)
	{
		$values = $this->getValues(TRUE);
		$phrase = $this->phrase;

		$translationsVariations = [];
		foreach($values['to'] as $languageIso => $variations) {
			$translationsVariations[$languageIso] = $variations['variations'];
		}

		$result = $this->phraseManager->updateTranslations($phrase, $translationsVariations);

		$values['oldVariations'] = $result['oldVariations'];
		$values['changedTranslations'] = $result['changedTranslations'];
		$values['displayedTranslations'] = $result['displayedTranslations'];

		$values['phrase'] = $phrase;

		return $values;
	}


	public function showDeleteLink()
	{
		return $this->isLatteType() && $this->getSettings('isAdmin');
	}


	public function showUsedStatus()
	{
		return $this->isLatteType();
	}


	public function showPhraseStatus()
	{
		return $this->getSettings('isAdmin');
	}




}
