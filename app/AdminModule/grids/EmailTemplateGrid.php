<?php

namespace AdminModule\Grids;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
class EmailTemplateGrid extends AdminGrid{

	protected function configure($presenter) {

		//Vytvoříme si zdroj dat pro Grid
		//Při výběru dat vždy vybereme id
		$source = new \NiftyGrid\DataSource\DoctrineDataSource($this->repositoryAccessor->get()->getDataSource(), 'e_id');
		//Předáme zdroj
		$this->setDataSource($source);

		//
		// Columns
		//
		$this->addColumn('e_id', 'Id');

		$this->addColumn('e_name', 'Name');

		//
		// Actions
		//
		$this->addButton('edit', 'Edit')
			->setText('Edit')
			->setLink(function($row) use ($presenter){return $presenter->link("edit", $row['e_id']);})
			->setAjax(FALSE);
	}
}