<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 */
class Rental extends BaseEntity {
	
	const STATUS_NONE = NULL;
	const STATUS_CHECKED = 'checked';
	const STATUS_LIVE = 'live';

	/**
	 * @Column(type="string", nullable=true)
	 * @UIControl(type="select", options="STATUS_*")
	 */
	protected $status = self::STATUS_NONE;

	/**
	 * @Column(type="string")
	 * @UIControl(type="url", label="Name url")
	 */
	protected $nameUrl;

	/**
	 * @OneToMany(type="User")
	 * @UIControl(type="select", options="%service%, getList")
	 */
	protected $user;
	
	/**
	 * @OneToMany(type="Country")
	 * @UIControl(type="select", options="%service%, getList")
	 */
	protected $country;
}
