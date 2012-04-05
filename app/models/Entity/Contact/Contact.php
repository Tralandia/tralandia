<?php

namespace Entity\Contact;

use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_contact")
 */
class Contact extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type", cascade={"persist"})
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Attraction\Attraction", inversedBy="contacts", cascade={"persist"})
	 */
	protected $attractions;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="contacts", cascade={"persist"})
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User", inversedBy="contact", cascade={"persist"})
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Country", inversedBy="contacts", cascade={"persist"})
	 */
	protected $countries;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $info;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $subscribed = TRUE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $banned = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $full = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $spam = FALSE;

	//@entity-generator-code

}