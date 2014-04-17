<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_invoice")
 */
class Invoice extends \Entity\BaseEntity {

	const GIVEN_FOR_SHARE = 'Share';
	const GIVEN_FOR_BACKLINK = 'Backlink';
	const GIVEN_FOR_PAID_INVOICE = 'Paid Invoice';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('Share', 'Backlink', 'Paid Invoice')")
	 */
	protected $givenFor;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $number;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $variableNumber;

	/**
	 * @var string
	 * @ORM\ManyToOne(targetEntity="Entity\Invoicing\Company")
	 */
	protected $company;

	/**
	 * @var \Entity\Rental\Rental
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $rental;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $timeDue;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timePaid;

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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientUrl;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientAddress;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientAddress2;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientLocality;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientPostcode;

	/**
	 * @var \Entity\Location\Location
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $clientPrimaryLocation;

	/**
	 * @var \Entity\Language
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

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $createdBy;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $vat;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $notes;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $paymentInfo;

	/**
	 * @var \DateTime|null
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timeFrom;

	/**
	 * @var \DateTime|null
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timeTo;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationStrtotime;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationNameEn;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $price;

	/**
	 * @var \Entity\Currency
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $priceEur;

	/**
	 * @var string
	 * @ORM\ManyToOne(targetEntity="Entity\Invoicing\ServiceType")
	 */
	protected $serviceType;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $serviceName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $serviceNameEn;


	/**
	 * @return string
	 */
	public function getGivenFor()
	{
		return $this->givenFor;
	}


	/**
	 * @param string $givenFor
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setGivenFor($givenFor)
	{
		if (!in_array($givenFor, array(
			self::GIVEN_FOR_BACKLINK,
			self::GIVEN_FOR_PAID_INVOICE,
			self::GIVEN_FOR_SHARE))) {
			throw new \InvalidArgumentException("Invalid givenFor value");
		}

		$this->givenFor = $givenFor;
	}
}
