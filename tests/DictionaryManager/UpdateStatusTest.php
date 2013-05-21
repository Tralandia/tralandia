<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class UpdateStatusTest extends \Tests\TestCase
{

	/**
	 * @var \Repository\LanguageRepository
	 */
	public $languageRepository, $userRepository, $userRoleRepository;
	public $updateTranslationStatus;

	public $en, $sk, $de;

	public $adminUser, $translatorUser, $ownerUser;

	public function setUp()
	{
		$this->languageRepository = $this->getEm()->getRepository(LANGUAGE_ENTITY);
		$this->userRepository = $this->getEm()->getRepository(USER_ENTITY);
		$this->userRoleRepository = $this->getEm()->getRepository(USER_ROLE_ENTITY);

		$this->updateTranslationStatus = $this->context->getService('dictionary.updateTranslationStatus');

		$this->en = $this->languageRepository->findOneByIso('en');
		$this->sk = $this->languageRepository->findOneByIso('sk');
		$this->de = $this->languageRepository->findOneByIso('de');

		$this->adminUser = $this->userRepository->findOneByLogin('toth@tralandia.com');
		$this->translatorUser = $this->userRepository->findOneByLogin('krcalova@tralandia.com');

		$ownerRole = $this->userRoleRepository->findOneBySlug('owner');
		$this->ownerUser = $this->userRepository->findOneByRole($ownerRole);
	}

	public function testCentralUpdatedByAdmin() {
		$currency = $this->getEm()->getRepository(CURRENCY_ENTITY)->createNew();

		$currency->name->status = \Entity\Phrase\Phrase::READY;
		foreach ($currency->name->getTranslations() as $key => $value) {
			$value->status = \Entity\Phrase\Translation::UP_TO_DATE;
		}

		$enTranslation = $currency->name->getTranslation($this->en);
		$enTranslation->setTranslation('this is a test');

		$this->updateTranslationStatus->translationUpdated($enTranslation, $this->adminUser);

		$this->assertEquals(\Entity\Phrase\Phrase::WAITING_FOR_CORRECTION, $currency->name->status, 'Phrase Status not updated correctly.');
		foreach ($currency->name->getTranslations() as $key => $value) {
			if ($value == $this->en) continue;
			if ($value->language == $currency->name->sourceLanguage) continue;

			$this->assertEquals(\Entity\Phrase\Translation::WAITING_FOR_CENTRAL, $value->status, 'Translation Status not updated correctly.');
		}
	}

	public function testCentralUpdatedByTranslator() {
		$currency = $this->getEm()->getRepository(CURRENCY_ENTITY)->createNew();

		$currency->name->status = \Entity\Phrase\Phrase::READY;
		foreach ($currency->name->getTranslations() as $key => $value) {
			$value->status = \Entity\Phrase\Translation::UP_TO_DATE;
		}

		$enTranslation = $currency->name->getTranslation($this->en);
		$enTranslation->setTranslation('this is a test');

		$this->updateTranslationStatus->translationUpdated($enTranslation, $this->translatorUser);

		$this->assertEquals(\Entity\Phrase\Phrase::WAITING_FOR_CORRECTION_CHECKING, $currency->name->status, 'Phrase Status not updated correctly.');
		foreach ($currency->name->getTranslations() as $key => $value) {
			if ($value == $this->en) continue;
			if ($value->language == $currency->name->sourceLanguage) continue;

			$this->assertEquals(\Entity\Phrase\Translation::WAITING_FOR_CENTRAL, $value->status, 'Translation Status not updated correctly.');
		}
	}

	public function testSourceUpdated() {
		$location = $this->getEm()->getRepository(LOCATION_ENTITY)->createNew();

		$location->name->sourceLanguage = $this->de;
		$location->name->status = \Entity\Phrase\Phrase::READY;

		foreach ($location->name->getTranslations() as $key => $value) {
			$value->status = \Entity\Phrase\Translation::UP_TO_DATE;
		}

		$deTranslation = $location->name->getTranslation($this->de);
		$deTranslation->setTranslation('das ist ein test');

		$this->updateTranslationStatus->translationUpdated($deTranslation, $this->ownerUser);

		$this->assertEquals(\Entity\Phrase\Phrase::WAITING_FOR_CENTRAL, $location->name->status, 'Phrase Status not updated correctly.');
		foreach ($location->name->getTranslations() as $key => $value) {
			if ($value == $this->en) {
				$this->assertEquals(\Entity\Phrase\Translation::WAITING_FOR_TRANSLATION, $value->status, 'Translation Status not updated correctly.');

			} else if ($value->language == $location->name->sourceLanguage) {
				$this->assertEquals(\Entity\Phrase\Translation::UP_TO_DATE, $value->status, 'Translation Status not updated correctly.');
			} else {
				$this->assertEquals(\Entity\Phrase\Translation::WAITING_FOR_CENTRAL, $value->status, 'Translation Status not updated correctly.');
			}
		}
	}

	public function testOtherUpdated() {
		$currency = $this->getEm()->getRepository(CURRENCY_ENTITY)->createNew();

		$currency->name->status = \Entity\Phrase\Phrase::READY;
		foreach ($currency->name->getTranslations() as $key => $value) {
			$value->status = \Entity\Phrase\Translation::UP_TO_DATE;
		}

		$deTranslation = $currency->name->getTranslation($this->de);
		$deTranslation->setTranslation('das ist ein test');

		$this->updateTranslationStatus->translationUpdated($deTranslation, $this->translatorUser);

		$this->assertEquals(\Entity\Phrase\Phrase::READY, $currency->name->status, 'Phrase Status not updated correctly.');
		foreach ($currency->name->getTranslations() as $key => $value) {
			if ($value == $this->de) {
				$this->assertEquals(\Entity\Phrase\Translation::WAITING_FOR_CHECKING, $value->status, 'Translation Status not updated correctly.');
			}
		}
	}
}

