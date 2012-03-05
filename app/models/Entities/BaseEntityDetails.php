<?php




/**
 * @Entity()
 * @Table(name="baseentitydetails")
 */
class BaseEntityDetails extends BaseEntity {

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $details;


	public function __construct() {

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
