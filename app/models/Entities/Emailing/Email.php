<?php

namespace Emailing;

use Dictionary;
use Emailing;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="emailing_email")
 */
class Email extends \BaseEntity {

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $subject;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $body;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $bodyHtml;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;


	public function __construct() {

	}


	/**
	 * @param string $name
	 * @return Email
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param Dictionary\Phrase $subject
	 * @return Email
	 */
	public function setSubject(Dictionary\Phrase  $subject) {
		$this->subject = $subject;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getSubject() {
		return $this->subject;
	}


	/**
	 * @param Dictionary\Phrase $body
	 * @return Email
	 */
	public function setBody(Dictionary\Phrase  $body) {
		$this->body = $body;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getBody() {
		return $this->body;
	}


	/**
	 * @param Dictionary\Phrase $bodyHtml
	 * @return Email
	 */
	public function setBodyHtml(Dictionary\Phrase  $bodyHtml) {
		$this->bodyHtml = $bodyHtml;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getBodyHtml() {
		return $this->bodyHtml;
	}


	/**
	 * @param Dictionary\Language $language
	 * @return Email
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param Type $type
	 * @return Email
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}

}
