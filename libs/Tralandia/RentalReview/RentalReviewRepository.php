<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 12/03/14 09:51
 */

namespace Tralandia\RentalReview;


use Nette;
use Tralandia\Lean\BaseRepository;

class RentalReviewRepository extends BaseRepository
{

	/**
	 * @param $rental
	 *
	 * @return float|NULL
	 */
	public function getRentalAvgRate($rental)
	{
		$fluent = $this->connection->select('AVG(avgRating) avg')
			->from($this->getTable())
			->where('rental_id = ?', $rental->id);

		$avg = $fluent->fetchSingle();

		return $avg ? round($avg, 1) : NULL;
	}

}
