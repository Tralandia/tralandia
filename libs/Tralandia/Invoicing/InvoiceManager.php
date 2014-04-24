<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/04/14 10:40
 */

namespace Tralandia\Invoicing;


use Nette;

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

	public function create()
	{
		$invoice = $this->invoiceDao->createNew();


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
