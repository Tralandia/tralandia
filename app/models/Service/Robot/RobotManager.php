<?php

namespace Service\Robot;


/**
 * Robot class
 *
 * @author Dávid Ďurika
 */
class RobotManager extends \Nette\Object {

	private $robots = array();

	public function __construct() {
		
	}

	public function addScanner(IRobot $robot) {
		$this->robots[] = $robot;
	}

	public function run() {
		foreach ($this->robots as $robot) {
			if($robot->needToRun()) {
				$robot->run();
			}
		}
	}
	

}