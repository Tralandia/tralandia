<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="baseentitydetails")
 */
class BaseEntityDetails extends BaseEntity {

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $details;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param json $details
	 * @return BaseEntityDetails
	 */
	public function setDetails($details) {
		$this->details = $details;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getDetails() {
		return $this->details;
	}

}
