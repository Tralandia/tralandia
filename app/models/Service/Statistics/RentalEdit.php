<?php

namespace Service\Statistics;

use Doctrine, Entity;
use Tralandia\BaseDao;
use Tralandia\Location\Countries;
use Tralandia\Rental\Rentals;

/**
 * @author
 */
class RentalEdit {

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationDao;


	public function __construct(BaseDao $locationDao, Rentals $rentals, Countries $countries) {

		$this->countries = $countries;
		$this->rentals = $rentals;
		$this->locationDao = $locationDao;
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

			$total = $this->rentals->getEditCounts(NULL, NULL, $from, $to);
			$harvested = $this->rentals->getEditCounts(NULL, TRUE, $from, $to);
			$results[$key]['total'] = $total;
			$results[$key]['harvested'] = $harvested;

		}

		$finalResults = array();
		foreach ($periods as $period => $value) {
			$finalResults['total']['key'] = $period;
			$finalResults['total'][$period]['total'] = 0;
			$finalResults['total'][$period]['harvested'] = 0;
		}
		foreach ($results as $period => $value) {
			foreach ($value['total'] as $country => $countInCountry) {
				$locationId = $this->locationDao->findOneBy(['iso' => $country])->getId();
				$iso = $countries[$locationId]->getIso();
				$finalResults[$iso]['key'] = $iso;
				$finalResults[$iso][$period]['total'] = $countInCountry;
				$finalResults['total'][$period]['total'] += $countInCountry;
			}
			foreach ($value['harvested'] as $country => $countInCountry) {
				$locationId = $this->locationDao->findOneBy(['iso' => $country])->getId();
				$iso = $countries[$locationId]->getIso();
				$finalResults[$iso]['key'] = $iso;
				$finalResults[$iso][$period]['harvested'] = $countInCountry;
				$finalResults['total'][$period]['harvested'] += $countInCountry;
			}
		}

		$total = $finalResults['total'];
		unset($finalResults['total']);
		ksort($finalResults);
		$finalResults = array('total' => $total) + $finalResults;

		return $finalResults;
	}

}
