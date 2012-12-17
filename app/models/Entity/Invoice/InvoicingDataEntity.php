<?php

namespace Entity\Invoice;

use Entity\Company;
use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_invoicingdata")
 * @EA\Primary(key="id", value="clientName")
 */
class InvoicingData extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientPhone;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientEmail;

	/**
	 * @var url
	 * @ORM\Column(type="url", nullable=true)
	 */
	protected $clientUrl;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $clientPrimaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $clientLanguage;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyVatId;

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
	public function setClientName($clientName)
	{
		$this->clientName = $clientName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientName()
	{
		$this->clientName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientName()
	{
		return $this->clientName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientPhone($clientPhone)
	{
		$this->clientPhone = $clientPhone;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientPhone()
	{
		$this->clientPhone = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientPhone()
	{
		return $this->clientPhone;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientEmail($clientEmail)
	{
		$this->clientEmail = $clientEmail;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientEmail()
	{
		$this->clientEmail = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientEmail()
	{
		return $this->clientEmail;
	}
		
	/**
	 * @param \Extras\Types\Url
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientUrl(\Extras\Types\Url $clientUrl)
	{
		$this->clientUrl = $clientUrl;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientUrl()
	{
		$this->clientUrl = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Url|NULL
	 */
	public function getClientUrl()
	{
		return $this->clientUrl;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientPrimaryLocation(\Entity\Location\Location $clientPrimaryLocation)
	{
		$this->clientPrimaryLocation = $clientPrimaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientPrimaryLocation()
	{
		$this->clientPrimaryLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getClientPrimaryLocation()
	{
		return $this->clientPrimaryLocation;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientLanguage(\Entity\Language $clientLanguage)
	{
		$this->clientLanguage = $clientLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientLanguage()
	{
		$this->clientLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getClientLanguage()
	{
		return $this->clientLanguage;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientCompanyName($clientCompanyName)
	{
		$this->clientCompanyName = $clientCompanyName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientCompanyName()
	{
		$this->clientCompanyName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyName()
	{
		return $this->clientCompanyName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientCompanyId($clientCompanyId)
	{
		$this->clientCompanyId = $clientCompanyId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientCompanyId()
	{
		$this->clientCompanyId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyId()
	{
		return $this->clientCompanyId;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function setClientCompanyVatId($clientCompanyVatId)
	{
		$this->clientCompanyVatId = $clientCompanyVatId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData
	 */
	public function unsetClientCompanyVatId()
	{
		$this->clientCompanyVatId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyVatId()
	{
		return $this->clientCompanyVatId;
	}
}