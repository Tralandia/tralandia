<?php

namespace Entities;

use Entities\Location;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="domain")
 */
class Domain extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Location\Location")
	 */
	protected $locations;

}