<?php

namespace MapModule;

use Extras\Models\Service\Exception;
use Nette;
use Nette\Utils\Json;

class SearchPresenter extends \BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\Rental\Rentals
	 */
	protected $rentals;

	public function actionDefault($latitudeA, $longitudeA, $latitudeB, $longitudeB, $zoom, $key)
	{
		if($key != 'totojesupertajneheslospravovaneufonmi') {
			$this->terminate();
		}

		$skipIds = $this->getParameter('skipids', []);
		Nette\Diagnostics\Debugger::log(Json::encode($skipIds), 'map');
		Nette\Diagnostics\Debugger::log(new Exception());

		$zoom = (int) $zoom;
		$zoomBorder1 = 7; // countries
		$zoomBorder2 = 11; // locations

		$isIn = function($from, $to, $zoom) {
			return $from+1 <= $zoom && $zoom <= $to;
		};

		if($isIn(0, $zoomBorder1, $zoom)) {
			$this->payload->countries = $this->rentals->getCountsInCountries($latitudeA, $longitudeA, $latitudeB, $longitudeB);
		} else if($isIn($zoomBorder1, $zoomBorder2, $zoom)) {
			$this->payload->localities = $this->rentals->getCountsInLocalities($latitudeA, $longitudeA, $latitudeB, $longitudeB);
		} else if($isIn($zoomBorder2, 20, $zoom)) {
			$this->payload->rentals = $this->rentals->getRentalsBetween($latitudeA, $longitudeA, $latitudeB, $longitudeB, $skipIds, $this);
		}

		$this->sendPayload();
	}

}
