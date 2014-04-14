<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rental_unit")
 *
 * @method \Entity\Rental\Rental getRental()
 */
class Unit extends \Entity\BaseEntity
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="units")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $maxCapacity;


	public function setRental(Rental $rental)
	{
		$this->rental = $rental;
	}

	public function unsetRental()
	{
		$this->rental = null;
	}

}
