<?php

/**
 * @Entity(repositoryClass="BaseRepository")
 * @Table(name="category")
 */
class Category extends BaseEntity {
	
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @OneToMany(targetEntity="Article", mappedBy="category")
	 */
	protected $articles;
	
	/**
	 * @Column(type="string")
	 */
	protected $name;
	
	/**
	 * Startup
	 */	
	public function __construct($data = array()) {
		$this->articles = new \Doctrine\Common\Collections\ArrayCollection;
		parent::__construct($data);
	}
	
	/**
	 * Articles getter
	 * @return Category
	 */
	public function getArticles() {
		return $this->articles;
	}
}