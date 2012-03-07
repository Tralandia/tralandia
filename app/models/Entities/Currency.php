<?php

namespace Entities;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 */
class Currency extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $iso;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal")
	 */
	protected $exchangeRate;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $decimalPlaces;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $rounding;

}