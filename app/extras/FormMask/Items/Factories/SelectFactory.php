<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class SelectFactory implements IFactory {

	/** @var \Tralandia\Localization\Translator */
	protected $translator;

	/**
	 * @param Extras\Translator
	 */
	public function __construct(\Tralandia\Localization\Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\Select
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Select($name, $label, $entity, $this->translator);
	}
}
