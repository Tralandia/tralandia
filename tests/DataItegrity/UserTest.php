<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class UserTest extends \Tests\TestCase
{

	/**
	 * @var \Repository\User\UserRepository
	 */
	public $userRepository;

	public function setUp()
	{
		$this->userRepository = $this->getEm()->getRepository(USER_ENTITY);
	}

	public function testCheckRoles() {
		$entity = $this->userRepository->findOneByRole(NULL);
		$this->assertTrue(is_null($entity), 'Niektory User nema nastavenu Rolu!');
	}

}
