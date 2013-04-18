<?php

namespace Extras\FormMask\Items\Foctories;

use Extras, Entity, Environment;

/**
 * @author Branislav Vaculčiak
 */
class AdvancedPhraseFactory implements IFactory {

	/** @var Environment\Environment */
	protected $environment;

	/**
	 * @param Environment\Environment
	 */
	public function __construct(Environment\Environment $environment) {
		$this->environment = $environment;
	}

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Phrase
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\AdvancedPhrase($name, $label, $entity, $this->environment);
	}
}