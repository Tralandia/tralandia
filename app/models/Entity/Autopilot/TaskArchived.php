<?php

namespace Entity\Autopilot;

use Entity\Autopilot;
use Entity\Dictionary;
use Entity\Location;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_taskarchived")
 */
class TaskArchived extends \Entity\BaseEntityDetails {

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
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $due;

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
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $userCountry;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $userLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $userLanguageLevel;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\Role")
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

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $completed;

}