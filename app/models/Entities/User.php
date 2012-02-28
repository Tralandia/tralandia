<?php

/**
 * @Entity(repositoryClass="UserRepository")
 * @HasLifecycleCallbacks
 * @Primary(key="id", value="login")
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
	 * @ManyToOne(targetEntity="Country", inversedBy="user")
	 * @UIControl(type="select", callback="getList")
	 */
	protected $country;
	
	/**
	 * @OneToMany(targetEntity="Rental", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $rentals;
	
	public function __construct($data = array()) {
       $this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
       parent::__construct($data);
	}
	
	public function addRental(\Rental $rental) {
		$this->rentals[] = $rental;
		$rental->user = $this; // ??? preco sa tu musi definovat aj tento vstah? 
	}
}
