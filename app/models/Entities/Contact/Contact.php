<?php

namespace Entities\Contact;

use Entities\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_contact")
 */
class Contact extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Attraction\Attraction", inversedBy="contacts")
	 */
	protected $attractions;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $info;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $unsubscribed;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $banned;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $full;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $spam;

}