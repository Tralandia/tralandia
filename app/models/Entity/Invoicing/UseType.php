<?php

namespace Entity\Invoicing;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_use")
 */
class UseType extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Package", inversedBy="uses")
	 */
	protected $packages;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Marketing", inversedBy="uses")
	 */
	protected $marketings;

}