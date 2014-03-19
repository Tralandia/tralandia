<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:23
 */

namespace PersonalSite;


use Nette;
use Tralandia\Rental\Rental;
use Tralandia\Rental\RentalDao;

class RentalData
{

	/**
	 * @var \Tralandia\Rental\Rental
	 */
	private $rental;

	/**
	 * @var PricesData
	 */
	private $prices;

	/**
	 * @var array
	 */
	private $_photos;


	public function __construct($slug, RentalDao $rentalDao)
	{
		//$this->rentalRow = $rentalDao->findOneBy(['slug', $slug]);
		$this->rental = $rentalDao->findOneBy(['id' => 21933]);
		$this->prices = new PricesData($this->rental);
	}


	/**
	 * @return PricesData
	 */
	public function getPrices()
	{
		return $this->prices;
	}


	public function getName()
	{
		return $this->rental->getNameId();
	}

	public function getTeaser()
	{
		return 'mock';
	}


	/**
	 * return array[answer, question]
	 * @return array
	 */
	public function getInterview()
	{
		return $this->rental->getInterview();
	}

	public function getPhotos($limit = NULL, $offset = 0)
	{
		if(!$this->_photos) {
			$photos = [];
			foreach($this->rental->images as $image) {
				$photos[] = Nette\ArrayHash::from([
					'path' => 'http://tralandiastatic.com/rental_images' . $image->filePath . '/medium.jpeg',
				]);
			}
			$this->_photos = $photos;
		}

		return array_slice($this->_photos, $offset, $limit);
	}


	/**
	 * @return int
	 */
	public function getPhotosCount()
	{
		return count($this->getPhotos());
	}


	/**
	 * @return mixed
	 */
	public function getMainPhoto()
	{
		$photos = $this->getPhotos(1);
		return reset($photos);
	}


	public function getType()
	{
		return 'mock';
	}


	public function getLocation()
	{
		return 'mock';
	}

	public function getMaxCapacity()
	{
		return $this->rental->maxCapacity;
	}


	public function getBoard()
	{
		return 'mock';
	}

	public function hasBoard()
	{
		return 'mock';
	}

	public function hasWifi()
	{
		return 'mock';
	}

	public function getAllowedPet()
	{
		return 'mock';
	}

	public function isPetAllowed()
	{
		return 'mock';
	}

	public function getRoomAmenities()
	{
		return 'mock';
	}

	public function getRentalAmenities()
	{
		return 'mock';
	}

	public function getMinRoost()
	{
		return 'mock';
	}

}


interface IRentalDataFactory
{
	public function create($slug);
}
