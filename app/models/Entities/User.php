<?php

/**
 * @Entity(repositoryClass="BaseRepository")
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
	 * @UIControl(type="select", label="Is active?", options="Yes, No")
	 */
	protected $active;

	/**
	 * @ManyToOne(targetEntity="Country")
	 * @UIControl(type="select", callback="getList")
	 */
	protected $country;
}
