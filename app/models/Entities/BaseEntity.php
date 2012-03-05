<?php




/**
 * @Entity()
 * @Table(name="baseentity")
 */
class BaseEntity {

	/**
	 * @var integer
	 * @Id @GeneratedValue
	 * @Column(type="integer")
	 */
	protected $id;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $updated;


	public function __construct() {

	}


	/**
	 * @param integer $id
	 * @return BaseEntity
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param datetime $created
	 * @return BaseEntity
	 */
	public function setCreated($created) {
		$this->created = $created;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getCreated() {
		return $this->created;
	}


	/**
	 * @param datetime $updated
	 * @return BaseEntity
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getUpdated() {
		return $this->updated;
	}

}
