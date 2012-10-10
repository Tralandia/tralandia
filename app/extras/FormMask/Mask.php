<?php

namespace Extras\FormMask;

use Nette;

/**
 * Trieda formularovej masky
 */
class Mask extends Nette\Object {

	/** @var array */
	protected $items = array();

	/** @var array */
	public $onBeforeProcess = array();

	/** @var array */
	public $onAfterProcess = array();

	/** type */
	const TEXT = 'Extras\FormMask\Items\Text';
	
	/** type */
	const TEXTAREA = 'Extras\FormMask\Items\TextArea';
	
	/** type */
	const BUTTON = 'Extras\FormMask\Items\Button';
	
	/** type */
	const SUBMIT = 'Extras\FormMask\Items\Submit';
	
	/** type */
	const HIDDDEN = 'Extras\FormMask\Items\Hidden';
	
	/** type */
	const CHECKBOX = 'Extras\FormMask\Items\Checkbox';
	
	/** type */
	const RADIOLIST = 'Extras\FormMask\Items\RadioList';
	
	/** type */
	const SELECT = 'Extras\FormMask\Items\Select';

	/** type */
	const PRICE = 'Extras\Forms\Items\Price';

	/** type */
	const PHRASE = 'Extras\FormMask\Items\Phrase';

	/**
	 * Pridanie polozky do masky
	 * @param param
	 * @param param
	 * @param param
	 * @return Mask
	 */
	public function add($type, $name, $label) {
		$item = new $type($name, $label);
		$this->addItem($item);
		return $item;
	}

	/**
	 * Pridanie polozky do masky
	 * @param Items\Base
	 * @return Mask
	 */
	public function addItem(Items\Base $item) {
		$this->items[$item->getName()] = $item;
		return $this;
	}

	/**
	 * Pridanie viacej poloziek do masky
	 * @return Mask
	 */
	public function addItems() {
		foreach (func_get_args() as $item) {
			$this->addItem($item);
		}
		return $this;
	}

	/**
	 * Vrati vsetky polozky masky
	 * @return Items\Base
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * Rozsiri formular
	 * @param Nette\Forms\Form
	 */
	public function extend(Nette\Forms\Form $form) {
		foreach ($this->items as $item) {
			$item->extend($form);
		}
	}

	/**
	 * Rozsiri formular
	 * @param Nette\Forms\Form
	 */
	public function process(Nette\Forms\Form $form) {
		$this->onBeforeProcess($form);
		foreach ($this->items as $item) {
			if ($item->getValueSetter()) {
				$value = $form->getComponent($item->getName())->getValue();
				$item->setValue($value);
			}
		}
		$this->onAfterProcess($form);
	}
}