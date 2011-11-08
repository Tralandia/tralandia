<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @Table(name="currency")
 */
class Currency extends BaseEntity {
	
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string", length=3)
	 */
	protected $code;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $name;
	
	/**
	 * @OneToMany(targetEntity="Index", mappedBy="currency", cascade={"all"})
	 */
	public $indexes = null;
}