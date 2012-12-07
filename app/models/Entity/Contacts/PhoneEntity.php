<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phone", indexes={@ORM\index(name="value", columns={"value"})})
 */
class Phone extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	protected $value;
		
	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	protected $international;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	protected $national;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		

}