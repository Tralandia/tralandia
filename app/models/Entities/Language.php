<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 */
class Language extends BaseEntity {

	/**
	 * @Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $iso;

	/**
	 * @Column(type="boolean")
	 * @UIControl(type="select", label="Is active?", options="Yes, No")
	 */
	protected $active;
}
