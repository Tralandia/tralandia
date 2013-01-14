<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;

/**
 * CurrencyRepository class
 *
 * @author Dávid Ďurika
 */
class CurrencyRepository extends \Repository\BaseRepository {

	public function getForSelect() {

		$return = [];
		$rows = $this->findAll();
		foreach($rows as $row) {
			$return[$row->id] = $row->name->id;
		}

		return $return;

	}
}