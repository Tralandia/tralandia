<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\AddRental;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
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


	public function __construct(User $user, RentalCreator $rentalCreator, Countries $countries,
								Languages $languages, ServiceRepository $serviceRepository,
								UserRepository $userRepository,
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
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->addText('name', $this->translate(152275))
			->setRequired(TRUE);

		$countries = $this->countries->getForSelect();
		$form->addSelect('country', 'o1094', $countries)
			->setDefaultValue($this->user->primaryLocation->id);

		$services = $this->serviceRepository->findAll();
		$services = \Tools::entitiesMap($services, 'id', 'label', $this->translator);
		$form->addOptionList('service', '', $services);


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
//			'video' => $videoUrl,
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

		$primaryLocation = $this->countries->find($values->country);

		$this->user->setDefaultInvoicingInformation($values);
		$this->userRepository->save($this->user);

		$doctrineUser = $this->em->getRepository(USER_ENTITY)->find($this->user->id);
		$rental = $this->rentalCreator->simpleCreate($doctrineUser, $primaryLocation, $values->name);

		$this->em->persist($rental);
		$this->em->flush();

		$this->onFormSuccess($form, $rental);
	}


}


interface IAddRentalFormFactory
{
	public function create(\Tralandia\User\User $user);
}
