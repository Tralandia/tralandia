<?php

namespace Mail;

use Entity\Email;
use Entity\Language;
use Entity\Location\Location;
use Nette\Application\Application;
use Nette\Utils\Strings;
use Mail\Variables;
/**
 * Compiler class
 *
 * @author Dávid Ďurika
 */
class Compiler {

	/**
	 * @var \Nette\Application\Application
	 */
	protected $application;

	/**
	 * @var \Entity\Email\Template
	 */
	protected $template;

	/**
	 * @var \Entity\Email\Layout
	 */
	protected $layout;

	/**
	 * @var array
	 */
	protected $variablesFactories = array();

	/**
	 * @var array
	 */
	protected $variables = array();

	/**
	 * @var array
	 */
	protected $customVariables = array();

	/**
	 * @param \Entity\Location\Location $location
	 * @param \Entity\Language $language
	 * @param \Nette\Application\Application $application
	 */
	public function __construct(Location $location, Language $language, Application $application)
	{
		$this->application = $application;
		$this->setEnvironment($location, $language);
	}

	/**
	 * @param \Entity\Email\Template $template
	 */
	public function setTemplate(Email\Template $template)
	{
		$this->template = $template;
	}

	/**
	 * @return \Entity\Email\Template
	 * @throws \Nette\InvalidArgumentException
	 */
	public function getTemplate()
	{
		if(!$this->template) {
			throw new \Nette\InvalidArgumentException('Template este nebol nastaveny');
		}

		return $this->template;
	}

	/**
	 * @param \Entity\Email\Layout $layout
	 */
	public function setLayout(Email\Layout $layout)
	{
		$this->layout = $layout;
	}

	/**
	 * @return \Entity\Email\Layout
	 * @throws \Nette\InvalidArgumentException
	 */
	public function getLayout()
	{
		if(!$this->layout) {
			throw new \Nette\InvalidArgumentException('Layout este nebol nastaveny');
		}

		return $this->layout;
	}

	/**
	 * @param \Entity\Location\Location $location
	 * @param \Entity\Language $language
	 **
	 * @return Compiler
	 */
	private function setEnvironment(\Entity\Location\Location $location, \Entity\Language $language = NULL)
	{
		if(!$language) $language = $location->getDefaultLanguage();

		$location = new Variables\LocationVariables($location);
		$language = new Variables\LanguageVariables($language);

		$this->variables['env'] = new Variables\EnvironmentVariables($location, $language, $this->application);
		return $this;
	}

	private function getEnvironment()
	{
		return $this->variables['env'];
	}

