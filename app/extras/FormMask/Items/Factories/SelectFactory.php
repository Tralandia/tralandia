<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class SelectFactory implements IFactory {
	
	/** @var Extras\Translator */
	protected $translator;

	/**
	 * @param Extras\Translator
	 */
	public function __construct(Extras\Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Select
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Select($name, $label, $entity, $this->translator);
	}
}