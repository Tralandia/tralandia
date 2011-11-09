<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 */
class Language extends BaseEntity {

	/**
	 * @Column(type="string")
	 */
	protected $iso;

	/**
	 * @Column(type="boolean")
	 */
	protected $active;
}
