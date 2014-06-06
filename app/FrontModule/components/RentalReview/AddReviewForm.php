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

		$form->addText('firstName', 'a27');
		$form->addText('lastName', 'a28');
		$form->addText('email', 'a29');

		$form->addAdvancedDatePicker('date_from')
			->getControlPrototype()
			->setPlaceholder($this->translate('o1043'));


		$form->addAdvancedDatePicker('date_to')
			->getControlPrototype()
			->setPlaceholder($this->translate('o1044'));

		$groups = ['foo', 'bar'];
		$form->addSelect('group', 'a30', $groups)
			->setPrompt('a45')
			->setRequired(TRUE);

		$messages = $form->addContainer('messages');
		$messages->addTextArea('positives', 'a31');
		$messages->addTextArea('negatives', 'a32');
		$messages->addTextArea('locality', 'a33');
		$messages->addTextArea('region', 'a34');

		$ratingContainer = $form->addContainer('rating');
		$ratings = [
			['position', 'a35'],
			['purity', 'a36'],
			['amenities', 'a37'],
			['personal', 'a38'],
			['services', 'a39'],
			['activities', 'a40'],
			['price', 'a41'],
		];
		foreach($ratings as $value) {
			$ratingContainer->addHidden($value[0])
				->setOption('caption', $value[1])
				->setDefaultValue(0)
				->addRule($form::INTEGER)
				->addRule($form::RANGE, NULL, [0,5]);

		}

		$form->addSubmit('submit', 'a46');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		$form->setDefaults($this->getTestDefaults());

		return $form;
	}

	public function getTestDefaults()
	{
		$defaults = [];
		$defaults['email'] = 'email';
		$defaults['firstName'] = 'firstName';
		$defaults['lastName'] = 'lastName';
		$defaults['group'] = '1';
		$defaults['date_from'] = 'date_from';
		$defaults['date_to'] = 'date_to';
		$defaults['messages']['positives'] = 'positives';
		$defaults['messages']['negatives'] = 'negatives';
		$defaults['messages']['locality'] = 'locality';
		$defaults['messages']['region'] = 'region';
//		$defaults['rating']['position'] = 'position';
//		$defaults['rating']['purity'] = 'purity';
//		$defaults['rating']['amenities'] = 'amenities';
//		$defaults['rating']['personal'] = 'personal';
//		$defaults['rating']['services'] = 'services';
//		$defaults['rating']['activities'] = 'activities';
//		$defaults['rating']['price'] = 'price';

		return $defaults;
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
		$review->groupType = $values->group;
//		$review->arrivalDate = $values->date_from;
//		$review->departureDate = $values->date_to;
		$review->messagePositives = $values->messages->positives;
		$review->messageNegatives = $values->messages->negatives;
		$review->messageLocality = $values->messages->locality;
		$review->messageRegion = $values->messages->region;
		$review->ratingLocation = $values->rating->position;
		$review->ratingCleanness = $values->rating->purity;
		$review->ratingAmenities = $values->rating->amenities;
		$review->ratingPersonal = $values->rating->personal;
		$review->ratingServices = $values->rating->services;
		$review->ratingAttractions = $values->rating->activities;
		$review->ratingPrice = $values->rating->price;

		$review->updateAvgRating();

		$this->em->persist($review);
		$this->em->flush();


		$this->onFormSuccess($form, $review);
	}


}


interface IAddReviewFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
