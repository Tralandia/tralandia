<?php

namespace Entity;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain", indexes={@ORM\index(name="domain", columns={"domain"})})
 *
 */
class Domain extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $expires;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $registratorDetails;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Location\Location", mappedBy="domain")
	 */
	protected $location;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entity\Domain
	 */
	public function setDomain($domain)
	{
		$this->domain = $domain;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getDomain()
	{
		return $this->domain;
	}

	/**
	 * @param \DateTime
	 * @return \Entity\Domain
	 */
	public function setExpires(\DateTime $expires)
	{
		$this->expires = $expires;

		return $this;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getExpires()
	{
		return $this->expires;
	}

	/**
	 * @param string
	 * @return \Entity\Domain
	 */
	public function setRegistratorDetails($registratorDetails)
	{
		$this->registratorDetails = $registratorDetails;

		return $this;
	}

	/**
	 * @return \Entity\Domain
	 */
	public function unsetRegistratorDetails()
	{
		$this->registratorDetails = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getRegistratorDetails()
	{
		return $this->registratorDetails;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Domain
	 */
	public function setLocation(\Entity\Location\Location $location)
	{
		$this->location = $location;
		$location->setDomain($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Location\Location[]
	 */
	public function getLocation()
	{
		return $this->location;
	}
}
