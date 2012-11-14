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

	/** @var Extras\Translator */
	protected $translator;

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Translator
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity, Extras\Translator $translator) {
		parent::__construct($name, $label, $entity);
		$this->translator = $translator;
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
		if (is_array($this->itemsParams)) {
			$itemsFormated = array();
			list($key, $value) = $this->itemsParams;
			foreach ($items as $item) {
				$itemsFormated[$item->{$key}] = $this->translator->translate($item->{$value});
			}
			$items = $itemsFormated;
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
		
		return $this->getValueGetter()->invoke()->id;
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addAdvancedSelect($this->getName(), $this->getLabel(), $this->getItems())
			->setDefaultValue($this->getValue())
			->setDisabled($this->disabled);
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
			$this->setValue($this->repositoryAccessor->get()->find($value));
		}
	}
}