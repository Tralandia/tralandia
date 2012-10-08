<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class FormMaskGeneratorTest extends PHPUnit_Framework_TestCase
{
	public $context;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
	}

	public function testDefault() {

	}
}