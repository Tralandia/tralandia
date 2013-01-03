<?php

namespace FormMask;

use  Nette, Extras;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class MaskTest extends \Tests\TestCase
{
	public $context;
	public $value = 'Hodnota 9786325';
	public $items = array(
		1 => 'Jedna',
		2 => 'Dva',
		3 => 'Tri',
		4 => 'Štyri',
		5 => 'Päť'
	);

	protected function setUp() {
	}

	public function testDefault() {
		$mask = new Extras\FormMask\Mask;
		$form = new Nette\Forms\Form;
		$form->onSuccess[] = array($mask, 'process');

		// vytvorim masku
		$item = $mask->add(Extras\FormMask\Mask::TEXT, 'text', 'Text');
		$this->assertInstanceOf('Extras\FormMask\Items\Text', $item);
		$this->assertSame('text', $item->getName());
		$this->assertSame('Text', $item->getLabel());

		$item->setValueGetter(new Extras\Callback($this, 'getter'));
		$this->assertInstanceOf('Extras\Callback', $item->getValueGetter());
		$this->assertSame('Hodnota 9786325', $item->getValue());

		$item->setValueSetter(new Extras\Callback($this, 'setter'));
		$this->assertInstanceOf('Extras\Callback', $item->getValueSetter());
		
		// rozsirim formular maskou
		$mask->extend($form);

		// nasetujem ososlane data a vyvolam sucess event
		$form->getComponent('text')->setValue('Iná hodnota 98755');
		$form->onSuccess($form);

		// overujem spravne ulozenie dat
		$this->assertSame('Iná hodnota 98755', $item->getValue());
	}

	public function testSelectItem() {
		$mask = new Extras\FormMask\Mask;
		$form = new Nette\Forms\Form;
		$form->onSuccess[] = array($mask, 'process');

		// vytvorim masku
		$item = $mask->add(Extras\FormMask\Mask::SELECT, 'selektik', 'Selekt');
		$item->setValueGetter(new Extras\Callback($this, 'getter'));
		$item->setItemsGetter(new Extras\Callback($this, 'getterItems'));
		$this->assertSame('Hodnota 9786325', $item->getValue());
		$this->assertSame($this->items, $item->getItems());
		$item->setValueSetter(new Extras\Callback($this, 'setter'));
		
		// rozsirim formular maskou
		$mask->extend($form);

		// nasetujem ososlane data a vyvolam sucess event
		$form->getComponent('selektik')->setValue(4);
		$form->onSuccess($form);

		// overujem spravne ulozenie dat
		$this->assertEquals(4, $item->getValue());
	}

	public function getter() {
		return $this->value;
	}

	public function setter($value) {
		$this->value = $value;
	}

	public function getterItems() {
		return $this->items;
	}
}