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
	 */
	protected $status;

	/**
	 * @Column(type="string")
	 */
	protected $nameUrl;

	/**
	 * @OneToMany(type="User")
	 */
	protected $active;

	/**
	 * @OneToMany(type="Country")
	 */
	protected $country;
}
