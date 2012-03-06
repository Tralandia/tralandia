<?php

namespace Entities\Ticket;

use Entities\Dictionary;
use Entities\Location;
use Entities\Ticket;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_ticket")
 */
class Ticket extends \BaseEntity {

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $client;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User\User")
	 */
	protected $staff;

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
	 * @var Collection
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Message", mappedBy="ticket")
	 */
	protected $messages;

}