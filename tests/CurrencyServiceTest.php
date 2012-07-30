<?php

require_once 'bootstrap.php';

class CurrencyServiceTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $presenter;
	public $session;
	public $user;

	protected function setUp() {
		//$this->context = Nette\Environment::getContext();

		/*
		$this->presenter = new FrontModule\HomePresenter($this->context);
		$this->presenter->autoCanonicalize = FALSE;
		$this->user = new Nette\Security\User(new SimpleUserStorage($this->context->session), $this->context);
		$this->user->setAuthenticator(new Nette\Security\SimpleAuthenticator(array('admin' => 'admin')));
		$this->context->removeService('user');
		$this->context->addService('user', $this->user);
		*/
	}

	public function testTitle() {
		/*
		$request = new Nette\Application\Request('Front:Home:default', 'GET', array());
		$response = $this->presenter->run($request);
		$this->assertInstanceOf('Nette\Application\Responses\TextResponse', $response);
		*/

		//$this->assertInternalType('integer', $this->presenter->template->totalCount);
		//$this->assertInternalType('array', $this->presenter->template->listing);
		//$this->assertInternalType('array', array());
	}
}