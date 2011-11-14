<?php

/**
 * @Entity(repositoryClass="UserRepository")
 * @HasLifecycleCallbacks
 */
class User extends BaseEntity {

	/**
	 * @Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $login;

	/**
	 * @Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $password;

	/**
	 * @Column(type="boolean")
	 * @UIControl(type="checkbox", label="Is active?")
	 */
	protected $active;

	/**
	 * @ManyToOne(targetEntity="Country")
	 * @UIControl(type="select", callback="getList", value="iso")
	 */
	protected $country;
	
	/**
	 * @OneToMany(targetEntity="Rental", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $rentals;
	
	public function __construct($data = array()) {
       $this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}
	
	public function addRental(\Rental $rental) {
		$this->rentals[] = $rental;
		$rental->user = $this; // ??? preo sa tu musi definovat aj tento vstah? 
	}
}
