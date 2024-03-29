<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Proxy\Proxy;
use Extras;
use Kdyby\Doctrine\Entities\IdentifiedEntity;
use Nette\Utils\Strings;

/**
 * @ORM\MappedSuperclass(repositoryClass="\Tralandia\BaseDao")
 * @ORM\Table(indexes={@ORM\Index(name="oldId", columns={"oldId"})})
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity extends IdentifiedEntity implements \Nette\Security\IResource {

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $oldId;

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


	public function __construct() {
		parent::__construct();
	}

	public function getResourceId()
	{
		return $this->getClass();
	}

	public function getClass()
	{
		$class = get_class($this);
		if(Strings::contains($class, 'Kdyby\GeneratedProxy\\')) {
			$class = get_parent_class($this);
		}

		return $class;
	}

	/**
	 * @param integer
	 * @return \Entity\BaseEntity
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @param integer
	 * @return \Entity\BaseEntity
	 */
	public function setOldId($oldId) {
		$this->oldId = $oldId;

		return $this;
	}

	/**
	 * @return \Entity\BaseEntity
	 */
	public function unsetOldId() {
		$this->oldId = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getOldId() {
		return $this->oldId;
	}


	/**
	 * @ORM\PrePersist
	 * @return BaseEntity
	 */
	public function fillCreated()
	{
		if(!$this->created) {
			$this->created = new \DateTime();
		}

		return $this;
	}


	/**
	 * @param \DateTime $created
	 *
	 * @return \Entity\BaseEntity
	 */
	public function setCreated(\DateTime $created = NULL){

		$this->created = $created ? $created : new \DateTime;

		return $this;
	}

	/**
	 * @return \Extras\Types\Datetime|NULL
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 * @return \Entity\BaseEntity
	 */
	public function setUpdated() {
		$this->updated = new \Nette\DateTime;
	}

	/**
	 * @return \Extras\Types\Datetime|NULL
	 */
	public function getUpdated() {
		return $this->updated;
	}


	public function __set($name, $value) {
		// if ($this->getReflection()->hasProperty($name)) {
		// 	$this->{$name} = $value;
		// 	return;
		// }
		if($value === NULL) {
			$method = 'unset' . Strings::firstUpper($name);
			$this->{$method}();
			return NULL;
		}

		parent::__set($name, $value);
	}



}
