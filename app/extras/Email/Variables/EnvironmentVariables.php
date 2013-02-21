<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * EnvironmentVariables class
 *
 * @author Dávid Ďurika
 */
class EnvironmentVariables extends Nette\Object {

	/**
	 * @var LocationVariables
	 */
	private $locationVariables;

	/**
	 * @var LanguageVariables
	 */
	private $languageVariables;

	/**
	 * @param LocationVariables $locationVariables
	 * @param LanguageVariables $languageVariables
	 */
	public function __construct(LocationVariables $locationVariables, LanguageVariables $languageVariables) {
		$this->locationVariables = $locationVariables;
		$this->languageVariables = $languageVariables;
	}

	/**
	 * @return \Entity\Language
	 */
	public function getLanguageEntity() {
		return $this->languageVariables->getEntity();
	}

	/**
	 * @return string
	 */
	public function getVariableSiteDomain() {
		return 'sk.tralandia.com';
	}

	/**
	 * @return string
	 */
	public function getVariableLoginLink() {
		return 'sk.tralandia.com';
	}

}