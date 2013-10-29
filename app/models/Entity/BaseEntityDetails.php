<?php

namespace Entity;


use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Arrays;

/**
 * @ORM\MappedSuperclass(repositoryClass="\Tralandia\BaseDao")
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
     * @return array
     */
    public function getDetails() {
		if($this->details === NULL) {
			$this->details = [];
		}

        return $this->details;
    }


	/**
	 * @param mixed $key
	 *
	 * @return mixed|NULL
	 */
	public function getDetail($key) {
		return Arrays::get($this->getDetails(), $key, NULL);
    }

}
