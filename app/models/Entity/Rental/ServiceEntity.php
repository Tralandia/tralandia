<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use    Extras\Annotation as EA;
use Extras\FileStorage;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_service")
 * @EA\Primary(key="id", value="name")
 */
class Service extends \Entity\BaseEntity
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="services")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $givenFor;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $serviceType;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $dateFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $dateTo;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Service
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Service
	 */
	public function unsetRental()
	{
		$this->rental = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
	}

	/**
	 * @param string
	 * @return \Entity\Rental\Service
	 */
	public function setGivenFor($givenFor)
	{
		$this->givenFor = $givenFor;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getGivenFor()
	{
		return $this->givenFor;
	}

	/**
	 * @param string
	 * @return \Entity\Rental\Service
	 */
	public function setServiceType($serviceType)
	{
		$this->serviceType = $serviceType;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getServiceType()
	{
		return $this->serviceType;
	}

	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Service
	 */
	public function setDateFrom(\DateTime $dateFrom)
	{
		$this->dateFrom = $dateFrom;

		return $this;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getDateFrom()
	{
		return $this->dateFrom;
	}

	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Service
	 */
	public function setDateTo(\DateTime $dateTo)
	{
		$this->dateTo = $dateTo;

		return $this;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getDateTo()
	{
		return $this->dateTo;
	}
}
