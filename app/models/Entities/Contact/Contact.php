<?php

namespace Entities\Contact;

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