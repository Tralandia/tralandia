<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/04/14 09:57
 */

namespace Tests\Invoicing;


use Entity\Invoicing\Invoice;
use Nette;
use Tests\TestCase;

/**
 * @backupGlobals disabled
 */
class InvoiceTest extends TestCase
{

	public function testPricePeriods()
	{

		$invoice = new Invoice;
		$invoice->setDatePaid(new \DateTime());

		$invoice->setPrice(3);
		$invoice->setPriceEur(6);
		$invoice->setDateFrom(new \DateTime('2014-1-1'));
		$invoice->setDateTo(new \DateTime('2016-12-31'));

		$prices = $invoice->getPricePeriods();

		$expected = [
			['from' => new \DateTime('2014-1-1'), 'to' => new \DateTime('2014-12-31'), 'price' => 1, 'priceEur' => 2],
			['from' => new \DateTime('2015-1-1'), 'to' => new \DateTime('2015-12-31'), 'price' => 1, 'priceEur' => 2],
			['from' => new \DateTime('2016-1-1'), 'to' => new \DateTime('2016-12-31'), 'price' => 1, 'priceEur' => 2],
		];

		$this->assertEquals($expected, $prices);

		$invoice->setDateFrom(new \DateTime('2014-5-16'));
		$invoice->setDateTo(new \DateTime('2016-8-22'));

		$prices = $invoice->getPricePeriods();

		$expected = [
			['from' => new \DateTime('2014-5-16'), 'to' => new \DateTime('2014-12-31'), 'price' => 0.83, 'priceEur' => 1.66],
			['from' => new \DateTime('2015-1-1'), 'to' => new \DateTime('2015-12-31'), 'price' => 1.32, 'priceEur' => 2.64],
			['from' => new \DateTime('2016-1-1'), 'to' => new \DateTime('2016-8-22'), 'price' => 0.85, 'priceEur' => 1.69],
		];


		$this->assertEquals($expected, $prices);
	}

}
