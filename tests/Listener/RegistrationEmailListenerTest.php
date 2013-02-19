<?php

namespace Tests\Listener;

use  Nette, Extras;

/**
 * @backupGlobals disabled
 */
class RegistrationEmailListenerTest extends \Tests\TestCase
{
	/**
	 * @var \Listener\RegistrationEmailListener
	 */
	public $registrationEmailListener;

	protected function setUp() {
		$this->registrationEmailListener = $this->getContext()->registrationEmailListener;
	}

	public function testCompiler() {
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->getEm()->getRepository('\Entity\Rental\Rental')->find(1);

		$this->registrationEmailListener->onSuccess($rental);
	}

}