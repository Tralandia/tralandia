<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class TranslationTest extends \Tests\TestCase
{

	/**
	 * @var \Repository\Phrase\TranslationRepository
	 */
	public $translationRepository;

	public function setUp()
	{
		$this->translationRepository = $this->getEm()->getRepository(TRANSLATION_ENTITY);
	}

	public function testCheckSourceLanguage() {
		$duplicates = $this->translationRepository->haveDuplicates();
		$this->assertFalse($duplicates, 'Niektore Phrase maju viac Translations v tom istom jazyku');
	}

}
