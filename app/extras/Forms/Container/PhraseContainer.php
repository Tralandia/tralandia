<?php

namespace Extras\Forms\Container;

class PhraseContainer extends BaseContainer
{

	/**
	 * @var \Entity\Phrase\Phrase
	 */
	protected $phrase;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepository;

	/**
	 * @var \Entity\Language|NULL
	 */
	protected $fromLanguage;

	public function __construct(\Entity\Phrase\Phrase $phrase, \Repository\LanguageRepository $languageRepository)
	{
		$this->phrase = $phrase;
		$this->languageRepository = $languageRepository;

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

		$languageList = $this->languageRepository->fetchPairs('id', 'iso');
		$this->addSelect('sourceLanguage', 'Source Language:', $languageList);

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$this['fromVariations'] = new TranslationVariationContainer($phrase->getTranslation($this->fromLanguage), TRUE);

		$this->addSelect('changeToLanguage', 'changeToLanguage', $languageList);

		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);

		$to = $this->addContainer('to');
		foreach($phrase->getTranslations() as $translation) {
			$language = $translation->getLanguage();
			$languageContainer = $to->addContainer($language->getIso());
			$languageContainer['variations'] = new TranslationVariationContainer($translation, TRUE);
			$languageContainer['variations']->setDefaults($translation->getVariations());

			$genderList = $language->getGenders();
			$languageContainer->addSelect('gender', 'Gender', $genderList)
				->setDefaultValue($translation->getGender());

			$languageContainer->addSelect('position', 'Position', $positionList)
				->setDefaultValue($translation->getPosition());
		}

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
