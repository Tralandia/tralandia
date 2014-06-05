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

		$form->addText('firstName', )


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
		$validValues = $form->getFormattedValues();


		$this->onFormSuccess($form, $rental);
	}


}


interface IAddReviewFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
