<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:30
 */

namespace BaseModule\Components;


use Nette;
use Nette\Reflection\ClassType;

class BaseFormControl extends Nette\Application\UI\Control
{

	public function createTemplate($class = null)
	{
		$template = parent::createTemplate($class);
		if ($template instanceof \Nette\Templating\FileTemplate) {
			$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';
			$template->setFile($path); // automatické nastavení šablony
		}
		$template->_form = $template->form = $this['form']; // kvůli snippetům
		return $template;
	}

	public function render()
	{
		if ($this->template instanceof \Nette\Templating\FileTemplate
			&& !is_file($this->template->getFile())) {

			$args = func_get_args();
			return call_user_func_array(array($this['form'], 'render'), $args);
		} else {
			$this->template->render();
		}
	}


}
