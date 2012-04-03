<?php

namespace Entity\Invoicing;

use Entity\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_coupon")
 */
class Coupon extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $code;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countTotal;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countLeft;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validTo;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $recommenderPackage;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $recommendeePackage;

}