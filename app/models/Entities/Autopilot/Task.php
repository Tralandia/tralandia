<?php

namespace Entities\Autopilot;

use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_task")
 */
class Task extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $subtype;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $mission;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $startTime;

	/**
	 * @var time
	 * @ORM\Column(type="time")
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $links;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $userCountry;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $userLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $userLanguageLevel;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\Role")
	 */
	protected $userRole;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\User\User", mappedBy="tasks")
	 */
	protected $usersExcluded;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $validation;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $actions;

}