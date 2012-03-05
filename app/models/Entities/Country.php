<?php

/**
 * @Entity(repositoryClass="CountryRepository")
 * @HasLifecycleCallbacks
 * @Primary(key="id", value="iso")
 */
class Country extends BaseEntity {

	/**
	 * @Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $iso;

	/**
	 * @ManyToOne(targetEntity="\Dictionary\Language")
	 * @UIControl(type="select", options="%service%, getList")
	 */
	protected $language;
}
