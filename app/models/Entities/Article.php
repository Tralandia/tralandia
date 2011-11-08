<?php

/**
 * @Entity(repositoryClass="ArticleRepository")
 * @Table(name="article")
 * @HasLifecycleCallbacks
 */
class Article extends BaseEntity {
	
	const STATUS_DRAFT = 'draft';
	const STATUS_PUBLISHED = 'published';
		
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="string")
	 */
	protected $title;
	
	/**
	 * @Column(type="text")
	 */
	protected $content;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $image;
	
	/**
	 * @Column(type="string", length=10)
	 */
	protected $status = self::STATUS_DRAFT;
	
	/**
	 * @Column(type="integer")
	 */
	protected $views = 0;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $created;

	/**
	 * @Column(type="datetime")
	 */
	protected $modified;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	protected $published;
	
	/**
	 * @ManyToOne(targetEntity="User", inversedBy="articles")
	 */
	protected $user = null;

	/**
	 * @ManyToOne(targetEntity="Category", inversedBy="articles")
	 */
	protected $category = null;
	
	/**
	 * Status setter
	 * @param string $role
	 */
	public function setStatus($status) {
		if (!in_array($status, array(self::STATUS_DRAFT, self::STATUS_PUBLISHED))) {
			throw new \InvalidArgumentException("Invalid status");
		}
		$this->status = $status;
	}
	
	/**
	 * Status getter
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Category setter
	 * @param Category $category
	 */
	public function setCategory(\Category $category = null) {
		$this->category = $category;
	}
	
	/**
	 * Category getter
	 * @return Category
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * User setter
	 * @param User $user
	 */
	public function setUser(\User $user = null) {
		$this->user = $user;
	}
	
	/**
	 * User getter
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * Create article
	 * @PrePersist
	 */
	public function created() {
		$this->created = new \DateTime('now');
	}
	
	/**
	 * Modify article
	 * @PrePersist
	 * @PreUpdate
	 */
	public function modified() {
		$this->modified = new \DateTime('now');
	}
	
	/**
	 * Publish article
	 */
	public function publish() {
		$this->status = self::STATUS_PUBLISHED;
		$this->published = new \DateTime("now");
	}
	
	/**
	 * Draft article
	 */
	public function draft() {
		$this->status = self::STATUS_DRAFT;
	}
	
	/**
	 * Checked
	 * @return string
	 */
	public function isPublished() {
		return $this->status == self::STATUS_PUBLISHED;
	}
}