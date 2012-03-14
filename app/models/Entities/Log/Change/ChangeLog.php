<?php

namespace Entities\Log\Change;

use Entities\Log;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changelog")
 */
class ChangeLog extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="ChangeType")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityName;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $userEmail;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

}