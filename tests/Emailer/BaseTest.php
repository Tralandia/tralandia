<?php

namespace Tests\Emailer;

use  Nette, Extras;

/**
 * @backupGlobals disabled
 */
class BaseTest extends \Tests\TestCase
{
	/**
	 * @var \Mail\ICompilerFactory
	 */
	public $emailCompilerFactory;

	protected function setUp() {
		$this->emailCompilerFactory = $this->getContext()->emailCompilerFactory;
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

		$emailCompiler = $this->emailCompilerFactory->create($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($template);
		$emailCompiler->setLayout($layout);
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addVisitor('sender', $sender);
		$emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		$html = $emailCompiler->compileBody();

		$i = 1;
	}

}