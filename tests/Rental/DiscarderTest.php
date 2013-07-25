<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class DiscarderTest extends \Tests\TestCase
{

	/**
	 * @var \Tralandia\Rental\Discarder
	 */
	protected $discarder;

	public function setUp()
	{
		$this->discarder = $this->getContext()->getByType('Tralandia\Rental\Discarder');
	}

	public function testDiscard() {
		$rental = $this->findRental(25450);
		$this->discarder->discard($rental);
	}

}
