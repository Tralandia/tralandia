<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseEntity extends Entity implements IEntity {

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;
	
	/** 
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/** 
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;
	
	/**
	 * @ORM\prePersist
	 */
	public function setCreated(){
		$this->created = new \Nette\DateTime;
	}

	/**
	 * @ORM\prePersist
	 * @ORM\preUpdate
	 */
	public function setUpdated(){
		$this->updated = new \Nette\DateTime;
	}
}
