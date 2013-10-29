<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Select polozka masky
 */
class Select extends Text {

	/** @var Extras\Callback */
	protected $itemsGetter;

	/** @var Extras\Models\Repository\RepositoryAccessor */
	protected $repositoryAccessor;

	/** @var array */
	protected $itemsParams;

	/** @var \Tralandia\Localization\Translator */
	protected $translator;

	/**
	 * @var string
	 */
	public $prompt;

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Translator
	 */
	public function __construct($name, $label, \Entity\BaseEntity $entity, \Tralandia\Localization\Translator $translator) {
		parent::__construct($name, $label, $entity);
		$this->translator = $translator;
		$this->setValueUnSetter(new Extras\Callback($entity, $this->unSetterMethodName($this->name)));
	}

	/**
	 * Setter accesora repozitara
	 * @param Extras\Models\Repository\RepositoryAccessor $repositoryAccessor
	 */
	public function setRepository(Extras\Models\Repository\RepositoryAccessor $repositoryAccessor) {
		$this->repositoryAccessor = $repositoryAccessor;
		return $this;
	}

	/**
	 * Setter nastaveni pre ziskanie itemov
	 * @param string $method
	 * @param array  $params
	 * @return $this
	 */
	public function setItems($method, array $params) {
		$this->itemsParams = $params;
		$this->setItemsGetter(new Extras\Callback($this->repositoryAccessor->get(), $method));
		return $this;
	}

	/**
	 * Setter gettera poloziek
	 * @param Extras\Callback
	 * @return Select
	 */
	public function setItemsGetter(Extras\Callback $items) {
		$this->itemsGetter = $items;
		return $this;
	}

	/**
	 * Getter gettera poloziek
	 * @return Extras\Callback
	 */
	public function getItemsGetter() {
		return $this->itemsGetter;
	}

	/**
	 * Vrati vsetky polozky
	 * @return mixed
	 */
	public function getItems() {
		if (!is_callable($this->getItemsGetter())) {
			throw new Nette\InvalidStateException("Nebol zadaný callback gettera poloziek.");
		}

		$items = $this->getItemsGetter()->invoke();
		if (count($this->itemsParams)) {
			$itemsFormatted = array();
			list($key, $value) = $this->itemsParams;
			foreach ($items as $item) {
				$itemsFormatted[$item->{$key}] = @$this->translator->translate($item->{$value});
			}
			$items = $itemsFormatted;
		}
		return $items;
	}

	/**
	 * Vrati hodnotu itemu
	 * @return mixed
	 */
	public function getValue() {
		if (!is_callable($this->getValueGetter())) {
			throw new Nette\InvalidStateException("Nebol zadaný callback gettera hodnot.");
		}
		$data = $this->getValueGetter()->invoke();
		return isset($data) ? $data->id : NULL;
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		$control = $form->addAdvancedSelect($this->getName(), $this->getLabel(), $this->getItems())
			->setDefaultValue($this->getValue())
			->setDisabled($this->disabled);

		if($this->prompt) $control->setPrompt($this->prompt);
		return $control;
	}

	/**
	 * Spracovanie dat z formulara
	 * @param Nette\Forms\Form
	 */
	public function process(Nette\Forms\Form $form) {
		if (!$this->repositoryAccessor) {
			throw new Nette\InvalidStateException("Nebol nasetovany accesor repozitara.");
		}

		if ($this->getValueSetter()) {
			$value = $form->getComponent($this->getName())->getValue();
			if($value !== NULL) {
				$value = $this->repositoryAccessor->get()->find($value);
			}
			$this->setValue($value);
		}
	}
}
