<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @Table(name="user")
 * @HasLifecycleCallbacks
 */
class User extends BaseEntity {
	
	const ROLE_GUEST = 'guest';
	const ROLE_MEMBER = 'member';
	const ROLE_ADMIN = 'admin';
	
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string")
	 */
	protected $email;
	
	/**
	 * @Column(type="string")
	 */
	protected $password;
	
	/**
	 * @Column(type="string", length=10)
	 */
	protected $role;
	
	/**
	 * @Column(type="string")
	 */
	protected $fullname;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $created;

	/**
	 * @Column(type="datetime")
	 */
	protected $modified;
	
	/**
	 * @OneToMany(targetEntity="Article", mappedBy="user")
	 */
	protected $articles = null;
	
	/**
	 * Startup
	 */
	public function __construct($data = array()) {
		$this->articles = new \Doctrine\Common\Collections\ArrayCollection;
		parent::__construct($data);
	}
	
	/**
	 * Create user
	 * @PrePersist
	 */
	public function created() {
		$this->created = new \DateTime('now');
	}
	
	/**
	 * Modify user
	 * @PrePersist
	 * @PreUpdate
	 */
	public function modified() {
		$this->modified = new \DateTime('now');
	}
	
	/**
	 * Password setter
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = \Authenticator::calculateHash($password);
	}
	
	/**
	 * Password getter
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Role setter
	 * @param string $role
	 */
	public function setRole($role) {
		if (!in_array($role, array(self::ROLE_GUEST, self::ROLE_MEMBER, self::ROLE_ADMIN))) {
			throw new \InvalidArgumentException("Invalid role");
		}
		$this->role = $role;
	}
	
	/**
	 * Role getter
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}
}