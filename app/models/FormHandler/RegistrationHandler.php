<?php

namespace FormHandler;

use Service\Rental\RentalCreator;
use Service\Invoice\IInvoiceCreatorFactory;
use Doctrine\ORM\EntityManager;

class RegistrationHandler extends FormHandler
{

	public $onInvoiceExists = array();
	public $onInvoiceNotExists = array();

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;

	/**
	 * @var \Service\Invoice\IInvoiceCreatorFactory
	 */
	protected $invoiceCreatorFactory;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @param \Service\Rental\RentalCreator $rentalCreator
	 * @param \Service\Invoice\IInvoiceCreatorFactory $invoiceCreatorFactory
	 */
	public function __construct(RentalCreator $rentalCreator, IInvoiceCreatorFactory $invoiceCreatorFactory,
								EntityManager $em)
	{
		$this->rentalCreator = $rentalCreator;
		$this->invoiceCreatorFactory = $invoiceCreatorFactory;
		$this->em = $em;
	}

	public function handleSuccess($values)
	{
		$userRepository = $this->em->getRepository('\Entity\User\User');
		$locationRepository = $this->em->getRepository('\Entity\Location\Location');
		$languageRepository = $this->em->getRepository('\Entity\Language');
		$rentalTypeRepository = $this->em->getRepository('\Entity\Rental\Type');
		$referralRepository = $this->em->getRepository('\Entity\Rental\Referral');
		$emailRepository = $this->em->getRepository('\Entity\Contact\Email');
		$packageRepository = $this->em->getRepository('\Entity\Invoice\Package');

		$error = new ValidationError;

		$values->country = $locationRepository->find($values->country);
		if(!$values->country || !$values->country->isPrimary()) {
			$error->addError("Invalid country", 'country');
		}

		$values->clientCountry = $locationRepository->find($values->clientCountry);
		if(!$values->clientCountry || !$values->clientCountry->isPrimary()) {
			$error->addError("Invalid clientCountry", 'clientCountry');
		}

		$values->language = $languageRepository->find($values->language);
		if(!$values->language) {
			$error->addError("Invalid language", 'language');
		}

		// User
		$user = $userRepository->findByLogin($values->email);
		if($user) {
			$error->addError("Email exists", 'email');
		}

		$values->rentalType = $rentalTypeRepository->find($values->rentalType);
		if(!$values->rentalType) {
			$error->addError("Invalid rental type", 'rentalType');
		}

		$values->package = $packageRepository->find($values->package);
		if(!$values->package) {
			$error->addError("Invalid package", 'package');
		}

		$clientInvoicingData = $this->prepareInvoicingData($values);

		$error->assertValid();

		/** @var $user \Entity\User\User */
		$user = $userRepository->createNew();
		$user->setLogin($values->email);
		$user->setPassword($values->password);
		$user->setLanguage($values->language);

		/** @var $referral \Entity\Rental\Referral */
		$referral = $referralRepository->createNew();

		/** @var $email \Entity\Contact\Email */
		$email = $emailRepository->createNew();

		/** @var $rental \Entity\Rental\Rental */
		$rentalCreator = $this->rentalCreator;
		$rental = $rentalCreator->create($values->country, $user, $values->rentalName);
		$rentalCreator->setPrice($rental, $values->rentalPrice);

		$rental->setType($values->rentalType)
			->setEditLanguage($values->language)
			->addSpokenLanguage($values->language)
			->addEmail($email)
			->setClassification($values->rentalClassification)
			->setMaxCapacity($values->rentalMaxCapacity)
			->addReferral($referral);

		$invoiceCreator = $this->invoiceCreatorFactory->create($rental, $clientInvoicingData, $values->package);
		$invoice = $invoiceCreator->createInvoice($values->country->getDefaultCurrency());

		//$this->model->save($values);
		return $rental;
	}

	/**
	 * @param $values
	 *
	 * @return \Entity\Invoice\InvoicingData
	 */
	protected function prepareInvoicingData($values)
	{

		/** @var $invoicingData \Entity\Invoice\InvoicingData */
		$invoicingData = $this->em->getRepository('\Entity\Invoice\InvoicingData')->createNew();

		$invoicingData->setName($values->clientName);
		$invoicingData->setPhone($values->phone);
		$invoicingData->setEmail($values->email)
			->setUrl($values->url)
			->setPrimaryLocation($values->clientCountry)
			->setLanguage($values->language)
			->setCompanyName($values->clientCompanyName)
			->setCompanyId($values->clientCompanyId)
			->setCompanyVatId($values->clientCompanyVatId1 . $values->clientCompanyVatId2)
			->setAddress($values->clientLocality . ' ' . $values->clientPostalCode . ' ' . $values->clientAddress1 .
			' ' . $values->clientAddress2);

		return $invoicingData;
	}


//	public function handleSuccess($values)
//	{
//		$error = new ValidationError;
//
//		if ($values->name != "Valid") {
//			$error->addError("Name is invalid", 'name');
//		}
//
//		$error->assertValid();
//		$this->model->save($values);
//	}
}