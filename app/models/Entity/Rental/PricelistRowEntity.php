<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use    Extras\Annotation as EA;
use Extras\FileStorage;

use Nette\Http\FileUpload;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_pricelistrow")
 *
 *
 */
class PricelistRow extends \Entity\BaseEntity
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sort = 0;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $roomCount = 0;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="RoomType")
	 */
	protected $roomType;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $bedCount = 0;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $extraBedCount = 0;

	/**
	 * @var price
	 * @ORM\Column(type="integer")
	 */
	protected $price;


	/**
	 * @return \Extras\Types\Price
	 */
	public function getPrice()
	{
		return new \Extras\Types\Price($this->price, $this->getCurrency());
	}


	public function setPrice(\Extras\Types\Price $price)
	{
		$this->price = $price->convertToFloat($this->getCurrency());

		return $this;
	}


	public function setFloatPrice($price)
	{
		$this->setPrice(new \Extras\Types\Price($price, $this->getCurrency()));
	}


	public function getCurrency()
	{
		return $this->getRental()->getCurrency();
	}


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\PricelistRow
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Rental\PricelistRow
	 */
	public function unsetRental()
	{
		$this->rental = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
	}

	/**
	 * @param integer
	 * @return \Entity\Rental\PricelistRow
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}

	/**
	 * @return \Entity\Rental\PricelistRow
	 */
	public function unsetSort()
	{
		$this->sort = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @param integer
	 * @return \Entity\Rental\PricelistRow
	 */
	public function setRoomCount($roomCount)
	{
		$this->roomCount = $roomCount;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getRoomCount()
	{
		return $this->roomCount;
	}

	/**
	 * @param \Entity\Rental\RoomType
	 * @return \Entity\Rental\PricelistRow
	 */
	public function setRoomType(\Entity\Rental\RoomType $roomType)
	{
		$this->roomType = $roomType;

		return $this;
	}

	/**
	 * @return \Entity\Rental\PricelistRow
	 */
	public function unsetRoomType()
	{
		$this->roomType = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\RoomType|NULL
	 */
	public function getRoomType()
	{
		return $this->roomType;
	}

	/**
	 * @param integer
	 * @return \Entity\Rental\PricelistRow
	 */
	public function setBedCount($bedCount)
	{
		$this->bedCount = $bedCount;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getBedCount()
	{
		return $this->bedCount;
	}

	/**
	 * @param integer
	 * @return \Entity\Rental\PricelistRow
	 */
	public function setExtraBedCount($extraBedCount)
	{
		$this->extraBedCount = $extraBedCount;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getExtraBedCount()
	{
		return $this->extraBedCount;
	}
}
