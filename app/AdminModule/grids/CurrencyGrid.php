<?php

namespace AdminModule\Grids;

use AdminModule\Components\Grid;

class CurrencyGrid extends Grid {

	public function render() {
		$this->grid->setTemplateFile(__DIR__ . '/../components/Grid/grid.latte');

		$this->grid->addColumn('name', 'Name');
		$this->grid->addColumn('iso', 'Iso');
		$this->grid->addColumn('rounding', 'Rounding');

		$this->grid->setPrimaryKey('id');
		$this->grid->setDataLoader($this->dataLoader);
		$this->grid->setRecordValueGetter($this->recordValueGetter);
		$this->grid->setTimelineBehavior(true);

		$this->grid->addRowAction('edit', 'Edit', $this->editRecord);
		$this->grid->addRowAction('delete', 'Smazat', $this->deleteRecord, 'Opravdu chcete smazat tento záznam?');

		return $this->grid;
	}
}