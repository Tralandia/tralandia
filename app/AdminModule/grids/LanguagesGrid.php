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
		$source = new \NiftyGrid\DoctrineDataSource($this->repository->getDataSource(), 'e_id');
		//Předáme zdroj
		$this->setDataSource($source);

		$this->addColumn('e_id', 'Id');
		$this->addColumn('e_iso', 'Iso');
	}
}