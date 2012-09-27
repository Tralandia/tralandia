<?php

require_once __DIR__ . '/bootstrap.php';


$entity = new TestEntity;


$item1 = new Extras\Forms\Items\Text('text', 'Text');
$item1->setValueGetter(array($entity, 'getName'))
	->setValueSetter(array($entity, 'setName'));

$item2 = new Extras\Forms\Items\Select('selekt', 'Selektik');
$item2->setValueGetter(array($entity, 'getCountry'))
	->setValueSetter(array($entity, 'setCountry'))
	->setItemsGetter(array($entity, 'getAllPaired'));

$item3 = new Extras\Forms\Items\Submit('submit', 'Submit');

$form = new Nette\Forms\Form;
$mask = new Extras\Forms\Mask;
$mask->addItems($item1, $item2, $item3);
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


