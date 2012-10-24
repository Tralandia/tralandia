<?php

namespace Extras\FormMask;

use Nette, Extras;

/**
 * Generator masky formulara z konfigu formularov
 */
class Generator extends Nette\Object {

	/** @var Extras\FormMask\Mask */
	protected $entity;

	/** @var Extras\Reflection\Entity\ClassType */
	protected $entityReflection;

	/** @var Extras\FormMask\Mask */
	protected $mask;

	/** @var Extras\Config\Configurator */
	protected $configurator;

	/** @var Extras\FormMask\Mask */
	protected $factories = array();

	/**
	 * @param Extras\FormMask\Mask
	 * @param Extras\Config\Configurator
	 * @param Extras\Models\Entity\IEntity
	 */
	public function __construct(Extras\FormMask\Mask $mask, Extras\Config\Configurator $configurator, Extras\Models\Entity\IEntity $entity) {
		$this->entity = $entity;
		$this->entityReflection = Extras\Reflection\Entity\ClassType::from($entity);
		$this->configurator = $configurator;
		$this->mask = $mask;	
	}

	/**
	 * Vyskladanie samostatne fungujucej masky formulara
	 */
	public function build() {
		foreach ($this->configurator->getForm() as $field) {
			$item = $this->factories[$field->getType()]->create($field->getName(), $field->getLabel(), $this->entity);
			if ($field->getValidators()) {
				$item->setValidators($field->getValidators());
			}
			$this->mask->addItem($item);
		}

		//TODO: toto nejako zautomatizovat, alebo minimalne prelozit
		$this->mask->add('Extras\FormMask\Items\Submit', 'submit', 'Odoslať');
	}

	/**
	 * Setter phrase item factory
	 * @param Extras\FormMask\Items\Foctories\PhraseFactory
	 */
	public function setItemPhrase(Extras\FormMask\Items\Foctories\PhraseFactory $factory) {
		$this->factories['phrase'] = $factory;
	}

	/**
	 * Setter text item factory
	 * @param Extras\FormMask\Items\Foctories\PhraseFactory
	 */
	public function setItemText(Extras\FormMask\Items\Foctories\TextFactory $factory) {
		$this->factories['text'] = $factory;
	}
}