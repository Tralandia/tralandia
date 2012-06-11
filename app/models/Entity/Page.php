<?php

namespace Entity;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="page", indexes={@ORM\index(name="destination", columns={"destination"})})
 * @EA\Service(name="\Service\Page")
 * @EA\ServiceList(name="\Service\PageList")
 * @EA\Primary(key="id", value="destination")
 */
class Page extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 * example: :Module:Presenter:Action
	 */
	protected $destination;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $parameters;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $genericContent;

//@entity-generator-code <--- NEMAZAT !!!

}