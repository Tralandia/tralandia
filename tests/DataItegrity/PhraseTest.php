<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class PhraseTest extends \Tests\TestCase
{

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	public $phraseRepository;

	public function setUp()
	{
		$this->phraseRepository = $this->getEm()->getRepository(PHRASE_ENTITY);
	}

	public function testCheckSourceLanguage() {
		$entity = $this->phraseRepository->findOneBySourceLanguage(NULL);
		$this->assertTrue(is_null($entity), 'Niektory Phrase nema nastaveny SourceLanguage!');
	}

}
