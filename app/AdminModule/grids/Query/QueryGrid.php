<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use LeanMapper\Connection;
use Nette\Utils\Paginator;

class QueryGrid extends AdminGridControl {

	/**
	 * @var string
	 */
	private $queryString;

	/**
	 * @var \DibiResult
	 */
	private $query;


	public function __construct($queryString, Connection $lean) {

		parent::__construct();

		$this->query = $lean->query($queryString);
		$this->queryString = $queryString;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$this->showActions = FALSE;
		$this->useTranslator = FALSE;

		$grid = $this->getGrid();

		$columns = $this->query->getInfo()->getColumnNames();
		foreach($columns as $column) {
			$grid->addColumn($column);
		}


		$grid->setDataSourceCallback($this->getData);
		$grid->setPagination(self::ITEMS_PER_PAGE, $this->getDataSum);


		return $grid;
	}

	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		return $this->query->fetchAll($paginator->getOffset(), $paginator->getItemsPerPage());
	}

	public function getDataSum($filter, $order)
	{
		return $this->query->count();
	}

}

interface IQueryGridFactory {
	public function create($queryString);
}
