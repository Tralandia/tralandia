<?php

namespace Entity\Invoicing\Service;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_duration")
 */
class Duration extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $duration;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort;

	//@entity-generator-code

}