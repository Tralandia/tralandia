<?php

namespace Extras\FormMask\Items;

use Nette, Service, Entity, Extras;

/**
 * Phrase polozka masky
 */
class Phrase extends Base {

	/** @var Service\Dictionary\Phrase */
	protected $phraseService;

	/** @var Entity\Dictionary\Language */
	protected $language;

	/**
	 * @param string
	 * @param string
	 * @param Service\Dictionary\Phrase
	 */
	public function __construct($name, $label, Service\Dictionary\Phrase $phraseService, Entity\Dictionary\Language $language) {
		parent::__construct($name, $label);
		$this->phraseService = $phraseService;
		$this->language = $language;

		$this->setValueGetter(new Extras\Callback($this->phraseService, 'getTranslateValue', array($this->language)));
		$this->setValueSetter(new Extras\Callback($this->phraseService, 'setTranslateValue', array($this->language)));
	}
}