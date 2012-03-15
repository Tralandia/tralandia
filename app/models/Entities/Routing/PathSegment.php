<?php

namespace Entities\Routing;

use Entities\Dictionary;
use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="routing_pathsegment")
 */
class PathSegment extends BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $pathSegment;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

}