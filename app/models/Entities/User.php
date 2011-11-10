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
}
