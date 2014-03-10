<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:23
 */

namespace PersonalSite;


use Nette;

class RentalData
{

	/**
	 * @var \Nette\Database\Connection
	 */
	private $db;

	/**
	 * @var bool|\Nette\Database\IRow|\Nette\Database\Row
	 */
	private $rentalRow;

	/**
	 * @var PricesData
	 */
	private $prices;

	/**
	 * @var array
	 */
	private $_photos;


	public function __construct($slug, Nette\Database\Connection $db)
	{
		$this->db = $db;
		//$this->rentalRow = $db->query('SELECT * FROM supply WHERE slug = ? LIMIT 1', $slug)->fetch();
		$this->rentalRow = $db->query('SELECT * FROM supply WHERE id = ? LIMIT 1', 3842)->fetch();
		$this->prices = new PricesData($this->rentalRow, $db);
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
		return $this->rentalRow->name;
	}

	public function getTeaser()
	{
		return 'mock';
	}

	public function getDescription()
	{
		return $this->rentalRow->description;
	}

	public function getPhotos($limit = NULL, $offset = 0)
	{
		if(!$this->_photos) {
			$photos = [];
			foreach(array_filter(explode(',,', $this->rentalRow->photos)) as $path) {
				$photos[] = Nette\ArrayHash::from([
					'path' => 'http://www.ubytovanienaslovensku.eu/u/' . $path,
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
		return $this->rentalRow->max_capacity;
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
		return $this->rentalRow->prices_min_length;
	}

}


interface IRentalDataFactory
{
	public function create($slug);
}
