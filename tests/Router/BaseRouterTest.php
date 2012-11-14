<?php

require_once __DIR__ . '/../bootstrap.php';

namespace \Test\Emailer;

/**
 * @backupGlobals disabled
 */
class BaseRouterTest extends \PHPUnit_Framework_TestCase
{
	public $context;
	public $emailCompiler;
	public $userServiceFactory;
	public $userRepositoryAccessor;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->emailCompiler = $this->context->emailCompiler;
		$this->userServiceFactory = $this->context->userServiceFactory;
		$this->userRepositoryAccessor = $this->context->userRepositoryAccessor;
	}

	public function testCompiler() {
		
	}

}