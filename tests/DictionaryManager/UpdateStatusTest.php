<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class UpdateStatusTest extends \Tests\TestCase
{

	/**
	 * @var \Dictionary\UpdateTranslationStatus
	 */
	public $updateTranslationStatus;

	public $en, $sk, $de;

	public $adminUser, $translatorUser, $ownerUser;

	public function setUp()
	{
		$languageRepository = $this->getEm()->getRepository(LANGUAGE_ENTITY);
		$userRepository = $this->getEm()->getRepository(USER_ENTITY);
		$userRoleRepository = $this->getEm()->getRepository(USER_ROLE_ENTITY);

		$this->updateTranslationStatus = $this->context->getService('dictionary.updateTranslationStatus');

		$this->en = $languageRepository->findOneByIso('en');
		$this->sk = $languageRepository->findOneByIso('sk');
		$this->de = $languageRepository->findOneByIso('de');

		$this->adminUser = $userRepository->findOneByLogin('toth@tralandia.com');
		$this->translatorUser = $userRepository->findOneByLogin('krcalova@tralandia.com');

		$ownerRole = $userRoleRepository->findOneBySlug('owner');
		$this->ownerUser = $userRepository->findOneByRole($ownerRole);
	}

	public function testCentralUpdatedByAdmin() {
		$currency = $this->getEm()->getRepository(CURRENCY_ENTITY)->createNew();
		/** @var $phrase Phrase */
		$phrase = $currency->name;

		$phrase = $this->setUpToDate($phrase);

		$enTranslation = $phrase->getTranslation($this->en);
		$enTranslation->setTranslation('this is a test');

		$this->updateTranslationStatus->translationUpdated($enTranslation, $this->adminUser);

		$this->assertEquals(Phrase::WAITING_FOR_CORRECTION, $phrase->getStatus(), 'Phrase Status not updated correctly.');
		foreach ($phrase->getTranslations() as $translation) {
			if ($translation->getLanguage() == $this->en) continue;
			if ($translation->getLanguage() == $phrase->getSourceLanguage()) continue;

			$this->assertEquals(Translation::WAITING_FOR_CENTRAL, $translation->getStatus(), 'Translation Status not updated correctly.');
		}
	}

	public function testCentralUpdatedByTranslator() {
		$currency = $this->getEm()->getRepository(CURRENCY_ENTITY)->createNew();
		/** @var $phrase Phrase */
		$phrase = $currency->name;

		$phrase = $this->setUpToDate($phrase);

		$enTranslation = $phrase->getTranslation($this->en);
		$enTranslation->setTranslation('this is a test');

		$this->updateTranslationStatus->translationUpdated($enTranslation, $this->translatorUser);

		$this->assertEquals(Phrase::WAITING_FOR_CORRECTION_CHECKING, $phrase->getStatus(), 'Phrase Status not updated correctly.');
		foreach ($phrase->getTranslations() as $translation) {
			if ($translation->getLanguage() == $this->en) continue;
			if ($translation->getLanguage() == $phrase->getSourceLanguage()) continue;

			$this->assertEquals(Translation::WAITING_FOR_CENTRAL, $translation->getStatus(), 'Translation Status not updated correctly.');
		}
	}

	public function testSourceUpdated() {
		$location = $this->getEm()->getRepository(LOCATION_ENTITY)->createNew();
		/** @var $phrase Phrase */
		$phrase = $location->name;

		$phrase->setSourceLanguage($this->de);
		$phrase = $this->setUpToDate($phrase);

		// @rado tu menis DE co je source
		$deTranslation = $phrase->getTranslation($this->de);
		$deTranslation->setTranslation('das ist ein test');

		$this->updateTranslationStatus->translationUpdated($deTranslation, $this->ownerUser);

		$this->assertEquals(Phrase::WAITING_FOR_CENTRAL, $phrase->getStatus(), 'Phrase Status not updated correctly.');
		foreach ($phrase->getTranslations() as $translation) {
			if ($translation->getLanguage() == $this->en) {
				$this->assertEquals(Translation::WAITING_FOR_TRANSLATION, $translation->getStatus(), 'Translation Status not updated correctly.');
			} else if ($translation->getLanguage() == $phrase->getSourceLanguage()) {
				$this->assertEquals(Translation::UP_TO_DATE, $translation->getStatus(), 'Translation Status not updated correctly.');
			} else {
				$this->assertEquals(Translation::WAITING_FOR_CENTRAL, $translation->getStatus(), 'Translation Status not updated correctly.');
			}
		}
	}

	public function testOtherUpdated() {
		$currency = $this->getEm()->getRepository(CURRENCY_ENTITY)->createNew();
		/** @var $phrase Phrase */
		$phrase = $currency->name;

		$phrase = $this->setUpToDate($phrase);

		$deTranslation = $phrase->getTranslation($this->de);
		$deTranslation->setTranslation('das ist ein test');

		$this->updateTranslationStatus->translationUpdated($deTranslation, $this->translatorUser);

		$this->assertEquals(Phrase::READY, $phrase->getStatus(), 'Phrase Status not updated correctly.');
		foreach ($phrase->getTranslations() as $translation) {
			if ($translation->getLanguage() == $this->de) {
				$this->assertEquals(Translation::WAITING_FOR_CHECKING, $translation->getStatus(), 'Translation Status not updated correctly.');
			}
		}
	}

	public function setUpToDate(Phrase $phrase)
	{
		$phrase->setStatus(Phrase::READY);
		foreach ($phrase->getTranslations() as $translation) {
			$translation->setStatus(Translation::UP_TO_DATE);
		}

		return $phrase;
	}
}

