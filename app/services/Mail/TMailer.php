<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/17/13 8:03 PM
 */

namespace Mail;


use Nette;
use Tester\NoTester;
use Tester\Options;

trait TMailer {

	/**
	 * @var Options
	 */
	private $tester;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	/**
	 * @return null|string
	 */
	protected function getTesterEmail()
	{
		return $this->tester ? $this->tester->getEmail() : NULL;
	}



	protected function prepareMessage(Nette\Mail\Message $message)
	{
		$testerEmail = $this->getTesterEmail();
		if ($testerEmail) {
			$message->setHeader('To', [$testerEmail => $testerEmail]);
		}

		$from = $message->getFrom();
		if($from) {
			$fromKeys = array_keys($from);
			$fromEmail = array_shift($fromKeys);
			$fromName = reset($from);

			$message->addReplyTo($fromEmail, $fromName);
		} else {
			$message->addReplyTo('info@tralandia.com', 'Tralandia.com');
		}

		$domain = $this->environment->getPrimaryLocation()->getFirstDomain();
		$domainHost = $domain->getDomain();
		if(Nette\Utils\Strings::endsWith($domain->getDomain(), '.com')) {
			$domainHost = 'tralandia.com';
		}

		$message->setFrom('info@' . $domainHost, ucfirst($domainHost));

		//$message->addBcc('tralandia.testing@gmail.com');


		return $message;
	}


}
