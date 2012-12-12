<?php

namespace Service;

use Service, Doctrine, Entity;

/**
 * @author Radoslav Toth
 */
class PolygonService extends Service\BaseService {

	protected $locationRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;
	protected $rentalRepositoryAccessor;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
	}
	
	public function setLocationsForRental(\Entity\Rental\Rental $rental){
		$matches = array();
		$locationType = $this->locationTypeRepositoryAccessor->get()->findBy(array('slug' => 'region'));

		$locations = $this->locationRepositoryAccessor->get()->findBy(array(
			'parent' => $rental->primaryLocation,
			'type' => $locationType,
		));

		foreach ($locations as $location) {			
			foreach ($location->polygons as $key2 => $val2) {
				if(count($val2) == 4){
					if($val2[0] <= $rental->address->latitude && $val2[2] >= $rental->address->latitude && $val2[1]<=$rental->address->longitude && $val2[3] >= $rental->address->longitude){
						$matches[] = $location;
						break;
					}
				}else if(count($val2) == 3){
					$distance=(((acos(sin(($val2[0]*pi()/180))*sin(($rental->address->latitude*pi()/180))+cos(($val2[0]*pi()/180))*cos(($rental->address->latitude*pi()/180))*cos((($val2[1]-$rental->address->longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344*1000);
					if($distance<$val2[2]){
						$matches[] = $location;
						break;
					}
				}
			}
		}

		foreach ($matches as $location) {
			$rental->address->addLocation($location);
		}

		return TRUE;
	}

	//david - ako zabezpecit, ze sem moze prist len "region", nie hociaky location?
	function setRentalsForLocation(\Entity\Location\Location $location){
		$matches = array();

		$locationType = $this->locationTypeRepositoryAccessor->get()->findBy(array('slug' => 'region'));

		$rentals = $this->locationRepositoryAccessor->get()->findBy(array(
			'primaryLocation' => $location->parent,
			'status' => \Entity\Rental\Rental::STATUS_LIVE,
		));

		// This is only done for regions, not localities or countries
		// Return false if no latitude, longitude or missing polygons
		if ($location->type != $locationType || !$location->latitude || !$location->longitude || !$location->polygons) {
			return FALSE;
		}
		
		foreach ($rentals as $rental) {
			foreach ($location->polygons as $key2 => $val2) {
				if(count($val2) == 4){
					if($val2[0] <= $rental->address->latitude && $val2[2] >= $rental->address->latitude && $val2[1]<=$rental->address->longitude && $val2[3] >= $rental->address->longitude){
						$matches[] = $location;
						break;
					}
				}else if(count($val2) == 3){
					$distance=(((acos(sin(($val2[0]*pi()/180))*sin(($rental->address->latitude*pi()/180))+cos(($val2[0]*pi()/180))*cos(($rental->address->latitude*pi()/180))*cos((($val2[1]-$rental->address->longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344*1000);
					if($distance<$val2[2]){
						$matches[] = $location;
						break;
					}
				}
			}

			foreach ($matches as $location) {
				$rental->address->addLocation($location);
			}
		}
		return TRUE;
	}
}