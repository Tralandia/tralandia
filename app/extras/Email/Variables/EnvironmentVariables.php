<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * EnvironmentVariables class
 *
 * @author Dávid Ďurika
 */
class EnvironmentVariables extends Nette\Object {

	private $locationVariables;
	private $languageVariables;

	public function __construct(LocationVariables $locationVariables, LanguageVariables $languageVariables) {
		$this->locationVariables = $locationVariables;
		$this->languageVariables = $languageVariables;
	}

	public function getLanguageEntity() {
		return $this->languageVariables->getEntity();
	}

	public function getVariableSiteDomain() {
		return 'sk.tralandia.com';
	} 

}

interface IEnvironmentVariablesFactory {
	/**
	 * @param LocationVariables $locationVariables
	 * @param LanguageVariables $languageVariables
	 *
	 * @return EnvironmentVariables
	 */
	function create(LocationVariables $locationVariables, LanguageVariables $languageVariables);
}