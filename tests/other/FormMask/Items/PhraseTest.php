<?php

namespace FormMask\Items;

use PHPUnit_Framework_TestCase, Nette, Extras;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class PhraseTest extends PHPUnit_Framework_TestCase
{
	public $context;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
	}

	public function testDefault() {

	}
}