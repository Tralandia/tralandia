<?php

namespace Mail;

use Entity\Contact\PotentialMember;
use Entity\Contact\PotentialMemberEntity;
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
	 * @var string
	 */
	private $subject;

	/**
	 * @var \ShareLinks
	 */
	private $shareLinks;

	/**
	 * @var \Image\RentalImagePipe
	 */
	private $imagePipe;


	/**
	 * @param \Environment\Environment $environment
	 * @param \Nette\Application\Application $application
	 * @param \ShareLinks $shareLinks
	 * @param \Security\Authenticator $authenticator
	 * @param \TranslationTexy $texy
	 */
	public function __construct(Environment $environment, Application $application, \Image\RentalImagePipe $imagePipe, \ShareLinks $shareLinks, Authenticator $authenticator, \TranslationTexy $texy)
	{
		$this->application = $application;
		$this->authenticator = $authenticator;
		$this->shareLinks = $shareLinks;
		$this->texy = $texy;
		$this->setEnvironment($environment);
		$this->imagePipe = $imagePipe;
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
	 * @param \Environment\Environment $environment
	 *
	 * @return Compiler
	 */
	private function setEnvironment(Environment $environment)
	{
		$location = new Variables\LocationVariables($environment->getPrimaryLocation());
		$language = new Variables\LanguageVariables($environment->getLanguage());

		$this->environment = $environment;

		$this->variables['environment'] = new Variables\EnvironmentVariables($location, $language, $this->application, $this->shareLinks);
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
		$this->variables[$variableName] = new Variables\RentalVariables($rental, $this->imagePipe);
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
	 * @param \Entity\Contact\PotentialMember $pm
	 *
	 * @return $this
	 */
	public function addPotentialMember($variableName, PotentialMember $pm)
	{
		$this->variables[$variableName] = new Variables\PotentialMemberVariables($pm);
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
		$layout = $template->getLayout();

		$bodyHtml = $this->environment->getTranslator()->translate($template->getBody());
		$bodyHtml = $this->texy->process($bodyHtml);

		$html = str_replace('{include #content}', $bodyHtml, $layout->getHtml());

		$html = $this->findAndReplaceVariables($html);

		return $html;
	}


	/**
	 * @return string
	 */
	public function compileSubject()
	{
		$template = $this->getTemplate();

		$html = $this->environment->getTranslator()->translate($template->getSubject());

		$variables = $this->findAllVariables($html);
		$html = $this->replaceVariables($html, $variables);

		return $html;
	}


	/**
	 * @param string $html
	 * @return string
	 */
	protected function findAndReplaceVariables($html)
	{
		for($i = 0; $i < 2; $i++) {
			$variables = $this->findAllVariables($html);
			$html = $this->replaceVariables($html, $variables);
		}

		return $html;
	}

	/**
	 * @param string $html
	 *
	 * @return array
	 */
	protected function findAllVariables($html)
	{
		$match = Strings::matchAll($html, '~(?P<originalname>\[(?P<fullname>((?P<prefix>[a-zA-Z]+)_)?(?P<name>[a-zA-Z0-9]+))\])~');
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

			if($variable['fullname'] == 'subject') {
				$val = $this->compileSubject();
			} else if($variable['fullname'] == 'supportLink') {
				$val = $this->getVariable('environment')->getVariableSupportLink();
			} else if(is_numeric($variable['fullname'])) {
				if(in_array($variable['fullname'], [972, 1245])) {
					$val = $this->environment->getTranslator()->translate($variable['fullname'], 2);
					$val = Strings::firstUpper($val);
				} else {
					$val = $this->environment->getTranslator()->translate($variable['fullname']);
				}
				$val = $this->findAndReplaceVariables($val);

				if(in_array($variable['fullname'], [791120])) {
					$val = $this->texy->process($val);
				} else {
					$val = str_replace("\n", '$$$', $val);
					$val = $this->texy->processLine($val);
					$val = str_replace('$$$', '<br>', $val);
				}
			} else if (array_key_exists('prefix', $variable)) {
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
