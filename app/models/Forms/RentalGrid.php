<?php

namespace Tra\Forms;

use Tra\Services;

class RentalGrid extends Form {

	private $sRental;

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->sRental = new Services\Rental;
		$this->sRental->prepareForm($this, TRUE);
				
//		$form = new Form($this,$name);
//		$form->getElementPrototype()->addClass("ajax"); // Zajaxovatění formulářů v jquery.nette.js
//
//		$form->addText("country", "Kranija")
//			->addRule(Form::FILLED, "Toto je validační pravidlo v Nette formulářích: políčko je prázdné!");
//
//		$form->addTextArea("user", "User")
//			->addRule(Form::FILLED,"Musí být vyplněno!")
//			->addRule(Form::REGEXP, "Musí začínat na m!","/^m(.*)$/i");
//
//		$form->addTextArea("url", "URL Address")
//			->addRule(Form::FILLED, "Toto je validační pravidlo v Nette formulářích: políčko je prázdné!")
//			->addRule(Form::URL, "Toto je validační pravidlo v Nette formulářích: políčko nie je platná URL!");
//
//		$form->addSelect("status", "Status", array('Cancelled' => 'Cancelled', 'Resolved' => 'Resolved', 'Shipped' => 'Shipped', 'NULL' => "Without orders"))
//			->addRule(Form::FILLED, "Toto je validační pravidlo v Nette formulářích: políčko je prázdné!");
//
//		$form->addSubmit("odeslat", "Odeslat");
//		return $form;
				
		$this->ajax(false);
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getPrepareValues($this->sRental);		
		
		$this->sRental->create($values);
    }
}
