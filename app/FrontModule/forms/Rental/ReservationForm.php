<?php

namespace FrontModule\Forms\Rental;

use Doctrine\ORM\EntityManager;
use Entity\Contact\Phone;
use Environment\Environment;
use Nette;
use Nette\DateTime;

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
	 * @var \Nette\Http\Request
	 */
	private $request;


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \ReservationProtector $reservationProtector
	 * @param \Environment\Environment $environment
	 */
	public function __construct(\Entity\Rental\Rental $rental, EntityManager $em,
								\ReservationProtector $reservationProtector,
								Environment $environment, \Nette\Http\Request $request)
	{
		$this->rental = $rental;
		$this->environment = $environment;
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->reservationRepository = $em->getRepository(RESERVATION_ENTITY);
		$this->reservationProtector = $reservationProtector;
		$this->request = $request;

		parent::__construct($environment->getTranslator());
	}

	public function buildForm()
	{
		$phonePrefixes = $this->locationRepository->getCountriesPhonePrefixes($this->environment->getLocale()->getCollator());


//		$nameControl = $this->addText('name')
//			//->setRequired('o100158')
//			->getControlPrototype()
//				->setPlaceholder('o1031');
//
		$this->addText('email')
			->addRule(self::EMAIL, 'o100144')
			->getControlPrototype()
				->setPlaceholder('o1034');

		$date = $this->addContainer('date');
		$today = (new DateTime)->modify('today');
		$dateFromControl = $date->addAdvancedDatePicker('from')
			->getControlPrototype()
			->setPlaceholder('o1043');

		$dateFromControl->addCondition(self::FILLED)
			->addRule(self::RANGE, 'o100160', [$today, $today->modifyClone('+1 years')]);

		$dateToControl = $date->addAdvancedDatePicker('to')
			->getControlPrototype()
				->setPlaceholder('o1044');

		$dateToControl->addCondition(self::FILLED)
			->addRule(self::RANGE, 'o100160', [$today, $today->modifyClone('+1 years')]);



		$phoneContainer = $this->addPhoneContainer('phone', 'o10899', $phonePrefixes);

		$phoneContainer->getPrefixControl()
				->setDefaultValue($this->environment->getPrimaryLocation()->getPhonePrefix());

		$phoneContainer->getNumberControl()
			->getControlPrototype()
				->setPlaceholder('o1037');


		$parents = array();
		$children = array();

		for($i = 0 ; $i < 50 ; ++$i) {

			if($i > 0){
				$parents[$i] = $i . ' ' . $this->translate('o12277', NULL, ['count' => $i]);
			}

			$children[$i] = ' ' . $i . ' ' . $this->translate('o100061', NULL, ['count' => $i]);

		}

		$this->addSelect('parents','',$parents)
			->setRequired()
			->setValue(2);

		$this->addSelect('children','',$children)
			->setValue(0);

		$messageControl = $this->addTextArea('message')
			->getControlPrototype()
			->setPlaceholder('o12279');

		$messageControl->addCondition(self::FILLED)
			->addRule(self::MIN_LENGTH, 'o100162', 3);


		$this->addSubmit('submit', 'o100017');

		$this->onSuccess[] = callback($this, 'process');
		$this->onValidate[] = callback($this, 'validation');


	}


	public function validation(ReservationForm $form)
	{
		$values = $form->getFormattedValues();

		$from = $values->date->from;
		$to = $values->date->to;

		if(($from || $to) && !($to > $from)) {
			$form['date']['to']->addError($this->translate('o100160'));
		}

		if($from && $to && !$this->rental->isAvailable($from, $to)) {
			$form->addError($this->translate('o100161'));
		}

		$phone = $values->phone->entity;
		if(!$phone) {
			$form['phone']['number']->addError($form->translate('o100159'));
		}

		if(strpos($values->message, "http://") !== false || stripos($values->message, '[/url]') !== false) {
			$form['message']->addError($this->translate('151893'));
		}

		try {
			$phone = $phone instanceof Phone ? $phone : NULL;
			$this->reservationProtector->canSendReservation($values->email, $this->request->getRemoteAddress(), $phone);
		} catch (\TooManyReservationForEmailException $e) {
			$form->addError($this->translate('o100112'));
		} catch (\InfringeMinIntervalReservationException $e) {
			$form->addError($this->translate('o100135'));
		} catch (\ReservationProtectorException $e) {
			$form->addError($this->translate('152336'));
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
		$values = $form->getFormattedValues();

		/** @var $reservation \Entity\User\RentalReservation */
		$reservation = $this->reservationRepository->createNew();

		$reservation->setLanguage($this->environment->getLanguage());
		$reservation->setRental($this->rental);
		$reservation->setSenderEmail($values->email);
		//$reservation->setSenderName($values->name);
		if($values->phone->entity) $reservation->setSenderPhone($values->phone->entity);
		if($values->date->from) $reservation->setArrivalDate($values->date->from);
		if($values->date->to) $reservation->setDepartureDate($values->date->to);
		$reservation->setAdultsCount($values->parents);
		$reservation->setChildrenCount($values->children);
		$reservation->setMessage($values->message);
		$reservation->setSenderRemoteAddress($this->request->getRemoteAddress());

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

