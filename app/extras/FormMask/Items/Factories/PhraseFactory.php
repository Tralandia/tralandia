<?php

namespace Extras\FormMask\Items\Foctories;

use Extras, Entity, Model;

/**
 * @author Branislav Vaculčiak
 */
class PhraseFactory implements IFactory {

	/** @var Model\Phrase\IPhraseDecoratorFactory */
	protected $serviceFactory;

	/** @var Entity\Language */
	protected $language;

	/**
	 * @param Model\Phrase\IPhraseDecoratorFactory
	 * @param Entity\Language
	 */
	public function __construct(Model\Phrase\IPhraseDecoratorFactory $serviceFactory, Entity\Language $language) {
		$this->serviceFactory = $serviceFactory;
		$this->language = $language;
	}

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Phrase
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Phrase($name, $label, $this->serviceFactory->create($entity->$name), $this->language);
	}
}