<?php

namespace Tra;

/**
 * @Entity(repositoryClass="BaseRepository")
 * @HasLifecycleCallbacks
 */
class Country extends \BaseEntity {

	/**
	 * @Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $iso;

	/**
	 * @ManyToOne(targetEntity="\Language")
	 * @UIControl(type="select", options="%service%, getList")
	 */
	protected $language;
}
