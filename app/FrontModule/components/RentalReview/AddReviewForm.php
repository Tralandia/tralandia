<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace FrontModule\RentalReview;


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

class AddReviewForm extends \BaseModule\Components\BaseFormControl
{

	public $onFormSuccess = [];


	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param \BaseModule\Forms\ISimpleFormFactory $formFactory
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(Rental $rental, Environment $environment, ISimpleFormFactory $formFactory,
								EntityManager $em){
		parent::__construct();
		$this->rental = $rental;
		$this->environment = $environment;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}



	public function createComponentForm()
	{
		$form = $this->formFactory->create();

		$form->addText('firstName', 'Meno');
		$form->addText('lastName', 'priezvisko');
		$form->addText('email', 'email');

		$form->addAdvancedDatePicker('date_from')
			->getControlPrototype()
			->setPlaceholder($this->translate('o1043'));


		$form->addAdvancedDatePicker('date_to')
			->getControlPrototype()
			->setPlaceholder($this->translate('o1044'));

		$groups = ['foo', 'bar'];
		$form->addSelect('group', 'skupina', $groups)
			->setPrompt('skupina');

		$messages = $form->addContainer('messages');
		$messages->addTextArea('positives', 'plus');
		$messages->addTextArea('negatives', 'minus');
		$messages->addTextArea('locality', 'obec');
		$messages->addTextArea('region', 'region');

		$ratingContainer = $form->addContainer('rating');
		$ratingContainer->addHidden('position')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);
		$ratingContainer->addHidden('purity')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);
		$ratingContainer->addHidden('amenities')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);
		$ratingContainer->addHidden('personal')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);
		$ratingContainer->addHidden('services')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);
		$ratingContainer->addHidden('activities')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);
		$ratingContainer->addHidden('price')->setOption('caption', 'position')->addCondition($form::FILLED)->addRule($form::RANGE, NULL, [1,5]);


		$form->addSubmit('submit', 'o100083');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		return $form;
	}


	public function validateForm(BaseForm $form)
	{

	}


	public function processForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

		$review = new \Entity\User\RentalReview;

		$review->language = $this->environment->getLanguage();
		$review->rental = $this->rental;
		$review->senderEmail = $values->email;
		$review->senderFirstName = $values->firstName;
		$review->senderLastName = $values->lastName;
		$review->group = $values->group;
		$review->arrivalDate = $values->date_from;
		$review->departureDate = $values->date_to;
		$review->messagePositives = $values->messages->positives;
		$review->messageNegatives = $values->messages->negatives;
		$review->messageLocality = $values->messages->locality;
		$review->messageRegion = $values->messages->region;
		$review->ratingLocation = $values->rating->position;
		$review->ratingCleanness = $values->rating->purity;
		$review->ratingAmenities = $values->rating->amenities;
		$review->ratingPersonal = $values->rating->personal;
		$review->ratingServices = $values->rating->services;
		$review->ratingAttractions = $values->rating->activites;
		$review->ratingPrice = $values->rating->price;

		$this->em->persist($review);
		$this->em->flush();


		$this->onFormSuccess($form, $review);
	}


}


interface IAddReviewFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
