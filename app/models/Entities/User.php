<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Primary(key="id", value="login")
 */
class User extends BaseEntity {

	/**
	 * @ORM\Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $login;

	/**
	 * @ORM\Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $password;

	/**
	 * @ORM\Column(type="boolean")
	 * @UIControl(type="checkbox", label="Is active?")
	 */
	protected $active;

	/**
	 * @ORM\ManyToOne(targetEntity="Country", inversedBy="user")
	 * @UIControl(type="select", callback="getList")
	 */
	protected $country;
	
	/**
	 * @ORM\OneToMany(targetEntity="Rental", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $rentals;
	
	public function __construct($data = array()) {
       $this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
       parent::__construct($data);
	}
	
	public function addRental(\Rental $rental) {
		$this->rentals[] = $rental;
		$rental->user = $this; 
	}
}
