<?php

/**
 * @Entity(repositoryClass="SentimentRepository")
 * @Table(name="sentiment")
 * @HasLifecycleCallbacks
 */
class Sentiment extends BaseEntity {
	
	const TYPE_EURUSD = 'eurusd';
	const TYPE_GOLD = 'gold';
	const TYPE_SAP500 = 'sap500';
	
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	/**
	 * @Column(type="boolean")
	 */
	protected $vote;
	
	/**
	 * @Column(type="string")
	 */
	protected $type = self::TYPE_EURUSD;
	
	/**
	 * @Column(type="string")
	 */
	protected $ip;
	
	/**
	 * @Column(type="datetime")
	 */
	protected $created;
	
	/**
	 * Create article
	 * @PrePersist
	 */
	public function created() {
		$this->created = new \DateTime('now');
		$this->ip = \Tools::getRemoteAddress();
	}
}