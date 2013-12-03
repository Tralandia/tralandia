<?php

namespace Mail;

use Environment\Environment;

trait TBaseMailerConstruct {


	/**
	 * @param \Environment\Environment $environment
	 * @param \Tester\ITester $tester
	 */
	public function __construct(Environment $environment, \Tester\ITester $tester)
	{
		if($tester instanceof \Tester\NoTester) {
		} else {
			$this->tester = $tester;
		}

		$this->environment = $environment;
	}

}
