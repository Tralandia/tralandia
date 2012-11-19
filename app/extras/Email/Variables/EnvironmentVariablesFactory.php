<?php

namespace Extras\Email\Variables;

/**
 * EnvironmentVariablesFactory class
 *
 * @author Dávid Ďurika
 */
class EnvironmentVariablesFactory {

	public function create(LocationVariables $locationVariables, LanguageVariables $languageVariables) {
		return new EnvironmentVariables($locationVariables, $languageVariables);
	}
}