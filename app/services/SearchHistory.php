<?php

use Service\Rental\RentalSearchService;

class SearchHistory {

	const MAX_COUNT = 10;
	const KEY_CRITERIA = 'criteria';
	const KEY_RENTALS = 'rentals';
	const KEY_URL = 'url';
	const KEY_HEADING = 'heading';

	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;


	public function __construct(\Nette\Http\SessionSection $section)
	{
		$this->section = $section;
	}


	/**
	 * @param array $criteria
	 * @param $rentals
	 * @param $url
	 * @param $heading
	 */
	public function addSearch(array $criteria, $rentals, $url, $heading)
	{
		$history = $this->getHistoryData();

		$hash = md5(Nette\Utils\Json::encode($criteria));
		unset($history[$hash]);

		$history[$hash] = [
			self::KEY_CRITERIA => $criteria,
			self::KEY_RENTALS => $rentals,
			self::KEY_URL => $url,
			self::KEY_HEADING => $heading,
		];

		if(count($history) > self::MAX_COUNT) {
			$history = array_chunk($history, self::MAX_COUNT, TRUE)[0];
		}

		$this->setHistoryData($history);

		if(isset($criteria[RentalSearchService::CRITERIA_GPS])) {
			$gpsSearchLogs = $this->getGpsSearchLogData();
			$hash = md5($criteria[RentalSearchService::CRITERIA_GPS]['latitude'].'|'.$criteria[RentalSearchService::CRITERIA_GPS]['longitude']);
			$gpsSearchLogs[$hash] = 1;
			$this->setGpsSearchLogData($gpsSearchLogs);
		}
	}


	/**
	 * @return bool
	 */
	public function exists()
	{
		return (bool) count($this->getHistoryData());
	}


	/**
	 * @return array
	 */
	public function getLast()
	{
		$history = $this->getHistoryData();
		return end($history);
	}


	/**
	 * @return array
	 */
	public function getHistory()
	{
		return array_reverse($this->getHistoryData());
	}


	/**
	 * @return int
	 */
	public function getCount()
	{
		return count($this->getHistoryData());
	}


	/**
	 * @return array
	 */
	protected function getHistoryData()
	{
		$history = $this->section->history;
		if(!$history) {
			$history = [];
		}

		return $history;
	}


	/**
	 * @param array $history
	 */
	protected function setHistoryData(array $history)
	{
		$this->section->history = $history;
	}

	/* ====================================================== */
	/* =================== GPS SEARCH LOG =================== */
	/* ====================================================== */

	/**
	 * @return array
	 */
	protected function getGpsSearchLogData()
	{
		$data = $this->section->gpsSearchLog;
		if(!$data) {
			$data = [];
		}

		return $data;
	}


	/**
	 * @param array $data
	 */
	protected function setGpsSearchLogData(array $data)
	{
		$this->section->gpsSearchLog = $data;
	}


	/**
	 * @param $latitude
	 * @param $longitude
	 *
	 * @return bool
	 */
	public function hasGps($latitude, $longitude)
	{
		$gpsSearchLogs = $this->getGpsSearchLogData();
		$hash = md5($latitude.'|'.$longitude);
		return isset($gpsSearchLogs[$hash]);
	}

}
