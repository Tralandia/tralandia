<?php

namespace Entity;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 */
class Currency extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $iso;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal", nullable=true)
	 */
	protected $exchangeRate;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $decimalPlaces;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $rounding;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Country", inversedBy="currencies")
	 */
	protected $countries;


    //@entity-generator-code

}