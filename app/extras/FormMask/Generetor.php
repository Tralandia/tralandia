<?php

namespace Extras\FormMask;

use Nette, Extras;

/**
 * Generator masky formulara z konfigu formularov
 */
class Generator extends Nette\Object {

	/** @var Extras\FormMask\Mask */
	protected $entity;

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
		//$this->entityReflection = Extras\Reflection\Entity\ClassType::from($entity);
		$this->configurator = $configurator;
		$this->mask = $mask;

		//debug($this->entityReflection->getAnnotation('EA\JsonStructure'));
	}

	/**
	 * Vyskladanie samostatne fungujucej masky formulara
	 * @return Generator
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
	 * Setter text item factory
	 * @param Extras\FormMask\Items\Foctories\TextFactory
	 */
	public function setItemText(Extras\FormMask\Items\Foctories\TextFactory $factory) {
		$this->factories['text'] = $factory;
	}

	/**
	 * Setter phrase item factory
	 * @param Extras\FormMask\Items\Foctories\PhraseFactory
	 */
	public function setItemPhrase(Extras\FormMask\Items\Foctories\PhraseFactory $factory) {
		$this->factories['phrase'] = $factory;
	}

	/**
	 * Setter yesno item factory
	 * @param Extras\FormMask\Items\Foctories\YesNoFactory
	 */
	public function setItemYesNo(Extras\FormMask\Items\Foctories\YesNoFactory $factory) {
		$this->factories['yesno'] = $factory;
	}

	/**
	 * Setter json item factory
	 * @param Extras\FormMask\Items\Foctories\JsonFactory
	 */
	public function setItemJson(Extras\FormMask\Items\Foctories\JsonFactory $factory) {
		$this->factories['json'] = $factory;
	}
}