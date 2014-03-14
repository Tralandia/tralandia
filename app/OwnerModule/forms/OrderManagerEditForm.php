<?php

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Entity\User\User;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;
use Tralandia\BaseDao;
use Tralandia\Location\Countries;

class OrderManagerEditForm extends BaseForm {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \Tralandia\Location\Countries
	 */
	private $countries;

	/**
	 * @param \Entity\User\User $user
	 * @param \Environment\Environment $environment
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(User $user, Countries $countries, ITranslator $translator){
		$this->user = $user;
		$this->countries = $countries;
		parent::__construct($translator);
	}


	public function buildForm() {
		$this->addRentalUnitContainer('units', 'Ubytovacie jednotky', $this->user);

		$this->addText('name', 'Meno')
			->setRequired('Povinne policko');

		$this->addText('surname', 'Priezvisko')
			->setRequired('Povinne policko');

		$this->addText('email', 'Emailova adresa')
			->setRequired('Povinne policko');

		$this->addPhoneContainer('phone', 'Telefon', $this->countries->getPhonePrefixes());

		$this->addText('peopleCount', 'Pocet dospelych')
			->setRequired('Povinne policko')
			->addRule(self::NUMERIC, 'Musi byt cislo');
		
		$this->addText('childrenCount', 'Pocet deti')
			->setRequired('Povinne policko')
			->addRule(self::NUMERIC, 'Musi byt cislo');

		$this->addText('childrenAge', 'Vek deti');

		$this->addText('dateFrom', 'Datum od')
			->setRequired('Povinne policko');
			
		$this->addText('dateTo', 'Datum do')
			->setRequired('Povinne policko');

		$this->addTextArea('message', 'Sprava');
		$this->addTextArea('ownerNotes', 'Poznamky');

		$this->addCheckbox('confirmed', 'Rezervacia potvrdena');

		$this->addText('referer', 'Zdroj rezervacie');

		$this->addText('price', 'Konecna cena')
			->setRequired('Povinne policko')
			->addRule(self::NUMERIC, 'Musi byt cislo');

		$this->addText('price_paid', 'Uhradena suma')
			->setRequired('Povinne policko')
			->addRule(self::NUMERIC, 'Musi byt cislo');

		$this->addSubmit('submit', 'o100151');

		$this->onSuccess[] = [$this, 'process'];
	}

	public function setDefaultsValues()
	{

	}

	public function process(OrderManagerEditForm $form)
	{
		
	}

}

interface IOrderManagerEditFormFactory {
	/**
	 * @param \Entity\User\User $user
	 * @param \Tralandia\Location\Countries $countries
	 *
	 * @return OrderManagerEditForm
	 */
	public function create(User $user, Countries $countries);
}
