<?php

namespace Tests\Emailer;

use  Nette, Extras;

/**
 * @backupGlobals disabled
 */
class BaseTest extends \Tests\TestCase
{
	/**
	 * @var \Extras\Email\Compiler
	 */
	public $emailCompiler;

	protected function setUp() {
		$this->emailCompiler = $this->getContext()->emailCompiler;
	}

	public function testCompiler() {

		$template = $this->getContext()->emailTemplateRepositoryAccessor->get()->find(1);
		$layout = $this->getContext()->emailLayoutRepositoryAccessor->get()->find(1);

		/** @var $sender \Entity\User\User */
		$sender = $this->getContext()->userRepositoryAccessor->get()->findOneByLogin('privat66@szm.sk');

		/** @var $receiver \Entity\User\User */
		$receiver = $this->getContext()->userRepositoryAccessor->get()->findOneByLogin('privatpodvrskom@gmail.com');

		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->getContext()->rentalRepositoryAccessor->get()->find(1);

		$emailCompiler = $this->emailCompiler;
		$emailCompiler->setTemplate($template);
		$emailCompiler->setLayout($layout);
		$emailCompiler->setEnvironment($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addVisitor('sender', $sender);
		$emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		$html = $emailCompiler->compile();

		$i = 1;
	}

}