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

	protected $template;
	protected $layout;

	protected $variablesFactories;

	public $phraseServiceFactory;

	/**
	 * Pole zo zakladnymi premennimy
	 * @var array
	 */
	protected $variables = array();

	protected $customVariables = array();

	public function setTemplate(Email\Template $template) {
		$this->template = $template;
	}

	public function getTemplate() {
		if(!$this->template) {
			throw new \Nette\InvalidArgumentException('Template este nebol nastaveny');
		}

		return $this->template;
	}

	public function setLayout(Email\Layout $layout) {
		$this->layout = $layout;
	}

	public function getLayout() {
		if(!$this->layout) {
			throw new \Nette\InvalidArgumentException('Layout este nebol nastaveny');
		}

		return $this->layout;
	}

	public function setPrimaryVariable($variableName, $variableType, \Entity\BaseEntity $variable) {
		if($variable instanceof \Entity\User\User) {
			$user = $variable;
			$this->setEnvironment($user->location, $user->language);
			$this->addVariable($variableName, $variableType, $variable);
		} else {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
	}

	public function addVariable($variableName, $variableType, \Entity\BaseEntity $variable) {
		$factory = $this->getVariableFactory($variableType);

		$params = array_slice(func_get_args(), 2);
		$this->variables[$variableName] = call_user_func_array(array($factory, 'create'), $params);
		
		return $this;
	}

	public function getVariable($name) {
		if(!array_key_exists($name, $this->variables)) {
			throw new \Nette\InvalidArgumentException("Variable '$name' does not exist.");
		}

		return $this->variables[$name];		
	}

	public function addCustomVariable($name, $variable) {
		$this->customVariables[$name] = $variable;
		return $this;
	}

	public function getCustomVariable($name) {
		if(!array_key_exists($name, $this->customVariables)) {
			throw new \Nette\InvalidArgumentException("Custom variable '$name' does not exist.");
		}

		return $this->customVariables[$name];				
	}

	public function setVariableFactory($name, $factory) {
		$this->variablesFactories[$name] = $factory;
		return $this;
	}

	public function getVariableFactory($name) {
		if(!array_key_exists($name, $this->variablesFactories)) {
			throw new \Nette\InvalidArgumentException("Pre typ premennej '$name' nieje nastaveny factory");
		}

		return $this->variablesFactories[$name];
	}

	public function compile() {
		$template = $this->getTemplate();
		$layout = $this->getLayout();
		$html = $this->buildHtml($layout, $template);
		$variables = $this->findAllVariables($html);
		$html = $this->replaceVariables($html, $variables);

		return $html;
	}

	protected function setEnvironment($location, $language) {
		$this->setLocation($location);
		$this->setLanguage($language);
		$this->addVariable('env', 'environment', $location, $language);
	}

	protected function setLanguage(\Entity\Language $language) {
		return $this->addVariable('language', 'language', $language);
	}

	protected function setLocation(\Entity\Location\Location $location) {
		if($location->type->id != 3) {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
		return $this->addVariable('location', 'location', $location);
	}

	protected function buildHtml($layout, $template) {
		$envVariables = $this->getVariable('env');
		$body = $this->phraseServiceFactory->create($template->body)->getTranslation($envVariables->getLanguageEntity());
		return str_replace('{include #content}', $body, $layout->html);
	}

	protected function findAllVariables($html) {
		$match = Strings::matchAll($html, '~(?P<originalname>\[(?P<fullname>((?P<prefix>[a-zA-Z]+)_)?(?P<name>[a-zA-Z]+))\])~');
		$match = array_map('array_filter', $match);
		return $match;
	}

	protected function replaceVariables($html, $variables) {
		d($variables);

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


