<?php

namespace FrontModule\Forms\Rental;

use Doctrine\ORM\EntityManager;
use Environment\Environment;
use Nette;

/**
 * ReservationForm class
 *
 * @author Dávid Ďurika
 */
class ReservationForm extends \FrontModule\Forms\BaseForm {

	/**
	 * @var array
	 */
	public $onReservationSent = [];

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepository;

	protected $reservationRepository;

	/**
	 * @var \ReservationProtector
	 */
	protected $reservationProtector;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \ReservationProtector $reservationProtector
	 * @param \Environment\Environment $environment
	 */
	public function __construct(\Entity\Rental\Rental $rental, EntityManager $em,
								\ReservationProtector $reservationProtector, Environment $environment)
	{
		$this->rental = $rental;
		$this->environment = $environment;
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->reservationRepository = $em->getRepository(RESERVATION_ENTITY);
		$this->reservationProtector = $reservationProtector;

		parent::__construct($environment->getTranslator());
	}

	public function buildForm()
	{
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes();


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


		$phoneContainer = $this->addPhoneContainer('phone', 'o10899', $phonePrefixes);

		$phoneContainer->getPrefixControl()
				->setDefaultValue($this->environment->getPrimaryLocation()->getPhonePrefix());

		$phoneContainer->getNumberControl()
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
		} catch (\TooManyReservationForEmailException $e) {
			$form->addError($this->translate('o100112'));
		} catch (\InfringeMinIntervalReservationException $e) {
			$form->addError($this->translate('o100135'));
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

		/** @var $reservation \Entity\User\RentalReservation */
		$reservation = $this->reservationRepository->createNew();

		$reservation->setLanguage($this->environment->getLanguage());
		$reservation->setRental($this->rental);
		$reservation->setSenderEmail($values->email);
		$reservation->setSenderName($values->name);
		$reservation->setSenderPhone($values->phone->phone);
		$reservation->setArrivalDate(Nette\DateTime::from($values->date->from));
		$reservation->setDepartureDate(Nette\DateTime::from($values->date->to));
		$reservation->setAdultsCount($values->parents);
		$reservation->setChildrenCount($values->children);
		$reservation->setMessage($values->message);

		$this->reservationRepository->save($reservation);

		$this->reservationProtector->newReservationSent($values->email);

		$this->onReservationSent($reservation);
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

