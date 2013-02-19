<?php
use Nette\Application\Application;

class TestListener extends Nette\Object implements Kdyby\Events\Subscriber
{
	public function getSubscribedEvents()
	{
		return array('Nette\Application\Application::onStartup');
	}

	public function onStartup(Application $app)
	{
		//d('ha!');
	}
}