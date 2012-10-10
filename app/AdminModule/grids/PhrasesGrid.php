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
		$source = new \NiftyGrid\DoctrineDataSource($this->repository->getDataSource(), 'e_id');
		//Předáme zdroj
		$this->setDataSource($source);

		$this->addColumn('e_id', 'Id');
		$this->addColumn('e_ready', 'Ready');

		$this->addButton("edit", 'Upravit')
			->setText('Upravit')
			->setLink(function($row) use ($presenter) {return $presenter->link("DictionaryPhrase:edit", $row['id']);})
			->setClass('edit');
	}
}