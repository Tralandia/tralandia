<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 20/03/14 15:35
 */

namespace Tralandia\Lean;


use LeanMapper\Fluent;
use Nette;

class CommonFilter
{

	public function sort(Fluent $statement, $column = 'sort', $sort = 'ASC')
	{
		$statement->orderBy("$column $sort");
	}

}
