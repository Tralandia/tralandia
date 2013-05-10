<?php
namespace Tests\Entity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * Testujem ci sa pri vytvoreni novej entity spravne vytvoria aj preklady (ak sa maju)\
 *
 * @backupGlobals disabled
 */
class BaseEntityTest extends \Tests\TestCase
{

	/**
	 * @dataProvider getTestGetClassData
	 */
	public function testGetClass($expectedClass, BaseEntity $entity) {
		$this->assertInstanceOf($expectedClass, $entity);

		$class = $entity->getClass();
		$this->assertSame($expectedClass, $class);
	}


	/**
	 * @return array
	 */
	public function getTestGetClassData()
	{

		/** @var $locationRepository \Repository\Location\LocationRepository */
		$locationRepository = $this->getContext()->locationRepositoryAccessor->get();
		$sk = $locationRepository->findOneByIso('sk');
		return [
			['Entity\Location\Location', $sk],
			['Entity\Location\Location', $sk->getParent()],
		];
	}


}
