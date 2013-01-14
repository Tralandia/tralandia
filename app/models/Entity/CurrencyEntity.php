<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\CurrencyRepository")
 * @ORM\Table(name="currency", indexes={@ORM\index(name="name", columns={"name_id"}), @ORM\index(name="iso", columns={"iso"})})
 * @EA\Primary(key="id", value="iso")
 */
class Currency extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"}, fetch="EAGER")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $iso;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $exchangeRate;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $rounding;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $searchInterval = 10;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Currency
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Currency
	 */
	public function setIso($iso)
	{
		$this->iso = $iso;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso()
	{
		return $this->iso;
	}
		
	/**
	 * @param float
	 * @return \Entity\Currency
	 */
	public function setExchangeRate($exchangeRate)
	{
		$this->exchangeRate = $exchangeRate;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency
	 */
	public function unsetExchangeRate()
	{
		$this->exchangeRate = NULL;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getExchangeRate()
	{
		return $this->exchangeRate;
	}
		
	/**
	 * @param string
	 * @return \Entity\Currency
	 */
	public function setRounding($rounding)
	{
		$this->rounding = $rounding;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency
	 */
	public function unsetRounding()
	{
		$this->rounding = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRounding()
	{
		return $this->rounding;
	}
		
	/**
	 * @param float
	 * @return \Entity\Currency
	 */
	public function setSearchInterval($searchInterval)
	{
		$this->searchInterval = $searchInterval;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getSearchInterval()
	{
		return $this->searchInterval;
	}
}