<?php
namespace Repository\Rental;

use Doctrine\ORM\Query\Expr;

/**
 * AddressRepository class
 *
 * @author Dávid Ďurika
 */
class TypeRepository extends \Repository\BaseRepository
{

	public function getForSelect() {

		$return = [];
		$rows = $this->findAll();
		foreach($rows as $row) {
			$return[$row->id] = $row->name->id;
		}

		return $return;

	}

}