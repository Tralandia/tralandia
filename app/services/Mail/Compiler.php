<?php

namespace Mail;

use Entity\Email;
use Entity\Language;
use Entity\Location\Location;
use Entity\Phrase\Phrase;
use Environment\Environment;
use Nette\Application\Application;
use Nette\Diagnostics\Debugger;
use Nette\Utils\Strings;
use Mail\Variables;
use Security\Authenticator;

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
	 * @var \Security\Authenticator
	 */
	protected $authenticator;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

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
	 * @var \TranslationTexy
	 */
	private $texy;


	/**
	 * @param \Environment\Environment $environment
	 * @param \Nette\Application\Application $application
	 * @param \Security\Authenticator $authenticator
	 * @param \TranslationTexy $texy
	 */
	public function __construct(Environment $environment, Application $application, Authenticator $authenticator, \TranslationTexy $texy)
	{
		$this->application = $application;
		$this->authenticator = $authenticator;
		$this->setEnvironment($environment);
		$this->texy = $texy;
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
	 * @param \Environment\Environment $environment
	 *
	 * @return Compiler
	 */
	private function setEnvironment(Environment $environment)
	{
		$location = new Variables\LocationVariables($environment->getPrimaryLocation());
		$language = new Variables\LanguageVariables($environment->getLanguage());

		$this->environment = $environment;

		$this->variables['environment'] = new Variables\EnvironmentVariables($location, $language, $this->application);
		return $this;
	}


	private function getEnvironment()
	{
		return $this->variables['environment'];
	}

	/**
	 * @param string $variableName
	 * @param \Entity\Language $language
	 *
	 * @return Compiler
	 */
	public function addLanguage($variableName, \Entity\Language $language)
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
	public function addLocation($variableName, \Entity\Location\Location $location)
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
		$this->variables[$variableName] = new Variables\VisitorVariables($user, $this->authenticator);
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
		$this->variables[$variableName] = new Variables\OwnerVariables($user, $this->authenticator);
		return $this;
	}


	/**
	 * @param $variableName
	 * @param \Entity\User\User $user
	 *
	 * @return $this
	 */
	public function addTranslator($variableName, \Entity\User\User $user)
	{
		$this->variables[$variableName] = new Variables\TranslatorVariables($user, $this->authenticator);
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
			$e = new UndeclaredVariable("Variable '$name' does not exist.");
			Debugger::log($e);
			return NULL;
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
			$e = new UndeclaredVariable("Custom variable '$name' does not exist.");
			Debugger::log($e);
			return NULL;
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

		/** @var $environmentVariables \Mail\Variables\EnvironmentVariables */
		$environmentVariables = $this->getEnvironment();
		$html = $template->getBody()->getTranslationText($environmentVariables->getLanguageEntity(), TRUE);

		$variables = $this->findAllVariables($html);
		$html = $this->replaceVariables($html, $variables);

		$html = $this->texy->process($html);

		$html = str_replace('{include #content}', $html, $layout->getHtml());

		return $html;
	}

	public function compileSubject()
	{
		$template = $this->getTemplate();
		/** @var $environmentVariables \Mail\Variables\EnvironmentVariables */
		$environmentVariables = $this->getEnvironment();
		$subjectHtml = $template->getSubject()->getTranslationText($environmentVariables->getLanguageEntity(), TRUE);
		$variables = $this->findAllVariables($subjectHtml);
		$html = $this->replaceVariables($subjectHtml, $variables);

		return $html;
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
		$environment = $this->getEnvironment();
		foreach ($variables as $variable) {
			if(array_key_exists($variable['fullname'], $replace)) continue;

			if(array_key_exists('prefix', $variable)) {
				$methodName = 'getVariable'.ucfirst($variable['name']);
				if(Strings::contains($methodName, 'Link')) {
					$val = $this->getVariable($variable['prefix'])->{$methodName}($environment);
				} else {
					$val = $this->getVariable($variable['prefix'])->{$methodName}();
				}
			} else {
				$val = $this->getCustomVariable($variable['name']);
			}
			if($val instanceof Phrase) $val = $this->environment->getTranslator()->translate($val);
			if($val instanceof \DateTime) $val = $this->environment->getLocale()->formatDate($val);
			$replace[$variable['originalname']] = (string) $val;
		}

		return str_replace(array_keys($replace), array_values($replace), $html);
	}

}

interface ICompilerFactory {
	/**
	 * @param \Environment\Environment $environment
	 *
	 * @return Compiler
	 */
	public function create(Environment $environment);
}

class UndeclaredVariable extends \Exception {}
