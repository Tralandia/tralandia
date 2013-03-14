<?php
namespace Mail\Variables;

use Nette;
use Nette\Application\Application;

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
	 * @var \Nette\Application\Application
	 */
	private $application;

	/**
	 * @param LocationVariables $locationVariables
	 * @param LanguageVariables $languageVariables
	 * @param Application $application
	 */
	public function __construct(LocationVariables $locationVariables, LanguageVariables $languageVariables, Application $application) {
		$this->locationVariables = $locationVariables;
		$this->languageVariables = $languageVariables;
		$this->application = $application;
	}

	/**
	 * @return \Entity\Location\Location
	 */
	public function getLocationEntity() {
		return $this->locationVariables->getEntity();
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
	public function getVariableSiteName() {
		return 'sk.tralandia.com';
	}

	/**
	 * @return string
	 */
	public function getVariableLoginLink() {
		return $this->link('//Sign:in');
	}

	/**
	 * @param string $destination
	 * @param array $arguments
	 *
	 * @return string
	 */
	public function link($destination, array $arguments = NULL)
	{
		$arguments = array_merge(
			['primaryLocation' => $this->getLocationEntity(), 'language' => $this->getLanguageEntity()],
			$arguments
		);
		return $this->application->getPresenter()->link($destination, $arguments);
	}


}