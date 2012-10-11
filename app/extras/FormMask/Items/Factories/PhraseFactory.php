<?php

namespace Extras\FormMask\Items\Foctories;

use Extras, Entity;

/**
 * @author Branislav Vaculčiak
 */
class PhraseFactory {

	/** @var */
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

	public function create($name, $label, $entity) {
		return new Extras\FormMask\Items\Phrase($name, $label, $this->serviceFactory->create($entity), $this->language);
	}
}