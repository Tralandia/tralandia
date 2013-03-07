<?php
namespace Repository\Rental;

use Doctrine\ORM\Query\Expr;

/**
 * AddressRepository class
 *
 * @author Dávid Ďurika
 */
class AmenityRepository extends \Repository\BaseRepository
{

	public function findByLocationTypeForSelect()
	{
		return $this->findByTypeForSelect('other');
	}

	public function findByBoardTypeForSelect()
	{
		return $this->findByTypeForSelect('board');
	}

	public function findByAvailabilityTypeForSelect()
	{
		return $this->findByTypeForSelect('owner-availability');
	}

	protected function findByTypeForSelect($type)
	{
		$type = $this->related('type')->findBySlug($type);
		$return = [];
		$rows = $this->findByType($type);
		foreach($rows as $row) {
			$return[$row->id] = $row->name->id;
		}

		return $return;
	}

	public function findImportantForSelect() {
		$rows = $this->findByImportant(TRUE);
		foreach($rows as $row) {
			$return[$row->id] = $row->name->id;
		}

		return $return;
	}

}