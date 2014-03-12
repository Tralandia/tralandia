<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="rental_type")
 *
 *
 */
class Unit extends \Entity\BaseEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $capacity;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="services")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

}
