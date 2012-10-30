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
		$this->configurator = $configurator;
		$this->mask = $mask;
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

	/**
	 * Setter neon item factory
	 * @param Extras\FormMask\Items\Foctories\NeonFactory
	 */
	public function setItemNeon(Extras\FormMask\Items\Foctories\NeonFactory $factory) {
		$this->factories['neon'] = $factory;
	}

	/**
	 * Setter textarea item factory
	 * @param Extras\FormMask\Items\Foctories\TextareaFactory
	 */
	public function setItemTextarea(Extras\FormMask\Items\Foctories\TextareaFactory $factory) {
		$this->factories['textarea'] = $factory;
	}

	/**
	 * Setter price item factory
	 * @param Extras\FormMask\Items\Foctories\PriceFactory
	 */
	public function setItemPrice(Extras\FormMask\Items\Foctories\PriceFactory $factory) {
		$this->factories['price'] = $factory;
	}

	/**
	 * Setter select item factory
	 * @param Extras\FormMask\Items\Foctories\SelectFactory
	 */
	public function setItemSelect(Extras\FormMask\Items\Foctories\SelectFactory $factory) {
		$this->factories['select'] = $factory;
	}

	/**
	 * Setter checkbox item factory
	 * @param Extras\FormMask\Items\Foctories\CheckboxFactory
	 */
	public function setItemCheckbox(Extras\FormMask\Items\Foctories\CheckboxFactory $factory) {
		$this->factories['checkbox'] = $factory;
	}

	/**
	 * Setter tinymce item factory
	 * @param Extras\FormMask\Items\Foctories\TinymceFactory
	 */
	public function setItemTinymce(Extras\FormMask\Items\Foctories\TinymceFactory $factory) {
		$this->factories['tinymce'] = $factory;
	}
}