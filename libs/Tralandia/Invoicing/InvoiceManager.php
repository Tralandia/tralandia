<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/04/14 10:40
 */

namespace Tralandia\Invoicing;


use Entity\Invoicing\Company;
use Entity\Invoicing\Service;
use Entity\Rental\Rental;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tralandia\BaseDao;
use Tralandia\Localization\Translator;

class InvoiceManager
{

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var BaseDao
	 */
	protected $invoiceDao;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->invoiceDao = $em->getRepository(INVOICING_INVOICE);
	}

	public function createInvoice(Rental $rental, Service $service, Company $company, $createdBy, Translator $translator)
	{
		$due = Nette\DateTime::from(strtotime('today'))->modify('+14 days');
		$user = $rental->getOwner();
		$address = $rental->getAddress();

		/** @var $invoice \Entity\Invoicing\Invoice */
		$invoice = $this->invoiceDao->createNew();

		//$invoice->setNumber();
		//$invoice->setVariableNumber();
		$invoice->setCompany($company);
		$invoice->setRental($rental);
		$invoice->setDateDue($due);
		$invoice->setClientName($rental->getContactName());
		$rental->getPhone() && $invoice->setClientPhone($rental->getPhone()->getInternational());
		$invoice->setClientEmail($rental->getContactEmail());
//		$invoice->setClientAddress();
//		$invoice->setClientAddress2();
		$invoice->setClientLocality($address->getLocality()->getName()->getSourceTranslationText());
//		$invoice->setClientCompanyName();
//		$invoice->setClientCompanyId();
//		$invoice->setClientCompanyVatId();
		$invoice->setCreatedBy($createdBy);
		$invoice->setVat($company->getVat());

		$duration = $service->getDuration();
		$currency = $rental->getCurrency();
		$price = $service->getPriceCurrent();
		$invoice->setDurationStrtotime($duration->getStrtotime());
		$invoice->setDurationName($translator->translate($duration->getName()));
		$invoice->setDurationNameEn($duration->getName()->getCentralTranslationText());
		$invoice->setPrice($service->getPriceCurrent()); // ?? key treba konvertovat menu ??
		$invoice->setCurrency($currency);


		return $invoice;
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param $serviceType
	 * @param $givenFor
	 *
	 * @throws \InvalidArgumentException
	 * @return \Entity\Rental\Service
	 */
	public function prolongService(\Entity\Rental\Rental $rental, $service, $givenFor)
	{
		if($givenFor == Service::GIVEN_FOR_SHARE) {
			$prolongBy = '+6 months';
		} else if($givenFor == Service::GIVEN_FOR_BACKLINK) {
			$prolongBy = '+12 months';
		} else if($givenFor == Service::GIVEN_FOR_PAID_INVOICE && $serviceType == Service::TYPE_PERSONAL_SITE) {
			$prolongBy = '+12 months';
		} else {
			throw new \InvalidArgumentException();
		}

		$lastService = $rental->getLastService($serviceType);

		if($lastService) $dateFrom = $lastService->getDateTo();
		else $dateFrom = new \DateTime();

		$dateFrom->modify('midnight');
		$dateTo = clone $dateFrom;
		$dateTo->modify($prolongBy);

		/** @var $newService Service */
		$newService = $this->serviceDao->createNew();
		$newService->setGivenFor($givenFor)
			->setServiceType($serviceType)
			->setDateFrom($dateFrom)
			->setDateTo($dateTo);

		$rental->addService($newService);
		$this->rentalDao->save($rental, $newService);

		return $newService;
	}


}
