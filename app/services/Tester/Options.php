<?php
namespace Tester;

use Nette;

/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 6/11/13 8:12 AM
 */

class Options implements ITester
{


	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;

	/**
	 * @var string
	 */
	protected $defaultEmail = 'toth.radoslav@gmail.com';


	/**
	 * @param $defaultEmail
	 * @param \Nette\Http\Session $session
	 */
	public function __construct($defaultEmail, \Nette\Http\Session $session)
	{
		$section = $session->getSection('testerOptions');
		$section->setExpiration(NULL);

		$this->section = $section;
		$this->defaultEmail = $defaultEmail;
	}


	/**
	 * @param $email
	 */
	public function setEmail($email)
	{
		$this->section->email = $email;
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		$email = $this->section->email;
		if (!$email) {
			$email = $this->defaultEmail;
		}

		return $email;
	}
}
