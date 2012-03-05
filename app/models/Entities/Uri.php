<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 * @Primary(key="id", value="name")
 */
class Uri extends BaseEntity {
	
	const TYPE_PAGE = 2;
	const TYPE_ATTRACTION_TYPE = 4;
	const TYPE_LOCATION = 6;
	const TYPE_RENTAL_TYPE = 8;
	const TYPE_TAG = 10;

	/**
	 * @Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $name;

	/**
	 * @Column(type="string")
	 * @UIControl(type="select", options="TYPE_PAGE:page, TYPE_LOCATION:location")
	 */
	protected $type;

	/**
	 * @Column(type="string")
	 * @UIControl(type="intiger")
	 */
	protected $row;

	/**
	 * @ManyToOne(targetEntity="\Dictionary\Language")
	 * @UIControl(type="select", options="%service%, getList")
	 */
	protected $language;
	
	/**
	 * @ManyToOne(targetEntity="Country", fetch="EAGER")
	 * @UIControl(type="select", callback="getList")
	 */
	protected $country;
}
