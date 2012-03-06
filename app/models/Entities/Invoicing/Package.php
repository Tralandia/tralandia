<?php

namespace Entities\Invoicing;

use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_package")
 */
class Package extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @ORM\OneToMany(type="Use")
	 */
	protected $uses;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Invoicing\Service\Service", mappedBy="package")
	 */
	protected $services;

}