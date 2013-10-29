<?php

namespace DataSource;


use Nette\Utils\Paginator;
use Tralandia\Location\Countries;

class CountryDataSource extends BaseDataSource {


	/**
	 * @var \ResultSorter
	 */
	private $resultSorter;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	private $repository;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;


	public function __construct($repository, \ResultSorter $resultSorter, Countries $countries)
	{
		$this->resultSorter = $resultSorter;
		$this->repository = $repository;
		$this->countries = $countries;
	}


	public function getData($filter, $order, Paginator $paginator = NULL)
	{
		$result = $this->countries->findAll();

		$orderedResult = $this->resultSorter->translateAndSort($result, function($v) {return $v->getParent()->getName();});

		return $orderedResult;
	}

}
