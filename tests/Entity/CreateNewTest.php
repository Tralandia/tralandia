<?php
namespace Tests\Entity;

use Nette, Extras;


/**
 * Testujem ci sa pri vytvoreni novej entity spravne vytvoria aj preklady (ak sa maju)\
 *
 * @backupGlobals disabled
 */
class CreateNewTest extends \Tests\TestCase
{
	/**
	 * @var \Repository\Location\LocationRepository
	 */
	public $locationRepository;

	protected function setUp() {
		$this->locationRepository = $this->getContext()->locationRepositoryAccessor->get();
	}

	public function testCreatePhrases() {
		/** @var $location \Entity\Location\Location */
		$location = $this->locationRepository->createNew();

		$this->assertInstanceOf('\Entity\Phrase\Phrase', $location->getName());

		$nameType = $location->getName()->getType();
		$this->assertInstanceOf('\Entity\Phrase\Type', $nameType);
		$this->assertSame('\Entity\Location\Location', $nameType->getEntityName());
		$this->assertSame('name', $nameType->getEntityAttribute());

		$translations = $location->getName()->translations;
		$this->assertGreaterThan(10, $translations->count());
	}

	public function testDoNotCreatePhrases() {
		/** @var $location \Entity\Location\Location */
		$location = $this->locationRepository->createNew(FALSE);

		$this->assertNull($location->getName());
	}

}
