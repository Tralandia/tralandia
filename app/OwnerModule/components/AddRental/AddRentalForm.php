<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\AddRental;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\Rental;
use Entity\Rental\Video;
use Entity\User\User;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Service\Rental\RentalCreator;
use Tralandia\BaseDao;
use Tralandia\Dictionary\PhraseManager;
use Tralandia\Invoicing\ServiceRepository;
use Tralandia\Language\Languages;
use Tralandia\Location\Countries;
use Tralandia\Placement\Placements;
use Tralandia\Rental\Amenities;
use Tralandia\Rental\Types;

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
	 * @var \Entity\User\User
	 */
	private $user;

	/**
	 * @var \Tralandia\Invoicing\ServiceRepository
	 */
	private $serviceRepository;


	public function __construct(User $user, RentalCreator $rentalCreator, Countries $countries,
								ServiceRepository $serviceRepository,
								ISimpleFormFactory $formFactory, EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->rentalCreator = $rentalCreator;
		$this->countries = $countries;
		$this->user = $user;
		$this->serviceRepository = $serviceRepository;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->addText('name', $this->translate(152275))
			->setRequired(TRUE);

		$countries = $this->countries->getForSelect();
		$form->addSelect('country', 'o1094', $countries)
			->setDefaultValue($this->user->getPrimaryLocation()->getId());

		$services = $this->serviceRepository->findAll();
		$services = \Tools::entitiesMap($services, 'id', 'label', $this->translator);
		$form->addOptionList('service', '', $services);

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

		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

	}


	public function processForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

		$primaryLocation = $this->countries->find($values->country);

		$rental = $this->rentalCreator->simpleCreate($this->user, $primaryLocation, $values->name);

		$this->em->persist($rental);
		$this->em->flush();

		$this->onFormSuccess($form, $rental);
	}



}


interface IAddRentalFormFactory
{
	public function create(\Entity\User\User $user);
}
