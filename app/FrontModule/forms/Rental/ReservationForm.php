<?php

namespace FrontModule\Forms\Rental;

use Nette;

/**
 * ReservationForm class
 *
 * @author Dávid Ďurika
 */
class ReservationForm extends \FrontModule\Forms\BaseForm {

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @var \ReservationProtector
	 */
	protected $reservationProtector;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Repository\Location\LocationRepository $locationRepository
	 * @param \ReservationProtector $reservationProtector
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(\Entity\Rental\Rental $rental, \Repository\Location\LocationRepository
	$locationRepository, \ReservationProtector $reservationProtector, Nette\Localization\ITranslator $translator)
	{
		$this->rental = $rental;
		$this->locationRepository = $locationRepository;
		$this->reservationProtector = $reservationProtector;

		parent::__construct($translator);
	}

	public function buildForm()
	{
		$this->addText('name')
			->getControlPrototype()
				->setPlaceholder('o1031');

		$this->addText('email')
			->addRule(self::EMAIL)
			->getControlPrototype()
				->setPlaceholder('o1034');

		$date = $this->addContainer('date');
		$date->addText('from')
			->getControlPrototype()
				->setPlaceholder('o1043');

		$date->addText('to')
			->getControlPrototype()
				->setPlaceholder('o1044');


		$phone = $this->addContainer('phone');
		$phone->addSelect('prefix', '', $this->locationRepository->getCountriesPhonePrefixes())
			->setDefaultValue($this->rental->primaryLocation->phonePrefix);

		$phone->addText('number')
			->getControlPrototype()
				->setPlaceholder('o1037');


		$parents = array();
		$children = array();

		for($i = 0 ; $i < 21 ; ++$i) {

			if($i > 0){
				$parents[$i] = $i . ' ' . $this->translate('o12277', NULL, ['count' => $i]);
			}

			$children[$i] = $i . ' ' . $this->translate('o100016', NULL, ['count' => $i]);

		}

		$this->addSelect('parents','',$parents)->setPrompt('o12277');
		$this->addSelect('children','',$children)->setPrompt('o100016');

		$this->addTextArea('message')
			->getControlPrototype()
				->setPlaceholder('o12279');


		$this->addSubmit('submit', 'o100017');

		$this->onSuccess[] = callback($this, 'process');
		$this->onValidate[] = callback($this, 'validation');


	}


	public function validation(ReservationForm $form){
		$values = $form->getValues();
		try {
			$this->reservationProtector->canSendReservation($values->email);
		} catch (\TooManyReservationException $e) {
			$form->addError('o100112');
		} catch (\InfringeMinIntervalReservationException $e) {
			$form->addError('o100111');
		}
		//$form->addError('yle');
		//$form['name']->addError('bar');
		//$form['email']->addError('bar');
		//$form['phone']['number']->addError('bar');
		//$form['date']['from']->addError('bar');
		//$form['date']['to']->addError('bar');
		//$form['message']->addError('bar');
	}

	public function setDefaultsValues()
	{

	}

	public function process(ReservationForm $form)
	{
		$values = $form->getValues();
		$this->reservationProtector->newReservationSent($values->email);
	}


}

interface IReservationFormFactory {
	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return ReservationForm
	 */
	public function create(\Entity\Rental\Rental $rental);
}

