<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class RentalSearchCachingTest extends \Tests\TestCase
{

	/**
	 * @var \Extras\Cache\RentalSearchCaching
	 */
	public $rentalSearchCaching;

	public function setUp()
	{
		$rentalSearchCachingFactory = $this->getContext()->getByType('\Extras\Cache\IRentalSearchCachingFactory');
		$this->rentalSearchCaching = $rentalSearchCachingFactory->create($this->findLocation('hu', TRUE, 'iso'));
	}

	public function testGeneration() {
		$this->rentalSearchCaching->updateWholeCache();
		//$this->rentalSearchCaching->updateRental($this->findRental(15047));
		$r = 1;
	}

}
