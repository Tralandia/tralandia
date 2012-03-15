<?php

namespace Entities\Autopilot;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_type")
 */
class Type extends BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var time
	 * @ORM\Column(type="time")
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $validation;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $stackable;

}