<?php

namespace Service\TaskManager;


/**
 * TaskManager class
 *
 * @author Dávid Ďurika
 */
class TaskManager extends \Nette\Object {

	private $scanners = array();

	public function __construct() {
		
	}

	public function addScanner(IScanner $scanner) {
		$this->scanners[] = $scanner;
	}

	public function run() {
		foreach ($this->scanners as $scanner) {
			if($scanner->needToRun()) {
				$scanner->run();
			}
		}
	}
	

}