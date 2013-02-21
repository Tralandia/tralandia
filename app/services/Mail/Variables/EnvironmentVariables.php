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
		return $this->getLink('//Sign:in');
	}

	/**
	 * @param string $destination
	 * @param array $arguments
	 *
	 * @return string
	 */
	protected function getLink($destination, array $arguments = NULL)
	{
		return $this->application->getPresenter()->link($destination, $arguments);
	}


}