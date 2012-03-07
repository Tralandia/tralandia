<?php

namespace Entities\Invoicing;

use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_marketing")
 */
class Marketing extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="marketings")
	 */
	protected $locations;

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
	 * @ORM\ManyToMany(targetEntity="UseType", mappedBy="marketings")
	 */
	protected $uses;

}