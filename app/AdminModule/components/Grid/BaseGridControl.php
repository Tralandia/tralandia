<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl;
use Nette\Utils\Paginator;
use Nextras\Datagrid\Datagrid;

abstract class BaseGridControl extends BaseControl {

	const ITEMS_PER_PAGE = 30;

	/**
	 * @var \Repository\BaseRepository
	 */
	protected $repository;

	/**
	 * @var \Nextras\Datagrid\Datagrid
	 */
	private $grid;


	/**
	 * @param \Nextras\Datagrid\Datagrid $grid
	 */
	public function setGrid(Datagrid $grid)
	{
		$this->grid = $grid;
	}


	/**
	 * @return \Nextras\Datagrid\Datagrid
	 */
	public function getGrid()
	{
		if(!$this->grid) {
			$grid = new Datagrid;
			$grid->setRowPrimaryKey('id');

			$grid->setTranslator($this->presenter->getContext()->getService('translator'));

			foreach($this->getCellsTemplatesFiles() as $value) {
				$grid->addCellsTemplate($value);
			}

			$grid->setDataSourceCallback($this->getDataSource);

			$this->grid = $grid;
		}

		return $this->grid;
	}


	/**
	 * @param $filter
	 * @param $order
	 * @param \Nette\Utils\Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		/** @var $builder \Doctrine\ORM\QueryBuilder */
		$builder = $this->repository->getDataSource();
		//$builder->orderBy('e.' . $this->orderBy, $this->sort);


		$query = $builder->getQuery();
		$query->setFirstResult(($paginator->page - 1) * $paginator->itemsPerPage);
		$query->setMaxResults($paginator->itemsPerPage);
		return $query->getResult();
	}

	/**
	 * @return array
	 */
	protected function getCellsTemplatesFiles()
	{
		return [VENDOR_DIR . '/nextras/datagrid/bootstrap-style/@bootstrap2.datagrid.latte'];
	}

}
