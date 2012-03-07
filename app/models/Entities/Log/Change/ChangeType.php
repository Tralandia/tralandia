<?php

namespace Entities\Log\Change;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changetype")
 */
class ChangeType extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $important;

}