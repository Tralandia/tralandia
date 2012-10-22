<?php

namespace AdminModule\Grids;

/**
 * Foo class
 *
 * @author Dávid Ďurika
 */
class PhrasesGrid extends AdminGrid{

	protected function configure($presenter) {

		//Vytvoříme si zdroj dat pro Grid
		//Při výběru dat vždy vybereme id
		$source = new \NiftyGrid\DataSource\DoctrineDataSource($this->repository->getDataSource(), 'e_id');
		//Předáme zdroj
		$this->setDataSource($source);



		$this->addColumn('e_id', 'Id')
			 ->setNumericFilter();

		$this->addColumn('e_ready', 'Ready')
			->setRenderer(function($row) use ($presenter){return $row->e_ready ? 'YES' : 'NO';})
			->setBooleanFilter();

		$this->addButton("action", 'Action')
			->setText('Action')
			->setLink(function($row) use ($presenter) {return '/';})
			->setClass('action btn');
		
		$this->addButton("edit", 'Edit')
			->setText('Edit')
			->setLink(function($row) use ($presenter) {return '/';})
			->setClass('edit btn btn-info');
		
		$this->addButton("delete", 'Delete')
			->setText('Delete')
			->setLink(function($row) use ($presenter) {return '/';})
			->setClass('delete btn btn-danger');
	}
}