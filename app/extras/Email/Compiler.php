<?php

namespace Extras\Email;

use Entity\Email;
/**
 * Compiler class
 *
 * @author Dávid Ďurika
 */
class Compiler {

	protected $template;
	protected $layout;

	protected $variablesFactories;

	/**
	 * Pole zo zakladnymi premennimy
	 * @var array
	 */
	protected $variables = array();

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

	public function setPrimaryVariable($name, \Service\BaseService $variable) {
		if($variable instanceof \Service\User\UserService) {
			$user = $variable->getEntity();
			$htis->setLanguage($user->language);
			$this->setCountry($user->location);
			$this->addVariable($name, $variable);
		} else {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
	}

	public function addVariable($name, $variable) {
		$factory = $this->getVariableFactory($name);
		$this->variables[$name] = $factory->create($variable);
		return $this;
	}

	public function setVariableFactory($name, $factory) {
		$this->variablesFactories[$name] = $factory;
		return $htis;
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
	}


	protected function setLanguage(\Entity\Language $language) {
		return $this->addVariable('language', $language);
	}

	protected function setCountry(\Entity\Location\Location $location) {
		if($location->type->id != 3) {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
		return $this->addVariable('country', $location);
	}
	
}


