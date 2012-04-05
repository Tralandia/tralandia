<?php

namespace Entity;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="domain")
 */
class Domain extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Location\Location", mappedBy="domain")
	 */
	protected $locations;

	

	//@entity-generator-code

}