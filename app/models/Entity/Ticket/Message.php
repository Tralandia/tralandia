<?php

namespace Entity\Ticket;

use Entity\Dictionary;
use Entity\Medium;
use Entity\Ticket;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_message")
 */
class Message extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="messages")
	 */
	protected $ticket;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $message;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="message")
	 */
	protected $attachments;

}