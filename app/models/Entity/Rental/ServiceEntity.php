<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_service")
 */
class Service extends \Entity\BaseEntity
{

	const GIVEN_FOR_SHARE = 'Share';
	const GIVEN_FOR_BACKLINK = 'Backlink';
	const GIVEN_FOR_PAID_INVOICE = 'Paid Invoice';
	const GIVEN_FOR_MEMBERSHIP = 'Membership';


	const TYPE_FEATURED = 'featured';
	const TYPE_PERSONAL_SITE = 'personalSite';
	const TYPE_PREMIUM_PS = 'premium-ps';

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
