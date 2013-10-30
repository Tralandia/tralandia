<?php

namespace Service;

use Service, Doctrine, Entity;
use Tralandia\Rental\Rentals;

/**
 * @author Radoslav Toth
 */
class PolygonService {

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $locationDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $locationTypeDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $rentalDao;

	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;


	/**
	 * @param Doctrine\ORM\EntityManager $em
	 * @param \Tralandia\Rental\Rentals $rentals
	 */
	public function __construct(Doctrine\ORM\EntityManager $em, Rentals $rentals)
	{
		$this->rentalDao = $em->getRepository(RENTAL_ENTITY);
		$this->locationDao = $em->getRepository(LOCATION_ENTITY);
		$this->locationTypeDao = $em->getRepository(LOCATION_TYPE_ENTITY);
		$this->rentals = $rentals;
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @internal param null $entity
	 */
	public function update(Entity\Rental\Rental $rental)
	{
		$this->rentalDao->save($rental, $rental->getAddress());
	}


	/**
	 * @param Entity\Rental\Rental $rental
	 *
	 * @return bool
	 */
	public function setLocationsForRental(\Entity\Rental\Rental $rental){
		$matches = array();
		$locationType = $this->locationTypeDao->findOneBy(array('slug' => 'region'));

		$locations = $this->locationDao->findBy(array(
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
		$rental->getAddress()->setLocations($matches);

		return TRUE;
	}


	/**
	 * @param Entity\Location\Location $location
	 *
	 * @return bool
	 */
	public function setRentalsForLocation(\Entity\Location\Location $location){

		$locationType = $this->locationTypeDao->findOneBy(array('slug' => 'region'));

		$rentals = $this->rentals->findByPrimaryLocation(
			$location->getPrimaryParent(),
			\Entity\Rental\Rental::STATUS_LIVE
		);

		// This is only done for regions, not localities or countries
		// Return false if no latitude, longitude or missing polygons
		if ($location->getType() != $locationType || !$location->getPolygons()) {
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
			foreach ($location->getPolygons() as $key2 => $val2) {
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
			if ($matches === TRUE) $rental->getAddress()->addLocation($location);
		}
		return TRUE;
	}
}
