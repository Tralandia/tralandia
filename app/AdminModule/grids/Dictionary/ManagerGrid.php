<?php

namespace AdminModule\Grids\Dictionary;

use AdminModule\Components\AdminGridControl;
use Doctrine\ORM\EntityManager;

class ManagerGrid extends AdminGridControl {

	public function __construct(EntityManager $em) {
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('name', 'Name');
		$grid->addColumn('slug', 'slug');

		return $grid;
	}
}

interface IManagerGridFactory {

	/**
	 * @return IManagerGridFactory
	 */
	public function create();
}
