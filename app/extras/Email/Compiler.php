<?php

namespace Extras\Email;

use Entity\Email;
use Nette\Utils\Strings;
/**
 * Compiler class
 *
 * @author Dávid Ďurika
 */
class Compiler {

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
	public function setEnvironment(\Entity\Location\Location $location, \Entity\Language $language)
	{
		$locationFactory = $this->getVariableFactory('location');
		$location = $locationFactory->create($location);

		$languageFactory = $this->getVariableFactory('language');
		$language = $languageFactory->create($language);

		$this->variables['env'] = $this->getVariableFactory('environment')->create($location, $language);
		return $this;
	}

	/**
	 * @param string $variableName
	 * @param \Entity\Language $language
	 *
	 * @return Compiler
	 */
	public function addLanguage($variableName, \Entity\Language $language)
	{
		$this->variables[$variableName] = $this->getVariableFactory('language')->create($language);
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
		$this->variables[$variableName] = $this->getVariableFactory('location')->create($location);
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
		$this->variables[$variableName] = $this->getVariableFactory('rental')->create($rental);
		return $this;
	}

	public function addVisitor($variableName, \Entity\User\User $visitor)
	{
		$this->variables[$variableName] = $this->getVariableFactory('visitor')->create($visitor);
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
	public function compile()
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
				$val = $this->getVariable($variable['prefix'])->{$methodName}();
			} else {
				$val = $this->getCustomVariable($variable['name']);
			}
			$replace[$variable['originalname']] = $val;
		}

		return str_replace(array_keys($replace), array_values($replace), $html);
	}

}