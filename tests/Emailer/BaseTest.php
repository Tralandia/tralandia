<?php

namespace Test\Emailer;

use PHPUnit_Framework_TestCase, Nette, Extras;


require_once __DIR__ . '/../bootstrap.php';

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

		// // pripravim si template a layout
		// $template = $this->context->emailTemplateRepositoryAccessor->get()->find(1);
		// $layout = $this->context->emailLayoutRepositoryAccessor->get()->find(1);

		// // pripravim si odosielatela
		// $sender = $this->context->userRepositoryAccessor->get()->findOneByLogin('infoubytovanie@gmail.com');

		// // pripravim si prijimatela
		// $receiver = $this->context->userRepositoryAccessor->get()->findOneByLogin('pavol@paradeiser.sk');

		// // pripravim si rental
		// $rental = $this->context->rentalRepositoryAccessor->get()->find(1);

		// // ponastavujem compiler
		// $emailCompiler = $this->context->emailCompiler;
		// $emailCompiler->setTemplate($template);
		// $emailCompiler->setLayout($layout);
		// $emailCompiler->setPrimaryVariable('receiver', 'visitor', $receiver);
		// $emailCompiler->addVariable('sender', 'visitor', $sender);
		// $emailCompiler->addVariable('rental', 'rental', $rental);
		// $emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		// $html = $emailCompiler->compile();
	}

}