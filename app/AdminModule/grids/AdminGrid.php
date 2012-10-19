<?php

namespace AdminModule\Grids;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
abstract class AdminGrid extends BaseGrid{

	protected $repository;

	public function __construct($repository) {
		parent::__construct();
		$this->repository = $repository;
	}
}