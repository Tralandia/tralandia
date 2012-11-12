<?php

namespace Extras\Email\Variables;

/**
 * LanguageVariablesFactory class
 *
 * @author Dávid Ďurika
 */
class LanguageVariablesFactory {

	protected $languageServiceFactory;

	public function __construct($languageServiceFactory) {
		$this->languageServiceFactory = $languageServiceFactory;
	}

	public function create(\Entity\Language $language) {
		return new LanguageVariables($this->languageServiceFactory->create($language));
	}
}