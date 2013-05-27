<?php

namespace DataSource;


use Nette\Utils\Paginator;

class LocalityDataSource extends BaseDataSource {


	/**
	 * @var \ResultSorter
	 */
	private $resultSorter;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	private $repository;


	public function __construct($repository, \ResultSorter $resultSorter)
	{
		$this->resultSorter = $resultSorter;
		$this->repository = $repository;
	}


	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$result = $this->repository->findLocalities(NULL, $paginator->getItemsPerPage(), $paginator->getOffset());

		$orderedResult = $this->resultSorter->translateAndSort($result, function($v) {return $v->getParent()->getName();});

		return $orderedResult;
	}

	public function getDataCount($filter, $order)
	{
		return $this->repository->getLocalitiesCount();
	}

}
