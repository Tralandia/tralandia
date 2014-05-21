<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\CustomPricelistRow;
use Entity\Rental\Pricelist;
use Entity\Rental\Rental;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Utils\Html;
use Tralandia\Currency\Currencies;
use Tralandia\Rental\Amenities;

class AmenitiesForm extends BaseFormControl
{

	public $onFormSuccess = [];

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Tralandia\Currency\Currencies
	 */
	private $currencies;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;

	/**
	 * @var \Tralandia\Rental\Amenities
	 */
	private $amenities;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param \BaseModule\Forms\ISimpleFormFactory $formFactory
	 * @param \Kdyby\Doctrine\EntityManager $em
	 * @param \Tralandia\Rental\Amenities $amenities
	 */
	public function __construct(Rental $rental, Environment $environment, ISimpleFormFactory $formFactory,
								EntityManager $em, Amenities $amenities){
		parent::__construct();
		$this->rental = $rental;
		$this->environment = $environment;
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->amenities = $amenities;
	}



	public function createComponentForm()
	{
		$form = $this->formFactory->create();

		$amenityBoard = $this->amenities->findByBoardTypeForSelect();
		$form->addMultiOptionList('board', 'o100080', $amenityBoard)
			->setRequired()
			->addRule(BaseForm::FILLED, $this->translate('o100109'))//->setOption('help', $this->translate('o5956'))
		;

		$amenities = $this->amenities->findByChildrenTypeForSelect();
		$form->addMultiOptionList('children', 'o100169', $amenities);

		$amenities = $this->amenities->findByServiceTypeForSelect();
		$form->addMultiOptionList('service', 'o100171', $amenities);

		$amenities = $this->amenities->findByWellnessTypeForSelect();
		$form->addMultiOptionList('wellness', 'o100172', $amenities);

		$amenities = $this->amenities->findByKitchenTypeForSelect();
		$form->addMultiOptionList('kitchen', 'o100174', $amenities);

		$amenities = $this->amenities->findByBathroomTypeForSelect();
		$form->addMultiOptionList('bathroom', 'o100175', $amenities);

		$amenities = $this->amenities->findByNearByTypeForSelect();
		$form->addMultiOptionList('nearBy', '152277', $amenities);

		$amenities = $this->amenities->findByRentalServicesTypeForSelect();
		$form->addMultiOptionList('rentalServices', '152278', $amenities);

		$amenities = $this->amenities->findByOnFacilityTypeForSelect();
		$form->addMultiOptionList('onFacility', '152279', $amenities);

		$amenities = $this->amenities->findBySportsFunTypeForSelect();
		$form->addMultiOptionList('sportsFun', '152280', $amenities);

		$form->addSubmit('submit', 'o100083');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		$this->setDefaults($form);

		return $form;
	}


	public function setDefaults(BaseForm $form)
	{
		$rental = $this->rental;
		$defaults = [
			'board' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getBoardAmenities()
			),
			'children' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getChildrenAmenities()
			),
			'service' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getServiceAmenities()
			),
			'wellness' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getWellnessAmenities()
			),
			'kitchen' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getKitchenAmenities()
			),
			'bathroom' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getBathroomAmenities()
			),
			'nearBy' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getNearByAmenities()
			),
			'rentalServices' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getRentalServicesAmenities()
			),
			'onFacility' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getOnFacilityAmenities()
			),
			'sportsFun' => array_map(function ($a) {
					return $a->getId();
				}, $rental->getSportsFunAmenities()
			),
		];
		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{

	}


	public function processForm(BaseForm $form)
	{
		$validValues = $form->getFormattedValues();

		$rental = $this->rental;

		$amenities = ['board', 'children', 'service', 'wellness', 'kitchen', 'bathroom', 'nearBy' => 'near-by', 'rentalServices' => 'rental-services', 'onFacility' => 'on-premises', 'sportsFun' => 'sports-fun'];
		$amenityRepository = $this->em->getRepository(RENTAL_AMENITY_ENTITY);
		foreach ($amenities as $valueName => $amenityName) {
			if(is_numeric($valueName)) $valueName = $amenityName;
			if($value = $validValues[$valueName]) {
				$amenities = $rental->getAmenitiesByType($amenityName);
				foreach ($amenities as $amenity) {
					$rental->removeAmenity($amenity);
				}

				foreach ($value as $amenityId) {
					$amenityEntity = $amenityRepository->find($amenityId);
					$rental->addAmenity($amenityEntity);
				}
			}
		}

		$this->em->flush();

		$this->onFormSuccess($form, $rental);
	}


}


interface IAmenitiesFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
