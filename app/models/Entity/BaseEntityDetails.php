<?php

namespace Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class BaseEntityDetails extends \Entity\BaseEntity {

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $details;

    /**
     * @param \Extras\Types\Json
     * @return self
     */
    public function setDetails($details) {
        $this->details = $details;
 
        return $this;
    }
 
 
    /**
     * @return self
     */
    public function unsetDetails() {
        $this->details = NULL;
 
        return $this;
    }
 
 
    /**
     * @return \Extras\Types\Json|NULL
     */
    public function getDetails() {
        return $this->details;
    }

}
