<?php

namespace Entities\Attraction;

use Entities\Attraction;
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
	 * @ORM\ManyToOne(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location\Location")
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
	 * @ORM\OneToMany(targetEntity="Contact\Contact")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User\User")
	 */
	protected $managingUser;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Medium\Medium")
	 */
	protected $media;

}