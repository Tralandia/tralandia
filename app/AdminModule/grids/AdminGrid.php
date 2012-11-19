<?php

namespace AdminModule\Grids;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
abstract class AdminGrid extends BaseGrid{

	protected $repositoryAccessor;

	public function __construct($repositoryAccessor) {
		parent::__construct();
		$this->repositoryAccessor = $repositoryAccessor;
	}
}