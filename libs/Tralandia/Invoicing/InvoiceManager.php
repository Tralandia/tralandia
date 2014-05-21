<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/04/14 10:40
 */

namespace Tralandia\Invoicing;


use Tralandia\Invoicing\Company;
use Tralandia\Invoicing\Service;
use Tralandia\Rental\Rental;
use Nette;
use Tralandia\BaseDao;
use Tralandia\Localization\Translator;

class InvoiceManager
{


	/**
	 * @var CompanyRepository
	 */
	private $companyRepository;


	public function __construct(CompanyRepository $companyRepository)
	{
		$this->companyRepository = $companyRepository;
	}

	public function createInvoice(Rental $rental, Service $service, $createdBy, Translator $translator)
	{
		$company = $this->pickCompany($service);

		$due = Nette\DateTime::from(strtotime('today'))->modify('+14 days');
//		$address = $rental->address;

		$invoice = new Invoice();

		//$invoice->setNumber();
		//$invoice->setVariableNumber();
		$invoice->company = $company;
		$invoice->rental = $rental;
		$invoice->dateDue = $due;
		$invoice->clientName = $rental->contactName;
		$rental->phone && $invoice->clientPhone = $rental->phone->international;
		$invoice->clientEmail = $rental->getContactEmail();
//		$invoice->setClientAddress();
//		$invoice->setClientAddress2();
//		$invoice->clientLocality = $translator->translate($address->locality->getNameId());
//		$invoice->setClientCompanyName();
//		$invoice->setClientCompanyId();
//		$invoice->setClientCompanyVatId();
		$invoice->createdBy = $createdBy;
		$invoice->vat = $company->vat;

		$duration = $service->duration;
		$currency = $rental->currency;
		$price = $service->priceCurrent;
		$invoice->durationStrtotime = $duration->strtotime;
		$invoice->durationName = $translator->translate($duration->getNameId());
		$invoice->durationNameEn = $duration->name->getCentralTranslationText();
		$invoice->price = $price; // ?? key treba konvertovat menu ??
		$invoice->currency = $currency;


		return $invoice;
	}


	protected function pickCompany(Service $service)
	{
		if($service->isForFree()) {
			return $this->companyRepository->findOneBy(['slug' => Company::SLUG_ZERO]);
		} else {
			return $this->companyRepository->findOneBy(['slug' => Company::SLUG_TRALANDIA_SRO]);
		}
	}


	/**
	 * @param \Tralandia\Rental\Rental $rental
	 * @param $serviceType
	 * @param $givenFor
	 *
	 * @throws \InvalidArgumentException
	 * @return \Tralandia\Rental\Service
	 */
	public function prolongService(\Tralandia\Rental\Rental $rental, $service, $givenFor)
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
