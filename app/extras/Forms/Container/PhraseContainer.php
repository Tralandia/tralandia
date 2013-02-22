<?php

namespace Extras\Forms\Container;

use Entity\Phrase\Phrase;
use Nette\Security\User;

class PhraseContainer extends BaseContainer
{

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

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

	public function __construct(User $user, Phrase $phrase, array $sourceLanguages)
	{
		$this->user = $user;
		$this->phrase = $phrase;
		$this->sourceLanguages = $sourceLanguages;

		# @todo nie stale sa to ma prekladat zo sourceLanguage
		$this->fromLanguage = $phrase->getSourceLanguage();
		parent::__construct();
		$this->build();
	}

	public function getMainControl()
	{
		return $this['sourceLanguage'];
	}

	protected function build() {
		$phrase = $this->phrase;

		$this->addSelect('phraseType', '#Phrase type')
			->setPrompt($phrase->getType()->getEntityName() . '.' . $phrase->getType()->getEntityAttribute())
			->setDisabled();

		$this->addSelect('sourceLanguage', 'Source Language:', $this->sourceLanguages);

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$this['fromVariations'] = new TranslationVariationContainer($phrase->getTranslation($this->fromLanguage), TRUE);


		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);

		$changeToLanguageList = [];
		$to = $this->addContainer('to');
		foreach($phrase->getTranslations() as $translation) {
			if(!$this->user->isAllowed($translation, 'translate')) continue;
			$language = $translation->getLanguage();
			$languageContainer = $to->addContainer($language->getIso());
			$languageContainer['variations'] = new TranslationVariationContainer($translation, TRUE);
			$languageContainer['variations']->setDefaults($translation->getVariations());

			$genderList = $language->getGenders();
			$languageContainer->addSelect('gender', 'Gender', $genderList)
				->setDefaultValue($translation->getGender());

			$languageContainer->addSelect('position', 'Position', $positionList)
				->setDefaultValue($translation->getPosition());

			$changeToLanguageList[$language->getId()] = $language->getName()->getTranslationText($language);
		}
		$this->addSelect('changeToLanguage', 'changeToLanguage', $changeToLanguageList);

		$this->addSubmit('save', 'Save');
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
			'phraseType' => $phrase->getType()->getId(),
			'sourceLanguage' => $phrase->getSourceLanguage()->getId(),
			'ready' => $phrase->getReady(),
			'corrected' => $phrase->getCorrected(),
			'fromTranslations' => $phrase->getTranslation($this->fromLanguage)->getVariations(),
		));
	}
}
