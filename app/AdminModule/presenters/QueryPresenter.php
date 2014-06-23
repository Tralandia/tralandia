<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/25/13 8:29 AM
 */

namespace AdminModule;


use Entity\Rental\Rental;
use Entity\Rental\Service;
use Entity\Seo\BackLink;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

class QueryPresenter extends BasePresenter
{

	protected $queryStrings = [
		'foo' => [
			'name' => 'Foo',
			'query' => 'select * from domain',
		],
	];

	protected $queryString = null;

	public function actionRun($id)
	{
		$query = $this->queryStrings[$id];
		$this->queryString = $query['query'];
		$geshi = new \GeSHi($this->queryString, 'mysql');
		$geshi->keyword_links = false;

		$this->template->queryName = $query['name'];
		$this->template->query = $geshi->parse_code();
	}


	protected function createComponentGrid(\AdminModule\Grids\IQueryGridFactory $factory)
	{
		return $factory->create($this->queryString);
	}
}
