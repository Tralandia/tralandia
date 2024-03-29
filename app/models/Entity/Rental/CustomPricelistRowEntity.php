<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_custompricelistrow")
 *
 * @method \DateTime getSeasonFrom()
 * @method setSeasonFrom(\DateTime $from)
 * @method \DateTime getSeasonTo()
 * @method setSeasonTo(\DateTime $to)
 * @method \Entity\Phrase\Phrase getNote()
 * @method setRental(\Entity\Rental\Rental $rental)
 * @method null|\Entity\Rental\Rental getRental()
 * @method setPriceFor(\Entity\Rental\PriceFor $priceFor)
 * @method \Entity\Rental\PriceFor getPriceFor()
 * @method setSort($int)
 * @method int getSort()
 *
 */
class CustomPricelistRow extends \Entity\BaseEntity
{
	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort = 0;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $seasonFrom;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $seasonTo;

	/**
	 * @var price
	 * @ORM\Column(type="integer")
	 */
	protected $price;

	/**
	 * @var \Entity\Phrase\Phrase
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\PriceFor")
	 */
	protected $priceFor;


	/**
	 * @var \Entity\Phrase\Phrase
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $note;


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
		$this->price = $price;
	}


	public function getCurrency()
	{
		return $this->getRental()->getCurrency();
	}

	public function unsetRental()
	{
		$this->rental = NULL;
	}

}
