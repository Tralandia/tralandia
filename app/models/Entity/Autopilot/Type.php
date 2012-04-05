<?php

namespace Entity\Autopilot;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_type")
 */
class Type extends \Entity\BaseEntityDetails {

	const ACTION_ON_SAVE = 'onSave';
	const ACTION_ON_DELEGATE = 'onDelegate';
	const ACTION_ON_COMPLETED = 'onCompleted';
	const ACTION_ON_DEFER = 'onDefer';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 * This is for internal use and quick
	 */
	protected $technicalName;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $mission;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 * defined in minutes
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $validation;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $stackable;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 * defined in minutes
	 */
	protected $timeLimit = 2;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $actions;

	//@entity-generator-code

}