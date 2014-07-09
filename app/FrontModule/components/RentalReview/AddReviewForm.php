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
use Nette\Localization\ITranslator;

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

	private $translator;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Environment\Environment $environment
	 * @param \BaseModule\Forms\ISimpleFormFactory $formFactory
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(Rental $rental, Environment $environment, ISimpleFormFactory $formFactory,
								EntityManager $em, ITranslator $translator){
		parent::__construct();
		$this->rental = $rental;
		$this->environment = $environment;
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->translator = $translator;
	}



	public function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->setTranslator($this->translator);

		$form->addText('firstName', $this->translate('a27'));
		$form->addText('lastName', $this->translate('a28'));
		$form->addText('email', $this->translate('a29'));

		$form->addAdvancedDatePicker('date_from')
			->getControlPrototype()
			->setPlaceholder($this->translate('o1043'));


		$form->addAdvancedDatePicker('date_to')
			->getControlPrototype()
			->setPlaceholder($this->translate('o1044'));

		$groups = [
			\Entity\User\RentalReview::GROUP_TYPE_SOLO,
			\Entity\User\RentalReview::GROUP_TYPE_YOUNG_PAIR,
			\Entity\User\RentalReview::GROUP_TYPE_OLD_PAIR,
			\Entity\User\RentalReview::GROUP_TYPE_GROUP,
			\Entity\User\RentalReview::GROUP_TYPE_FRIENDS,
			\Entity\User\RentalReview::GROUP_TYPE_FAMILY_YOUNG_KIDS,
			\Entity\User\RentalReview::GROUP_TYPE_FAMILY_OLD_KIDS,
		];
		$form->addSelect('group', $this->translate('a30'))
			->setPrompt($this->translate('a45'))
			->setItems($groups, FALSE)
			->setRequired(TRUE);

		$messages = $form->addContainer('messages');
		$messages->addTextArea('positives', $this->translate('a31'));
		$messages->addTextArea('negatives', $this->translate('a32'));
		$messages->addTextArea('locality', $this->translate('a33'));
		$messages->addTextArea('region', $this->translate('a34'));

		$ratingContainer = $form->addContainer('rating');
		$ratings = [
			['position', $this->translate('a35')],
			['purity', $this->translate('a36')],
			['amenities', $this->translate('a37')],
			['personal', $this->translate('a38')],
			['services', $this->translate('a39')],
			['activities', $this->translate('a40')],
			['price', $this->translate('a41')],
		];
		foreach($ratings as $value) {
			$ratingContainer->addHidden($value[0])
				->setOption('caption', $value[1])
				->setDefaultValue(0)
				->addRule($form::INTEGER)
				->addRule($form::RANGE, NULL, [0,5]);

		}

		$form->addSubmit('submit', $this->translate('a46'));

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

//		$form->setDefaults($this->getTestDefaults());

		return $form;
	}

	public function getTestDefaults()
	{
		$defaults = [];
		$defaults['email'] = 'email';
		$defaults['firstName'] = 'firstName';
		$defaults['lastName'] = 'lastName';
		$defaults['group'] = 'a51';
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
