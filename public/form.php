<?php

require_once __DIR__ . '/bootstrap.php';


$entity = new TestEntity;
$form = new Nette\Forms\Form;

$ui = new UI($form);
$ui->ext();

if ($form->isSuccess()) {
	$ui->onSuccess();
}

echo $form;

// --------------------------------------------------------------------------------

class UI {

	private $form;
	private $fields = array();

	public function __construct(Nette\Forms\Form $form) {
		$this->form = $form;
	}

	public function getForm() {
		return $this->form;
	}

	public function ext() {
		global $entity;

		$field = new Text($this->form, 'nazov', 'Nazov');
		$field->setValueGetter(array($entity, 'getName'))
			->setValueSetter(array($entity, 'setName'));

		$this->fields[] = $field;
		$this->form->addText($field->getName(), $field->getLabel())
			->setDefaultValue($field->getValueGetter());


		$field = new Select($this->form, 'selekt', 'Selektik');
		$field->setValueGetter(array($entity, 'getCountry'))
			->setItemsGetter(array($entity /*$em->getRepository('Entities\Country')*/, 'getAllPaired'))
			->setValueSetter(array($entity, 'setCountry'));
		
		$this->fields[] = $field;
		$this->form->addSelect($field->getName(), $field->getLabel(), $field->getItems())
			->setDefaultValue($field->getValueGetter());


		$this->form->addSubmit('submit', 'Submit');
	}

	public function onSuccess() {
		global $entity;

		foreach ($this->fields as $field) {
			$newValue = $this->form->getComponent($field->getName())->getValue();
			call_user_func($field->getValueSetter(), $newValue);
		}


		dump($entity);
	}	
}

abstract class Field {

	protected $form;
	protected $name;
	protected $label;
	protected $value;
	protected $saveValue;

	public function __construct(Nette\Forms\Form $form, $name) {
		$this->form = $form;
		$this->name = md5($name);
	}

	public function getName() {
		return $this->name;
	}

	public function getLabel() {
		return $this->label;
	}

	public function getValue() {
		return call_user_func($this->getValueGetter());
	}

	public function getValueGetter() {
		return call_user_func($this->value);
	}

	public function setValueGetter($value) {
		$this->value = $value;
		return $this;
	}

	public function setValueSetter($saveValue) {
		$this->saveValue = $saveValue;
		return $this;
	}

	public function getValueSetter() {
		return $this->saveValue;
	}
}

class Text extends Field {

}

class Select extends Field {

	protected $items;

	public function setItemsGetter($items) {
		$this->items = $items;
		return $this;
	}

	public function getItemsGetter() {
		return $this->items;
	}

	public function getItems() {
		return call_user_func($this->getItemsGetter());
	}
}



class TestEntity {

	private $name = 'Prvotný názov';
	private $country = 2;

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setCountry($country) {
		$this->country = $country;
		return $this;
	}

	public function getCountry() {
		return $this->country;
	}

	public function getAllPaired() {
		return array(
			1 => 'Slovensko',
			2 => 'Česko',
			3 => 'Nemecko',
			4 => 'Maďarsko',
			5 => 'Poľsko',
			6 => 'Ukraina',
			7 => 'Kanada'
		);
	}

	public function __toString() {
		$countries = $this->getAllPaired();
		return $this->name . ' - ' . $countries[$this->country];
	}
}


