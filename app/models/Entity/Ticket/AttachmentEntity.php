<?php

namespace Entity\Ticket;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="ticket_attachment")
 *
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

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entity\Ticket\Attachment
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\Ticket\Attachment
	 */
	public function setMessage(\Entity\Ticket\Message $message)
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * @return \Entity\Ticket\Attachment
	 */
	public function unsetMessage()
	{
		$this->message = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Ticket\Message|NULL
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string
	 * @return \Entity\Ticket\Attachment
	 */
	public function setFilePath($filePath)
	{
		$this->filePath = $filePath;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getFilePath()
	{
		return $this->filePath;
	}
}