	/**
	 * @param string $variableName
	 * @param \Entity\Language $language
	 *
	 * @return Compiler
	 */
	private function addLanguage($variableName, \Entity\Language $language)
	{
		$this->variables[$variableName] = new Variables\LanguageVariables($language);
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param \Entity\Location\Location $location
	 *
	 * @return Compiler
	 */
	private function addLocation($variableName, \Entity\Location\Location $location)
	{
		$this->variables[$variableName] = new Variables\LocationVariables($location);
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return Compiler
	 */
	public function addRental($variableName, \Entity\Rental\Rental $rental)
	{
		$this->variables[$variableName] = new Variables\RentalVariables($rental, $this->application);
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param \Entity\User\User $user
	 *
	 * @return Compiler
	 */
	public function addVisitor($variableName, \Entity\User\User $user)
	{
		$this->variables[$variableName] = new Variables\VisitorVariables($user);
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param \Entity\User\User $user
	 *
	 * @return Compiler
	 */
	public function addOwner($variableName, \Entity\User\User $user)
	{
		$this->variables[$variableName] = new Variables\OwnerVariables($user);
		return $this;
	}

	/**
	 * @param $variableName
	 * @param \Entity\User\RentalReservation $reservation
	 *
	 * @return Compiler
	 */
	public function addReservation($variableName, \Entity\User\RentalReservation $reservation)
	{
		$this->variables[$variableName] = new Variables\ReservationVariables($reservation);
		return $this;
	}

	/**
	 * @param $variableName
	 * @param \Entity\Ticket\Ticket $ticket
	 *
	 * @return $this
	 */
	public function addTicket($variableName, \Entity\Ticket\Ticket $ticket)
	{
		$this->variables[$variableName] = new Variables\ReservationVariables($ticket);
		return $this;
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return Compiler
	 */
	public function addCustomVariable($name, $value)
	{
		$this->customVariables[$name] = $value;
		return $this;
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function getVariable($name)
	{
		if(!array_key_exists($name, $this->variables)) {
			throw new \Nette\InvalidArgumentException("Variable '$name' does not exist.");
		}

		return $this->variables[$name];
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function getCustomVariable($name)
	{
		if(!array_key_exists($name, $this->customVariables)) {
			throw new \Nette\InvalidArgumentException("Custom variable '$name' does not exist.");
		}

		return $this->customVariables[$name];				
	}

	/**
	 * @param $name
	 * @param $factory
	 *
	 * @return Compiler
	 */
	public function registerVariableFactory($name, $factory)
	{
		$this->variablesFactories[$name] = $factory;
		return $this;
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function getVariableFactory($name)
	{
		if(!array_key_exists($name, $this->variablesFactories)) {
			throw new \Nette\InvalidArgumentException("Pre typ premennej '$name' nieje nastaveny factory");
		}

		return $this->variablesFactories[$name];
	}

	/**
	 * Vrati html emailu uz aj s dosadenymi premennymi
	 * @return mixed
	 */
	public function compileBody()
	{
		$template = $this->getTemplate();
		$layout = $this->getLayout();
		$html = $this->buildHtml($layout, $template);
		$variables = $this->findAllVariables($html);
		$html = $this->replaceVariables($html, $variables);

		return $html;
	}

	/**
	 * @param \Entity\Email\Layout $layout
	 * @param \Entity\Email\Template $template
	 *
	 * @return string
	 */
	protected function buildHtml(\Entity\Email\Layout $layout, \Entity\Email\Template $template)
	{
		/** @var $envVariables \Extras\Email\Variables\EnvironmentVariables */
		$envVariables = $this->getVariable('env');
		$body = $template->getBody()->getTranslationText($envVariables->getLanguageEntity(), TRUE);
		return str_replace('{include #content}', $body, $layout->getHtml());
	}

	/**
	 * @param string $html
	 *
	 * @return array
	 */
	protected function findAllVariables($html)
	{
		$match = Strings::matchAll($html, '~(?P<originalname>\[(?P<fullname>((?P<prefix>[a-zA-Z]+)_)?(?P<name>[a-zA-Z]+))\])~');
		$match = array_map('array_filter', $match);
		return $match;
	}

	/**
	 * @param string $html
	 * @param array $variables
	 *
	 * @return string
	 */
	protected function replaceVariables($html, array $variables)
	{
		$replace = array();
		foreach ($variables as $variable) {
			if(array_key_exists($variable['fullname'], $replace)) continue;

			if(array_key_exists('prefix', $variable)) {
				$methodName = 'getVariable'.ucfirst($variable['name']);
				if(\Tra\Utils\Strings::contains($methodName, 'link')) {
					$environment = $this->getEnvironment();
					$val = $this->getVariable($variable['prefix'])->{$methodName}($environment);
				} else {
					$val = $this->getVariable($variable['prefix'])->{$methodName}();
				}
			} else {
				$val = $this->getCustomVariable($variable['name']);
			}
			$replace[$variable['originalname']] = $val;
		}

		return str_replace(array_keys($replace), array_values($replace), $html);
	}

}

interface ICompilerFactory {
	/**
	 * @param \Entity\Location\Location $location
	 * @param \Entity\Language $language
	 *
	 * @return Compiler
	 */
	public function create(Location $location, Language $language);
}