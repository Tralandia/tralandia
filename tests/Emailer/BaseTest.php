<?php

require_once __DIR__ . '/../bootstrap.php';

namespace \Test\Emailer;

/**
 * @backupGlobals disabled
 */
class BaseTest extends \PHPUnit_Framework_TestCase
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
		$emailCompiler = $this->emailCompiler;

		// pripravim si odosielatela
		$sender = $this->userRepositoryAccessor->get()->find(3);
		$sender = $this->userServiceFactory->create($sender);

		// pripravim si prijimatela
		$receiver = $this->userRepositoryAccessor->get()->find(3);
		$receiver = $this->userServiceFactory->create($receiver);

		// ponastavujem compiler
		$emailCompiler->setTemplate($template);
		$emailCompiler->setLayout($layout);
		$emailCompiler->setPrimaryVariable('user', $receiver);
		$emailCompiler->setVariable('sender', $sender);
		$html = $emailCompiler->compile();
	}

}