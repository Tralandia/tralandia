<?php

namespace Entities\Autopilot;

use Entities\Autopilot;
use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_task")
 */
class Task extends \BaseEntity {

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
	 * @ORM\ManyToOne(targetEntity="User\User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location\Location")
	 */
	protected $userCountry;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Dictionary\Language")
	 */
	protected $userLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Dictionary\Quality")
	 */
	protected $userLanguageQuality;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User\Role")
	 */
	protected $userRole;

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