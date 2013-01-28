<?php

namespace Entity\Ticket;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="ticket_attachment")
 * @EA\Primary(key="id", value="name")
 */
class Attachment extends \Entity\BaseEntity {

	/**
	 * @var text
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Message")
	 */
	protected $message;

	/**
	 * @var text
	 * @ORM\Column(type="string")
	 */
	protected $filePath;

	//@entity-generator-code --- NEMAZAT !!!

}