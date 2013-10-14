<?php

namespace Extras\FormMask;

use Nette, Extras;

/**
 * Generator masky formulara z konfigu formularov
 */
class Generator extends Nette\Object {

	/** @var \Entity\BaseEntity */
	protected $entity;

	/** @var Extras\FormMask\Mask */
	protected $mask;

	/** @var array */
	protected $factories = array();


	/**
	 * @param Mask $mask
	 */
	public function __construct(Mask $mask) {
		$this->mask = $mask;
	}

	/**
	 * Vyskladanie samostatne fungujucej masky formulara
	 * @return Generator
	 */
	public function build() {
		foreach ($this->factories as $factory) {
			$item = $factory->object->create($factory->field->getName(), $factory->field->getLabel(), $this->entity);

			// nastavenie validatorov
			if ($factory->field->getValidators()) {
				$item->setValidators($factory->field->getValidators());
			}

			if ($factory->field->isControlDisabled()) {
				$item->setDisabled(TRUE);
			}

			// nastavenie veci pre ziskanie itemov ku selektu
			if ($factory->field->getType() === 'select' || $factory->field->getType() === 'address') {
				$item->setRepository($factory->field->getControlOption('repository'));
				$params = $factory->field->getControlOption('items');
				$method = array_shift($params);
				$item->setItems($method, $params);
				$item->prompt = $factory->field->getControlOption('prompt');
			}

			$this->mask->addItem($item);
		}

		//TODO: toto nejako zautomatizovat, alebo minimalne prelozit
		$this->mask->add('Extras\FormMask\Items\Submit', 'submit', 'Odoslať');
		return $this;
	}

	/**
	 * Getter form mask
	 * @return Extras\FormMask\Mask
	 */
	public function getMask() {
		return $this->mask;
	}

	/**
	 * Getter form mask
	 * @param Extras\FormMask\Items\Foctories\IFactory
	 * @param Extras\Config\Field
	 * @return Generator
	 */
	public function addItem(Extras\FormMask\Items\Foctories\IFactory $factory, Extras\Config\Field $field) {
		$this->factories[$field->getName()] = (object)array(
			'object' => $factory,
			'field' => $field
		);
		return $this;
	}

	/**
	 * Setter entity
	 * @param \Entity\BaseEntity
	 * @return Generator
	 */
	public function setEntity(\Entity\BaseEntity $entity) {
		$this->entity = $entity;
		return $this;
	}
}
