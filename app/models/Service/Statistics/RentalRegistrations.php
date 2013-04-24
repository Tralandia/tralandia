<?php

namespace Service\Statistics;

use Doctrine, Entity;

/**
 * @author 
 */
class RentalRegistrations {

	protected $rentalRepository;
	protected $locationRepository;

	public function __construct(\Repository\Rental\RentalRepository $rentalRepository, \Repository\Location\LocationRepository $locationRepository) {

		$this->rentalRepository = $rentalRepository;
		$this->locationRepository = $locationRepository;
	}

	public function getData() {
		$results = array();
		//$results['total']['total'] = $this->rentalRepository->getCounts();
		//$results['total']['live'] = $this->rentalRepository->getCounts(NULL, TRUE);

		$periods = $this->getPeriods();
		foreach ($periods as $key => $value) {
			$from = new \Nette\DateTime();
			$from->setTimestamp($value[0]);
			$to = new \Nette\DateTime();
			$to->setTimestamp($value[1]);

			$results[$key]['total'] = $this->rentalRepository->getCounts(NULL, TRUE, $from, $to);
			$results[$key]['live'] = $this->rentalRepository->getCounts(NULL, TRUE, $from, $to);

		}

		$finalResults = array();
		foreach ($periods as $period => $value) {
			$finalResults['total']['key'] = $key;
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

	protected function getPeriods() {
		$d=getdate(time());
		$periods = array();
		$periods["today"]=array(mktime(0, 0, 0, $d["mon"], $d["mday"], $d["year"]), time());

		$thisMonthStart=mktime(0, 0, 0, $d["mon"], 1, $d["year"]);
		$lastMonthStart=mktime(0, 0, 0, (($d["mon"]==1)?(12):($d["mon"]-1)), 1, (($d["mon"]==1)?($d["year"]-1):($d["year"])));

		if (strtotime('monday')==$periods["today"][0]) {
			$thisWeekStart=$periods["today"][0];
		} else {
			$thisWeekStart=strtotime('monday')-(7*24*60*60);
		}

		$d=getdate(time()-(24*60*60));
		$periods["yesterday"]=array(mktime(0, 0, 0, $d["mon"], $d["mday"], $d["year"]), $periods["today"][0]);
		$periods["this_week"]=array($thisWeekStart, time());
		$periods["last_week"]=array($thisWeekStart-(7*24*60*60), $thisWeekStart);
		$periods["this_month"]=array($thisMonthStart, time());
		$periods["last_month"]=array($lastMonthStart, $thisMonthStart);
		$periods["total"]=array(0, time());

		return $periods;
	}
}