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
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Tester\ITester $tester
	 */
	public function __construct(\Entity\Location\Location $primaryLocation, \Tester\ITester $tester)
	{
		if($tester instanceof NoTester) {
		} else {
			$this->tester = $tester;
		}

		$this->primaryLocation = $primaryLocation;
	}


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
		$fromName = reset($from);

		$domain = $this->primaryLocation->getFirstDomain();
		$message->setFrom('info@' . $domain->getDomain(), ucfirst($domain->getDomain()));

		return $message;
	}


}
