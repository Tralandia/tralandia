<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity extends Entity{

	/**
	 * @var integer
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
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