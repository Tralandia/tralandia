<?php

namespace Entities\Ticket;

use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_ticket")
 */
class Ticket extends \Entities\BaseEntity {

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $client;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User")
	 */
	protected $staff;

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
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Message", mappedBy="ticket")
	 */
	protected $messages;

}