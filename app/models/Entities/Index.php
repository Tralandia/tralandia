<?php

/**
 * @Entity(repositoryClass="IndexRepository")
 * @Table(name="indexes")
 * @HasLifecycleCallbacks
 */
class Index extends BaseEntity {
	
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="float", nullable=true)
	 */
	protected $actual;
	
	/**
	 * @Column(type="float", nullable=true)
	 */
	protected $forecast;
	
	/**
	 * @Column(type="float", nullable=true)
	 */
	protected $previous;
	
	/**
	 * @Column(type="smallint", length=1)
	 */
	protected $impact;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $source;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $measures;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $usualEffect;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $frequency;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	protected $nextRelease;
	
	/**
	 * @Column(type="text", nullable=true)
	 */
	protected $ffNotes;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $whyTradersCare;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $alsoCalled;
	
	/**
	 * @Column(type="string", nullable=true)
	 */
	protected $acroExpand;
	
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
	 * @ManyToOne(targetEntity="Event", inversedBy="indexes")
	 */
	protected $event = null;
	
	/**
	 * @ManyToOne(targetEntity="Currency", inversedBy="indexes")
	 */
	protected $currency = null;
	
	/**
	 * Event setter
	 * @param Event $incident
	 */
	public function setEvent(\Event $event = null) {
		$this->event = $event;
	}
	
	/**
	 * Event getter
	 * @return Event
	 */
	public function getEvent() {
		return $this->event;
	}
	
	/**
	 * Currency setter
	 * @param Currency $currency
	 */
	public function setCurrency(\Currency $currency = null) {
		$this->currency = $currency;
	}
	
	/**
	 * Currency getter
	 * @return Currency
	 */
	public function getCurrency() {
		return $this->currency;
	}
	
	/**
	 * Create index
	 * @PrePersist
	 */
	public function created() {
		$this->created = new \DateTime('now');
	}
	
	/**
	 * Modify index
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
		$this->published = new \DateTime("now");
	}
}