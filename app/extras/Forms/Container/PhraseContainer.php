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


	public function __construct(Phrase $phrase, EntityManager $em)
	{
		$this->phrase = $phrase;

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
		if(is_array($editableLanguages)) {
			$translations = [];
			foreach($editableLanguages as $language) {
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
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);

		$values['changedTranslations'] = [];
		$values['displayedTranslations'] = [];
		foreach($values['to'] as $languageIso => $variations) {
			$language = $languageRepository->findOneByIso($languageIso);
			$variations = $variations['variations'];
			$translation = $phrase->getTranslation($language);

			$oldVariations = $translation->getVariations();

			if($oldVariations != $variations) {
				$values['changedTranslations'][] = $translation;
			}
			$values['displayedTranslations'][] = $translation;

			$translation->updateVariations($variations);
		}

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
