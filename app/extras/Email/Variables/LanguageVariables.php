<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * LanguageVariables class
 *
 * @author Dávid Ďurika
 */
class LanguageVariables extends Nette\Object {

	private $language;

	public function __construct(\Service\LanguageService $language) {
		$this->language = $language;
	}

	public function getEntity() {
		return $this->language->getEntity();
	}

}