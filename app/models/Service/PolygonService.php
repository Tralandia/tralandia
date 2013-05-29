<?php

namespace Service;

use Service, Doctrine, Entity;

/**
 * @author Radoslav Toth
 */
class PolygonService {

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @var \Repository\Location\TypeRepository
	 */
	protected $locationTypeRepository;

	/**
	 * @var \Repository\Rental\RentalRepository
	 */
	protected $rentalRepository;


	/**
	 * @param Doctrine\ORM\EntityManager $em
	 */
	public function __construct(Doctrine\ORM\EntityManager $em)
	{
		$this->rentalRepository = $em->getRepository(RENTAL_ENTITY);
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->locationTypeRepository = $em->getRepository(LOCATION_TYPE_ENTITY);
	}


	/**
	 * @param null $entity
	 */
	public function update($entity = NULL)
	{
		$this->rentalRepository->update($entity);
	}


	/**
	 * @param Entity\Rental\Rental $rental
	 *
	 * @return bool
	 */
	public function setLocationsForRental(\Entity\Rental\Rental $rental){
		$matches = array();
		$locationType = $this->locationTypeRepository->findOneBy(array('slug' => 'region'));

		$locations = $this->locationRepository->findBy(array(
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
		$rental->address->setLocations($matches);

		return TRUE;
	}


	/**
	 * @param Entity\Location\Location $location
	 *
	 * @return bool
	 */
	public function setRentalsForLocation(\Entity\Location\Location $location){

		$locationType = $this->locationTypeRepository->findOneBy(array('slug' => 'region'));

		$rentals = $this->rentalRepository->findByPrimaryLocation(
			$location->getPrimaryParent(),
			\Entity\Rental\Rental::STATUS_LIVE
		);

		$location->clearAddresses();

		// This is only done for regions, not localities or countries
		// Return false if no latitude, longitude or missing polygons
		if ($location->type != $locationType || !$location->polygons) {
			return FALSE;
		}

		foreach ($rentals as $rental) {
			$matches = false;
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
						$matches = true;
						break;
					}
				}else if(count($val2) == 3){
					$distance=(((acos(sin(($val2[0]*pi()/180))*sin(($latitude*pi()/180))+cos(($val2[0]*pi()/180))*cos(($latitude*pi()/180))*cos((($val2[1]-$longitude)*pi()/180))))*180/pi())*60*1.1515*1.609344*1000);
					if($distance<$val2[2]){
						$matches = true;
						break;
					}
				}
			}
			if ($matches === TRUE) $rental->address->addLocation($location);
		}
		return TRUE;
	}
}
