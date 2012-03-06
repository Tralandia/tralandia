<?php

namespace Entities\Ticket;

use Entities\Dictionary;
use Entities\Medium;
use Entities\Ticket;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_message")
 */
class Message extends \BaseEntity {

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
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $message;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Medium\Medium")
	 */
	protected $attachments;

}