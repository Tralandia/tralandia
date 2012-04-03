<?php

namespace Entity\Ticket;

use Entity\Dictionary;
use Entity\Location;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_ticket")
 */
class Ticket extends \Entity\BaseEntity {

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $client;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $staff;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
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