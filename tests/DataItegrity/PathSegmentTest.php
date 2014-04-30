<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class PathSegmentTest extends \Tests\TestCase
{

	/**
	 * @var \Repository\User\UserRepository
	 */
	public $userRepository;

	public function setUp()
	{
		$this->userRepository = $this->getEm()->getRepository(USER_ENTITY);
	}

	public function testCheckDuplicates() {
		/** @var $pathSegmentRepository \Repository\Routing\PathSegmentRepository */
		$pathSegmentRepository = $this->getEm()->getRepository(PATH_SEGMENT_ENTITY);
		$qb = $pathSegmentRepository->createQueryBuilder();

		$qb->select('count(e.id) as c')
			->groupBy('e.language')
			->addGroupBy('e.primaryLocation')
			->addGroupBy('e.pathSegment')
			->having('c > 1');

		$result = $qb->getQuery()->getScalarResult();
		$this->assertEquals(0, count($result), 'Niektore path segmenty su duplicitne');
	}

}
