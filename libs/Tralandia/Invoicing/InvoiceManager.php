<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/04/14 10:40
 */

namespace Tralandia\Invoicing;


use Tralandia\Rental\Rental;
use Nette;
use Tralandia\Localization\Translator;

class InvoiceManager
{


	/**
	 * @var CompanyRepository
	 */
	private $companyRepository;

	/**
	 * @var InvoiceRepository
	 */
	private $invoiceRepository;


	public function __construct(InvoiceRepository $invoiceRepository, CompanyRepository $companyRepository)
	{
		$this->companyRepository = $companyRepository;
		$this->invoiceRepository = $invoiceRepository;
	}

	public function createInvoice(Rental $rental, Service $service, $createdBy, Translator $translator)
	{
		$company = $this->pickCompany($service);
		$info = $rental->user->getDefaultInvoicingInformation();

		$due = Nette\DateTime::from(strtotime('today'))->modify('+14 days');

		$invoice = new Invoice();

		$invoice->number = Nette\Utils\Strings::random(4, '0-9');
		$invoice->variableNumber = Nette\Utils\Strings::random(4, '0-9');
		$invoice->company = $company;
		$invoice->rental = $rental;
		$invoice->dateDue = $due;
//		$invoice->datePaid = NULL;

		$invoice->clientName = $info['clientName'];
		$invoice->clientPhone = $info['clientPhone'];
		$invoice->clientAddress = $info['clientAddress'];
		$invoice->clientAddress2 = $info['clientAddress2'];
		$invoice->clientLocality = $info['clientLocality'];
		$invoice->clientPostcode = $info['clientPostcode'];
		$invoice->clientCompanyName = $info['clientCompanyName'];
		$invoice->clientCompanyId = $info['clientCompanyId'];
		$invoice->clientCompanyVatId = $info['clientCompanyVatId'];

		$invoice->createdBy = $createdBy;
		$invoice->vat = $company->vat;

		$duration = $service->duration;
		$currency = $rental->currency;
		$price = $service->getPriceCurrent();
		$invoice->durationStrtotime = $duration->strtotime;
		$invoice->durationName = $translator->translate($duration->getNameId());
		$invoice->durationNameEn = $duration->name->getCentralTranslationText();
		$invoice->price = $price->convertToFloat($rental->getSomeCurrency()); // ?? key treba konvertovat menu ??
		$invoice->priceEur = $price->convertToEurFloat();
		$invoice->currency = $currency;
		$invoice->serviceName = $translator->translate($service->type->getNameId());
		$invoice->serviceNameEn = $service->type->name->getCentralTranslationText();
		$invoice->serviceType = $service->type;


		return $invoice;
	}


	public function save($invoice)
	{
		$this->invoiceRepository->save($invoice);
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
