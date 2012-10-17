<?php

namespace Extras\FormMask\Items\Foctories;

use Extras, Entity;

/**
 * @author Branislav Vaculčiak
 */
class PhraseFactory {

	/** @var Extras\Models\Service\ServiceFactory */
	protected $serviceFactory;

	/** @var Entity\Dictionary\Language */
	protected $language;

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Service\ServiceFactory
	 */
	public function __construct(Extras\Models\Service\ServiceFactory $serviceFactory, Entity\Dictionary\Language $language) {
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