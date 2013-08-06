<?php

namespace Service\Statistics;

use Doctrine, Entity;

/**
 * @author
 */
class RentalRegistrations {

	protected $rentalRepository;
	protected $locationRepository;

	public function __construct(\Repository\Rental\RentalRepository $rentalRepository,
								\Repository\Location\LocationRepository $locationRepository) {

		$this->rentalRepository = $rentalRepository;
		$this->locationRepository = $locationRepository;
	}

	public function getData() {
		$results = array();
		//$results['total']['total'] = $this->rentalRepository->getCounts();
		//$results['total']['live'] = $this->rentalRepository->getCounts(NULL, TRUE);

		$periods = \Tools::getPeriods();
		foreach ($periods as $key => $value) {
			$from = $value['from'];
			$to = $value['to'];

			$total = $this->rentalRepository->getCounts(NULL, NULL, $from, $to);
			$live = $this->rentalRepository->getCounts(NULL, TRUE, $from, $to);
			$results[$key]['total'] = $total;
			$results[$key]['live'] = $live;

		}

		$finalResults = array();
		foreach ($periods as $period => $value) {
			$finalResults['total']['key'] = $period;
			$finalResults['total'][$period]['total'] = 0;
			$finalResults['total'][$period]['live'] = 0;
		}
		foreach ($results as $period => $value) {
			foreach ($value['total'] as $country => $countInCountry) {
				$finalResults[$country]['key'] = $country;
				$finalResults[$country][$period]['total'] = $countInCountry;
				$finalResults['total'][$period]['total'] += $countInCountry;
			}
			foreach ($value['live'] as $country => $countInCountry) {
				$finalResults[$country]['key'] = $country;
				$finalResults[$country][$period]['live'] = $countInCountry;
				$finalResults['total'][$period]['live'] += $countInCountry;
			}
		}
		return $finalResults;
	}

}
