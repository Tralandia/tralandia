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
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(type="Package")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Location\Location")
	 */
	protected $locationsIncluded;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Location\Location")
	 */
	protected $locationsExcluded;

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
	 * @ORM\OneToMany(type="Use")
	 */
	protected $uses;

}