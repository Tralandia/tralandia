<?php
namespace BaseModule\Components;

use Nette\Application\UI\Control;
use Nette\Localization\ITranslator;
use Nette\Reflection\ClassType;


abstract class BaseControl extends Control {

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;


	/**
	 * @param ITranslator $translator
	 */
	public function __construct(ITranslator $translator = NULL)
	{
		$this->translator = $translator;
		parent::__construct();
	}

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);

		$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';

		if($this->translator) {
			$template->setTranslator($this->translator);
		}

		if(is_file($path)) {
			$template->setFile($path); // automatické nastavení šablony
		}

		return $template;
	}


}
