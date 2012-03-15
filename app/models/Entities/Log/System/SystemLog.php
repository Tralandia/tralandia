<?php

namespace Entities\Log\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_system_systemlog")
 */
class SystemLog extends \Entities\BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $comment;


    public function __construct() {
        parent::__construct();
    }
 
 
    /**
     * @param string
     * @return \Entities\Log\System\SystemLog
     */
    public function setName($name) {
        $this->name = $name;
 
        return $this;
    }
 
 
    /**
     * @return string|NULL
     */
    public function getName() {
        return $this->name;
    }
 
 
    /**
     * @param string
     * @return \Entities\Log\System\SystemLog
     */
    public function setComment($comment) {
        $this->comment = $comment;
 
        return $this;
    }
 
 
    /**
     * @return string|NULL
     */
    public function getComment() {
        return $this->comment;
    }

}