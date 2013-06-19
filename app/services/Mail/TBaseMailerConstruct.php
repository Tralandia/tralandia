<?php

namespace Mail;

trait TBaseMailerConstruct {



	/**
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Tester\ITester $tester
	 */
	public function __construct(\Entity\Location\Location $primaryLocation, \Tester\ITester $tester)
	{
		if($tester instanceof \Tester\NoTester) {
		} else {
			$this->tester = $tester;
		}

		$this->primaryLocation = $primaryLocation;
	}

}
