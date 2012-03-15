<?php

namespace Entities\Invoicing;

use Entities\Attraction;
use Entities\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_item")
 */
class Item extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="items")
	 */
	protected $invoice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Attraction\Type")
	 */
	protected $serviceType;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $nameEn;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $serviceFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $serviceTo;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationNameEn;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $price;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $priceEur;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $marketingName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $marketingNameEn;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $couponName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $couponNameEn;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $packageName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $packageNameEn;

}