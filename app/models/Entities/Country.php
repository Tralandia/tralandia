<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 */
class Country extends BaseEntity {

	/**
	 * @Column(type="string")
	 */
	protected $iso;

	/**
	 * @OneToMany(type="Language")
	 */
	protected $language;
}
