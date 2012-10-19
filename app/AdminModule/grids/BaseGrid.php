<?php

namespace AdminModule\Grids;

use \NiftyGrid\Grid;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
abstract class BaseGrid extends Grid{

	public function __construct() {
		parent::__construct();
		$this->setTemplate(__DIR__ . '/template.latte');
	}
}