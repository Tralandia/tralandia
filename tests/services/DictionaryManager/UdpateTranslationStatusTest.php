<?php
namespace Tests;

use Entity\Phrase\Translation;
use Nette, Extras;


/**
 *
 * @backupGlobals disabled
 */
class UpdateTranslationStatusTest extends TestCase
{

	/**
	 * @var \DictionaryManager\UpdateTranslationStatus
	 */
	public $updateTranslationStatus;

	/**
	 * @var \Service\Phrase\PhraseCreator
	 */
	public $phraseCreator;


	protected function setUp() {
		$this->updateTranslationStatus = new \DictionaryManager\UpdateTranslationStatus;

		$languageRepository = $this->getEm()->getRepository(LANGUAGE_ENTITY);
		$phraseRepository = $this->getEm()->getRepository(PHRASE_ENTITY);
		$this->phraseCreator = new \Service\Phrase\PhraseCreator($phraseRepository, $languageRepository);

	}

	public function testBase()
	{
		$phraseTypeName = '\Entity\Currency:name';
		$phrase = $this->phraseCreator->create($phraseTypeName);

		$sourceLanguage = $this->findLanguage('144');
		$sourceTranslation = $phrase->getTranslation($sourceLanguage);
		$sourceTranslation->setTimeTranslated(new \DateTime());

		$phrase->setSourceLanguage($sourceLanguage);

		$this->updateTranslationStatus->updatePhrase($phrase);

		$this->assertEquals($phrase->getTranslation($this->findLanguage('38'))->getTranslationStatus(), Translation::WAITING_FOR_TRANSLATION);
		$this->assertEquals($phrase->getTranslation($this->findLanguage('28'))->getTranslationStatus(), Translation::WAITING_FOR_CENTRAL);
	}

	public function testFoo()
	{
		$phraseTypeName = '\Entity\Currency:name';
		$phrase = $this->phraseCreator->create($phraseTypeName);

		$sourceTranslation = $phrase->getCentralTranslation();
		$sourceTranslation->setTimeTranslated(new \DateTime());

		$this->updateTranslationStatus->updatePhrase($phrase);

		$this->assertEquals(Translation::UP_TO_DATE, $sourceTranslation->getTranslationStatus());
		$this->assertEquals(Translation::WAITING_FOR_TRANSLATION, $phrase->getTranslation($this->findLanguage('28'))->getTranslationStatus());
	}

}
