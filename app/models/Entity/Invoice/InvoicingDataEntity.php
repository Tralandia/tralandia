<?php

namespace Entity\Invoice;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Invoice\InvoicingDataRepository")
 * @ORM\Table(name="invoice_invoicingdata")
 * @EA\Primary(key="id", value="name")
 */
class InvoicingData extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phone;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $email;

	/**
	 * @var url
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $url;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyVatId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $address;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetName()
	{
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetPhone()
	{
		$this->phone = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPhone()
	{
		return $this->phone;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetEmail()
	{
		$this->email = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEmail()
	{
		return $this->email;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetUrl()
	{
		$this->url = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getUrl()
	{
		return $this->url;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetLanguage()
	{
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getLanguage()
	{
		return $this->language;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setCompanyName($companyName)
	{
		$this->companyName = $companyName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetCompanyName()
	{
		$this->companyName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCompanyName()
	{
		return $this->companyName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setCompanyId($companyId)
	{
		$this->companyId = $companyId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetCompanyId()
	{
		$this->companyId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCompanyId()
	{
		return $this->companyId;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setCompanyVatId($companyVatId)
	{
		$this->companyVatId = $companyVatId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetCompanyVatId()
	{
		$this->companyVatId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCompanyVatId()
	{
		return $this->companyVatId;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setAddress($address)
	{
		$this->address = $address;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetAddress()
	{
		$this->address = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getAddress()
	{
		return $this->address;
	}
}