<?php

namespace FormMask;

use PHPUnit_Framework_TestCase, Nette;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class GeneratorTest extends PHPUnit_Framework_TestCase
{
	public $context;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
	}

	public function testDefault() {

	}
}