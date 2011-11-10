<?php

/**
 * @Entity(repositoryClass="RentalRepository")
 * @HasLifecycleCallbacks
 */
class Rental extends BaseEntity {
	
	const STATUS_NONE = NULL;
	const STATUS_CHECKED = 'checked';
	const STATUS_LIVE = 'live';

	/**
	 * @Column(type="string", nullable=true)
	 * @UIControl(type="select", options="STATUS_NONE:Nič, STATUS_CHECKED:Checked, STATUS_LIVE:Live")
	 */
	protected $status = self::STATUS_NONE;

	/**
	 * @Column(type="string")
	 * @UIControl(type="text", label="Name url")
	 */
	protected $nameUrl;

	/**
	 * @ManyToOne(targetEntity="User")
	 * @UIControl(type="select", callback="getList", value="login")
	 */
	protected $user;
	
	/**
	 * @ManyToOne(targetEntity="Country")
	 * @UIControl(type="select", callback="getList", value="iso")
	 */
	protected $country;
}
