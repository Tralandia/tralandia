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

			$total = $this->rentals->getCounts(NULL, NULL, NULL, $from, $to);
//			$live = $this->rentals->getCounts(NULL, \Entity\Rental\Rental::STATUS_LIVE, NULL, $from, $to);
			$harvested = $this->rentals->getCounts(NULL, NULL, 'harvester', $from, $to);
			$fromEmail = $this->rentals->getCounts(NULL, NULL, 'email', $from, $to);
			$results[$key]['total'] = $total;
//			$results[$key]['live'] = $live;
			$results[$key]['harvested'] = $harvested;
			$results[$key]['fromEmail'] = $fromEmail;
		}

		$finalResults = array();
		$keys = ['total', /*'live',*/ 'harvested', 'fromEmail'/*, 'organic'*/];
		foreach ($periods as $period => $value) {
			$finalResults['total']['key'] = $period;
			foreach($keys as $keyName) {
				$finalResults['total'][$period][$keyName] = 0;
			}
		}
		foreach ($results as $period => $value) {
			foreach($keys as $keyName) {
				foreach ($value[$keyName] as $country => $countInCountry) {
					$iso = $countries[$country]->getIso();
					$finalResults[$iso]['key'] = $iso;
//					if($keyName == 'total') {
//						$finalResults[$iso][$period][$keyName] = $value['total'][$country] - $value['harvested'][$country] - $value['fromEmail'][$country];
//					}
					$finalResults[$iso][$period][$keyName] = $countInCountry;
					$finalResults['total'][$period][$keyName] += $countInCountry;
				}
			}
		}

		$total = $finalResults['total'];
		unset($finalResults['total']);
		ksort($finalResults);
		$finalResults = array('total' => $total) + $finalResults;

		return $finalResults;
	}

}
