<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/05/14 09:14
 */

namespace Tralandia\GpsSearchLog;


use Entity\Location\Location;
use Nette;
use Tralandia\Location\LocationRepository;

class GpsSearchLogManager
{

	/**
	 * @var GpsSearchLogRepository
	 */
	private $repository;

	/**
	 * @var \Tralandia\Location\LocationRepository
	 */
	private $locationRepository;


	public function __construct(GpsSearchLogRepository $repository, LocationRepository $locationRepository)
	{
		$this->repository = $repository;
		$this->locationRepository = $locationRepository;
	}


	public function log($latitude, $longitude, $address, $primaryLocation)
	{
		if($primaryLocation instanceof Location) {
			$primaryLocation = $primaryLocation->getId();
		}

		if(is_numeric($primaryLocation)) {
			$primaryLocation = $this->locationRepository->find($primaryLocation);
		}

		if($gpsSearchLog = $this->findOneByGps($latitude, $longitude)) {
			$gpsSearchLog->count = $gpsSearchLog->count + 1;
		} else {
			$gpsSearchLog = $this->repository->createNew();

			$address = explode(',', $address);
			array_pop($address);
			$gpsSearchLog->text = implode(',', $address);
			$gpsSearchLog->latitude = $latitude;
			$gpsSearchLog->longitude = $longitude;
			$gpsSearchLog->primaryLocation = $primaryLocation;
			$gpsSearchLog->count = 1;
		}
		$this->repository->save($gpsSearchLog);


		return $gpsSearchLog;
	}


	/**
	 * @param $latitude
	 * @param $longitude
	 *
	 * @return GpsSearchLog
	 */
	public function findOneByGps($latitude, $longitude)
	{
		return $this->repository->findOneBy(['latitude' => $latitude, 'longitude' => $longitude]);
	}
}
