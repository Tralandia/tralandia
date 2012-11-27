<?php

require_once __DIR__ . '/bootstrap.php';


$entity = new TestEntity;
$form = new Nette\Forms\Form;
$mask = new Extras\Forms\Mask;

$mask->add(Extras\Forms\Mask::TEXT, 'text', 'Text')
	->setValueGetter(array($entity, 'getName'))
	->setValueSetter(array($entity, 'setName'))
$mask->add(Extras\Forms\Mask::SELECT, 'selektik', 'Selekt')
	->setValueGetter(array($entity, 'getCountry'))
	->setValueSetter(array($entity, 'setCountry'))
	->setItemsGetter(array($entity, 'getAllPaired'));
$mask->add(Extras\Forms\Mask::SUBMIT, 'submit', 'Odoslať');


$mask->extend($form);


if ($form->isSuccess()) {
	$mask->process($form);
	dump($entity);
}


echo $form;


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


