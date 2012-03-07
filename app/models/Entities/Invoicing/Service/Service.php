<?php

namespace Entities\Invoicing\Service;

use Entities\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_service")
 */
class Service extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Invoicing\Package", inversedBy="services")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Duration")
	 */
	protected $duration;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $defaultPrice;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $currentPrice;

}