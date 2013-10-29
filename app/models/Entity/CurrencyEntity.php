<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency", indexes={@ORM\Index(name="name", columns={"name_id"}), @ORM\Index(name="iso", columns={"iso"})})
 *
 *
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


	/**
	 * @param float
	 * @return \Entity\Currency
	 */
	public function setExchangeRate($exchangeRate)
	{
		$this->exchangeRate = $exchangeRate;

		if ($exchangeRate <= 10) {
			$searchInterval = 10;
		} else if ($exchangeRate <= 100) {
			$searchInterval = 100;
		} else {
			$searchInterval = 1000;
		}

		$this->setSearchInterval($searchInterval);

		return $this;
	}

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
