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
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\Phrase
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Phrase($name, $label, $this->serviceFactory->create($entity->$name), $this->language);
	}
}
