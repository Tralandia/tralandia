<?php

namespace Entities\Invoicing\Service;

use Entities\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_service")
 */
class Service extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Invoicing\Package", inversedBy="services")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Duration")
	 */
	protected $duration;

	/**
	 * @var price
	 * @ORM\ManyToMany(type="price")
	 */
	protected $defaultPrice;

	/**
	 * @var price
	 * @ORM\ManyToMany(type="price")
	 */
	protected $currentPrice;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param Invoicing\Package $package
	 * @return Service
	 */
	public function setPackage(Invoicing\Package  $package) {
		$this->package = $package;
		return $this;
	}


	/**
	 * @return Invoicing\Package
	 */
	public function getPackage() {
		return $this->package;
	}


	/**
	 * @param Type $type
	 * @return Service
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param Duration $duration
	 * @return Service
	 */
	public function setDuration(Duration  $duration) {
		$this->duration = $duration;
		return $this;
	}


	/**
	 * @return Duration
	 */
	public function getDuration() {
		return $this->duration;
	}


	/**
	 * @param price $defaultPrice
	 * @return Service
	 */
	public function setDefaultPrice($defaultPrice) {
		$this->defaultPrice = $defaultPrice;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getDefaultPrice() {
		return $this->defaultPrice;
	}


	/**
	 * @param price $currentPrice
	 * @return Service
	 */
	public function setCurrentPrice($currentPrice) {
		$this->currentPrice = $currentPrice;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getCurrentPrice() {
		return $this->currentPrice;
	}

}
