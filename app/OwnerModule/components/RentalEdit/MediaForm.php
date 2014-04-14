<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace OwnerModule\RentalEdit;


use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\Rental\Rental;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tralandia\BaseDao;
use Tralandia\Dictionary\PhraseManager;
use Tralandia\Language\Languages;
use Tralandia\Location\Countries;
use Tralandia\Placement\Placements;
use Tralandia\Rental\Amenities;
use Tralandia\Rental\Types;

class MediaForm extends BaseFormControl
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	private $rental;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;



	public function __construct(Rental $rental, ISimpleFormFactory $formFactory, EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
		$this->rental = $rental;
		$this->formFactory = $formFactory;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();

		$form->addRentalPhotosContainer('photos', $this->rental);


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

		];

		$form->setDefaults($defaults);
	}


	public function validateForm(BaseForm $form)
	{
		$photos = $form['photos']->getFormattedValues();
		if(count($photos->images) < 3) {
			$form['photos']['upload']->addError($this->translate('1294'));
		}

	}


	public function processForm(BaseForm $form)
	{
		$validValues = $form->getFormattedValues();
		$rental = $this->rental;

		if ($value = $validValues['photos']) {
			$i = 0;
			/** @var $imageEntity \Entity\Rental\Image */
			foreach ($value->images as $imageEntity) {
				$imageEntity->setSort($i);
				$this->rental->addImage($imageEntity);
				$i++;
			}
		}

		$this->em->persist($rental);
		$this->em->flush();
	}


}


interface IMediaFormFactory
{
	public function create(\Entity\Rental\Rental $rental);
}
