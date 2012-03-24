<?php

namespace Entities\Invoicing;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_use")
 */
class UseType extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
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