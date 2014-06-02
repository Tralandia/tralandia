<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\AddRental;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Tralandia\Invoicing\InvoiceManager;
use Tralandia\Rental\RentalRepository;
use Tralandia\User\User;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Service\Rental\RentalCreator;
use Tralandia\Invoicing\ServiceRepository;
use Tralandia\Language\Languages;
use Tralandia\Location\Countries;
use Tralandia\User\UserRepository;

class AddRentalForm extends \BaseModule\Components\BaseFormControl
{

	public $onFormSuccess = [];

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	private $rentalCreator;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @var \Tralandia\User\User
	 */
	private $user;

	/**
	 * @var \Tralandia\Invoicing\ServiceRepository
	 */
	private $serviceRepository;

	/**
	 * @var \Tralandia\Language\Languages
	 */
	private $languages;

	/**
	 * @var \Tralandia\User\UserRepository
	 */
	private $userRepository;

	/**
	 * @var \Tralandia\Invoicing\InvoiceManager
	 */
	private $invoiceManager;

	/**
	 * @var \Tralandia\Rental\RentalRepository
	 */
	private $rentalRepository;


	/**
	 * @var \Tralandia\Invoicing\Service[]
	 */
	protected $services;


	public function __construct(User $user, RentalCreator $rentalCreator, InvoiceManager $invoiceManager, Countries $countries,
								Languages $languages, ServiceRepository $serviceRepository,
								UserRepository $userRepository, RentalRepository $rentalRepository,
								ISimpleFormFactory $formFactory, EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->rentalCreator = $rentalCreator;
		$this->countries = $countries;
		$this->user = $user;
		$this->serviceRepository = $serviceRepository;
		$this->languages = $languages;
		$this->userRepository = $userRepository;
		$this->invoiceManager = $invoiceManager;
		$this->rentalRepository = $rentalRepository;
		$this->services = $this->serviceRepository->findForRegistration();
	}


	public function prepareTemplate()
	{
		parent::prepareTemplate();

		$this->template->services = $this->services;

	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->addText('name', $this->translate(152275))
			->setRequired(TRUE);

		$countries = $this->countries->getForSelect();
		$form->addSelect('country', 'o1094', $countries)
			->setDefaultValue($this->user->primaryLocation->id);


		$services = $this->services;
		$services = \Tools::entitiesMap($services, 'id', 'label', $this->translator);
		$form->addOptionList('service', '', $services)
			->setRequired(TRUE);


		$languages = $this->languages->getForSelectWithLinks();

		$form->addText('clientName', 'clientName');
		$form->addText('clientPhone', 'clientPhone');
		$form->addText('clientAddress', 'clientAddress');
		$form->addText('clientAddress2', 'clientAddress2');
		$form->addText('clientLocality', 'clientLocality');
		$form->addText('clientPostcode', 'clientPostcode');
		$form->addSelect('clientPrimaryLocation', 'clientPrimaryLocation', $countries);
		$form->addSelect('clientLanguage', 'clientLanguage', $languages);
		$form->addText('clientCompanyName', 'clientCompanyName');
		$form->addText('clientCompanyId', 'clientCompanyId');
		$form->addText('clientCompanyVatId', 'clientCompanyVatId');

		$form->addSubmit('submit', '450090');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		$this->setDefaults($form);

		return $form;
	}


	public function setDefaults(BaseForm $form)
	{

		$defaults = [
			'name' => Nette\Utils\Strings::random(6, 'a-z'),
			'clientName' => 'Client Name',
			'clientPhone' => '+421 909 090 909',
			'clientAddress' => 'Address',
			'clientAddress2' => 'Address 2',
			'clientLocality' => 'Locality',
			'clientPostcode' => '12 345',
			'clientPrimaryLocation' => 52,
			'clientLanguage' => 144,
			'clientCompanyName' => 'Company Name',
			'clientCompanyId' => '123456789',
			'clientCompanyVatId' => 'VAT123456789',
		];

		$defaultInfo = $this->user->getDefaultInvoicingInformation();
		$defaults = array_merge($defaultInfo, $defaults);

		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

	}


	public function processForm(BaseForm $form)
	{
		$values = $form->getFormattedValues(TRUE);

		$primaryLocation = $this->countries->find($values['country']);

		$this->user->setDefaultInvoicingInformation($values);
		$this->userRepository->save($this->user);

		$doctrineUser = $this->em->getRepository(USER_ENTITY)->find($this->user->id);
		$doctrineRental = $this->rentalCreator->simpleCreate($doctrineUser, $primaryLocation, $values['name']);

		$this->em->persist($doctrineRental);
		$this->em->flush();

		$rental = $this->rentalRepository->find($doctrineRental->getId());
		$service = $this->serviceRepository->find($values['service']);

		$invoice = $this->invoiceManager->createInvoice($rental, $service, $this->user->login, $this->translator);
		$this->invoiceManager->save($invoice);

		$this->onFormSuccess($form, $doctrineRental);
	}


}


interface IAddRentalFormFactory
{
	public function create(\Tralandia\User\User $user);
}
