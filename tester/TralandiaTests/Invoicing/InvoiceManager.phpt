<?php

namespace TralandiaTests\Invoicing;

use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class InvoiceManagerTest extends Tester\TestCase
{

    public function setUp()
    {

    }

    public function testBasic()
    {
		Assert::equal(1,1);
    }
}

\run(new InvoiceManagerTest());
