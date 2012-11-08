<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;

/**
 * LocationRepository class
 */
class LocationRepository extends BaseRepository {

	public function getItems($key, $value) {
		return array(
			1 => 'Položka 1',
			2 => 'Položka 2',
			3 => 'Položka 3',
			4 => 'Položka 4',
			5 => 'Položka 5',
			6 => 'Položka 6',
			7 => 'Položka 7',
			8 => 'Položka 8',
			9 => 'Položka 9',
			10 => 'Položka 10',
			11 => 'Položka 11',
			12 => 'Položka 12',
			52 => 'Položka 52',
			53 => 'Položka 53',
			54 => 'Položka 54'
		);
	}
}