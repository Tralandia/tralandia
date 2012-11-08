<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * EnvironmentVariables class
 *
 * @author Dávid Ďurika
 */
class EnvironmentVariables extends Nette\Object {

	private $location;
	private $language;

	public function __construct(\Service\Location\LocationService $location, \Service\LanguageService $language) {
		$this->location = $location;
		$this->language = $language;
	}

	public function getLanguageEntity() {
		return $this->language->getEntity();
	}

	public function getVariableSiteDomain() {
		return 'sk.tralandia.com';
	} 

}