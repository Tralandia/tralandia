<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @Table(name="event")
 */
class Event extends BaseEntity {
	
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string")
	 */
	protected $name;
	
	/**
	 * @Column(type="string")
	 */
	protected $code;
	
	/**
	 * @Column(type="string", length=3)
	 */
	protected $unit;
	
	/**
	 * @OneToMany(targetEntity="Index", mappedBy="event", cascade={"all"})
	 */
	protected $indexes = null;
}