<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 */
class User extends BaseEntity {

	/**
	 * @Column(type="string")
	 */
	protected $login;

	/**
	 * @Column(type="string")
	 */
	protected $password;

	/**
	 * @Column(type="boolean")
	 */
	protected $active;

	/**
	 * @OneToMany(type="Country")
	 */
	protected $country;
}
