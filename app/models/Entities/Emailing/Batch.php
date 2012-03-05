<?php

namespace Entities\Emailing;

use Entities\Emailing;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="emailing_batch")
 */
class Batch extends \BaseEntityDetails {

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $confirmed;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Email")
	 */
	protected $template;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $totalCount;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $subject;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $body;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $bodyHtml;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param boolean $confirmed
	 * @return Batch
	 */
	public function setConfirmed($confirmed) {
		$this->confirmed = $confirmed;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getConfirmed() {
		return $this->confirmed;
	}


	/**
	 * @param Email $template
	 * @return Batch
	 */
	public function setTemplate(Email  $template) {
		$this->template = $template;
		return $this;
	}


	/**
	 * @return Email
	 */
	public function getTemplate() {
		return $this->template;
	}


	/**
	 * @param integer $totalCount
	 * @return Batch
	 */
	public function setTotalCount($totalCount) {
		$this->totalCount = $totalCount;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getTotalCount() {
		return $this->totalCount;
	}


	/**
	 * @param text $subject
	 * @return Batch
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getSubject() {
		return $this->subject;
	}


	/**
	 * @param text $body
	 * @return Batch
	 */
	public function setBody($body) {
		$this->body = $body;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getBody() {
		return $this->body;
	}


	/**
	 * @param text $bodyHtml
	 * @return Batch
	 */
	public function setBodyHtml($bodyHtml) {
		$this->bodyHtml = $bodyHtml;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getBodyHtml() {
		return $this->bodyHtml;
	}

}
