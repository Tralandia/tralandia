<?php

namespace Entity\Email;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email_batch", indexes={@ORM\index(name="confirmed", columns={"confirmed"}), @ORM\index(name="totalCount", columns={"totalCount"})})
 * @EA\Primary(key="id", value="domain")
 */
class Batch extends \Entity\BaseEntityDetails {

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $confirmed;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Template", inversedBy="batches")
	 */
	protected $template;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $totalCount;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $subject;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $body;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $bodyHtml;

									//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Email\Batch
	 */
	public function setConfirmed($confirmed)
	{
		$this->confirmed = $confirmed;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getConfirmed()
	{
		return $this->confirmed;
	}
		
	/**
	 * @param \Entity\Email\Template
	 * @return \Entity\Email\Batch
	 */
	public function setTemplate(\Entity\Email\Template $template)
	{
		$this->template = $template;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\Batch
	 */
	public function unsetTemplate()
	{
		$this->template = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\Template|NULL
	 */
	public function getTemplate()
	{
		return $this->template;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Email\Batch
	 */
	public function setTotalCount($totalCount)
	{
		$this->totalCount = $totalCount;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getTotalCount()
	{
		return $this->totalCount;
	}
		
	/**
	 * @param string
	 * @return \Entity\Email\Batch
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSubject()
	{
		return $this->subject;
	}
		
	/**
	 * @param string
	 * @return \Entity\Email\Batch
	 */
	public function setBody($body)
	{
		$this->body = $body;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getBody()
	{
		return $this->body;
	}
		
	/**
	 * @param string
	 * @return \Entity\Email\Batch
	 */
	public function setBodyHtml($bodyHtml)
	{
		$this->bodyHtml = $bodyHtml;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getBodyHtml()
	{
		return $this->bodyHtml;
	}
}