<?php
namespace Tests\Router;

use Nette, Extras;


/**
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
		$this->assertInstanceOf('\Entity\Phrase\Phrase', $location->getNameOfficial());
		$this->assertInstanceOf('\Entity\Phrase\Phrase', $location->getNameShort());

		$nameType = $location->getName()->getType();
		$this->assertInstanceOf('\Entity\Phrase\Type', $nameType);
		$this->assertSame('\Entity\Location\Location', $nameType->getEntityName());
		$this->assertSame('name', $nameType->getEntityAttribute());
	}

	public function testDoNotCreatePhrases() {
		/** @var $location \Entity\Location\Location */
		$location = $this->locationRepository->createNew(FALSE);

		$this->assertNull($location->getName());
		$this->assertNull($location->getNameOfficial());
		$this->assertNull($location->getNameShort());
	}

}