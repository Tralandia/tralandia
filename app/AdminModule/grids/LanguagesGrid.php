<?php

namespace AdminModule\Grids;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
class LanguagesGrid extends AdminGrid{

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

		$this->addColumn('e_supported', 'Supported', '100px')
			->setRenderer(function($row){return $row->e_supported ? 'YES' : 'NO';})
			->setCellRenderer(function($row){return $row['e_supported'] ? "background-color:#E4FFCC" : "background-color:#FFCCCC";})
			->setSelectFilter(array(1 => 'YES', 0 => 'NO'));

		$this->addColumn('e_iso', 'Iso');

		//
		// Actions
		//
		$this->addButton('edit', 'Edit')
			->setText('Edit')
			->setLink(function($row) use ($presenter){return $presenter->link("edit", $row['e_id']);})
			->setAjax(FALSE);
	}
}