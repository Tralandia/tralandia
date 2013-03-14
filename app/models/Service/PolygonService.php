<?php

namespace Service;

use Service, Doctrine, Entity;

/**
 * @author Radoslav Toth
 */
class PolygonService {

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
		$locationType = $this->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'region'));
		
		$locations = $this->locationRepositoryAccessor->get()->findBy(array(
			'parent' => $rental->primaryLocation,
			'type' => $locationType,
		));

		$gps =$rental->getAddress()->getGps();
		if($gps->isValid()) {
			$latitude = $gps->getLatitude();
			$longitude = $gps->getLongitude();
		} else {
			return FALSE;
		}

		foreach ($locations as $location) {			
			foreach ($location->polygons as $key2 => $val2) {
				if(count($val2) == 4){
					if($val2[0] <= $latitude && $val2[2] >= $latitude && $val2[1]<=$longitude && $val2[3] >= $longitude){
						$matches[] = $location;
						break;
					}
				}else if(count($val2) == 3){
					$distance=(((acos(sin(($val2[0]*pi()/180))*sin(($latitude*pi()/180))+cos(($val2[0]*pi()/180))*cos(($latitude*pi()/180))*cos((($val2[1]-$longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344*1000);
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

		$locationType = $this->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'region'));

		$rentals = $this->rentalRepositoryAccessor->get()->findByPrimaryLocation(
			$location->getPrimaryParent(),
			\Entity\Rental\Rental::STATUS_LIVE
		);

		// This is only done for regions, not localities or countries
		// Return false if no latitude, longitude or missing polygons
		if ($location->type != $locationType || !$location->polygons) {
			$location->clearAddresses();
			return FALSE;
		}
		foreach ($rentals as $rental) {
			$matches = array();
			$rentalGps = $rental->getAddress()->getGps();
			if($rentalGps->isValid()) {
				$latitude = $rentalGps->getLatitude();
				$longitude = $rentalGps->getLongitude();
			} else {
				continue;
			}
			foreach ($location->polygons as $key2 => $val2) {
				if(count($val2) == 4){
					if($val2[0] <= $latitude && $val2[2] >= $latitude && $val2[1]<=$longitude && $val2[3] >= $longitude){
						$matches[] = $location;
						break;
					}
				}else if(count($val2) == 3){
					$distance=(((acos(sin(($val2[0]*pi()/180))*sin(($latitude*pi()/180))+cos(($val2[0]*pi()/180))*cos(($latitude*pi()/180))*cos((($val2[1]-$longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344*1000);
					if($distance<$val2[2]){
						$matches[] = $location;
						break;
					}
				}
			}
			//d($rental->id, $latitude, $longitude, $matches);
			$rental->address->setLocations($matches);
		}
		return TRUE;
	}
}