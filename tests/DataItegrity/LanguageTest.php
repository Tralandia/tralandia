<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class LanguageTest extends \Tests\TestCase
{

	/**
	 * @var \Repository\LanguageRepository
	 */
	public $languageRepository;

	public function setUp()
	{
		$this->languageRepository = $this->getEm()->getRepository(LANGUAGE_ENTITY);
	}

	public function testCheckRoles() {
		$entity = $this->languageRepository->findOneBy([
			'supported' => TRUE,
			'translator' => NULL,
		]);
		$this->assertTrue(is_null($entity), 'Kazdy podporovany jazyk musi mat nastaveneho translatora');
	}

}
