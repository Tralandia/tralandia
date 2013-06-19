<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/19/13 7:27 AM
 */

namespace Mail;


use Nette;
use Tester\NoTester;

class SmtpMailer extends Nette\Mail\SmtpMailer {

	use TMailer;


	/**
	 * @param array $options
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Tester\ITester $tester
	 */
	public function __construct(array $options = array(), \Entity\Location\Location $primaryLocation, \Tester\ITester $tester)
	{
		if($tester instanceof NoTester) {
		} else {
			$this->tester = $tester;
		}

		$this->primaryLocation = $primaryLocation;

		parent::__construct($options);
	}




	public function send(Nette\Mail\Message $message)
	{
		$message = $this->prepareMessage($message);
		parent::send($message);
	}


}
