<?php

namespace Entities\Medium;

use Entities\Dictionary;
use Entities\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_medium")
 */
class Medium extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Attraction\Attraction", inversedBy="media")
	 */
	protected $attractions;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $location;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort;

}