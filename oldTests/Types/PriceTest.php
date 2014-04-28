<?php

namespace Tests\Emailer;

use  Nette, Extras;


require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class PriceTest extends \Tests\TestCase
{

	public function testExchange()
	{

		$usd = $this->findCurrency('USD', TRUE, 'iso');
		$eur = $this->findCurrency('EUR', TRUE, 'iso');
		$jpy = $this->findCurrency('JPY', TRUE, 'iso');

		$price = new Extras\Types\Price('20', $usd);

		$this->assertEquals('14.47 EUR', $price->convertTo($eur));
		$this->assertEquals('2050.95', $price->getAmount($jpy));

	}

}
