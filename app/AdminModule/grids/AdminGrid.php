<?php

namespace AdminModule\Grids;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
class AdminGrid extends BaseGrid{

	protected $repository;

	public function __construct($repository) {
		parent::__construct();
		$this->repository = $repository;
	}
}