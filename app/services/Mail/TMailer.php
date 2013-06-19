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
	 * @var \Entity\Location\Location
	 */
	private $primaryLocation;


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
		$fromKeys = array_keys($from);
		$fromEmail = array_shift($fromKeys);
		$fromName = reset($from);

		$domain = $this->primaryLocation->getFirstDomain();
		$message->setFrom('info@' . $domain->getDomain(), ucfirst($domain->getDomain()));

		$message->addReplyTo($fromEmail, $fromName);

		return $message;
	}


}
