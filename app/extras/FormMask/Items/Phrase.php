<?php

namespace Extras\FormMask\Items;

use Nette, Service, Entity, Extras;

/**
 * Phrase polozka masky
 */
class Phrase extends Base {

	/** @var Service\Phrase\PhraseService */
	protected $phraseService;

	/** @var Entity\Language */
	protected $language;

	/**
	 * @param string
	 * @param string
	 * @param Service\Phrase\PhraseService
	 */
	public function __construct($name, $label, Service\Phrase\PhraseService $phraseService, Entity\Language $language) {
		parent::__construct($name, $label);
		$this->phraseService = $phraseService;
		$this->language = $language;

		$this->setValueGetter(new Extras\Callback($this->phraseService, 'getTranslateValue', array($this->language)));
		$this->setValueSetter(new Extras\Callback($this->phraseService, 'setTranslateValue', array($this->language)));
	}
}