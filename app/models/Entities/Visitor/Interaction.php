<?php

namespace Entities\Visitor;

use Entities\Dictionary;
use Entities\Rental;
use Entities\User;
use Entities\Visitor;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="visitor_interaction")
 */
class Interaction extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\Role")
	 */
	protected $role;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Rental\Rental")
	 */
	protected $rental;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;

}