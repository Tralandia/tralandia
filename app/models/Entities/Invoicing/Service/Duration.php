<?php

namespace Entities\Invoicing\Service;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_duration")
 */
class Duration extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
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

}