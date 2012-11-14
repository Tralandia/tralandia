<?php

namespace Extras\FormMask;

use Nette, Extras;

/**
 * Generator masky formulara z konfigu formularov
 */
class Generator extends Nette\Object {

	/** @var Extras\Models\Entity\IEntity */
	protected $entity;

	/** @var Extras\FormMask\Mask */
	protected $mask;

	/** @var array */
	protected $factories = array();

	/**
	 * @param Extras\FormMask\Mask
	 */
	public function __construct(Extras\FormMask\Mask $mask) {
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
				$item->setDisabled(true);
			}

			// nastavenie veci pre ziskanie itemov ku selektu
			if ($factory->field->getType() === 'select') {
				$item->setRepository($factory->field->getControlOption('repository'));
				$params = $factory->field->getControlOption('items');
				$method = array_shift($params);
				$item->setItems($method, $params);
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
	 * @param Extras\Models\Entity\IEntity
	 * @return Generator
	 */
	public function setEntity(Extras\Models\Entity\IEntity $entity) {
		$this->entity = $entity;
		return $this;
	}
}