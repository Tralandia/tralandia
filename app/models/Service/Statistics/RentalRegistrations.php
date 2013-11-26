<?php

namespace Service\Statistics;

use Doctrine, Entity;
use Tralandia\BaseDao;
use Tralandia\Location\Countries;
use Tralandia\Rental\Rentals;

/**
 * @author
 */
class RentalRegistrations {

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;


	public function __construct(Rentals $rentals, Countries $countries) {

		$this->countries = $countries;
		$this->rentals = $rentals;
	}

	public function getData() {
		$countries = $this->countries->findAll();
		$countries = \Tools::arrayMap($countries, function($key, $value) {return $value->getId();}, NULL);

		$results = array();
		//$results['total']['total'] = $this->rentalRepository->getCounts();
		//$results['total']['live'] = $this->rentalRepository->getCounts(NULL, TRUE);

		$periods = \Tools::getPeriods();
		foreach ($periods as $key => $value) {
			$from = $value['from'];
			$to = $value['to'];

			$total = $this->rentals->getCounts(NULL, NULL, $from, $to);
			$live = $this->rentals->getCounts(NULL, TRUE, $from, $to);
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
				$iso = $countries[$country]->getIso();
				$finalResults[$iso]['key'] = $iso;
				$finalResults[$iso][$period]['total'] = $countInCountry;
				$finalResults['total'][$period]['total'] += $countInCountry;
			}
			foreach ($value['live'] as $country => $countInCountry) {
				$iso = $countries[$country]->getIso();
				$finalResults[$iso]['key'] = $iso;
				$finalResults[$iso][$period]['live'] = $countInCountry;
				$finalResults['total'][$period]['live'] += $countInCountry;
			}
		}

		$total = $finalResults['total'];
		unset($finalResults['total']);
		ksort($finalResults);
		$finalResults = array('total' => $total) + $finalResults;

		return $finalResults;
	}

}
