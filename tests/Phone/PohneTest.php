<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Entity\Contact\Phone;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class PhoneTest extends \Tests\TestCase
{

	/**
	 * @var \Extras\Books\Phone
	 */
	public $phoneBook;

	public function setUp()
	{
		$this->phoneBook = $this->getContext()->getByType('\Extras\Books\Phone');
	}

	public function testBase() {
		$phone = $this->phoneBook->getOrCreate('45/558 31 32', '421');
		$this->assertTrue($phone instanceof Phone);
	}

}
