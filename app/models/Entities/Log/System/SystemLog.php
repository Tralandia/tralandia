<?php

namespace Entities\Log\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_system_systemlog")
 */
class SystemLog extends \BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $comment;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param string $name
	 * @return SystemLog
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
	 * @param text $comment
	 * @return SystemLog
	 */
	public function setComment($comment) {
		$this->comment = $comment;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getComment() {
		return $this->comment;
	}

}
