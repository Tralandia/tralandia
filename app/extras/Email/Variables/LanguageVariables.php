<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * LanguageVariables class
 *
 * @author Dávid Ďurika
 */
class LanguageVariables extends Nette\Object {

	/**
	 * @var \Entity\Language
	 */
	private $language;

	/**
	 * @param \Entity\Language $language
	 */
	public function __construct(\Entity\Language $language) {
		$this->language = $language;
	}

	/**
	 * @return \Entity\Language
	 */
	public function getEntity() {
		return $this->language;
	}

}