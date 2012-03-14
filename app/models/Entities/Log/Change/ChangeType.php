<?php

namespace Entities\Log\Change;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changetype")
 */
class ChangeType extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $important;



    /**
     * @param string|NULL
     * @return Entities\Log\Change\ChangeType
     */
    public function setName($name = NULL) {
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
     * @param boolean|NULL
     * @return Entities\Log\Change\ChangeType
     */
    public function setImportant($important = NULL) {
        $this->important = $important;
 
        return $this;
    }
 
 
    /**
     * @return boolean|NULL
     */
    public function getImportant() {
        return $this->important;
    }

}