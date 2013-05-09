<?php

namespace DataSource;


use Nette\Utils\Paginator;

interface IDataSource {

	/**
	 * @param $filter
	 * @param $order
	 * @param Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getData($filter, $order, Paginator $paginator = NULL);

}
