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
class Order extends \Entity\BaseEntity
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
	protected $surname;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Phone", cascade={"persist"})
	 */
	protected $phone;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $peopleCount;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $childrenCount;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $childrenAge;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $exceedingBedsCount;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $dateFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $dateTo;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $message;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $ownerNotes;

	/**
	 * @var text
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $confirmed;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $referer;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $price;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="services")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

}
