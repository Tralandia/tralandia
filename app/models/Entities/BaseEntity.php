<?php
/**
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
abstract class BaseEntity extends Entity {
	
	const PRIMARY_KEY = 'id';

	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/** 
	 * @Column(type="datetime")
	 */
	protected $created;

	/** 
	 * @Column(type="datetime")
	 */
	protected $updated;

	
	/**
	 * @prePersist
	 */
	public function setCreated(){
		$this->created = new \Nette\DateTime;
	}

	/**
	 * @prePersist
	 * @preUpdate
	 */
	public function setUpdated(){
		$this->updated = new \Nette\DateTime;
	}

}
