<?php

namespace Entity\Invoicing;

use Entity\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_service")
 */
class Service extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Duration")
	 */
	protected $duration;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $defaultPrice;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $currentPrice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoicing\Package", inversedBy="services")
	 */
	protected $package;
	




















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Invoicing\Service\Type
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function setType(\Entity\Invoicing\Service\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param \Entity\Invoicing\Service\Duration
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function setDuration(\Entity\Invoicing\Service\Duration $duration) {
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function unsetDuration() {
		$this->duration = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Duration|NULL
	 */
	public function getDuration() {
		return $this->duration;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function setDefaultPrice(\Extras\Types\Price $defaultPrice) {
		$this->defaultPrice = $defaultPrice;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getDefaultPrice() {
		return $this->defaultPrice;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function setCurrentPrice(\Extras\Types\Price $currentPrice) {
		$this->currentPrice = $currentPrice;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getCurrentPrice() {
		return $this->currentPrice;
	}
		
	/**
	 * @param \Entity\Invoicing\Package
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function setPackage(\Entity\Invoicing\Package $package) {
		$this->package = $package;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Service
	 */
	public function unsetPackage() {
		$this->package = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Package|NULL
	 */
	public function getPackage() {
		return $this->package;
	}
}