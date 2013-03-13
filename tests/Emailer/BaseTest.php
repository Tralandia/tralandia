<?php

namespace Tests\Emailer;

use Environment\Environment;
use  Nette, Extras;

/**
 * @backupGlobals disabled
 */
class BaseTest extends \Tests\TestCase
{

	public function testCompiler() {

		$template = $this->getContext()->emailTemplateRepositoryAccessor->get()->find(1);
		$layout = $this->getContext()->emailLayoutRepositoryAccessor->get()->find(1);

		/** @var $sender \Entity\User\User */
		$sender = $this->getContext()->userRepositoryAccessor->get()->findOneByLogin('privat66@szm.sk');

		/** @var $receiver \Entity\User\User */
		$receiver = $this->getContext()->userRepositoryAccessor->get()->findOneByLogin('privatpodvrskom@gmail.com');

		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->getContext()->rentalRepositoryAccessor->get()->find(1);

		$presenter = $this->mockista->create('Nette\Application\UI\Presenter', [
			'link' => function($destination, $arguments) {
				return $destination;
			}
		]);

		$application = $this->mockista->create('Nette\Application\Application', [
			'getPresenter' => $presenter,
		]);

		$reflectionProperty = Nette\Reflection\ClassType::from('Nette\Application\Application')->getProperty('presenter');
		$reflectionProperty->setAccessible(TRUE);
		$reflectionProperty->setValue($application, $presenter);

		$environment = new Environment($receiver->getPrimaryLocation(), $receiver->getLanguage(), $this->getContext()->translatorFactory);
		$emailCompiler = new \Mail\Compiler($environment, $application);
		$emailCompiler->setTemplate($template);
		$emailCompiler->setLayout($layout);
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addVisitor('sender', $sender);
		$emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		$html = $emailCompiler->compileBody();


		$this->assertGreaterThan(100, strlen($html));
	}

}