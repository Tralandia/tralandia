<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class BaseEntityDetails extends BaseEntity {

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $details;

    /**
     * @param json
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
     * @return json|NULL
     */
    public function getDetails() {
        return $this->details;
    }

}
