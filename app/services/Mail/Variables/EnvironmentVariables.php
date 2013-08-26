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
	 * @var \ShareLinks
	 */
	private $shareLinks;


	/**
	 * @param LocationVariables $locationVariables
	 * @param LanguageVariables $languageVariables
	 * @param Application $application
	 * @param \ShareLinks $shareLinks
	 */
	public function __construct(LocationVariables $locationVariables, LanguageVariables $languageVariables,
								Application $application, \ShareLinks $shareLinks) {
		$this->locationVariables = $locationVariables;
		$this->languageVariables = $languageVariables;
		$this->application = $application;
		$this->shareLinks = $shareLinks;
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
		return $this->getVariableSiteName();
	}

	/**
	 * @return string
	 */
	public function getVariableSiteName() {
		return ucfirst($this->getLocationEntity()->getFirstDomain()->getDomain());
	}


	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getVariableCountryName()
	{
		$location = $this->getLocationEntity();
		return $location->isWorld() ? '' : $location->getName();
	}


	/**
	 * @return string
	 */
	public function getVariableFacebookPage()
	{
		return $this->shareLinks->facebookPage;
	}


	/**
	 * @return string
	 */
	public function getVariableLoginLink() {
		return $this->link('//:Front:Sign:in');
	}


	/**
	 * @return string
	 */
	public function getVariableSupportLink()
	{
		return $this->link('//:Front:SupportUs:default');
	}

	/**
	 * @param string $destination
	 * @param array $arguments
	 *
	 * @return string
	 */
	public function link($destination, array $arguments = array())
	{
		$arguments = array_merge(
			['primaryLocation' => $this->getLocationEntity(), 'language' => $this->getLanguageEntity()],
			$arguments
		);
		return $this->application->getPresenter()->link($destination, $arguments);
	}


}
