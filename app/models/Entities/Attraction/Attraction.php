<?php

namespace Entities\Attraction;

use Entities\Contact;
use Entities\Dictionary;
use Entities\Location;
use Entities\Medium;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attraction_attraction")
 */
class Attraction extends \BaseEntityDetails {

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
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $country;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong")
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong")
	 */
	protected $longitude;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Contact\Contact", mappedBy="attractions")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User")
	 */
	protected $managingUser;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Medium\Medium", mappedBy="attraction")
	 */
	protected $media;

}