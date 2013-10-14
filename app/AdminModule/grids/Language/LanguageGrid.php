<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class LanguageGrid extends AdminGridControl {

	public function __construct($repository) {
		$this->repository = $repository;
	}

	public function render() {
		$this->template->datagridClass = 'languageGrid';
		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('iso');
		$grid->addColumn('name');
		$grid->addColumn('supported');
		$grid->addColumn('live');

		return $grid;
	}

}

interface ILanguageGridFactory {

	/**
	 * @return ILanguageGridFactory
	 */
	public function create();
}
