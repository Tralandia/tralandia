<?php

namespace DataSource;


use Nette\Utils\Paginator;

class Region extends BaseDataSource {


	/**
	 * @var \ResultSorter
	 */
	private $resultSorter;

	/**
	 * @var
	 */
	private $repository;


	public function __construct($repository, \ResultSorter $resultSorter)
	{
		$this->resultSorter = $resultSorter;
		$this->repository = $repository;
	}


	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$result = $this->repository->findRegions();

		$orderedResult = $this->resultSorter->translateAndSort($result, function($v) {return $v->getParent()->getName();});

		return $orderedResult;
	}

}
