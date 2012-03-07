<?php

namespace Entities\Routing;

use Entities\Dictionary;
use Entities\Location;
use Entities\Routing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="routing_pathsegmentold")
 */
class PathSegmentOld extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\ManyToOne(targetEntity="PathSegment")
	 */
	protected $pathSegmentNew;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $pathSegment;

	/**
	 * @var Integer
	 * @ORM\Column(type="Integer")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

}