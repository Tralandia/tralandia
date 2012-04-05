<?php

namespace Entity\Invoicing\Service;

use Entity\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_service")
 */
class Service extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoicing\Package", inversedBy="services")
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

	//@entity-generator-code

}